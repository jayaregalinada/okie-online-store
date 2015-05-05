
/**
 * OKIE Angularjs application module
 *
 * @type {object}
 */

(function() {
  window._okie = angular.module('Okie', ['ui.bootstrap', 'ngAnimate', 'ui.router', 'ng-currency', 'bootstrapLightbox', 'LocalStorageModule', 'slugifier', 'textAngular', 'ui-notification', 'ui.select', 'ngTagsInput', 'colorpicker.module']);


  /**
   * OKIE Configuration
   */

  window._okie.config(function($interpolateProvider, $locationProvider, LightboxProvider, localStorageServiceProvider, $httpProvider, $animateProvider) {
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
      width = Math.max(400, dimensions.imageDisplayWidth - 8);
      if (width >= dimensions.windowWidth - 20 || dimensions.windowWidth < 768) {
        width = 'auto';
      }
      return {
        'width': width,
        'height': 'auto'
      };
    };
    LightboxProvider.templateUrl = 'views/lightbox.html';
    localStorageServiceProvider.setPrefix('okie');
    $httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
    $animateProvider.classNameFilter(/carousel|animate/);
  });

  window._okie.run(function($rootScope, $state, $stateParams, UserFactory, $templateCache, Notification, $window, $location, $log) {
    'use strict';
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
    $rootScope.$messagesCount = UserFactory.messages;
    $rootScope.notification = Notification;
    $window.Notification = Notification;
    $rootScope.location = $window.location;
    $rootScope.collapseToggle = function() {
      $('#navbar_navigation .dropdown .collapse').collapse('hide');
    };
    $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
      UserFactory.getNotify();
    });
    $rootScope.$on('cfpLoadingBar:loading', function(loading) {
      $log.log('cfpLoadingBar:loading', loading);
    });
    $rootScope.$on('cfpLoadingBar:started', function(started) {
      $log.log('cfpLoadingBar:started', started);
    });
    $rootScope.$on('cfpLoadingBar:completed', function(completed) {
      $log.log('cfpLoadingBar:completed', completed);
    });
    $templateCache.put('angular-ui-notification.html', '<div class="ui-notification"><h3 ng-show="title" ng-bind-html="title"></h3><div class="message" ng-bind-html="message"></div></div>');
  });


  /**
   * For Dropzone autodiscovery
   * 
   * @type {boolean}
   */

  Dropzone.autoDiscover = false;


  /**
   * Initialize if document is ready
   *
   * @return {void}
   */

  angular.element(document).ready(function() {
    angular.bootstrap(document, ['Okie']);
    $('[data-toggle="tooltip"]').tooltip({
      container: 'body'
    });
    $('[data-toggle="popover"]').popover();
    $('.content-container').css({
      minHeight: ($(window).height() - ($('#navigation').outerHeight() + ($('#navigation').outerHeight() / 2) + $('#footer').outerHeight())) - 28
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
        },
        'banner': {
          templateUrl: '/views/banners.html',
          controller: 'BannerController'
        }
      }
    }).state('item', {
      url: '/item/:itemId',
      views: {
        'items': {
          templateUrl: '/views/items/item.html',
          controller: 'ItemController'
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
    }).state('messages.inquiriesProduct', {
      parent: 'messages',
      url: '^/inquiries/product/:productId',
      templateUrl: '/views/messages/inquiries.html',
      controller: function($scope, $stateParams) {
        $scope.header = 'Inquiries by Product ' + $stateParams.productId;
        $scope.getInquiriesByProductId($stateParams.productId);
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
    $stateProvider.state('asettings', {
      abstract: true,
      controller: 'AdminSettingsController',
      template: '<ui-view/>',
      url: '/settings'
    }).state('asettings.permissions', {
      parent: 'asettings',
      url: '^/permissions',
      templateUrl: '/views/settings/permissions.html',
      controller: function($scope) {
        $scope.getPermissions();
      }
    }).state('asettings.general', {
      parent: 'asettings',
      url: '^/general',
      templateUrl: '/views/settings/general.html',
      controller: function($scope) {
        $scope.getGeneral();
      }
    }).state('asettings.banners', {
      parent: 'asettings',
      url: '^/banners',
      templateUrl: '/views/settings/banner.html',
      controller: function($scope) {
        $scope.getAllBanads();
      }
    });
    $stateProvider.state('reviews', {
      abstract: true,
      controller: 'ReviewController',
      template: '<ui-view/>',
      url: '/review'
    }).state('reviews.all', {
      parent: 'reviews',
      url: '/all',
      templateUrl: '/views/reviews/all.html',
      controller: function($scope) {
        $scope.getAllReviews();
      }
    });
    $urlRouterProvider.otherwise('/');
  });

}).call(this);

(function() {
  _okie.animation('.items-animation', function($timeout) {
    var queue, queueAnimation;
    queue = {
      enter: [],
      leave: []
    };
    queueAnimation = function(event, delay, fn) {
      var index, timeouts;
      timeouts = [];
      index = queue[event].length;
      queue[event].push(fn);
      queue[event].timer && $timeout.cancel(queue[event].timer);
      queue[event].timer = $timeout(function() {
        angular.forEach(queue[event], function(fn, index) {
          timeouts[index] = $timeout(fn, index * delay * 1000, false);
        });
        queue[event] = [];
      }, 10, false);
      return function() {
        if (timeouts[index]) {
          $timeout.cancel(timeouts[index]);
        } else {
          queue[index] = angular.noop;
        }
      };
    };
    return {
      enter: function(element, done) {
        var cancel, onClose;
        element = $(element[0]);
        cancel = queueAnimation('enter', 0.1, function() {
          var cancelFn;
          element.css({
            bottom: -20,
            opacity: 0
          });
          element.animate({
            bottom: 0,
            opacity: 1
          }, done);
          element.addClass('enter');
          cancelFn = cancel;
          cancel = function() {
            cancelFn();
            element.stop();
            element.css({
              bottom: 0,
              opacity: 1
            });
          };
        });
        return onClose = function(cancelled) {
          cancelled && cancel();
        };
      },
      leave: function(element, done) {
        var cancel, onClose;
        element = $(element[0]);
        cancel = queueAnimation('leave', 0.1, function() {
          var cancelFn;
          element.css({
            bottom: 0,
            opacity: 1
          });
          element.animate({
            bottom: -20,
            opacity: 0
          }, done);
          element.addClass('leave');
          cancelFn = cancel;
          return cancel = function() {
            cancelFn();
            element.stop();
          };
        });
        return onClose = function(cancelled) {
          cancelled && cancel();
        };
      }
    };
  });

}).call(this);

(function() {
  _okie.controller('BannerController', function($scope, $log, $http, $state, $stateParams) {
    $scope.banners = [];
    $scope.bannerInterval = 3000;
    $scope.checkState = function() {
      $log.info('BannerController@checkState', $state.current);
      switch ($state.current.name) {
        case 'index':
          $scope.getAllBanads();
          break;
        default:
          $log.log('Nothing will return');
      }
    };
    $scope.getAllBanads = function() {
      $scope.banners = [];
      $http.get('banners').success(function(success) {
        $log.log('ItemController@getAllBanads::success', success);
        $scope.bannerInterval = success.success.data.interval;
        angular.forEach(success.success.data.banners, function(val, key) {
          $scope.banners.push(val);
        });
      });
    };
    $scope.initializeDropzone = function(url, token) {
      $log.info('BannerController@initializeDropzone::url', url);
      $scope.dropzoneInit = new Dropzone(document.body, {
        url: url,
        previewsContainer: '#productPreview',
        clickable: false,
        acceptedFiles: 'image/*',
        params: {
          '_token': token
        }
      });
      $scope.dropzoneInit.on('queuecomplete', function(file, xhr) {
        $scope.getAllBanads();
        Notification.success({
          title: 'Hooray!',
          message: 'Uploading complete'
        });
        this.removeAllFiles();
        $('#product_add_image_form header.drag').fadeIn();
        $('#product_add_image_form header.dropping').hide();
      });
      $scope.dropzoneInit.on('dragenter', function(file, xhr) {
        $log.info('DROPZONE DRAG ENTER');
        $('#product_add_image_form header.drag').hide();
        $('#product_add_image_form header.dropping').fadeIn();
      });
      $scope.dropzoneInit.on('drop', function(file, xhr) {
        $('#product_add_image_form header').hide();
      });
    };
    $scope.checkState();
  });

}).call(this);

(function() {
  _okie.controller('ItemController', function($rootScope, $scope, $log, $http, $window, ItemFactory, $state, $stateParams, localStorageService, $timeout, RatingFactory, Notification, Lightbox) {
    $scope.items = [];
    $scope.item = {};
    $scope.categoryInfo = {};
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
    $rootScope.searching = false;
    $scope.rating = {
      maximum: 5
    };
    $scope.inquire = {
      reserve: 0
    };
    $scope.rateSubmittingState = false;
    $scope.ratingState = false;
    $scope.featured = {};
    $scope.banads = [];
    $scope.clickImage = function(index) {
      $log.info('clickImage', index);
      Lightbox.openModal($scope.item.images, index);
    };
    $scope.backToHeader = function(delayTime, animateTime) {
      $timeout(function() {
        return $('body,html').animate({
          scrollTop: $('#item_container .item-wrapper').offset().top - ($('#navigation').outerHeight(true) + ($('#navigation').outerHeight(true) / 2))
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
      $scope.backToHeader(50, 500);
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
        message: $scope.inquire.message,
        reserve: $scope.inquire.reserve
      }).success(function(data, xhr) {
        $scope.backToHeader();
        $scope.inquireSubmitButton.state = !$scope.inquireSubmitButton.state;
        $scope.getItem($stateParams.itemId);
        $scope.inquire = {
          reserve: 0,
          message: ''
        };
        $scope.inquireItem();
        $scope.inquireMessages.push({
          type: 'success',
          message: data.success.message
        });
        Notification.success(data.success);
      }).error(function(data, xhr) {
        $scope.inquireSubmitButton.state = !$scope.inquireSubmitButton.state;
        $scope.inquireMessages.push({
          type: 'danger',
          message: data.error.message
        });
        Notification.error(data.error);
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
      ItemFactory.getAllByCategory($stateParams.categoryId, pageNumber).success(function(success, xhr) {
        $scope.errorMessage = null;
        $scope.categoryInfo = success.category;
        $rootScope.categoryInfo = $scope.categoryInfo;
        if (Boolean(success.products.next_page_url)) {
          $scope.getItemsByCategory(data.current_page + 1);
        }
      }).error(function(error) {
        $scope.errorMessage = error.error.message;
      }).then(function(data) {
        angular.forEach(data.data.products.data, function(value, key) {
          $scope.items.push(value);
        });
        $scope.featured = data.data.featured;
      });
    };
    $scope.getAllItem = function(pageNumber) {
      $log.info('Getting all items');
      ItemFactory.getAll(pageNumber).success(function(data, xhr) {
        $scope.errorMessage = null;
        if (Boolean(data.next_page_url)) {
          $scope.getAllItem(data.current_page + 1);
        }
      }).error(function(error) {
        $log.error('ItemController.getAllItem::error', error);
        $scope.errorMessage = error.error.message;
      }).then(function(data) {
        angular.forEach(data.data.data, function(value, key) {
          $scope.items.push(value);
        });
      });
    };
    $scope.affixItem = function() {
      if ($('#item_container .item-right').outerHeight(true) >= $('#item_content_left .item-carousel').outerHeight(true)) {
        $('#item_content_left .item-carousel').affix({
          offset: {
            top: function() {
              return this.top = $('#navigation').outerHeight(true);
            },
            bottom: function() {
              return this.bottom = $('#item_container .item-related').outerHeight(true) + $('#footer').outerHeight(true) - 50;
            }
          }
        });
      }
    };
    $scope.getItem = function(id) {
      ItemFactory.getItem(id).success(function(data, xhr) {
        $scope.backToHeader();
        $scope.errorMessage = null;
        $scope.item = data;
        $log.log('item: ', $scope.item);
        $rootScope.bigTitle = ' :: ' + data.name;
      }).error(function(error, xhr) {
        $scope.errorMessage = error.error.message;
      }).then(function(data) {
        $timeout(function() {
          $scope.affixItem();
        }, 500);
      });
    };
    $scope.redirectInItem = function() {
      localStorageService.set('redirect_to_item', $stateParams.itemId);
    };
    $scope.ratingItem = function() {
      $scope.ratingState = !$scope.ratingState;
    };
    $scope.rateTheItem = function($event, id, elementId) {
      var element, rating;
      $event.preventDefault();
      element = elementId ? elementId : '#ratingCollapse';
      $scope.rateSubmittingState = true;
      $log.info('ItemController.rateTheItem', $event);
      rating = $scope.item.review ? $scope.item.review : $scope.item.rating;
      RatingFactory.rateItem(id, rating).success(function(success) {
        $log.log(success);
        $scope.rateSubmittingState = false;
        $scope.item.rating.message = '';
        Notification.success(success.success);
        $(element).collapse('hide');
        $scope.item.rating = success.success.data.product.rating;
        $scope.ratingState = !$scope.ratingState;
      }).error(function(error) {
        $log.error(error);
        $scope.rateSubmittingState = false;
        Notification.error(error.error);
      });
    };
    $scope.getAllBanads = function() {
      $http.get('banners').success(function(success) {
        $log.log('ItemController@getAllBanads::success', success);
        angular.forEach(success, function(val, key) {
          $scope.banners.push(val);
        });
      });
    };
    $scope.checkState();
  });

}).call(this);

(function() {
  _okie.directive('okieSearch', function($window, $log, SearchFactory) {
    return {
      templateUrl: '/views/searchbox.html',
      restrict: 'AEC',
      link: function(scope, element, attrs, ngModel) {},
      controller: function($scope, $element, $attrs, $window, $timeout, $log, SearchFactory) {
        $scope.results = [];
        $scope.resultErrorState = false;
        $scope.selected = -1;
        $scope.onFocus = function(event) {
          var formOffset;
          formOffset = $element.find('form').offset().left;
          $element.find('form').css({
            left: formOffset,
            width: '40%'
          }).addClass('active');
          $element.find('input').animate({
            width: '100%'
          }, 500);
          $('#navigation').addClass('okie-search-searching');
          angular.element($window).on('keydown', function(e) {
            var code;
            $log.log('keydownEvent', e);
            code = e.which ? e.which : e.keyCode;
            switch (code) {
              case 27:
                $scope.onBlur(e);
                break;
            }
          });
        };
        $scope.inputClone = function() {
          return $element.find('input').clone().css({
            width: 'auto',
            position: 'fixed',
            left: -9999999,
            top: -9999999
          }).appendTo('body');
        };
        $scope.onBlur = function(event) {
          $element.find('.search-results').slideUp();
          $('#navigation').removeClass('okie-search-searching');
          $element.find('form').css({
            width: 'auto',
            left: 0
          }).removeClass('active');
          if ($scope.inputClone().length) {
            $element.find('input').animate({
              width: $scope.inputClone().css('width')
            }, 500);
          }
        };
        $scope.goTo = function(url) {
          $scope.search = '';
          $window.location.replace(url);
        };
        $scope.onChange = function(event) {
          $scope.inputClone();
          $element.find('input').animate({
            width: '100%'
          }, 500);
          $log.info('You are searching for', $scope.search);
          if (Boolean($scope.search)) {
            $scope.onSearch(event);
          }
        };
        $scope.onSearch = function(event) {
          var leftPosition, topPosition;
          leftPosition = $element.find('input').position().left;
          topPosition = $element.find('input').position().top + $element.find('input').outerHeight(true) + 2;
          $element.find('.search-results').css({
            left: $element.find('form').offset().left,
            top: topPosition,
            width: $element.find('form').width()
          });
          SearchFactory.getProduct($scope.search).success(function(response) {
            $scope.results = [];
            $scope.resultErrorState = false;
            $element.find('.search-results').slideDown();
            $element.find('.search-results').css({
              left: $element.find('form').offset().left,
              top: topPosition,
              width: $element.find('form').width()
            });
          }).error(function(error) {
            $element.find('.search-results').slideDown();
            $scope.resultErrorState = true;
            $scope.resultErrorMessage = error.error.message;
            $timeout(function() {
              return $scope.onBlur();
            }, 5000);
          }).then(function(data) {
            return angular.forEach(data.data.success.data, function(value, key) {
              $scope.results.push(value);
            });
          });
        };
        $scope.on_focus = function(event) {
          $scope.inputClone = $element.find('input').clone().css({
            width: 'auto',
            position: 'fixed',
            left: -9999999,
            top: -9999999
          }).appendTo('body');
          $element.find('input').animate({
            width: '100%'
          }, 500);
          angular.element($window).on('keydown', function(e) {
            if (e.keyCode === 27) {
              $scope.onBlur(e);
            }
          });
          $scope.$watch('search', function() {
            var formPosition, topPosition;
            $log.info($scope.search);
            formPosition = $element.find('form').offset().left;
            topPosition = $element.find('input').offset().top + $element.find('input').outerHeight(true);
            $element.find('.search-results').css({
              left: formPosition + 15,
              top: topPosition,
              width: $element.find('form').width()
            });
            SearchFactory.getProduct($scope.search).success(function(response) {
              $scope.results = [];
              $scope.resultErrorState = false;
              $element.find('.search-results').slideDown();
            }).error(function(error) {
              $element.find('.search-results').slideDown();
              $scope.resultErrorState = true;
              $scope.resultErrorMessage = error.error.message;
              $timeout(function() {
                return $scope.onBlur();
              }, 5000);
            }).then(function(data) {
              return angular.forEach(data.data.success.data, function(value, key) {
                $scope.results.push(value);
              });
            });
          });
        };

        /**
         * Watch the scope of search 
         *
         * @return {void}
         */
        return $scope.$watch('search', function() {
          var leftPosition, topPosition;
          leftPosition = $element.find('input').position().left;
          topPosition = $element.find('input').position().top + $element.find('input').outerHeight(true) + 2;
          $element.find('.search-results').css({
            left: $element.find('form').offset().left,
            top: topPosition,
            width: $element.find('form').width()
          });
        });
      }
    };
  });

}).call(this);

(function() {
  _okie.factory('ClassFactory', function($http, $q) {
    var _c;
    _c = {};
    _c.badgeClass = ['ribbon-info', 'ribbon-success', 'ribbon-info', 'ribbon-warning', 'ribbon-danger', 'content-description', 'content-serif', 'font-light', 'font-bold', 'font-normal', 'letter-spacing-1', 'letter-spacing-2', 'letter-spacing-3', 'letter-spacing-4', 'letter-spacing-5'];
    _c.load = function() {
      var deferred;
      deferred = $q.defer();
      deferred.resolve(_c.badgeClass);
      return deferred.promise;
    };
    return _c;
  });

}).call(this);

(function() {
  _okie.factory('ItemFactory', function($http, $q) {
    var _i, urls;
    _i = {};
    urls = {
      base: '/items/',
      category: '/items/category/',
      inquiry: '/item/inquire',
      rate: '/item/_ITEM_ID_/rate'
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
          page: pageNumber ? pageNumber : 1
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
          page: pageNumber ? pageNumber : 1
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

    /**
     * Rate the item
     *
     * @param  {int} id
     * @param  {object} data
     * @param  {string} method
     *
     * @return $http
     */
    _i.rateItem = function(id, data, method) {
      return $http({
        url: urls.rate.replace('_ITEM_ID_', id),
        data: data,
        method: method ? method : "POST"
      });
    };
    return _i;
  });

}).call(this);

(function() {
  _okie.factory('RatingFactory', function($http, $window) {
    var _r;
    _r = {};
    _r.sendRating = function(data, url, method, params) {
      return $http({
        url: url,
        method: method ? method : "POST",
        params: params,
        data: data
      });
    };

    /**
     * Rate the item
     *
     * @param  {int} id
     * @param  {object} data
     * @param  {string} method
     *
     * @return $http
     */
    _r.rateItem = function(id, data, url, method) {
      var rateUrl;
      rateUrl = '/item/_ITEM_ID_/rate';
      return $http({
        url: url ? url : rateUrl.replace('_ITEM_ID_', id),
        data: data,
        method: method ? method : "POST"
      });
    };
    return _r;
  });

}).call(this);

(function() {
  _okie.factory('SearchFactory', function($http, $state, $stateParams, $rootScope, localStorageService) {
    var _s;
    _s = {};
    _s.url = {
      base: '/search/',
      users: '/search/user/',
      products: '/search/product/'
    };
    _s.getUser = function(user, params) {
      return $http({
        url: _s.url.users + user,
        method: "GET",
        params: params
      });
    };
    _s.getProduct = function(product, params) {
      return $http({
        url: _s.url.products + product,
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
    _u.getNotify = function(callback) {
      return $http({
        url: '/me',
        ignoreLoadingBar: true
      }).success(function(data, xhr) {
        $log.log('getNotify::data', data);
        $rootScope.me = data;
        _u.checkState();
        _u.checkRedirectItem();
        if (callback && typeof callback === 'function') {
          callback();
        }
      });
    };
    _u.getUser = function() {
      return $http({
        url: '/me',
        ignoreLoadingBar: true
      });
    };
    _u.checkState = function() {
      $log.info('UserFactory::checkState()', $stateParams);
      $log.info('UserFactory::checkState()', $state.current);
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


/*
* Inspired by: 
* http://designedbythomas.co.uk/blog/how-detect-width-web-browser-using-jquery
* 
* This script is ideal for getting specific class depending on device width 
* for enhanced theming. Media queries are fine in most cases but sometimes
* you want to target a specific JQuery call based on width. This will work 
* for that. Be sure to put it first in your script file. Note that you could
* also target the body class instead of 'html' as well. 
* Modify as needed
 */

(function() {
  (function($) {
    $(document).ready(function() {
      var current_width;
      current_width = $(window).width();
      if (current_width < 481) {
        $('html').addClass('m320').removeClass('m768').removeClass('desktop').removeClass('m480');
      } else if (current_width < 739) {
        $('html').addClass('m768').removeClass('desktop').removeClass('m320').removeClass('tablet');
      } else if (current_width < 970) {
        $('html').addClass('tablet').removeClass('desktop').removeClass('m320').removeClass('m768');
      } else if (current_width > 971) {
        $('html').addClass('desktop').removeClass('m320').removeClass('m768').removeClass('tablet');
      }
      if (current_width < 650) {
        $('html').addClass('mobile-menu').removeClass('desktop-menu');
      }
      if (current_width > 651) {
        $('html').addClass('desktop-menu').removeClass('mobile-menu');
      }
    });
    $(window).resize(function() {
      var current_width;
      current_width = $(window).width();
      if (current_width < 481) {
        $('html').addClass('m320').removeClass('m768').removeClass('desktop').removeClass('tablet');
      } else if (current_width < 669) {
        $('html').addClass('m768').removeClass('desktop').removeClass('m320').removeClass('tablet');
      } else if (current_width < 970) {
        $('html').addClass('tablet').removeClass('desktop').removeClass('m320').removeClass('m768');
      } else if (current_width > 971) {
        $('html').addClass('desktop').removeClass('m320').removeClass('m768').removeClass('tablet');
      }
      if (current_width < 650) {
        $('html').addClass('mobile-menu').removeClass('desktop-menu');
      }
      if (current_width > 651) {
        $('html').addClass('desktop-menu').removeClass('mobile-menu');
      }
    });
  })(jQuery);

}).call(this);
