_okie.controller 'ProductController', ( $rootScope, $scope, $http, $location, $window, $timeout, Lightbox, localStorageService, $state, $stateParams, Slug, SettingsFactory ) ->

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
    $scope.product = {}
    $scope.url = 
        base: '/me/products'
    $scope.categories = []
    $scope.category = {}
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
    $scope.getTitle = ->
        if( $window.location.pathname is $scope.path + '/create' )
            console.log('Create Product')

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
        $http(
            url: '/product/' + $scope.id
            method: "PUT"
            data: $scope.product
            ).success ( data, status )->
                # console.log data
                $scope.getInformation()
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
                    $scope.images.push value
                    $window.productImages.push value
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
        $scope.editState = !$scope.editState

        newProduct = JSON.stringify $scope.product
        oldProduct = JSON.stringify $scope.getItem 'product_info'

        if ( newProduct is oldProduct )
            console.log 'Just the same eh?'
            return
        else
            $scope.productUpdate()
            return

        return

    $scope.getProducts = ( pageNumber )->
        $http(
            url: $scope.url.base
            method: "GET"
            params: 
                json: true
                page: ( if ( pageNumber ) then pageNumber else 1 )
            ).success ( data, xhr )->
                $scope.nextPageUrl = data.next_page_url
                $scope.changeHeading 'Products'

                if $scope.products.length < data.total
                    $scope.getProducts( data.current_page + 1 )
                    angular.forEach data.data, ( value, key )->
                        $scope.products.push value
                        return
                    return
                

                return
            .then ( data )->
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
                $scope.getInformation()
                return

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

                return
            .then ( data )->
                $scope.category = {}

                return

        return

    $scope.getCategories = ->
        $scope.changeHeading 'Categories'
        $scope.categories.splice 0, $scope.categories.length
        $scope.categories = []
        SettingsFactory.getAllCategories()
            .success ( data, xhr )->
                $scope.categories.splice 0, $scope.categories.length
                $scope.categories = []
                
                return
            .then ( data )->
                angular.forEach data.data, ( value, key )->
                    $scope.categories.push value

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
        SettingsFactory.updateCategory $scope.hotSeatCategory
            .success ( data, xhr )->
                $( $scope.modalId ).modal 'hide'
                $scope.getCategories()

                return

        return

    $scope.deleteCategory = ( id, event )->
        SettingsFactory.deleteCategory id
            .success ( data, xhr )->
                $scope.getCategories()

                return

        return

    return
