_okie.controller 'DeliverController', ( DeliverFactory, textAngularManager, $timeout, $log, $window, $scope, $state, $stateParams, $rootScope )->

    $scope.heading = 'Delivered'
    $scope.factory = DeliverFactory
    $scope.deliveries = []
    $scope.deliverState = false
    $scope.deliverErrorState = false
    $scope.deliverConversations = []
    $scope.alerts = []
    $scope.replySubmitButton = 
        state: false

    ###*
     * Change the heading
     *
     * @param  {string} heading
     *
     * @return {void}
    ###
    $scope.changeHeading = ( heading, prepend )->
        $scope.heading = heading
        $('.profile-container .profile-full-name').text heading
        $('.profile-container .profile-full-name').prepend prepend

        return

    ###*
     * Close the alert
     *
     * @param  {integer} index
     *
     * @return {void}
    ###
    $scope.closeAlert = ( index )->
        $scope.alerts.splice( index, 1 )
        return

    $scope.getAllDeliver = ( pageNumber )->
        $scope.deliveries = []
        page = if pageNumber then pageNumber else 1
        $scope.changeHeading 'Delivered'
        $scope.deliverState = true
        $scope.deliverLoadingState = true
        $scope.deliverErrorState = false
        DeliverFactory.getAll( page )
            .success ( data, xhr )->
                $log.log 'DeliverController.getAllDeliver::data', data
                $scope.deliverErrorState = false
                if Boolean( data.next_page_url )
                    $scope.getAllDeliver data.current_page + 1

                return
            .error ( data, xhr )->
                $log.error 'DeliverController.getAllDeliver::data', data
                $scope.deliverErrorState = true
                $scope.deliverLoadingState = false
                $scope.deliverErrorMessage = data.error.message.replace '[DELIVER] ', ''

                return
            .then ( data, xhr )->
                $scope.pushToDeliveries data.data.data


        return

    $scope.pushToDeliveries = ( data )->
        angular.forEach data, ( value, key )->
            $scope.deliveries.push value
            return
        $scope.deliverLoadingState = false
        $timeout(->
            $scope.deliverState = false
            $scope.deliverErrorState = false
            return
        , 3000 )

        return

    $scope.getToConversation = ( deliverId, pageNumber )->
        $scope.deliverConversations = []
        $scope.changeHeading 'Loading conversations'
        $scope.deliverState = true
        DeliverFactory.getConversations deliverId, pageNumber
            .success ( data, xhr )->
                $log.log 'DeliverController.getToConversation::data', data
                $scope.changeHeading data.deliver.title, '<span>DELIVERED: &nbsp;</span>'
                $scope.deliverErrorState = false
                if Boolean data.conversations.next_page_url
                    $scope.getToConversation $rootScope.$stateParams.deliverId, data.conversations.current_page + 1

                return
            .error ( data, xhr )->
                $scope.deliverErrorState = true
                $log.error 'DeliverController.getToConversation::data', data
                $scope.changeHeading 'ERROR'
                $scope.deliverErrorMessage = data.error.deliverErrorMessage

                return
            .then ( data, xhr )->
                angular.forEach data.data.conversations.data, ( value, key )->
                    $scope.deliverConversations.push value
                    return
                $scope.deliverInfo = data.data.deliver
                $scope.backToTextArea()
                $timeout(->
                    $scope.deliverState = false
                , 3000 )

                return

        return

    ###*
     * Back to Text Area
     *
     * @param  {integer} delayTime
     * @param  {integer} animateTime
     *
     * @return {void}
    ###
    $scope.backToTextArea = ( delayTime, animateTime )->
        $timeout(->
            $( 'body,html' ).animate
                scrollTop: $( '#reply' ).offset().top + $( '#reply' ).outerHeight( true ) - $( window ).height() + 20
            , if animateTime then animateTime else 1000
        , if delayTime then delayTime else 1500 )

        return

    $scope.replySubmit = ( event, form )->
        event.preventDefault()
        $scope.replySubmitButton.state = !$scope.replySubmitButton.state
        tA = textAngularManager.retrieveEditor 'reply'
        data = 
            deliver: $rootScope.$stateParams.deliverId
            message: form.reply.$modelValue

        DeliverFactory.reply data
            .success ( response, xhr )->
                $log.log 'DeliverController.replySubmit::response', response
                tA.scope.$parent.reply = ''
                $scope.deliverConversations.push response.success.data

                return
            .error ( response, xhr )->
                $scope.alerts.push response.error
                $timeout ->
                    $scope.alerts = []
                    $scope.getToConversation $rootScope.$stateParams.deliverId
                    $scope.replySubmitButton.state = false
                    return
                , 4000

                return
            .then ( response )->
                $scope.replySubmitButton.state = !$scope.replySubmitButton.state
                $scope.backToTextArea()

                return

        return


    #### END OF LINE ####
    return

