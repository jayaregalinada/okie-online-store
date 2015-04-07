###########
## ROUTES
###########

window._okie.config ( $stateProvider, $urlRouterProvider )->

    # Items
    $stateProvider
        .state 'index',
            controller: 'ItemController'
            url: '/'
            views:
                'items': 
                    templateUrl: '/views/items/index.html'
                    controller: 'ItemController'

        .state 'item',
            url: '/item/:itemId'
            views:
                'items':
                    controller: 'ItemController'
                    templateUrl: '/views/items/item.html'


        .state 'category',
            url: '/category/:categoryId'
            views:
                'items': 
                    templateUrl: '/views/items/index.html'
                    controller: 'ItemController'

    # Products
    $stateProvider
        .state 'products',
            abstract: true
            controller: 'ProductController'
            template: '<ui-view/>'

        .state 'products.category',
            parent: 'products'
            url: '^/category'
            templateUrl: '/views/products/category.html'
            controller: ( $scope )->
                $scope.header = 'Category'
                $scope.getCategories()

                return

        .state 'products.all',
            parent: 'products'
            url: '^/all'
            templateUrl: '/views/products/index.html'
            controller: ( $scope )->
                $scope.header = 'Products'
                $scope.getProducts()

                return

    # Messages
    $stateProvider
        .state 'messages',
            abstract: true
            controller: 'MessageController'
            template: '<ui-view/>'
            url: '/messages'

        .state 'messages.inquiries',
            parent: 'messages'
            url: '^/inquiries'
            templateUrl: '/views/messages/messages.html'
            controller: ( $scope )->
                $scope.header = 'Inquiries'
                $scope.getAllInquiries()

                return

        .state 'messages.delivered',
            parent: 'messages'
            url: '^/delivered'
            templateUrl: '/views/messages/messages.html'
            controller: ( $scope )->
                $scope.header = 'Delivered'
                $scope.getAllDeliveries()

                return

        .state 'messages.create',
            parent: 'messages'
            url: '^/create'
            templateUrl: '/views/messages/create.html'
            controller: ( $scope )->
                $scope.header = 'Create'
                $scope.createMessage()

                return

        .state 'messages.inbox',
            parent: 'messages'
            url: '^/inbox'
            templateUrl: '/views/messages/inbox.html'
            controller: ( $scope )->
                $scope.header = 'Inbox'
                $scope.getAllInboxes()

                return

        .state 'messages.viewInbox',
            parent: 'messages'
            url: '^/inbox/:msgId'
            templateUrl: '/views/messages/conversation_inbox.html'
            controller: ( $scope, $stateParams )->
                $scope.getToInboxConversation( $stateParams.msgId )
                $scope.msgId = $stateParams.msgId

                return

        .state 'messages.thread',
            parent: 'messages'
            url: '^/view/:threadId'
            templateUrl: '/views/messages/conversation.html'
            controller: ( $scope, $stateParams )->
                $scope.getToConversation( $stateParams.threadId )
                $scope.threadId = $stateParams.threadId

                return

        .state 'messages.threaddeliver',
            parent: 'messages'
            url: '^/deliver/:threadId'
            templateUrl: '/views/messages/conversation_delivered.html'
            controller: ( $scope, $stateParams )->
                $scope.getToConversation( $stateParams.threadId )
                $scope.threadId = $stateParams.threadId

                return

    $urlRouterProvider.otherwise( '/' );




    return
