_okie.controller 'MessageController', ( $scope, $document, $window, $log, $interval, $http, $state, $stateParams, $rootScope, $sce, $timeout, MessageFactory, textAngularManager, UserFactory, SearchFactory, InquiryFactory, localStorageService, InboxFactory, Notification, RatingFactory )->

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
    ## INQUIRIES
    $scope.inquiries = []
    $scope.inquiryConversations = []
    $scope.inquiryInfo = {}
    $scope.inquiryState = false
    $scope.inquiryLoadingState = false
    $scope.inquiryErrorState = false
    $scope.inquiriesKey = 'inquiries'
    $scope.inquiryStateReserve = false
    $scope.reserve = 0

    ## INBOX
    $scope.inbox = []
    $scope.inboxConversations = []
    $scope.inboxInfo = {}
    $scope.inboxState = false
    $scope.inboxLoadingState = false
    $scope.inboxErrorState = false
    $scope.inboxKey = 'inbox'
    $scope.threadDeliveries = []
    $scope.threadInboxes = []
    $scope.intervalSeconds = 3000
    $scope.alerts = []
    $scope.message = {}
    $scope.search = []
    $scope.searchError = false
    $scope.url = $window._url
    $scope.storage = localStorageService
    $scope.autoSubmitConversation = false

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
     * Store to localStorage in JSON.stringify
     *
     * @param  {string} key
     * @param  {object|array} data
     *
     * @return {void}
    ###
    $scope.store = ( key, data )->
        $scope.storage.set key, JSON.stringify data

        return

    $scope.autoSubmit = ->
        $scope.autoSubmitConversation = !$scope.autoSubmitConversation
        localStorageService.set 'auto_submit', $scope.autoSubmitConversation
        $log.log 'MessageController@autoSubmit', $scope.autoSubmitConversation

        return

    $scope.checkIfAutoSubmit = ->
        $log.log 'MessageController@checkIfAutoSubmit', localStorageService.get 'auto_submit'
        if ! localStorageService.get 'auto_submit'
            localStorageService.set 'auto_submit', true

        localStorageService.get 'auto_submit'

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

    ###*
     * Get all inquiries by page
     *
     * @param  {integer} page [Page number]
     *
     * @return {void}
    ###
    $scope.getAllInquiries = ( page )->
        $scope.inquiries = []
        $scope.changeHeading 'Inquiries'
        page = if page then page else 1
        $scope.inquiryState = true
        $scope.inquiryLoadingState = true
        $scope.inquiryErrorState = false
        $scope.inquiryConversations = []
        InquiryFactory.getAllInquiries( page )
            .success ( data, xhr )->
                $log.log 'getAllInquiries::data', data
                $scope.inquiryErrorState = false
                if Boolean( data.next_page_url )
                    $scope.getAllInquiries( data.current_page + 1 )

                return
            .error ( data, xhr )->
                $scope.inquiryErrorState = true
                $scope.inquiryLoadingState = false
                $scope.inquiryErrorMessage = data.error.message.replace '[INQUIRY] ', ''

                return
            .then ( data, xhr )->
                $scope.pushToInquiries data.data.data

                return

        return

    ###*
     * Push data to $scope.inquiries
     *
     * @param  {array} data
     *
     * @return {void}
    ###
    $scope.pushToInquiries = ( data )->
        angular.forEach data, ( value, key )->
            $scope.inquiries.push value
            return
        $scope.inquiryLoadingState = false
        $timeout(->
            $scope.inquiryState = false
            $scope.inquiryErrorState = false
        , 3000 )

        return

    ###*
     * Push data to $scope.inbox
     *
     * @param  {array} data
     *
     * @return {void}
    ###
    $scope.pushToInbox = ( data )->
        angular.forEach data, ( value, key )->
            $scope.inbox.push value
            return
        $scope.inboxLoadingState = false
        $timeout(->
            $scope.inboxState = false
        , 3000 )

    ###*
     * All shortcuts
     * instead of refreshing the whole page
     * just alt + r
     *
     * @return {void}
    ###
    $scope.keyBinder = ->
        $document.bind 'keyup', ( event )->
            # $log.info event
            if event.keyCode is 82 && event.altKey
                event.preventDefault()
                switch $state.current.name
                    when 'messages.inquiries'
                        if ! $scope.inquiryState
                            $scope.inquiries = []
                            $scope.getAllInquiries()
                    when 'messages.viewInquiry'
                        if ! $scope.inquiryState
                            $scope.inquiryConversations = []
                            $scope.getToInquiryMessages( $rootScope.$stateParams.inquiryId )
                    when 'messages.inbox'
                        if ! $scope.inboxState
                            $scope.inbox = []
                            $scope.getAllInboxes()
                    when 'messages.viewInbox'
                        if ! $scope.inboxState
                            $scope.inboxConversations = []
                            $scope.getToInboxMessages( $rootScope.$stateParams.inboxId, 1 )

        return

    ###*
     * Reply on inquiry
     *
     * @param  {object} event
     * @param  {object} form
     *
     * @return {mixed}
    ###
    $scope.inquiryReplySubmit = ( event, form )->
        event.preventDefault()
        $scope.replySubmitButton.state = !$scope.replySubmitButton.state
        tA = textAngularManager.retrieveEditor( 'reply' )
        data =
            message: form.reply.$modelValue
            item: $scope.inquiryInfo.product_id
            inquisition: $scope.inquiryInfo.inquisition_id
            inquiry: $rootScope.$stateParams.inquiryId

        InquiryFactory.replyInquiry( data )
            .success ( d, xhr )->
                $log.log 'inquiryReplySubmit::data', d
                tA.scope.$parent.reply = ''
                $scope.inquiryConversations.push d.success.data

                return
            .error ( data, xhr )->
                $scope.alerts.push data.error
                $timeout(->
                    $scope.alerts = []
                    $scope.inquiryConversations = []
                    $scope.getToInquiryMessages $rootScope.$stateParams.inquiryId
                    $scope.replySubmitButton.state = false
                    return
                , 4000 )

                return
            .then ( d )->
                $scope.replySubmitButton.state = !$scope.replySubmitButton.state
                $scope.backToTextArea( 500 )
                tA.scope.displayElements.text.trigger 'focus'

                return

        return

    ###*
     * Reply on Inbox
     *
     * @param  {object} event
     * @param  {object} form
     *
     * @return {mixed}
    ###
    $scope.inboxReplySubmit = ( event, form )->
        event.preventDefault()
        $scope.replySubmitButton.state = !$scope.replySubmitButton.state
        tA = textAngularManager.retrieveEditor( 'reply' )
        data =
            message: form.reply.$modelValue
            inbox: $scope.inboxInfo.id

        InboxFactory.reply( data )
            .success ( d, xhr )->
                $log.log 'inboxReplySubmit::data', d
                tA.scope.$parent.reply = ''
                $scope.inboxConversations.push d.success.data

                return
            .error ( data, xhr )->
                $scope.alerts.push data.error
                $timeout(->
                    $scope.alerts = []
                    $scope.getToInboxMessages $rootScope.$stateParams.inboxId

                    return
                , 4000 )

                return
            .then ( d )->
                $scope.replySubmitButton.state = !$scope.replySubmitButton.state
                $scope.backToTextArea( 500 )
                tA.scope.displayElements.text.trigger 'focus'

                return

        return

    ###*
     * Get to INQUIRY conversation
     *
     * @param  {integer} inquiryId
     * @param  {integer} pageNumber
     *
     * @return {mixed}
    ###
    $scope.getToInquiryMessages = ( inquiryId, pageNumber )->
        # $scope.inquiryConversations = []
        $scope.changeHeading 'Loading conversations'
        $scope.inquiryState = true
        InquiryFactory.getConversations( inquiryId, pageNumber )
            .success ( data, xhr )->
                $log.log 'getToInquiryMessages::data', data
                $scope.changeHeading data.inquiry.title, '<span>INQUIRY: &nbsp;</span>'
                $scope.inquiryErrorState = false
                if Boolean( data.conversations.next_page_url )
                    $scope.getToInquiryMessages( $rootScope.$stateParams.inquiryId, data.conversations.current_page + 1 )

                $scope.autoSubmitConversation = localStorageService.get 'auto_submit'
                return

            .error ( data, xhr )->
                $scope.inquiryErrorState = true
                $log.error 'getToInquiryMessages::data', data
                $scope.changeHeading 'ERROR'
                $scope.inquiryErrorMessage = data.error.message

                return
            .then ( data, xhr )->
                angular.forEach data.data.conversations.data, ( value, key )->
                    $scope.inquiryConversations.push value
                    return
                $scope.inquiryInfo = data.data.inquiry
                $scope.backToTextArea()
                $timeout(->
                    $scope.inquiryState = false
                , 3000 )

                return

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
            $scope.backToTextArea()
            tA.scope.displayElements.text.trigger 'focus'


            return

        return

    $scope.moveToDelivered = ->
        InquiryFactory.markAsDeliver
            inquiry: $rootScope.$stateParams.inquiryId
        .success ( data, xhr )->
            $log.log 'moveToDelivered::data', data
            $log.info 'moveToDelivered()::Checkuser', $rootScope.user.is_permitted

        # MessageFactory.updateToDeliver( $rootScope.$stateParams.threadId )
        #     .success ( data, xhr )->
        #         $log.log 'moveToDelivered::data', data
        #         UserFactory.getNotify() # So notification change
        #         $state.go 'messages.inquiries'

        #         return

        return

    ###*
     * Create INBOX
     *
     * @return {void}
    ###
    $scope.createMessage = ->
        $log.info 'Create a message'
        $scope.changeHeading 'Create'
        UserFactory.getNotify( $scope.creatingMessage )
        return

    $scope.creatingMessage = ->
        name = if $rootScope.me.user.is_permitted then $rootScope.me.user.first_name + ' ' + $rootScope.me.user.last_name else 'You'
        $scope.message.subject = 'Message from ' + name

        return

    ###*
     * Searching the user in INBOX
     *
     * @param  {object} event
     *
     * @return {void}
    ###
    $scope.getUser = ( event )->
        # event.preventDefault()
        $scope.search = []
        $scope.message.recipient = null
        if Boolean( $scope.message.send )
            $scope.searchUserByFilter $scope.message.send

        return

    ###*
     * The user Searched in INBOX
     *
     * @param  {object} user
     *
     * @return {void}
    ###
    $scope.sendWithUser = ( user )->
        $scope.message.recipient = user.id
        $scope.message.send = user.full_name
        $scope.search = []
        $log.log $scope.message
        return

    ###*
     * Search user in INBOX now on progress
     *
     * @param  {string} value
     *
     * @return {void}
    ###
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
        $scope.alerts = []
        InboxFactory.createMessage( event.target.action, $scope.message )
            .success ( data, xhr )->
                $log.log 'submitNewMessage::data', data
                $scope.messageSubmitButton.state = !$scope.messageSubmitButton.state
                $state.go 'messages.viewInbox',
                    inboxId: data.success.data.inbox.id

                return
            .error ( error, xhr )->
                $scope.message.recipient = null
                $scope.message.send = null
                $scope.messageSubmitButton.state = !$scope.messageSubmitButton.state
                Notification.error error.error

                return

        return

    ###*
     * Get all INBOX
     *
     * @param  {integer} page
     *
     * @return {void}
    ###
    $scope.getAllInboxes = ( page )->
        $scope.inbox = []
        $scope.changeHeading 'Inbox'
        page = if page then page else 1
        $scope.inboxState = true
        $scope.inboxLoadingState = true
        $scope.inboxErrorState = false
        $scope.inboxConversations = []
        InboxFactory.getAllInbox( page )
            .success ( data, xhr )->
                $log.log 'getAllInboxes::data', data
                $scope.inboxErrorState = false
                if Boolean( data.next_page_url )
                    $scope.getAllInboxes( data.current_page + 1 )

                return
            .error ( data, xhr )->
                $scope.inboxErrorState = true
                $scope.inboxLoadingState = false
                $scope.inboxErrorMessage = data.error.message.replace '[INBOX] ', ''
                return
            .then ( data, xhr )->
                angular.forEach data.data.data, ( value, key )->
                    $scope.inbox.push value
                    return
                $scope.inboxLoadingState = false
                $scope.inboxState = false
                $scope.inboxErrorState = false

                return

        return

    ###*
     * Get INBOX Conversation
     *
     * @param  {integer} inboxId
     * @param  {integer} pageNumber
     *
     * @return {void}
    ###
    $scope.getToInboxMessages = ( inboxId, pageNumber )->
        $scope.changeHeading 'Loading conversations'
        $scope.inboxState = true
        InboxFactory.getConversations( inboxId, pageNumber )
            .success ( data, xhr )->
                $log.log 'getToInboxMessages::data', data
                $scope.changeHeading data.inbox.title, '<span>INBOX: &nbsp;</span>'
                $scope.inboxErrorState = false

                if Boolean( data.conversations.next_page_url )
                    $scope.getToInboxMessages( $rootScope.$stateParams.inboxId, data.conversations.current_page + 1 )

                return

            .error ( data, xhr )->
                $scope.inboxErrorState = true
                $log.error 'getToInboxMessages::data', data
                $scope.changeHeading 'ERROR'
                $scope.inboxErrorMessage = data.error.message

                return
            .then ( data, xhr )->
                angular.forEach data.data.conversations.data, ( value, key )->
                    $scope.inboxConversations.push value
                    return
                $scope.inboxInfo = data.data.inbox
                $scope.backToTextArea()
                $timeout(->
                    $scope.inboxState = false
                , 3000 )

                return

        return

    $scope.inquiryReserve = ( event )->
        event.preventDefault()
        $scope.inquiryStateReserve = !$scope.inquiryStateReserve

        return

    $scope.reserveItem = ->
        InquiryFactory.reserveInquiry(
            inquiry: $scope.inquiryInfo.id
            reserve: $scope.reserve
        ).success ( success )->
            $log.log 'MessageController.reserveItem::success', success
            $log.log $scope.reserve
            $scope.inquiryStateReserve = !$scope.inquiryStateReserve
            Notification.success success.success
            $scope.reserve = 0
            $scope.inquiryInfo = success.success.data.inquiry
            $log.log $scope.reserve

            return
        .error ( error )->
            Notification.error error.error

            return

        .then ( data )->
            $scope.reserve = 0
            $log.log $scope.reserve

            return

        return

    $scope.destroyConversation = ( id, index )->
        InboxFactory.removeConversation id
            .success ( success )->
                Notification.success success.success
                $scope.inboxConversations.splice( index, 1 )
                
                return
            .error ( error )->
                Notification.error error.error

                return

        return

    $scope.reserveButton = ( e )->
        switch e
            when 'minus'
                $scope.reserve = if ( $scope.reserve > 1 ) then $scope.reserve - 1 else 0
            when 'add'
                $scope.reserve = if ( $scope.reserve < $scope.inquiryInfo.product.unit ) then $scope.reserve + 1 else $scope.reserve + 0
            else

        return

    $scope.conversationShortcuts = ( event, form )->
        # $log.log 'MessageController@conversationShortcuts.event', event
        tA = textAngularManager.retrieveEditor( 'reply' )
        
        if ( event.keyCode is 13 && event.altKey )
            $log.log 'MessageController@conversationShortcuts.form.$valid', form.$valid
            $log.log 'MessageController@conversationShortcuts.tA', tA
            $log.log 'MessageController@conversationShortcuts.form', form
            # tA.scope.$parent.reply = ''
            
            # $log.log tA.editorFunctions.sendKeyCommand( event )
            if( form.$valid )
                event.preventDefault()
                $log.log tA.scope.html = ''
                # $scope.emptyTextArea()
                # $scope.inquiryReplySubmit( event, form )
                # $( '#conversation_submit' ).submit()


            event.preventDefault()

        return

    $scope.emptyTextArea = ->
        tA = textAngularManager.retrieveEditor( 'reply' )
        tA.scope.$parent.reply = ''

        return

    $scope.getInquiriesByProductId = ( id, page )->
        $scope.inquiries = []
        $scope.changeHeading 'Inquiries by Product ' + id
        $scope.inquiryState = true
        $scope.inquiryLoadingState = true
        $scope.inquiryErrorState = false
        $scope.inquiryConversations = []
        InquiryFactory.getByProduct( id, page )
            .success ( success )->
                $log.log 'MessageController@getInquiriesByProductId::success', success
                $scope.inquiryErrorState = false
                if Boolean( success.next_page_url )
                    $scope.getInquiriesByProductId( $stateParams.productId, success.current_page + 1 )

                return
            .error ( error )->
                $scope.inquiryErrorState = true
                $scope.inquiryLoadingState = false
                $scope.inquiryErrorMessage = error.error.message.replace '[INQUIRY] ', ''

                return
            .then ( data )->
                $scope.pushToInquiries data.data.success.data.data

                return

        return




    return
