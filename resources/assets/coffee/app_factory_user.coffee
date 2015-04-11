_okie.factory 'UserFactory', ( $http, $state, $stateParams, $rootScope, localStorageService, $window, $log )->

    _u = {}

    ids = 
        message: '[data-notify=message]'
        delivered: '[data-notify=delivered]'
        inbox: '[data-notify=inbox]'
        inquiry: '[data-notify=inquiry]'

    _u.route = 
        index: '/'
        messages: '/me/messages'
        settings: '/me/settings'
        products: '/me/products'

    _u.messages = 0

    _u.redirectInItem = ->
        $log.info 'RedirectInItem', $stateParams.itemId

    _u.checkRedirectItem = ->
        if localStorageService.get 'redirect_to_item'
            $log.log 'Now redirecting to ' + localStorageService.get 'redirect_to_item'
            $state.go 'item', 
                itemId: localStorageService.get 'redirect_to_item'
            localStorageService.remove 'redirect_to_item'

    _u.checkUnreadMessages = ( element, count )->
        $( element ).each ( k, v )->
            if( count > 0 )
                if $( v ).hasClass 'with-tooltip'
                    $( v ).attr 'data-original-title', 'You have new messages'
                          .tooltip 'fixTitle'

                $( v ).find '.badge'
                      .text count
            else
                if $( v ).hasClass 'with-tooltip'
                    $( v ).attr 'data-original-title', 'See all messages'
                          .tooltip 'fixTitle'

                $( v ).find '.badge'
                      .empty()

    _u.notifyToBadges = ( counts )->
        if counts.delivered
            _u.checkUnreadMessages( ids.delivered, counts.delivered )

        _u.checkUnreadMessages( ids.inquiry, counts.inquiry )
        _u.checkUnreadMessages( ids.inbox, counts.inbox )

        return

    _u.checkIfUnreadMessages = ( messages )->
        if( messages )
            $( ids.message ).each (k,v)->
                
                if $( v ).hasClass 'with-tooltip'
                    $( v )
                    .attr 'data-original-title', 'New messages'
                    .tooltip 'fixTitle'

                $( v )
                .find '.badge'
                .text messages

                return
            return
        else
            $( ids.message ).each (k,v)->

                if $( v ).hasClass 'with-tooltip'
                    $( v )
                    .attr 'data-original-title', 'See all messages'
                    .tooltip 'fixTitle'

                $( v )
                .find '.badge'
                .empty()

                return
            return

    _u.getNotify = ->
        $http(
            url: '/me'
            ignoreLoadingBar: true
        ).success ( data, xhr )->
            $log.log 'getNotify::data', data
            $rootScope.me = data
            _u.checkState()
#            _u.checkIfUnreadMessages data.messages.all
            _u.checkRedirectItem()
#            _u.notifyToBadges data.messages

            return

    _u.getUser = ->
        $http(
            url: '/me'
            ignoreLoadingBar: true
        )

    _u.checkState = ->
        $log.info 'UserFactory::checkState()', $window.location.pathname
        switch $window.location.pathname
            when _u.route.messages
                if $state.current.name is 'index'
                    $state.go 'messages.inquiries'
                break
            when _u.route.products
                if $state.current.name is 'index'
                    $state.go 'products.all'
                break

            
        

    _u
