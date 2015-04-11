_okie.controller 'ItemController', ( $scope, $log, $http, $window, ItemFactory, $state, $stateParams, localStorageService, $timeout )->

    $scope.items = []
    $scope.item = {}
    $scope.carouselInterval = 3000
    $scope.categoryFilterName = $stateParams.categoryId
    $scope.inquireState = false
    $scope.inquireMessages = []
    $scope.inquireSubmitButton =
        text: 'SUBMIT'
        state: false
        success: false
    $scope.inquireResponseTime = 5000

    $scope.backToHeader = ( delayTime, animateTime )->
        $timeout ->
            $( 'body,html' ).animate
                scrollTop: $( '#header' ).offset().top - $( '#navigation' ).outerHeight( true )
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
            message: $scope.inquire
        ).success ( data, xhr )->
            $scope.backToHeader()
            $scope.inquireSubmitButton.state = !$scope.inquireSubmitButton.state
            $scope.inquire = ''
            $scope.inquireMessages.push
                type: 'success'
                message: data.success.message

            return
        .error ( data, xhr )->
            $scope.inquireSubmitButton.state = !$scope.inquireSubmitButton.state
            $scope.inquireMessages.push
                type: 'danger'
                message: data.error.message

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
            .success ( data, xhr )->
                if data.to > 0
                    if $scope.items.length < data.total
                        $scope.getItemsByCategory( data.current_page + 1 )
                        angular.forEach data.data, ( value, key )->
                            $scope.items.push value
                            return

                return

        return

    $scope.getAllItem = ( pageNumber ) ->
        ItemFactory.getAll( pageNumber )
            .success ( data, xhr )->
                if $scope.items.length < data.total
                    $scope.getAllItem( data.current_page + 1 )
                    angular.forEach data.data, ( value, key )->
                        $scope.items.push value
                        return

                return

    $scope.getItem = ( id )->
        ItemFactory.getItem( id )
            .success ( data, xhr )->
                $scope.item = data
                $log.log 'item: ', $scope.item

                return

        return

    $scope.redirectInItem = ->
        localStorageService.set 'redirect_to_item', $stateParams.itemId

        return

    $scope.checkState()
    

    return
