(function() {
  _okie.controller('DeliverController', function(DeliverFactory, textAngularManager, $timeout, $log, $window, $scope, $state, $stateParams, $rootScope) {
    $scope.heading = 'Delivered';
    $scope.factory = DeliverFactory;
    $scope.deliveries = [];
    $scope.deliverState = false;
    $scope.deliverErrorState = false;
    $scope.deliverConversations = [];
    $scope.alerts = [];
    $scope.replySubmitButton = {
      state: false
    };

    /**
     * Change the heading
     *
     * @param  {string} heading
     *
     * @return {void}
     */
    $scope.changeHeading = function(heading, prepend) {
      $scope.heading = heading;
      $('.profile-container .profile-full-name').text(heading);
      $('.profile-container .profile-full-name').prepend(prepend);
    };

    /**
     * Close the alert
     *
     * @param  {integer} index
     *
     * @return {void}
     */
    $scope.closeAlert = function(index) {
      $scope.alerts.splice(index, 1);
    };
    $scope.getAllDeliver = function(pageNumber) {
      var page;
      $scope.deliveries = [];
      page = pageNumber ? pageNumber : 1;
      $scope.changeHeading('Delivered');
      $scope.deliverState = true;
      $scope.deliverLoadingState = true;
      $scope.deliverErrorState = false;
      DeliverFactory.getAll(page).success(function(data, xhr) {
        $log.log('DeliverController.getAllDeliver::data', data);
        $scope.deliverErrorState = false;
        if (Boolean(data.next_page_url)) {
          $scope.getAllDeliver(data.current_page + 1);
        }
      }).error(function(data, xhr) {
        $log.error('DeliverController.getAllDeliver::data', data);
        $scope.deliverErrorState = true;
        $scope.deliverLoadingState = false;
        $scope.deliverErrorMessage = data.error.message.replace('[DELIVER] ', '');
      }).then(function(data, xhr) {
        return $scope.pushToDeliveries(data.data.data);
      });
    };
    $scope.pushToDeliveries = function(data) {
      angular.forEach(data, function(value, key) {
        $scope.deliveries.push(value);
      });
      $scope.deliverLoadingState = false;
      $timeout(function() {
        $scope.deliverState = false;
        $scope.deliverErrorState = false;
      }, 3000);
    };
    $scope.getToConversation = function(deliverId, pageNumber) {
      $scope.deliverConversations = [];
      $scope.changeHeading('Loading conversations');
      $scope.deliverState = true;
      DeliverFactory.getConversations(deliverId, pageNumber).success(function(data, xhr) {
        $log.log('DeliverController.getToConversation::data', data);
        $scope.changeHeading(data.deliver.title, '<span>DELIVERED: &nbsp;</span>');
        $scope.deliverErrorState = false;
        if (Boolean(data.conversations.next_page_url)) {
          $scope.getToConversation($rootScope.$stateParams.deliverId, data.conversations.current_page + 1);
        }
      }).error(function(data, xhr) {
        $scope.deliverErrorState = true;
        $log.error('DeliverController.getToConversation::data', data);
        $scope.changeHeading('ERROR');
        $scope.deliverErrorMessage = data.error.deliverErrorMessage;
      }).then(function(data, xhr) {
        angular.forEach(data.data.conversations.data, function(value, key) {
          $scope.deliverConversations.push(value);
        });
        $scope.deliverInfo = data.data.deliver;
        $scope.backToTextArea();
        $timeout(function() {
          return $scope.deliverState = false;
        }, 3000);
      });
    };

    /**
     * Back to Text Area
     *
     * @param  {integer} delayTime
     * @param  {integer} animateTime
     *
     * @return {void}
     */
    $scope.backToTextArea = function(delayTime, animateTime) {
      $timeout(function() {
        return $('body,html').animate({
          scrollTop: $('#reply').offset().top + $('#reply').outerHeight(true) - $(window).height() + 20
        }, animateTime ? animateTime : 1000);
      }, delayTime ? delayTime : 1500);
    };
    $scope.replySubmit = function(event, form) {
      var data, tA;
      event.preventDefault();
      $scope.replySubmitButton.state = !$scope.replySubmitButton.state;
      tA = textAngularManager.retrieveEditor('reply');
      data = {
        deliver: $rootScope.$stateParams.deliverId,
        message: form.reply.$modelValue
      };
      DeliverFactory.reply(data).success(function(response, xhr) {
        $log.log('DeliverController.replySubmit::response', response);
        tA.scope.$parent.reply = '';
        $scope.deliverConversations.push(response.success.data);
      }).error(function(response, xhr) {
        $scope.alerts.push(response.error);
        $timeout(function() {
          $scope.alerts = [];
          $scope.getToConversation($rootScope.$stateParams.deliverId);
          $scope.replySubmitButton.state = false;
        }, 4000);
      }).then(function(response) {
        $scope.replySubmitButton.state = !$scope.replySubmitButton.state;
        $scope.backToTextArea();
      });
    };
  });

}).call(this);

(function() {
  _okie.controller('ImageController', function($scope, $window, Lightbox, $rootScope, $http, localStorageService) {
    $scope.productKey = 'product_info';
    $scope.product = {};
    $scope.images = [];

    /**
     * Delete product image by id
     *
     * @param  {integer} index
     *
     * @return {void}
     */
    $scope.deleteImage = function(index) {
      $scope.product = localStorageService.get($scope.productKey);
      $http({
        url: '/product/' + $scope.product.id + '/images',
        method: "DELETE",
        params: {
          id: $rootScope.images[index].id
        }
      }).success(function(data, xhr) {
        Lightbox.closeModal();
        $('.product-images .image-' + index).remove();
      }).then(function(data) {
        $scope.getImages();
      });
    };

    /**
     * Set the image as primary thumbnail of the product
     *
     * @param {integer} index
     */
    $scope.setAsPrimary = function(index) {
      $scope.product = localStorageService.get($scope.productKey);
      $http({
        url: '/product/' + $scope.product.id + '/images',
        method: "POST",
        data: {
          id: $rootScope.images[index].id
        }
      }).success(function(data, xhr) {
        Lightbox.closeModal();
      });
    };

    /**
     * Get images of the product
     *
     * @param  {integer} skip
     *
     * @return {void}
     */
    $scope.getImages = function(skip) {
      $http({
        url: '/product/' + $scope.id + '/images',
        method: "GET",
        params: {
          skip: (skip ? skip : $scope.images.length)
        }
      }).success(function(data, status, headers, config) {
        return angular.forEach(data.data, function(value, key) {
          $scope.images.push(value);
          $window.productImages.push(value);
        });
      }).then(function(data) {
        if ($scope.images.length < data.data.total) {
          $scope.getImages($scope.images.length);
          return;
        }
      });
    };
  });

}).call(this);

(function() {
  _okie.controller('ProductController', function($rootScope, $log, $scope, $http, $location, $window, $timeout, Lightbox, localStorageService, $state, $stateParams, Slug, SettingsFactory) {
    $scope.info = 'Product Information';
    $scope.header = null;
    $scope.title = null;
    $scope.path = 'product';
    $window.productImages = [];
    $scope.images = [];
    $scope.id = null;
    $scope.lastImageId = null;
    $scope.editState = false;
    $scope.editStateCategory = false;
    $scope.product = {};
    $scope.url = {
      base: '/me/products'
    };
    $scope.categories = [];
    $scope.category = {};
    $scope.hotSeatCategory = {
      name: 'LOADING'
    };
    $scope.stateCategory = false;
    $scope.modalId = '#modal_category_edit';
    $scope.products = [];
    $scope.nextPageUrl = null;
    $scope.productConfig = {
      sizes: 2
    };
    $rootScope.images = $scope.images;
    $rootScope.product = $scope.product;
    $scope.setItem = function(key, val, stringify) {
      if (stringify) {
        return localStorageService.set(key, JSON.stringify(val));
      }
      return localStorageService.set(key, val);
    };
    $scope.getItem = function(key) {
      return localStorageService.get(key);
    };
    $scope.changeHeading = function(heading) {
      $scope.header = heading;
      $('.profile-container .profile-full-name').text(heading);
    };
    $scope.getTitle = function() {
      if ($window.location.pathname === $scope.path + '/create') {
        $log.log('Create Product');
      }
    };
    $scope.openLightboxModal = function(index) {
      Lightbox.openModal($scope.images, index);
    };
    $scope.lightboxClose = function(index) {
      $window.alert(index);
    };
    $scope.dropzoneConfig = {
      options: {
        acceptedFiles: 'image/*'
      },
      eventHandlers: {
        success: function(file, xhr) {},
        queuecomplete: function(file, xhr, asd) {
          $scope.getImages($scope.images.length);
          this.removeAllFiles();
        }
      }
    };
    $scope.addImages = function(data) {
      $scope.images.push($scope.images);
    };
    $scope.getInformation = function() {
      $http({
        url: '/product/' + $scope.id,
        method: 'GET',
        params: {
          ajax: true
        }
      }).success(function(data) {
        $scope.product = data;
        $scope.setItem('product_info', $scope.product, true);
        console.log('Product Information:', data);
      });
    };
    $scope.productUpdate = function() {
      $http({
        url: '/product/' + $scope.id,
        method: "PUT",
        data: $scope.product
      }).success(function(data, status) {
        $scope.getInformation();
      });
    };

    /**
     * Get Images in a product
     *
     * @param  {integer} skip
     *
     * @return {object}
     */
    $scope.getImages = function(skip) {
      $http({
        url: '/product/' + $scope.id + '/images',
        method: "GET",
        params: {
          skip: (skip ? skip : $scope.images.length)
        }
      }).success(function(data, status, headers, config) {
        return angular.forEach(data.data, function(value, key) {
          $scope.images.push(value);
          $window.productImages.push(value);
        });
      }).then(function(data) {
        if ($scope.images.length < data.data.total) {
          $scope.getImages($scope.images.length);
          return;
        }
      });
    };
    $scope.editInfo = function() {
      $scope.editState = !$scope.editState;
    };
    $scope.updateInfo = function() {
      var newProduct, oldProduct;
      $scope.editState = !$scope.editState;
      newProduct = JSON.stringify($scope.product);
      oldProduct = JSON.stringify($scope.getItem('product_info'));
      if (newProduct === oldProduct) {
        console.log('Just the same eh?');
        return;
      } else {
        $scope.productUpdate();
        return;
      }
    };
    $scope.getProducts = function(pageNumber) {
      $http({
        url: $scope.url.base,
        method: "GET",
        params: {
          json: true,
          page: (pageNumber ? pageNumber : 1)
        }
      }).success(function(data, xhr) {
        $scope.nextPageUrl = data.next_page_url;
        $scope.changeHeading('Products');
        if ($scope.products.length < data.total) {
          $scope.getProducts(data.current_page + 1);
          angular.forEach(data.data, function(value, key) {
            $scope.products.push(value);
          });
          return;
        }
      }).then(function(data) {});
    };
    $scope.goToItem = function(id) {
      $window.location.href = '/product/' + id;
    };
    $scope.editProductCategory = function() {
      $scope.editStateCategory = !$scope.editStateCategory;
    };
    $scope.updateProductCategory = function() {
      $scope.editStateCategory = !$scope.editStateCategory;
      $scope.product.categories.map(Number);
      $http({
        url: '/product/' + $scope.id + '/category',
        data: $.param($scope.product),
        method: 'PUT',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).success(function(data, xhr) {
        $scope.getInformation();
      });
    };

    /**
     * ====================
     * SCOPES FOR CATEGORY
     * ====================
     */
    $scope.addCategory = function(event) {
      event.preventDefault();
      SettingsFactory.addCategory($scope.category).success(function(data, xhr) {
        $scope.getCategories();
      }).then(function(data) {
        $scope.category = {};
      });
    };
    $scope.getCategories = function() {
      $scope.changeHeading('Categories');
      $scope.categories.splice(0, $scope.categories.length);
      $scope.categories = [];
      SettingsFactory.getAllCategories().success(function(data, xhr) {
        $scope.categories.splice(0, $scope.categories.length);
        $scope.categories = [];
      }).then(function(data) {
        angular.forEach(data.data, function(value, key) {
          return $scope.categories.push(value);
        });
      });
    };
    $scope.getCategoryById = function(id) {
      SettingsFactory.getCategoryById(id).success(function(data, xhr) {
        $scope.stateCategory = false;
        $scope.hotSeatCategory = data;
        if (!$scope.hotSeatCategory.slug) {
          $scope.hotSeatCategory.slug = Slug.slugify($scope.hotSeatCategory.name);
        }
      });
    };
    $scope.editCategory = function(id) {
      $scope.hotSeatCategory = {};
      $scope.stateCategory = true;
      $scope.getCategoryById(id);
    };
    $scope.updateCategory = function(event) {
      event.preventDefault();
      SettingsFactory.updateCategory($scope.hotSeatCategory).success(function(data, xhr) {
        $($scope.modalId).modal('hide');
        $scope.getCategories();
      });
    };
    $scope.deleteCategory = function(id, event) {
      SettingsFactory.deleteCategory(id).success(function(data, xhr) {
        $scope.getCategories();
      });
    };
  });

}).call(this);

(function() {
  _okie.controller('SettingsController', function($scope, $log, $window, $http, localStorageService, $stateParams, $state, $location, $modal, SettingsFactory) {
    $scope.categories = [];
    $scope.category = {};
    $scope.hotSeatCategory = {
      name: 'LOADING'
    };
    $scope.stateCategory = false;
    $scope.modalId = '#modal_category_edit';
    $scope.heading = 'Settings';
    $scope.checkState = function() {
      $log('Welcome to Settings Controller. Now checking state', $state.current);
      switch ($state.current.name) {
        case 'products.category':
          $scope.getCategories();
      }
    };
    $scope.addCategory = function(event) {
      event.preventDefault();
      SettingsFactory.addCategory($scope.category).success(function(data, xhr) {
        console.info('addCategory::data', data);
        $scope.getCategories();
      }).then(function(data) {
        $scope.category = {};
      });
    };
    $scope.getCategories = function() {
      $scope.categories = [];
      SettingsFactory.getAllCategories().success(function(data, xhr) {
        console.log('getCategories::data', data);
        $scope.categories.push(data);
      });
    };
    $scope.getCategoryById = function(id) {
      $http({
        url: '/me/settings/categories',
        params: {
          find: id
        }
      }).success(function(data, xhr) {
        $scope.stateCategory = false;
        $scope.hotSeatCategory = data;
      });
    };
    $scope.editCategory = function(id) {
      $scope.hotSeatCategory = {};
      $scope.stateCategory = true;
      $scope.getCategoryById(id);
    };
    $scope.updateCategory = function(event) {
      $http({
        url: '/me/settings/categories',
        params: {
          update: true
        },
        method: 'POST',
        data: $.param($scope.hotSeatCategory),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).success(function(data, xhr) {
        $($scope.modalId).modal('hide');
        $scope.getCategories();
      });
      event.preventDefault();
    };
    $scope.deleteCategory = function(id, event) {
      $http({
        url: '/me/settings/categories',
        params: {
          "delete": true
        },
        method: 'POST',
        data: $.param({
          'id': id
        }),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).success(function(data, xhr) {
        $scope.getCategories();
      });
    };
    $scope.checkState();
  });

}).call(this);

(function() {
  _okie.directive('dropzone', function() {
    return function(scope, element, attrs) {
      var config, dropzone;
      config = void 0;
      dropzone = void 0;
      config = scope[attrs.dropzone];
      dropzone = new Dropzone(element[0], config.options);
      angular.forEach(config.eventHandlers, function(handler, event) {
        dropzone.on(event, handler);
      });
    };
  });

}).call(this);

(function() {
  _okie.factory('DeliverFactory', function($http, $window) {
    var _d;
    _d = {};
    _d.availableMethod = ['GET', 'POST'];

    /**
     * @param  {int} pageNumber
     * @param  {string} url
     * @param  {string} method
     *
     * @return {$http}
     */
    _d.getAll = function(pageNumber, url, method) {
      return $http({
        url: url ? url : $window._url.deliver.all,
        method: method ? method : "GET",
        params: {
          page: pageNumber
        }
      });
    };

    /**
     * @param  {int} id
     * @param  {int} pageNumber
     * @param  {string} method
     *
     * @return {$http}
     */
    _d.getConversations = function(id, pageNumber, method) {
      return $http({
        url: $window._url.deliver.conversations.replace('_DELIVER_ID', id),
        method: method ? method : "GET",
        params: {
          page: pageNumber
        }
      });
    };

    /**
     * @param  {object} data
     * @param  {string} url
     * @param  {string} method
     * @param  {object} params
     *
     * @return {$http}
     */
    _d.reply = function(data, url, method, params) {
      return $http({
        url: url ? url : $window._url.deliver.reply,
        data: data,
        method: method ? method : "POST",
        params: params
      });
    };
    return _d;
  });

}).call(this);

(function() {
  _okie.factory('ProductFactory', function($http, $q, $rootScope, $window) {
    var prod, products;
    prod = {};
    products = {};
    prod.getProducts = function() {
      return products;
    };
    prod.setProducts = function(p) {
      return products = p;
    };
    return prod;
  });

}).call(this);

(function() {
  _okie.factory('SettingsFactory', function($http, $rootScope, $state, $stateParams) {
    var _s;
    _s = {};
    _s.url = {
      base: '/me/settings',
      categories: '/me/products/categories',
      product: '/product/'
    };

    /**
    	 * CATEGORIES
     */
    _s.addCategory = function(data) {
      return $http({
        url: _s.url.categories,
        method: "POST",
        params: {
          create: data.category
        },
        data: data
      });
    };
    _s.getAllCategories = function() {
      return $http({
        url: _s.url.categories,
        method: "GET",
        params: {
          all: true
        }
      });
    };
    _s.getCategoryById = function(id) {
      return $http({
        url: _s.url.categories,
        method: "GET",
        params: {
          find: id
        }
      });
    };
    _s.updateCategory = function(data) {
      return $http({
        url: _s.url.categories,
        method: "POST",
        params: {
          update: true
        },
        data: data
      });
    };
    _s.deleteCategory = function(id) {
      return $http({
        url: _s.url.categories,
        params: {
          "delete": true
        },
        method: "POST",
        data: {
          id: id
        }
      });
    };
    return _s;
  });

}).call(this);
