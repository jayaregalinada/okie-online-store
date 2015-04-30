_okie.controller 'ItemController', ( $rootScope, $scope, $log, $http, $window, ItemFactory, $state, $stateParams, localStorageService, $timeout, RatingFactory, Notification, Lightbox )->

    $scope.items = []
    $scope.item = {}
    $scope.categoryInfo = {}
    $scope.carouselInterval = 3000
    $scope.categoryFilterName = $stateParams.categoryId
    $scope.inquireState = false
    $scope.inquireMessages = []
    $scope.inquireSubmitButton =
        text: 'SUBMIT'
        state: false
        success: false
    $scope.inquireResponseTime = 5000
    $rootScope.searching = false
    $scope.rating =
        maximum: 5
    $scope.inquire =
        reserve: 0
    $scope.rateSubmittingState = false
    $scope.ratingState = false
    $scope.featured = {}
    $scope.banads = []

    $scope.clickImage = ( index )->
        $log.info 'clickImage', index
        Lightbox.openModal( $scope.item.images, index );

        return

    $scope.backToHeader = ( delayTime, animateTime )->
        $timeout ->
            $( 'body,html' ).animate
                scrollTop: $( '#item_container .item-wrapper' ).offset().top - ( $( '#navigation' ).outerHeight( true ) + ( $( '#navigation' ).outerHeight( true ) / 2 ) )
            , if animateTime then animateTime else 1000
        , if delayTime then delayTime else 500

        return

    $scope.checkState = ->
        $log.info 'ItemController.checkState()', $state.current
        switch $state.current.name
            when 'item' then $scope.getItem $stateParams.itemId
            when 'category' then $scope.getItemsByCategory 1
            when 'index' then $scope.getAllItem()

        return

    $scope.inquireItem = ->
        $scope.backToHeader( 50, 500 )
        $scope.inquireState = !$scope.inquireState
        $log.log 'Inquiring item', $stateParams.itemId

        return

    $scope.inquireSubmit = ( e )->
        e.preventDefault()
        $scope.inquireSubmitButton.state = !$scope.inquireSubmitButton.state
        $scope.inquireSubmitButton.text = 'SUBMITTING'
        $scope.inquireMessages = []
        ItemFactory.sendInquiryMessage(
            item: $stateParams.itemId
            message: $scope.inquire.message
            reserve: $scope.inquire.reserve
        ).success ( data, xhr )->
            $scope.backToHeader()
            $scope.inquireSubmitButton.state = !$scope.inquireSubmitButton.state
            $scope.getItem $stateParams.itemId
            $scope.inquire = 
                reserve: 0
                message: ''
            $scope.inquireItem()
            $scope.inquireMessages.push
                type: 'success'
                message: data.success.message
            Notification.success data.success

            return
        .error ( data, xhr )->
            $scope.inquireSubmitButton.state = !$scope.inquireSubmitButton.state
            $scope.inquireMessages.push
                type: 'danger'
                message: data.error.message

            Notification.error data.error

            return
        .then ( data )->
            $timeout(->
                $scope.inquireMessages.splice 0, $scope.inquireMessages.length
            , $scope.inquireResponseTime )

            return

        return

    $scope.closeInquireMessage = ( index )->
        $scope.inquireMessages.splice index, 1

        return

    $scope.getItemsByCategory = ( pageNumber )->
        ItemFactory.getAllByCategory( $stateParams.categoryId, pageNumber )
            .success ( success, xhr )->
                $scope.errorMessage = null
                $scope.categoryInfo = success.category
                $rootScope.categoryInfo = $scope.categoryInfo
                # if data.to > 0
                #     if $scope.items.length < data.total
                #         $scope.getItemsByCategory( data.current_page + 1 )
                #         angular.forEach data.data, ( value, key )->
                #             $scope.items.push value
                #             return
                if Boolean success.products.next_page_url
                    $scope.getItemsByCategory( data.current_page + 1 )

                return
            .error ( error )->
                $scope.errorMessage = error.error.message

                return
            .then ( data )->
                angular.forEach data.data.products.data, ( value, key )->
                    $scope.items.push value
                    return

                $scope.featured = data.data.featured

                return

        return

    $scope.getAllItem = ( pageNumber ) ->
        $log.info 'Getting all items'   
        ItemFactory.getAll( pageNumber )
            .success ( data, xhr )->
                $scope.errorMessage = null
                if Boolean( data.next_page_url )
                    $scope.getAllItem( data.current_page + 1 )
                    
                return
            .error ( error )->
                $log.error 'ItemController.getAllItem::error', error
                $scope.errorMessage = error.error.message

                return
            .then ( data )->
                angular.forEach data.data.data, ( value, key )->
                    $scope.items.push value
                    return

                return

        return

    $scope.affixItem = ->
        # $log.log 'ItemController@affixItem', $( '#item_container .item-right').outerHeight( true ) >= ( $( '#item_content_left .item-carousel' ).outerHeight( true ) + 100 )
        # $log.log 'ItemController@affixItem::.item-right', $( '#item_container .item-right').outerHeight( true )
        # $log.log 'ItemController@affixItem::.item-carousel', $( '#item_content_left .item-carousel' ).outerHeight( true )
        if $( '#item_container .item-right').outerHeight( true ) >= $( '#item_content_left .item-carousel' ).outerHeight( true )
            $( '#item_content_left .item-carousel' ).affix
                offset:
                    top: ->
                        this.top = $( '#navigation' ).outerHeight( true )
                    bottom: ->
                        # this.bottom = $( '#item_container .item-right' ).outerHeight( true ) + ( $( '#item_content_left .item-carousel' ).outerHeight( true ) + $('#navigation').outerHeight(true) + $('#category_nav').outerHeight(true) + 36 )
                        # this.bottom = $( '#item_container .item-right' ).outerHeight( true ) - ( $( '#item_content_left .item-carousel' ).outerHeight( true ) - $('#navigation').outerHeight(true) - $('#category_nav').outerHeight(true) - 17 )
                        this.bottom = $( '#item_container .item-related' ).outerHeight( true ) + $( '#footer' ).outerHeight( true ) - 50
        return


    $scope.getItem = ( id )->

        ItemFactory.getItem( id )
            .success ( data, xhr )->
                $scope.backToHeader()
                $scope.errorMessage = null
                $scope.item = data
                $log.log 'item: ', $scope.item
                $rootScope.bigTitle = ' :: ' + data.name

                return
            .error ( error, xhr )->
                $scope.errorMessage = error.error.message

                return

            .then ( data )->
                $timeout ->
                    $scope.affixItem()
                    return
                , 500
                return

        return

    $scope.redirectInItem = ->
        localStorageService.set 'redirect_to_item', $stateParams.itemId

        return

    $scope.ratingItem = ->
        $scope.ratingState = !$scope.ratingState

        return


    $scope.rateTheItem = ( $event, id, elementId )->
        $event.preventDefault()
        element = if elementId then elementId else '#ratingCollapse'
        $scope.rateSubmittingState = true
        $log.info 'ItemController.rateTheItem', $event
        rating = if $scope.item.review then $scope.item.review else $scope.item.rating
        RatingFactory.rateItem( id, rating )
            .success ( success )->
                $log.log success
                $scope.rateSubmittingState = false
                $scope.item.rating.message = ''
                Notification.success success.success
                $( element ).collapse( 'hide' )
                $scope.item.rating = success.success.data.product.rating
                $scope.ratingState = !$scope.ratingState
                # $scope.getItem success.success.data.product_id

                return
            .error ( error )->
                $log.error error
                $scope.rateSubmittingState = false
                Notification.error error.error

                return

        return

    $scope.getAllBanads = ->

        $http.get 'banners'
            .success ( success )->
                $log.log 'ItemController@getAllBanads::success', success
                
                angular.forEach success, ( val, key )->
                    $scope.banners.push val

                    return

                return

        return


    $scope.checkState()
    

    return
