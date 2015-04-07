_okie.controller 'MessageController', ( $scope, $log, $interval, $http, $state, $stateParams, $rootScope, $sce, $timeout, MessageFactory, textAngularManager, UserFactory, SearchFactory )->

    $scope.heading = 'Messages'
    $scope.messages = []
    $scope.conversation = []
    $scope.messageInfo = {}
    $scope.threadId = ''
    $scope.replySubmitButton = 
        state: false
    $scope.messageSubmitButton =
        state: false
    $scope.threadInquiries = []
    $scope.threadDeliveries = []
    $scope.threadInboxes = []
    $scope.intervalSeconds = 3000
    $scope.alerts = []
    $scope.message = {}
    $scope.search = []
    $scope.searchError = false


    ###*
     * Change the heading
     *
     * @param  {string} heading
     *
     * @return {void}
    ###
    $scope.changeHeading = ( heading )->
        $scope.heading = heading
        $('.profile-container .profile-full-name').text heading
        return

    ###*
     * Get all inquiries by page
     *
     * @param  {integer} page [Page number]
     *
     * @return {void}
    ###
    $scope.getAllInquiries = ( page )->
        $scope.changeHeading 'Inquiries'
        $scope.messages = []
        MessageFactory.getInquiryMessages( page )
            .success ( data, xhr )->
                $log.log 'getAllInquiries::data', data
                if Boolean( data.next_page_url )
                    $scope.getAllInquiries( data.current_page + 1 )

                return
            .then ( data, xhr )->
                angular.forEach data.data.data, ( value, key )->
                    $scope.messages.push value
                    $scope.threadInboxes.push value
                
                return

        return

    $scope.stopLatestMessage = ->
        $log.info 'Stopping latest messages trolling'
        if angular.isDefined stop
            $interval.cancel stop
            stop = undefined
        return

    $scope.changeHeadingWhenInquiring = ( product_name, name )->
        $('.profile-container .profile-full-name').html 'Inquiring for ' + product_name + ' <small>by ' + name + '</small>'
        return

    $scope.getToConversation = ( thread_id, pageNumber )->
        $scope.conversation = []
        MessageFactory.getThreadMessages thread_id
            .success ( data, xhr )->
                $scope.changeHeading data.name
                $scope.messageInfo = data
                $scope.getConversations thread_id, pageNumber
                    
                return

        return

    $scope.getConversations = ( thread_id, pageNumber )->
        MessageFactory.getMessages thread_id, pageNumber
            .success ( data, xhr )->
                $log.log 'getConversations::data', data
                # stop = $interval(->
                #     $scope.getLatestMessages( $scope.conversation.length, data.total )
                # , $scope.intervalSeconds )
                if Boolean( data.next_page_url )
                    $scope.getConversations( $rootScope.$stateParams.threadId, data.current_page + 1 )

                return
            .then ( data, xhr )->
                angular.forEach data.data.data, ( value, key )->
                    $scope.conversation.unshift value

                UserFactory.getNotify() # So notification change

                $timeout(->
                    $( 'body,html' ).animate
                        scrollTop: $( '#reply' ).offset().top
                    , 1000
                , 1500 )

                return

        return

    $scope.getMessagesByProduct = ( product_id, user_id )->
        $scope.conversation = []
        MessageFactory.getInquiryMessageByProductId product_id, user_id
            .success ( data, xhr )->

                if data.to > 0
                    $scope.changeHeadingWhenInquiring data.data[0].product.name, data.data[0].user.first_name + ' ' + data.data[0].user.last_name
                    angular.forEach data.data, ( value, key )->
                        $scope.conversation.unshift value

                        return

        return

    $scope.closeAlert = ( index )->
        $scope.alerts.splice( index, 1 )
        return

    $scope.replySubmit = ( event, form )->
        event.preventDefault()
        tA = textAngularManager.retrieveEditor( 'reply' )
        $scope.replySubmitButton.state = !$scope.replySubmitButton.state
        MessageFactory.replyToMessage( event.target.action, 
            reply: tA.scope.html
            item: $scope.messageInfo.product_id
            thread: $rootScope.$stateParams.threadId
        ).success ( data, xhr )->
            $scope.conversation.push data.success.data
            $scope.me = $rootScope.me
            tA.scope.$parent.reply = ''

            return
        .error ( data, xhr )->
            $scope.alerts.push data.error
            $timeout(->
                $scope.alerts = []
                $scope.getToConversation $rootScope.$stateParams.threadId
            , 5000 )

            return
        .then ( data )->
            $scope.replySubmitButton.state = !$scope.replySubmitButton.state
            $timeout(->
                $( 'body,html' ).animate
                    scrollTop: $( '#reply' ).offset().top
                , 1000
            , 2000 )

            return

        return


    $scope.getAllDeliveries = ( page )->
        $log.info 'Getting all deliveries'
        $scope.changeHeading 'Delivered'
        $scope.messages = []
        MessageFactory.getDeliveryMessages( page )
            .success ( data, xhr )->
                $log.log 'getAllDeliveries::data', data
                if Boolean( data.next_page_url )
                    $scope.getAllDeliveries( data.current_page + 1 )
                    
                return
            .then ( data, xhr )->
                angular.forEach data.data.data, ( value, key )->
                    $scope.messages.push value
                    $scope.threadDeliveries.push value
                    return

                return

        return

    $scope.moveToDelivered = ->
        MessageFactory.updateToDeliver( $rootScope.$stateParams.threadId )
            .success ( data, xhr )->
                $log.log 'moveToDelivered::data', data
                UserFactory.getNotify() # So notification change
                $state.go 'messages.inquiries'

                return

        return

    $scope.getLatestMessages = ( conversationLength, dataTotal )->
        $log.log 'conversationLength: ', conversationLength
        $log.log 'dataTotal:', dataTotal
        if( conversationLength > dataTotal )
            $scope.getMessagesByOffset conversationLength, 15
        return

    $scope.getMessagesByOffset = ( offset, take )->
        thread = $rootScope.$stateParams.threadId
        MessageFactory.getMessageOffset( thread, offset, take )
            .success ( data, xhr )->
                $log.log 'getMessagesByOffset::data', data
                angular.forEach data.messages, ( value, key )->
                    $scope.messages.unshift value
                    return
                return

        return

    $scope.createMessage = ->
        $log.info 'Create a message'
        $scope.changeHeading 'Create'
        $scope.message.subject = 'Message from ' + $rootScope.me.user.first_name + ' ' + $rootScope.me.user.last_name
        
        return

    $scope.getUser = ( event )->
        if event.keyCode == 13
            $log.log event.target.value
            $scope.search = []
            $scope.searchUserByFilter event.target.value

        event.preventDefault()
        return

    $scope.sendWithUser = ( user )->
        $scope.search = []
        $scope.message.user = user.id
        $scope.message.send = user.full_name
        $log.log $scope.message
        return

    $scope.searchUserByFilter = ( value )->
        $log.log( 'searchUserByFilter::ifElse', Boolean( value.substr 0, value.indexOf ":" ) )
        if Boolean( value.substr 0, value.indexOf ":" )
            v = value.substr( value.indexOf( ":" ) + 2 )
            param = value.substr 0, value.indexOf ":"
            SearchFactory.getUser( v, { 'action': param } )
                .success (data, xhr )->
                    $scope.searchError = false
                    $log.log 'searchUserByFilter::data', data
                    angular.forEach data.success.data, ( value, key )->
                        $scope.search.push value
                    return
                .error ( data, xhr )->
                    $log.error 'searchUserByFilter::data', data
                    $scope.searchError = true
                    $scope.searchErrorMessage = data.error.message
                    return

        SearchFactory.getUser( value )
            .success (data, xhr )->
                $scope.searchError = false
                $log.log 'searchUserByFilter::data', data
                angular.forEach data.success.data, ( value, key )->
                    $scope.search.push value
                return
            .error ( data, xhr )->
                $log.error 'searchUserByFilter::data', data
                $scope.searchError = true
                $scope.searchErrorMessage = data.error.message
                return

        return

    ###*
     * Submit new message
     *
     * @param  {$event} event 
     * @param  {object|mixed} form
     *
     * @return {void}
    ###
    $scope.submitNewMessage = ( event, form )->
        event.preventDefault()
        $scope.messageSubmitButton.state = !$scope.messageSubmitButton.state
        MessageFactory.sendMessage( event.target.action, $scope.message )
            .success ( data, xhr )->
                $log.log 'submitNewMessage::data', data
                $scope.messageSubmitButton.state = !$scope.messageSubmitButton.state
                $scope.message.body = ''
                $state.go 'messages.thread', 
                    threadId: data.success.data.thread.id

                return

        return

    $scope.getAllInboxes = ( page )->
        $scope.changeHeading 'Inbox'
        $scope.inbox = []
        $scope.threadInboxes = []
        MessageFactory.getInboxMessages( page )
            .success ( data, xhr )->
                $log.log 'getAllInboxes::data', data
                if Boolean( data.next_page_url )
                    $scope.getAllInboxes( data.current_page + 1 )
                    
                return
            .then ( data, xhr )->
                angular.forEach data.data.data, ( value, key )->
                    $scope.inbox.push value
                    $scope.threadInboxes.push value
                    return

                return

        return


    return
