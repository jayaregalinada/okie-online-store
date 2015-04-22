(function() {
  _okie.controller('AdminSettingsController', function($scope, $log, $window, $rootScope, Notification, SettingsFactory, $state, $stateParams, $timeout) {
    $scope.users = [];
    $scope.settings = {
      permission: {
        error: false
      }
    };
    $scope.updatingState = [];

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
    $scope.checkState = function() {
      $log.info('AdminSettingsController.checkState()', $state.current);
    };
    $scope.getPermissions = function(pageNumber) {
      $scope.changeHeading('Permissions');
      $log.log('Getting permissions');
      $scope.settings.permission.error = false;
      SettingsFactory.getAllUsers(pageNumber).success(function(response, xhr) {
        $log.info('AdminSettingsController.getPermissions::response', response);
        $scope.settings.permission.error = false;
        if (Boolean(response.next_page_url)) {
          $scope.getPermissions(response.current_page + 1);
        }
      }).error(function(error) {
        $scope.settings.permission.error = true;
        $scope.settings.permission.errorMessage = error.error.message;
        Notification.error(error.error);
      }).then(function(thenResponse, xhr) {
        angular.forEach(thenResponse.data.data, function(value, key) {
          $scope.users.push(value);
        });
      });
    };
    $scope.changePermission = function(user, permission) {
      $log.log(user);
      $log.log(permission);
      user.permissionState = true;
      SettingsFactory.changePermission({
        user: user,
        user_id: user.id,
        permission: permission
      }).success(function(response, xhr) {
        $log.info('AdminSettingsController.changePermission', response);
        user.permissionState = false;
        Notification.success(response.success);
      });
    };
    $scope.getGeneral = function() {
      $scope.changeHeading('General Settings');
    };
    $scope.changeValue = function(value) {
      SettingsFactory.changeGeneral(value).success(function(success) {
        $log.info('AdminSettingsController.changeValue::success', success);
        Notification.success(success.success);
      }).error(function(error) {
        $log.info('AdminSettingsController.changeValue::error', error);
        Notification.error(error.error);
      });
    };
    $scope.checkState();
  });

}).call(this);

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
      $scope.deliverConversations = [];
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
        $scope.deliverErrorMessage = data.error.message.replace('[DELIVER] ', '');
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
  _okie.controller('ImageController', function($scope, $window, Lightbox, $rootScope, $http, localStorageService, Notification) {
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
        Notification.success(data.success);
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
      }).success(function(response, xhr) {
        Notification.success(response.success);
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
  _okie.controller('ProductController', function(ProductFactory, $rootScope, $log, $scope, $http, $location, $window, $timeout, Lightbox, localStorageService, $state, $stateParams, Slug, SettingsFactory, Notification, ClassFactory) {
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
    $scope.editStateBadge = false;
    $scope.product = {};
    $scope.url = {
      base: '/me/products'
    };
    $scope.categories = [];
    $scope.category = {};
    $scope.cat = {};
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
    $scope.create = {
      basic: true,
      name: false,
      code: false,
      description: false,
      price: false,
      unit: false
    };
    $scope.condition = {
      products: {
        loading: false,
        error: false
      },
      categories: {
        loading: false,
        error: false
      }
    };
    $scope.loadingState = {
      products: false
    };
    $scope.classFactory = ClassFactory;
    $scope.class_array = [];
    $scope.$watchCollection('product.badge.class_array', function(val) {
      $log.log('product.badge.class_array:val', val);
      $log.log('typeof(val)', Boolean(typeof val));
      $log.log('val', Boolean(val));
      if (typeof val === 'object' || val) {
        $scope.class_array = [];
        angular.forEach(val, function(value, key) {
          $scope.class_array.push(value.text);
        });
        $scope.product.badge["class"] = $scope.class_array.join(' ');
      }
      $log.log('product.badge.class', $scope.product.badge);
    });
    $scope.loadClass = function(query) {
      $log.log('ClassFactory.load()', ClassFactory.load());
      return ClassFactory.load();
    };
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
    $scope.changeDescription = function(name) {
      $scope.create = {
        basic: false,
        name: false,
        code: false,
        description: false,
        price: false,
        unit: false
      };
      switch (name) {
        case 'name':
          $scope.create.name = true;
          break;
        case 'code':
          $scope.create.code = true;
          break;
        case 'description':
          $scope.create.description = true;
          break;
        case 'price':
          $scope.create.price = true;
          break;
        case 'unit':
          $scope.create.unit = true;
          break;
        default:
          $scope.create.basic = true;
      }
      $log.info($scope.create);
    };
    $scope.autoChangeProductCode = function() {
      if (!$scope.product.code) {
        $scope.product.code = Slug.slugify($scope.product.name);
      }
    };
    $scope.initializeDropzone = function(url, token) {
      $log.info('ProductController.initializeDropzone', url);
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
        $scope.getImages($scope.images.length);
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
      var obj;
      obj = {
        url: '/product/' + $scope.id,
        method: "PUT",
        data: $scope.product
      };
      $http(obj).success(function(success) {
        Notification.success(success.success);
        $scope.getInformation();
        $scope.editState = !$scope.editState;
      }).error(function(error) {
        Notification.error(error.error);
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
          $scope.images.unshift(value);
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
      newProduct = JSON.stringify($scope.product);
      oldProduct = JSON.stringify($scope.getItem('product_info'));
      if (newProduct === oldProduct) {
        $log.log('Just the same eh?');
        $scope.editState = !$scope.editState;
        return;
      } else {
        $scope.productUpdate();
        return;
      }
    };
    $scope.getProducts = function(pageNumber) {
      $scope.changeHeading('Products');
      $scope.condition.products.loading = true;
      $scope.condition.products.error = false;
      $scope.products = [];
      ProductFactory.getAllProducts(pageNumber).success(function(response, xhr) {
        $scope.condition.products.error = false;
        if (Boolean(response.next_page_url)) {
          $scope.getProducts(response.current_page + 1);
        }
      }).error(function(error, xhr) {
        $scope.condition.products.error = true;
        $scope.condition.products.loading = false;
        $scope.condition.products.errorMessage = error.error;
      }).then(function(data) {
        $scope.pushToProducts(data.data.data);
        $scope.condition.products.loading = false;
      });
    };
    $scope.pushToProducts = function(data) {
      angular.forEach(data, function(value, key) {
        $scope.products.push(value);
      });
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
        Notification.success({
          message: 'Successfully update product'
        });
        $scope.getInformation();
      });
    };
    $scope.editProductBadge = function() {
      $scope.editStateBadge = true;
    };
    $scope.updateProductBadge = function(event, form) {
      $log.log('ProductController.updateProductBadge::event', event);
      $log.log('ProductController.updateProductBadge::form', form);
      $log.log('$scope.product.badge', $scope.product.badge);
      ProductFactory.updateBadge($scope.product.badge, form_product_badge.getAttribute('action')).success(function(success) {
        Notification.success(success.success);
        $scope.editStateBadge = false;
      }).error(function(error) {
        Notification.error(error.error);
      });
    };
    $scope.removeProductBadge = function(event, form) {
      $http({
        url: form_product_badge_remove.getAttribute('action'),
        method: 'PATCH'
      }).success(function(success) {
        $scope.editStateBadge = false;
        Notification.success(success.success);
        $scope.getInformation();
      }).error(function(error) {
        Notification.error(error.error);
      });
      event.preventDefault();
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
        $log.info('ProductController.addCategory::data', data);
        Notification.success(data.success);
      }).then(function(data) {
        $scope.category = {};
      });
    };
    $scope.getCategories = function(page) {
      $scope.categories = [];
      $scope.changeHeading('Categories');
      $scope.condition.categories.loading = true;
      $scope.condition.categories.error = false;
      SettingsFactory.getAllCategories(page).success(function(data, xhr) {
        $scope.condition.categories.error = false;
        if (Boolean(data.next_page_url)) {
          $scope.getCategories(data.current_page + 1);
        }
      }).error(function(error, xhr) {
        $scope.condition.categories.loading = false;
        $scope.condition.categories.error = true;
        $scope.condition.categories.errorMessage = error.error;
      }).then(function(data, xhr) {
        $scope.condition.categories.loading = false;
        $scope.condition.categories.error = false;
        angular.forEach(data.data.data, function(value, key) {
          $scope.categories.push(value);
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
      $scope.hotSeatCategory.parent_selected = $scope.hotSeatCategory.parent_info.id;
      SettingsFactory.updateCategory($scope.hotSeatCategory).success(function(data, xhr) {
        $log.info('ProductController.updateCategory::data', data);
        $($scope.modalId).modal('hide');
        $scope.getCategories();
        Notification.success(data.success);
      }).error(function(error) {
        Notification.error(error.error);
      });
    };
    $scope.deleteCategory = function(id, event) {
      SettingsFactory.deleteCategory(id).success(function(data, xhr) {
        $log.info('ProductController.deleteCategory::data', data);
        $scope.getCategories();
        Notification.success(data.success);
      });
    };
  });

}).call(this);

(function() {
  _okie.controller('ReviewController', function($scope, $state, $stateParams, $http, $log, Notification, $window, $timeout) {
    $scope.reviews = [];
    $scope.hoverApproved = false;

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
    $scope.__get = function(url, params) {
      return $http({
        url: url,
        method: "GET",
        params: params
      });
    };
    $scope.__post = function(url, data, method, params) {
      return $http({
        url: url,
        data: data,
        method: method ? method : "POST",
        params: params
      });
    };
    $scope.pushToReviews = function(data) {
      angular.forEach(data, function(value, key) {
        $scope.reviews.push(value);
      });
      $scope.loadingState = false;
      $timeout(function() {
        $scope.errorState = false;
      }, 3000);
    };
    $scope.getAllReviews = function(page) {
      $scope.loadingState = true;
      $scope.errorState = false;
      $scope.changeHeading('Loading...');
      $scope.__get($window._url.reviews.all, {
        page: page
      }).success(function(success) {
        $scope.changeHeading('Reviews');
        $log.log('ReviewController.getAllReviews:success', success);
        $scope.errorState = false;
        if (Boolean(success.next_page_url)) {
          $scope.getAllReviews(success.current_page + 1);
        }
      }).error(function(error) {
        $log.log('ReviewController.getAllReviews:error', error);
        $scope.errorState = true;
        $scope.loadingState = false;
        $scope.errorMessage = error.error.message;
      }).then(function(data) {
        $scope.pushToReviews(data.data.data);
      });
    };
    $scope.approveReview = function(id, index) {
      $scope.__post($window._url.reviews.approved.replace('_REVIEW_ID_', id)).success(function(success) {
        Notification.success(success.success);
        $scope.reviews[index] = success.success.data;
      }).error(function(error) {
        Notification.error(error.error);
      });
    };
    $scope.hoverApprovedChange = function(index, boolean) {
      $scope.reviews[index].hoverApproved = boolean;
    };
    $scope.unapproveReview = function(id, index) {
      $scope.__post($window._url.reviews.unapproved.replace('_REVIEW_ID_', id)).success(function(success) {
        Notification.success(success.success);
        $scope.reviews[index] = success.success.data;
      }).error(function(error) {
        Notification.error(error.error);
      });
    };
  });

}).call(this);

(function() {
  _okie.directive('BadgeClasshelper', function($http, $log) {
    return {
      restrict: 'AE',
      scope: {
        selectedTags: '=model'
      },
      templateUrl: 'badge-class-helper.html',
      link: function(scope, elem, attrs) {
        scope.suggestions = [];
        scope.selectedTags = [];
        scope.selectedIndex = -1;
        scope.removeTag = function(index) {
          scope.selectedTags.splice(index, 1);
        };
        scope.search = function() {
          var data;
          $log.log('SearchingText: ', scope.searchText);
          $log.log('SearchingSuggestions: ', scope.suggestions);
          $log.log('SearchingIndex: ', scope.selectedIndex);
          data = attrs.scope;
          if (data.indexOf(scope.searchText) === -1) {
            data.unshift(scope.searchText);
            scope.suggestions = data;
            scope.selectedIndex = -1;
          }
        };
        scope.addToSelectedTags = function(index) {
          if (scope.selectedTags.indexOf(scope.suggestions[index]) === -1) {
            scope.selectedTags.push(scope.suggestions[index]);
            scope.searchText = '';
            scope.suggestions = [];
          }
        };
        scope.checkKeyDown = function(event) {
          if (event.keyCode === 40) {
            event.preventDefault();
            if (scope.selectedIndex + 1 !== scope.suggestions.length) {
              scope.selectedIndex++;
            }
          } else if (event.keyCode === 38) {
            event.preventDefault();
            if (scope.selectedIndex - 1 !== -1) {
              scope.selectedIndex--;
            }
          } else if (event.keyCode === 13) {
            scope.addToSelectedTags(scope.selectedIndex);
          }
        };
        scope.$watch('selectedIndex', function(val) {
          if (val !== -1) {
            scope.searchText = scope.suggestions[scope.selectedIndex];
          }
        });
        scope.$watch('searchText', function(val) {
          $log.info('SearchText', val);
        });
      }
    };
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
        url: $window._url.deliver.conversations.replace('_DELIVER_ID_', id),
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
  _okie.factory('ProductFactory', function($http, $rootScope, $window) {
    var _p;
    _p = {};
    _p.getAllProducts = function(pageNumber, url, method, params) {
      var defaultParams;
      defaultParams = {
        page: pageNumber
      };
      return $http({
        url: url ? url : $window._url.products.all,
        params: params ? params : defaultParams,
        method: method ? method : "GET"
      });
    };
    _p.updateBadge = function(data, url, method, params) {
      return $http({
        url: url,
        params: params,
        method: method ? method : "PUT",
        data: data
      });
    };
    return _p;
  });

}).call(this);

(function() {
  _okie.factory('SettingsFactory', function($http, $rootScope, $state, $stateParams, $window) {
    var _s;
    _s = {};
    _s.url = {
      base: '/me/settings',
      categories: '/me/products/categories',
      product: '/product/'
    };
    _s.availableMethod = ["GET", "POST"];
    _s.getAllUsers = function(pageNumber, url, method, params) {
      var defaultParams;
      defaultParams = {
        page: pageNumber
      };
      return $http({
        url: url ? url : $window._url.settings.users,
        method: method ? method : "GET",
        params: params ? params : defaultParams
      });
    };

    /**
    	 * CATEGORIES
     */
    _s.addCategory = function(data, url, method, params) {
      var defaultParams;
      defaultParams = {
        create: data.category
      };
      return $http({
        url: url ? url : _s.url.categories,
        method: method ? method : "POST",
        params: params ? params : defaultParams,
        data: data
      });
    };
    _s.getAllCategories = function(pageNumber, url, method, defaultParams) {
      defaultParams = {
        page: pageNumber ? pageNumber : 1
      };
      return $http({
        url: url ? url : _s.url.categories,
        method: method ? method : "GET",
        params: defaultParams
      });
    };
    _s.getCategoryById = function(id, url, method, params) {
      var defaultParams;
      defaultParams = {
        find: id
      };
      return $http({
        url: url ? url : _s.url.categories,
        method: method ? method : "GET",
        params: params ? params : defaultParams
      });
    };
    _s.updateCategory = function(data, url, method, params) {
      var defaultParams;
      defaultParams = {
        update: true
      };
      return $http({
        url: url ? url : _s.url.categories,
        method: method ? method : "POST",
        params: params ? params : defaultParams,
        data: data
      });
    };
    _s.deleteCategory = function(id, url, data, method, params) {
      var defaultData, defaultParams;
      defaultParams = {
        "delete": true
      };
      defaultData = {
        id: id
      };
      return $http({
        url: url ? url : _s.url.categories,
        params: params ? params : defaultParams,
        method: method ? method : "POST",
        data: data ? data : defaultData
      });
    };

    /**
    	 * PERMISSIONS
     */
    _s.changePermission = function(data, url, method, params) {
      return $http({
        url: url ? url : $window._url.settings.permissions,
        method: method ? method : "PATCH",
        data: data,
        params: params
      });
    };
    _s.changeGeneral = function(data, url, method, params) {
      return $http({
        url: url ? url : $window._url.settings.general,
        method: method ? method : "POST",
        data: data,
        params: params
      });
    };
    return _s;
  });

}).call(this);
