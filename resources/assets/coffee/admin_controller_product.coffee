_okie.controller 'ProductController', ( ProductFactory, $rootScope, $log, $scope, $http, $location, $window, $timeout, Lightbox, localStorageService, $state, $stateParams, Slug, SettingsFactory, Notification, ClassFactory ) ->

    ## Scope Variables
    $scope.info = 'Product Information'
    $scope.header = null
    $scope.title = null
    $scope.path = 'product'
    $window.productImages = []
    $scope.images = []
    $scope.id = null
    $scope.lastImageId = null
    $scope.editState = false
    $scope.editStateCategory = false
    $scope.editStateBadge = false
    $scope.product = {}
    $scope.url = 
        base: '/me/products'
    $scope.categories = []
    $scope.category = {}
    $scope.cat = {}
    $scope.hotSeatCategory = 
        name: 'LOADING'
    $scope.stateCategory = false
    $scope.modalId = '#modal_category_edit'
    $scope.products = []
    $scope.nextPageUrl = null
    $scope.productConfig = 
        sizes: 2
    $rootScope.images = $scope.images
    $rootScope.product = $scope.product
    $scope.create =
        basic: true
        name: false
        code: false
        description: false
        price: false
        unit: false

    $scope.condition = 
        products: 
            loading: false
            error: false
        categories:
            loading: false
            error: false

    $scope.loadingState =
        products: false

    $scope.classFactory = ClassFactory
    $scope.class_array = []

    $scope.$watchCollection 'product.badge.class_array', ( val )->
        $log.log 'product.badge.class_array:val', val
        
        $log.log 'typeof(val)', Boolean( typeof val )
        $log.log 'val', Boolean( val )
        
        if( typeof( val ) is 'object' || val )
            $scope.class_array = []
            angular.forEach val, ( value, key )->
                $scope.class_array.push( value.text )
                
                return
            $scope.product.badge.class = $scope.class_array.join ' '
            
        # if( key )
            # $scope.product.badge.class = key.join ' '
        $log.log 'product.badge.class', $scope.product.badge

        return

    $scope.loadClass = ( query )->
        $log.log 'ClassFactory.load()', ClassFactory.load()
        ClassFactory.load()

    $scope.setItem = ( key, val, stringify )->
        if( stringify )
            return localStorageService.set( key, JSON.stringify( val ) )

        localStorageService.set( key, val )

    $scope.getItem = ( key )->
        localStorageService.get( key )

    $scope.changeHeading = ( heading )->
        $scope.header = heading
        $('.profile-container .profile-full-name').text heading
        return

    ##############
    ## PRODUCTS
    ##############
    
    $scope.changeDescription = ( name )->
        $scope.create =
            basic: false
            name: false
            code: false
            description: false
            price: false
            unit: false
        switch name
            when 'name' then $scope.create.name = true
            when 'code' then $scope.create.code = true
            when 'description' then $scope.create.description = true
            when 'price' then $scope.create.price = true
            when 'unit' then $scope.create.unit = true
            else $scope.create.basic = true

        $log.info $scope.create
        return

    $scope.autoChangeProductCode = ->
        if( ! $scope.product.code )
            $scope.product.code = Slug.slugify $scope.product.name
            
        return

    $scope.initializeDropzone = ( url, token )->
        $log.info 'ProductController.initializeDropzone', url
        $scope.dropzoneInit = new Dropzone( document.body,
            url: url
            previewsContainer: '#productPreview'
            clickable: false
            acceptedFiles: 'image/*'
            params: 
                '_token': token
        )
        $scope.dropzoneInit.on 'queuecomplete', ( file, xhr )->
            $scope.getImages( $scope.images.length )
            Notification.success
                title: 'Hooray!'
                message: 'Uploading complete'
            @.removeAllFiles()
            $('#product_add_image_form header.drag').fadeIn()
            $('#product_add_image_form header.dropping').hide()
            return
        $scope.dropzoneInit.on 'dragenter', ( file, xhr )->
            $log.info 'DROPZONE DRAG ENTER'
            $('#product_add_image_form header.drag').hide()
            $('#product_add_image_form header.dropping').fadeIn()
            return
        $scope.dropzoneInit.on 'drop', ( file, xhr )->
            $('#product_add_image_form header').hide()
            return


        return

    $scope.getTitle = ->
        if( $window.location.pathname is $scope.path + '/create' )
            $log.log('Create Product')

        return

    $scope.openLightboxModal = ( index )->
        Lightbox.openModal( $scope.images, index )

        return

    $scope.lightboxClose = ( index )->
        $window.alert index

        return

    # Dropzone
    $scope.dropzoneConfig =
        options:
            # paramName: 'images[]'
            # maxFilesize: 2
            acceptedFiles: 'image/*'
            # uploadMultiple: true
            
        eventHandlers:
            success: ( file, xhr )->
                return
            queuecomplete: ( file, xhr, asd )->
                $scope.getImages( $scope.images.length )
                @.removeAllFiles()

                return

    $scope.addImages = ( data )->
        $scope.images.push $scope.images

        return

    $scope.getInformation = ->
        $http(
            url: '/product/' + $scope.id
            method: 'GET'
            params: 
                ajax: true
            )
            .success ( data )->
                $scope.product = data
                $scope.setItem('product_info', $scope.product, true )
                console.log 'Product Information:', data
                return

        return

    $scope.productUpdate = ->
        obj = 
            url: '/product/' + $scope.id
            method: "PUT"
            data: $scope.product
        $http obj
        .success ( success )->
            Notification.success success.success
            $scope.getInformation()
            $scope.editState = !$scope.editState

            return
        .error ( error )->
            Notification.error error.error

            return

        return

    ###*
     * Get Images in a product
     *
     * @param  {integer} skip
     *
     * @return {object}
    ###
    $scope.getImages = ( skip )->
        $http(
                url: '/product/' + $scope.id + '/images'
                method: "GET"
                params: 
                    skip: ( if ( skip ) then skip else $scope.images.length )
            ).success ( data, status, headers, config )->
                angular.forEach data.data, ( value, key )->
                    # console.log value 
                    $scope.images.unshift value
                    # $window.productImages.push value
                    return
            .then ( data )->
                if( $scope.images.length < data.data.total )
                    $scope.getImages( $scope.images.length )
                    # console.log 'yes'
                    return
                return

        return

    $scope.editInfo = ->
        $scope.editState = !$scope.editState
        
        return

    $scope.updateInfo = ->
        # $scope.editState = !$scope.editState
        newProduct = JSON.stringify $scope.product
        oldProduct = JSON.stringify $scope.getItem 'product_info'

        if ( newProduct is oldProduct )
            $log.log 'Just the same eh?'
            $scope.editState = !$scope.editState

            return
        else
            $scope.productUpdate()

            return

        return

    $scope.getProducts = ( pageNumber )->
        $scope.changeHeading 'Products'
        $scope.condition.products.loading = true
        $scope.condition.products.error = false
        $scope.products = []
        ProductFactory.getAllProducts( pageNumber )
            .success ( response, xhr )->
                $scope.condition.products.error = false
                if Boolean( response.next_page_url )
                    $scope.getProducts( response.current_page + 1 )
                
                return
            .error ( error, xhr )->
                $scope.condition.products.error = true
                $scope.condition.products.loading = false
                $scope.condition.products.errorMessage = error.error

                return
            .then ( data )->
                $scope.pushToProducts data.data.data
                $scope.condition.products.loading = false

                return

        return

    $scope.pushToProducts = ( data )->
        angular.forEach data, ( value, key )->
            $scope.products.push value
            return

        return

    $scope.goToItem = ( id )->
        $window.location.href = '/product/' + id

        return

    $scope.editProductCategory = ->
        $scope.editStateCategory = !$scope.editStateCategory

        return

    $scope.updateProductCategory = ->
        $scope.editStateCategory = !$scope.editStateCategory
        $scope.product.categories.map( Number )
        $http(
            url: '/product/' + $scope.id + '/category'
            data: $.param( $scope.product )
            method: 'PUT'
            headers: 
                'Content-Type': 'application/x-www-form-urlencoded'
            ).success ( data, xhr )->
                Notification.success
                    message: 'Successfully update product'
                $scope.getInformation()
                return

        return

    $scope.editProductBadge = ->
        $scope.editStateBadge = true

        return

    $scope.updateProductBadge = ( event, form )->
        $log.log 'ProductController.updateProductBadge::event', event
        $log.log 'ProductController.updateProductBadge::form', form
        $log.log '$scope.product.badge', $scope.product.badge
        ProductFactory.updateBadge $scope.product.badge, form_product_badge.getAttribute 'action'
            .success ( success )->
                Notification.success success.success
                $scope.editStateBadge = false
                
                return
            .error ( error )->
                Notification.error error.error

                return

        return

    $scope.removeProductBadge = ( event, form )->
        $http
            url: form_product_badge_remove.getAttribute 'action'
            method: 'PATCH'
        .success ( success )->
            $scope.editStateBadge = false
            Notification.success success.success
            $scope.getInformation()

            return
        .error ( error )->
            Notification.error error.error 

            return

        event.preventDefault()
        
        return

    ###*
     * ====================
     * SCOPES FOR CATEGORY
     * ====================
    ###


    $scope.addCategory = ( event )->
        event.preventDefault()
        SettingsFactory.addCategory $scope.category
            .success ( data, xhr )->
                $scope.getCategories()
                $log.info 'ProductController.addCategory::data', data
                Notification.success data.success
                
                return
            .then ( data )->
                $scope.category = {}

                return

        return


    $scope.getCategories = ( page )->
        $scope.categories = []
        $scope.changeHeading 'Categories'
        $scope.condition.categories.loading = true
        $scope.condition.categories.error = false
        SettingsFactory.getAllCategories( page )
            .success ( data, xhr )->
                $scope.condition.categories.error = false
                if Boolean( data.next_page_url )
                    $scope.getCategories data.current_page + 1

                return
            .error ( error, xhr )->
                $scope.condition.categories.loading = false
                $scope.condition.categories.error = true
                $scope.condition.categories.errorMessage = error.error

                return
            .then ( data, xhr )->
                $scope.condition.categories.loading = false
                $scope.condition.categories.error = false
                angular.forEach data.data.data, ( value, key )->
                    $scope.categories.push value
                    return

                return

        return

    $scope.getCategoryById = ( id )->
        SettingsFactory.getCategoryById id
            .success ( data, xhr )->
                $scope.stateCategory = false
                $scope.hotSeatCategory = data
                if ( ! $scope.hotSeatCategory.slug )
                    $scope.hotSeatCategory.slug = Slug.slugify $scope.hotSeatCategory.name

                return

        return

    $scope.editCategory = ( id )->
        $scope.hotSeatCategory = {}
        $scope.stateCategory = true
        $scope.getCategoryById id 
        return

    $scope.updateCategory = ( event )->
        event.preventDefault()
        $scope.hotSeatCategory.parent_selected = $scope.hotSeatCategory.parent_info.id
        SettingsFactory.updateCategory $scope.hotSeatCategory
            .success ( data, xhr )->
                $log.info 'ProductController.updateCategory::data', data
                $( $scope.modalId ).modal 'hide'
                $scope.getCategories()
                Notification.success data.success

                return
            .error ( error )->
                Notification.error error.error

                return

        return

    $scope.deleteCategory = ( id, event )->
        SettingsFactory.deleteCategory id
            .success ( data, xhr )->
                $log.info 'ProductController.deleteCategory::data', data
                $scope.getCategories()
                Notification.success data.success

                return

        return

    # $scope.$watch 'product.'

    return
