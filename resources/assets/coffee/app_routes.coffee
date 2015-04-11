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
            templateUrl: '/views/messages/inquiries.html'
            controller: ( $scope )->
                $scope.header = 'Inquiries'
                $scope.getAllInquiries()
                $scope.keyBinder()

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
                $scope.keyBinder()

                return

        .state 'messages.viewInbox',
            parent: 'messages'
            url: '^/inbox/view/:inboxId'
            templateUrl: '/views/messages/conversation_inbox.html'
            controller: ( $scope, $stateParams )->
                $scope.getToInboxMessages( $stateParams.inboxId, 1 )
                $scope.keyBinder()

                return

        .state 'messages.thread',
            parent: 'messages'
            url: '^/view/:threadId'
            templateUrl: '/views/messages/conversation.html'
            controller: ( $scope, $stateParams )->
                $scope.getToConversation( $stateParams.threadId )
                $scope.threadId = $stateParams.threadId

                return

        .state 'messages.viewInquiry',
            parent: 'messages'
            url: '^/inquiry/read/:inquiryId'
            templateUrl: '/views/messages/conversation_inquiry.html'
            controller: ( $scope, $stateParams )->
                $scope.getToInquiryMessages( $stateParams.inquiryId, 1 )
                $scope.keyBinder()

                return

    # Deliver
    $stateProvider
        .state 'delivered',
            abstract: true
            controller: 'DeliverController'
            template: '<ui-view/>'
            url: '/deliver'

        .state 'delivered.all',
            parent: 'delivered'
            url: '/all'
            templateUrl: '/views/messages/deliver.html'
            controller: ( $scope )->
                $scope.getAllDeliver()

                return

        .state 'delivered.viewDeliver',
            parent: 'delivered'
            url: '/read/:deliverId'
            templateUrl: '/views/messages/conversation_deliver.html'
            controller: ( $scope, $stateParams )->
                $scope.getToConversation( $stateParams.deliverId, 1 )

                return

    # Settings (User)
    $stateProvider
        .state 'settings',
            abstract: true
            controller: 'UserSettingsController'
            template: '<ui-view/>'
            url: '/settings'

        .state 'settings.newsletter',
            parent: 'settings'
            url: '^/newsletter'
            templateUrl: '/views/settings/newsletter.html'
            controller: ( $scope )->
                $scope.getEmailSubscribe()

                return


    $urlRouterProvider.otherwise( '/' );

    return
