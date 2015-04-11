(function() {
  window._okie = angular.module('Okie', ['ui.bootstrap', 'ngAnimate', 'ui.router', 'ng-currency', 'bootstrapLightbox', 'LocalStorageModule', 'slugifier', 'textAngular']);

  window._okie.config(function($interpolateProvider, LightboxProvider, localStorageServiceProvider, $httpProvider, $animateProvider) {
    $interpolateProvider.startSymbol('{#');
    $interpolateProvider.endSymbol('#}');
    LightboxProvider.getImageUrl = function(image) {
      return image.sizes[0].url;
    };
    LightboxProvider.getImageCaption = function(image) {
      return image.caption;
    };
    LightboxProvider.calculateModalDimensions = function(dimensions) {
      var width;
      width = Math.max(400, dimensions.imageDisplayWidth + 32);
      if (width >= dimensions.windowWidth - 20 || dimensions.windowWidth < 768) {
        width = 'auto';
      }
      return {
        'width': width,
        'height': 'auto'
      };
    };
    LightboxProvider.templateUrl = '/views/product/lightbox.html';
    localStorageServiceProvider.setPrefix('okie');
    $httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
    $animateProvider.classNameFilter(/carousel|animate/);
  });

  window._okie.run(function($rootScope, $state, $stateParams, UserFactory) {
    'use strict';
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
    $rootScope.$messagesCount = UserFactory.messages;
    $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
      UserFactory.getNotify();
    });
  });

  Dropzone.autoDiscover = false;

  angular.element(document).ready(function() {
    angular.bootstrap(document, ['Okie']);
    $('[data-toggle="tooltip"]').tooltip({
      container: 'body'
    });
    $('[data-toggle="popover"]').popover();
    return $('.content-container').css({
      minHeight: ($(window).height() - ($('#navigation').outerHeight() + $('#footer').outerHeight())) - 28
    });
  });

}).call(this);

(function() {
  window._okie.config(function($stateProvider, $urlRouterProvider) {
    $stateProvider.state('index', {
      controller: 'ItemController',
      url: '/',
      views: {
        'items': {
          templateUrl: '/views/items/index.html',
          controller: 'ItemController'
        }
      }
    }).state('item', {
      url: '/item/:itemId',
      views: {
        'items': {
          controller: 'ItemController',
          templateUrl: '/views/items/item.html'
        }
      }
    }).state('category', {
      url: '/category/:categoryId',
      views: {
        'items': {
          templateUrl: '/views/items/index.html',
          controller: 'ItemController'
        }
      }
    });
    $stateProvider.state('products', {
      abstract: true,
      controller: 'ProductController',
      template: '<ui-view/>'
    }).state('products.category', {
      parent: 'products',
      url: '^/category',
      templateUrl: '/views/products/category.html',
      controller: function($scope) {
        $scope.header = 'Category';
        $scope.getCategories();
      }
    }).state('products.all', {
      parent: 'products',
      url: '^/all',
      templateUrl: '/views/products/index.html',
      controller: function($scope) {
        $scope.header = 'Products';
        $scope.getProducts();
      }
    });
    $stateProvider.state('messages', {
      abstract: true,
      controller: 'MessageController',
      template: '<ui-view/>',
      url: '/messages'
    }).state('messages.inquiries', {
      parent: 'messages',
      url: '^/inquiries',
      templateUrl: '/views/messages/inquiries.html',
      controller: function($scope) {
        $scope.header = 'Inquiries';
        $scope.getAllInquiries();
        $scope.keyBinder();
      }
    }).state('messages.create', {
      parent: 'messages',
      url: '^/create',
      templateUrl: '/views/messages/create.html',
      controller: function($scope) {
        $scope.header = 'Create';
        $scope.createMessage();
      }
    }).state('messages.inbox', {
      parent: 'messages',
      url: '^/inbox',
      templateUrl: '/views/messages/inbox.html',
      controller: function($scope) {
        $scope.header = 'Inbox';
        $scope.getAllInboxes();
        $scope.keyBinder();
      }
    }).state('messages.viewInbox', {
      parent: 'messages',
      url: '^/inbox/view/:inboxId',
      templateUrl: '/views/messages/conversation_inbox.html',
      controller: function($scope, $stateParams) {
        $scope.getToInboxMessages($stateParams.inboxId, 1);
        $scope.keyBinder();
      }
    }).state('messages.thread', {
      parent: 'messages',
      url: '^/view/:threadId',
      templateUrl: '/views/messages/conversation.html',
      controller: function($scope, $stateParams) {
        $scope.getToConversation($stateParams.threadId);
        $scope.threadId = $stateParams.threadId;
      }
    }).state('messages.viewInquiry', {
      parent: 'messages',
      url: '^/inquiry/read/:inquiryId',
      templateUrl: '/views/messages/conversation_inquiry.html',
      controller: function($scope, $stateParams) {
        $scope.getToInquiryMessages($stateParams.inquiryId, 1);
        $scope.keyBinder();
      }
    });
    $stateProvider.state('delivered', {
      abstract: true,
      controller: 'DeliverController',
      template: '<ui-view/>',
      url: '/deliver'
    }).state('delivered.all', {
      parent: 'delivered',
      url: '/all',
      templateUrl: '/views/messages/deliver.html',
      controller: function($scope) {
        $scope.getAllDeliver();
      }
    }).state('delivered.viewDeliver', {
      parent: 'delivered',
      url: '/read/:deliverId',
      templateUrl: '/views/messages/conversation_deliver.html',
      controller: function($scope, $stateParams) {
        $scope.getToConversation($stateParams.deliverId, 1);
      }
    });
    $stateProvider.state('settings', {
      abstract: true,
      controller: 'UserSettingsController',
      template: '<ui-view/>',
      url: '/settings'
    }).state('settings.newsletter', {
      parent: 'settings',
      url: '^/newsletter',
      templateUrl: '/views/settings/newsletter.html',
      controller: function($scope) {
        $scope.getEmailSubscribe();
      }
    });
    $urlRouterProvider.otherwise('/');
  });

}).call(this);

(function() {
  _okie.controller('ItemController', function($scope, $log, $http, $window, ItemFactory, $state, $stateParams, localStorageService, $timeout) {
    $scope.items = [];
    $scope.item = {};
    $scope.carouselInterval = 3000;
    $scope.categoryFilterName = $stateParams.categoryId;
    $scope.inquireState = false;
    $scope.inquireMessages = [];
    $scope.inquireSubmitButton = {
      text: 'SUBMIT',
      state: false,
      success: false
    };
    $scope.inquireResponseTime = 5000;
    $scope.backToHeader = function(delayTime, animateTime) {
      $timeout(function() {
        return $('body,html').animate({
          scrollTop: $('#header').offset().top - $('#navigation').outerHeight(true)
        }, animateTime ? animateTime : 1000);
      }, delayTime ? delayTime : 500);
    };
    $scope.checkState = function() {
      $log.info('ItemController.checkState()', $state.current);
      switch ($state.current.name) {
        case 'item':
          $scope.getItem($stateParams.itemId);
          break;
        case 'category':
          $scope.getItemsByCategory(1);
          break;
        case 'index':
          $scope.getAllItem();
      }
    };
    $scope.inquireItem = function() {
      $scope.inquireState = !$scope.inquireState;
      $log.log('Inquiring item', $stateParams.itemId);
    };
    $scope.inquireSubmit = function(e) {
      e.preventDefault();
      $scope.inquireSubmitButton.state = !$scope.inquireSubmitButton.state;
      $scope.inquireSubmitButton.text = 'SUBMITTING';
      $scope.inquireMessages = [];
      ItemFactory.sendInquiryMessage({
        item: $stateParams.itemId,
        message: $scope.inquire
      }).success(function(data, xhr) {
        $scope.backToHeader();
        $scope.inquireSubmitButton.state = !$scope.inquireSubmitButton.state;
        $scope.inquire = '';
        $scope.inquireMessages.push({
          type: 'success',
          message: data.success.message
        });
      }).error(function(data, xhr) {
        $scope.inquireSubmitButton.state = !$scope.inquireSubmitButton.state;
        $scope.inquireMessages.push({
          type: 'danger',
          message: data.error.message
        });
      }).then(function(data) {
        $timeout(function() {
          return $scope.inquireMessages.splice(0, $scope.inquireMessages.length);
        }, $scope.inquireResponseTime);
      });
    };
    $scope.closeInquireMessage = function(index) {
      $scope.inquireMessages.splice(index, 1);
    };
    $scope.getItemsByCategory = function(pageNumber) {
      ItemFactory.getAllByCategory($stateParams.categoryId, pageNumber).success(function(data, xhr) {
        if (data.to > 0) {
          if ($scope.items.length < data.total) {
            $scope.getItemsByCategory(data.current_page + 1);
            angular.forEach(data.data, function(value, key) {
              $scope.items.push(value);
            });
          }
        }
      });
    };
    $scope.getAllItem = function(pageNumber) {
      return ItemFactory.getAll(pageNumber).success(function(data, xhr) {
        if ($scope.items.length < data.total) {
          $scope.getAllItem(data.current_page + 1);
          angular.forEach(data.data, function(value, key) {
            $scope.items.push(value);
          });
        }
      });
    };
    $scope.getItem = function(id) {
      ItemFactory.getItem(id).success(function(data, xhr) {
        $scope.item = data;
        $log.log('item: ', $scope.item);
      });
    };
    $scope.redirectInItem = function() {
      localStorageService.set('redirect_to_item', $stateParams.itemId);
    };
    $scope.checkState();
  });

}).call(this);

(function() {
  _okie.factory('ItemFactory', function($http, $q) {
    var _i, urls;
    _i = {};
    urls = {
      base: '/items/',
      category: '/items/category/',
      inquiry: '/item/inquire'
    };

    /**
     * Get all items by page number
     *
     * @param  {integer} pageNumber
     *
     * @return $http
     */
    _i.getAll = function(pageNumber) {
      return $http({
        url: urls.base,
        params: {
          page: (pageNumber ? pageNumber : 1)
        }
      });
    };

    /**
     * Get item by id
     *
     * @param  {integer} id
     *
     * @return $http
     */
    _i.getItem = function(id) {
      return $http({
        url: urls.base + id
      });
    };

    /**
     * Get all items by its category
     *
     * @param  {integer|string} category
     * @param  {integer} pageNumber
     *
     * @return $http
     */
    _i.getAllByCategory = function(category, pageNumber) {
      return $http({
        url: urls.category + category,
        params: {
          page: (pageNumber ? pageNumber : 1)
        }
      });
    };

    /**
     * Get Item Url
     *
     * @return {string}
     */
    _i.getItemUrl = function() {
      return urls.base;
    };

    /**
     * Set Item url
     *
     * @param {string} url
     *
     * @return {string}
     */
    _i.setItemUrl = function(url) {
      return urls.base = url;
    };

    /**
     * Send Inquiry message to the product
     *
     * @param  {object} message
     * @param  {object} params
     *
     * @return $http
     */
    _i.sendInquiryMessage = function(message, params) {
      return $http({
        url: urls.inquiry,
        data: message,
        params: params,
        method: "POST"
      });
    };
    return _i;
  });

}).call(this);

(function() {
  _okie.factory('SearchFactory', function($http, $state, $stateParams, $rootScope, localStorageService) {
    var _s;
    _s = {};
    _s.url = {
      base: '/search/',
      users: '/search/user/'
    };
    _s.getUser = function(user, params) {
      return $http({
        url: _s.url.users + user,
        method: "GET",
        params: params
      });
    };
    return _s;
  });

}).call(this);

(function() {
  _okie.factory('UserFactory', function($http, $state, $stateParams, $rootScope, localStorageService, $window, $log) {
    var _u, ids;
    _u = {};
    ids = {
      message: '[data-notify=message]',
      delivered: '[data-notify=delivered]',
      inbox: '[data-notify=inbox]',
      inquiry: '[data-notify=inquiry]'
    };
    _u.route = {
      index: '/',
      messages: '/me/messages',
      settings: '/me/settings',
      products: '/me/products'
    };
    _u.messages = 0;
    _u.redirectInItem = function() {
      return $log.info('RedirectInItem', $stateParams.itemId);
    };
    _u.checkRedirectItem = function() {
      if (localStorageService.get('redirect_to_item')) {
        $log.log('Now redirecting to ' + localStorageService.get('redirect_to_item'));
        $state.go('item', {
          itemId: localStorageService.get('redirect_to_item')
        });
        return localStorageService.remove('redirect_to_item');
      }
    };
    _u.checkUnreadMessages = function(element, count) {
      return $(element).each(function(k, v) {
        if (count > 0) {
          if ($(v).hasClass('with-tooltip')) {
            $(v).attr('data-original-title', 'You have new messages').tooltip('fixTitle');
          }
          return $(v).find('.badge').text(count);
        } else {
          if ($(v).hasClass('with-tooltip')) {
            $(v).attr('data-original-title', 'See all messages').tooltip('fixTitle');
          }
          return $(v).find('.badge').empty();
        }
      });
    };
    _u.notifyToBadges = function(counts) {
      if (counts.delivered) {
        _u.checkUnreadMessages(ids.delivered, counts.delivered);
      }
      _u.checkUnreadMessages(ids.inquiry, counts.inquiry);
      _u.checkUnreadMessages(ids.inbox, counts.inbox);
    };
    _u.checkIfUnreadMessages = function(messages) {
      if (messages) {
        $(ids.message).each(function(k, v) {
          if ($(v).hasClass('with-tooltip')) {
            $(v).attr('data-original-title', 'New messages').tooltip('fixTitle');
          }
          $(v).find('.badge').text(messages);
        });
      } else {
        $(ids.message).each(function(k, v) {
          if ($(v).hasClass('with-tooltip')) {
            $(v).attr('data-original-title', 'See all messages').tooltip('fixTitle');
          }
          $(v).find('.badge').empty();
        });
      }
    };
    _u.getNotify = function() {
      return $http({
        url: '/me',
        ignoreLoadingBar: true
      }).success(function(data, xhr) {
        $log.log('getNotify::data', data);
        $rootScope.me = data;
        _u.checkState();
        _u.checkRedirectItem();
      });
    };
    _u.getUser = function() {
      return $http({
        url: '/me',
        ignoreLoadingBar: true
      });
    };
    _u.checkState = function() {
      $log.info('UserFactory::checkState()', $window.location.pathname);
      switch ($window.location.pathname) {
        case _u.route.messages:
          if ($state.current.name === 'index') {
            $state.go('messages.inquiries');
          }
          break;
        case _u.route.products:
          if ($state.current.name === 'index') {
            $state.go('products.all');
          }
          break;
      }
    };
    return _u;
  });

}).call(this);
