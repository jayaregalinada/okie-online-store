_okie.factory 'MessageFactory', ( $http )->

    _m = {}
    urls = 
        base: '/me/messages'
        inquiries: '/me/messages/inquiries'
        inquire: '/me/messages/inquire/'
        delivered: '/me/messages/delivered'
        updateDeliver: '/me/messages/inquire/delivered'
        offset: '/me/messages/offset/'
        inbox: '/me/messages/inbox'
        search:
            users: '/search/user/'

    _m.urls = urls



    _m.getAllMessages = ->
        console.log 'Get All Messages'

    _m.getInquiryMessages = ( pageNumber )->
        $http
            url: urls.inquiries
            method: "GET"
            params:
                page: ( if ( pageNumber ) then pageNumber else 1 )

    _m.getThreadMessages = ( threadId )->
        $http
            url: urls.inquire + threadId

    _m.getMessages = ( id, pageNumber )->
        $http
            url: urls.inquire + id + '/messages'
            method: "GET"
            params:
                page: ( if ( pageNumber ) then pageNumber else 1 )

    _m.replyToMessage = ( action, message )->
        $http
            url: action
            method: "POST"
            data: message

    _m.getDeliveryMessages = ( pageNumber )->
        $http
            url: urls.delivered
            method: "GET"
            params:
                page: ( if ( pageNumber ) then pageNumber else 1 )

    _m.updateToDeliver = ( threadId )->
        $http
            url: urls.updateDeliver
            method: "POST"
            data:
                id: threadId
        
    _m.getMessageOffset = ( thread, offset, take )->
        $http
            url: urls.offset
            method: "GET"
            params:
                offset: offset
                thread: thread
                take: ( if ( take ) then take else 5 )
        

    _m.sendMessage = ( url, data )->
        $http
            url: url
            method: "POST"
            data: data
        

    _m.getInboxMessages = ( pageNumber )->
        $http
            url: urls.inbox
            method: "GET"
            params:
                page: ( if ( pageNumber ) then pageNumber else 1 )

    _m
