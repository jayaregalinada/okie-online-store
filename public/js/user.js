(function() {
  _okie.controller('MessageController', function($scope, $log, $interval, $http, $state, $stateParams, $rootScope, $sce, $timeout, MessageFactory, textAngularManager, UserFactory, SearchFactory) {
    $scope.heading = 'Messages';
    $scope.messages = [];
    $scope.conversation = [];
    $scope.messageInfo = {};
    $scope.threadId = '';
    $scope.replySubmitButton = {
      state: false
    };
    $scope.messageSubmitButton = {
      state: false
    };
    $scope.threadInquiries = [];
    $scope.threadDeliveries = [];
    $scope.threadInboxes = [];
    $scope.intervalSeconds = 3000;
    $scope.alerts = [];
    $scope.message = {};
    $scope.search = [];
    $scope.searchError = false;

    /**
     * Change the heading
     *
     * @param  {string} heading
     *
     * @return {void}
     */
    $scope.changeHeading = function(heading) {
      $scope.heading = heading;
      $('.profile-container .profile-full-name').text(heading);
    };

    /**
     * Get all inquiries by page
     *
     * @param  {integer} page [Page number]
     *
     * @return {void}
     */
    $scope.getAllInquiries = function(page) {
      $scope.changeHeading('Inquiries');
      $scope.messages = [];
      MessageFactory.getInquiryMessages(page).success(function(data, xhr) {
        $log.log('getAllInquiries::data', data);
        if (Boolean(data.next_page_url)) {
          $scope.getAllInquiries(data.current_page + 1);
        }
      }).then(function(data, xhr) {
        angular.forEach(data.data.data, function(value, key) {
          $scope.messages.push(value);
          return $scope.threadInboxes.push(value);
        });
      });
    };
    $scope.stopLatestMessage = function() {
      var stop;
      $log.info('Stopping latest messages trolling');
      if (angular.isDefined(stop)) {
        $interval.cancel(stop);
        stop = void 0;
      }
    };
    $scope.changeHeadingWhenInquiring = function(product_name, name) {
      $('.profile-container .profile-full-name').html('Inquiring for ' + product_name + ' <small>by ' + name + '</small>');
    };
    $scope.getToConversation = function(thread_id, pageNumber) {
      $scope.conversation = [];
      MessageFactory.getThreadMessages(thread_id).success(function(data, xhr) {
        $scope.changeHeading(data.name);
        $scope.messageInfo = data;
        $scope.getConversations(thread_id, pageNumber);
      });
    };
    $scope.getConversations = function(thread_id, pageNumber) {
      MessageFactory.getMessages(thread_id, pageNumber).success(function(data, xhr) {
        $log.log('getConversations::data', data);
        if (Boolean(data.next_page_url)) {
          $scope.getConversations($rootScope.$stateParams.threadId, data.current_page + 1);
        }
      }).then(function(data, xhr) {
        angular.forEach(data.data.data, function(value, key) {
          return $scope.conversation.unshift(value);
        });
        UserFactory.getNotify();
        $timeout(function() {
          return $('body,html').animate({
            scrollTop: $('#reply').offset().top
          }, 1000);
        }, 1500);
      });
    };
    $scope.getMessagesByProduct = function(product_id, user_id) {
      $scope.conversation = [];
      MessageFactory.getInquiryMessageByProductId(product_id, user_id).success(function(data, xhr) {
        if (data.to > 0) {
          $scope.changeHeadingWhenInquiring(data.data[0].product.name, data.data[0].user.first_name + ' ' + data.data[0].user.last_name);
          return angular.forEach(data.data, function(value, key) {
            $scope.conversation.unshift(value);
          });
        }
      });
    };
    $scope.closeAlert = function(index) {
      $scope.alerts.splice(index, 1);
    };
    $scope.replySubmit = function(event, form) {
      var tA;
      event.preventDefault();
      tA = textAngularManager.retrieveEditor('reply');
      $scope.replySubmitButton.state = !$scope.replySubmitButton.state;
      MessageFactory.replyToMessage(event.target.action, {
        reply: tA.scope.html,
        item: $scope.messageInfo.product_id,
        thread: $rootScope.$stateParams.threadId
      }).success(function(data, xhr) {
        $scope.conversation.push(data.success.data);
        $scope.me = $rootScope.me;
        tA.scope.$parent.reply = '';
      }).error(function(data, xhr) {
        $scope.alerts.push(data.error);
        $timeout(function() {
          $scope.alerts = [];
          return $scope.getToConversation($rootScope.$stateParams.threadId);
        }, 5000);
      }).then(function(data) {
        $scope.replySubmitButton.state = !$scope.replySubmitButton.state;
        $timeout(function() {
          return $('body,html').animate({
            scrollTop: $('#reply').offset().top
          }, 1000);
        }, 2000);
      });
    };
    $scope.getAllDeliveries = function(page) {
      $log.info('Getting all deliveries');
      $scope.changeHeading('Delivered');
      $scope.messages = [];
      MessageFactory.getDeliveryMessages(page).success(function(data, xhr) {
        $log.log('getAllDeliveries::data', data);
        if (Boolean(data.next_page_url)) {
          $scope.getAllDeliveries(data.current_page + 1);
        }
      }).then(function(data, xhr) {
        angular.forEach(data.data.data, function(value, key) {
          $scope.messages.push(value);
          $scope.threadDeliveries.push(value);
        });
      });
    };
    $scope.moveToDelivered = function() {
      MessageFactory.updateToDeliver($rootScope.$stateParams.threadId).success(function(data, xhr) {
        $log.log('moveToDelivered::data', data);
        UserFactory.getNotify();
        $state.go('messages.inquiries');
      });
    };
    $scope.getLatestMessages = function(conversationLength, dataTotal) {
      $log.log('conversationLength: ', conversationLength);
      $log.log('dataTotal:', dataTotal);
      if (conversationLength > dataTotal) {
        $scope.getMessagesByOffset(conversationLength, 15);
      }
    };
    $scope.getMessagesByOffset = function(offset, take) {
      var thread;
      thread = $rootScope.$stateParams.threadId;
      MessageFactory.getMessageOffset(thread, offset, take).success(function(data, xhr) {
        $log.log('getMessagesByOffset::data', data);
        angular.forEach(data.messages, function(value, key) {
          $scope.messages.unshift(value);
        });
      });
    };
    $scope.createMessage = function() {
      $log.info('Create a message');
      $scope.changeHeading('Create');
      $scope.message.subject = 'Message from ' + $rootScope.me.user.first_name + ' ' + $rootScope.me.user.last_name;
    };
    $scope.getUser = function(event) {
      if (event.keyCode === 13) {
        $log.log(event.target.value);
        $scope.search = [];
        $scope.searchUserByFilter(event.target.value);
      }
      event.preventDefault();
    };
    $scope.sendWithUser = function(user) {
      $scope.search = [];
      $scope.message.user = user.id;
      $scope.message.send = user.full_name;
      $log.log($scope.message);
    };
    $scope.searchUserByFilter = function(value) {
      var param, v;
      $log.log('searchUserByFilter::ifElse', Boolean(value.substr(0, value.indexOf(":"))));
      if (Boolean(value.substr(0, value.indexOf(":")))) {
        v = value.substr(value.indexOf(":") + 2);
        param = value.substr(0, value.indexOf(":"));
        SearchFactory.getUser(v, {
          'action': param
        }).success(function(data, xhr) {
          $scope.searchError = false;
          $log.log('searchUserByFilter::data', data);
          angular.forEach(data.success.data, function(value, key) {
            return $scope.search.push(value);
          });
        }).error(function(data, xhr) {
          $log.error('searchUserByFilter::data', data);
          $scope.searchError = true;
          $scope.searchErrorMessage = data.error.message;
        });
      }
      SearchFactory.getUser(value).success(function(data, xhr) {
        $scope.searchError = false;
        $log.log('searchUserByFilter::data', data);
        angular.forEach(data.success.data, function(value, key) {
          return $scope.search.push(value);
        });
      }).error(function(data, xhr) {
        $log.error('searchUserByFilter::data', data);
        $scope.searchError = true;
        $scope.searchErrorMessage = data.error.message;
      });
    };

    /**
     * Submit new message
     *
     * @param  {$event} event 
     * @param  {object|mixed} form
     *
     * @return {void}
     */
    $scope.submitNewMessage = function(event, form) {
      event.preventDefault();
      $scope.messageSubmitButton.state = !$scope.messageSubmitButton.state;
      MessageFactory.sendMessage(event.target.action, $scope.message).success(function(data, xhr) {
        $log.log('submitNewMessage::data', data);
        $scope.messageSubmitButton.state = !$scope.messageSubmitButton.state;
        $scope.message.body = '';
        $state.go('messages.thread', {
          threadId: data.success.data.thread.id
        });
      });
    };
    $scope.getAllInboxes = function(page) {
      $scope.changeHeading('Inbox');
      $scope.inbox = [];
      $scope.threadInboxes = [];
      MessageFactory.getInboxMessages(page).success(function(data, xhr) {
        $log.log('getAllInboxes::data', data);
        if (Boolean(data.next_page_url)) {
          $scope.getAllInboxes(data.current_page + 1);
        }
      }).then(function(data, xhr) {
        angular.forEach(data.data.data, function(value, key) {
          $scope.inbox.push(value);
          $scope.threadInboxes.push(value);
        });
      });
    };
  });

}).call(this);

(function() {
  _okie.controller('ProfileController', function($scope, $http, $timeout, $rootScope, $state, $stateParams, UserFactory) {
    $scope.getHeading = function() {
      UserFactory.getUser().success(function(data, xhr) {
        $scope.heading = data.user.first_name + ' ' + data.user.last_name + '<span>\'s profile</span>';
      });
    };
    $scope.getHeading();
  });

}).call(this);

(function() {
  _okie.controller('UserSettingsController', function($scope, $http, $state, $stateParams, $rootScope) {});

}).call(this);

(function() {
  _okie.factory('MessageFactory', function($http) {
    var _m, urls;
    _m = {};
    urls = {
      base: '/me/messages',
      inquiries: '/me/messages/inquiries',
      inquire: '/me/messages/inquire/',
      delivered: '/me/messages/delivered',
      updateDeliver: '/me/messages/inquire/delivered',
      offset: '/me/messages/offset/',
      inbox: '/me/messages/inbox',
      search: {
        users: '/search/user/'
      }
    };
    _m.urls = urls;
    _m.getAllMessages = function() {
      return console.log('Get All Messages');
    };
    _m.getInquiryMessages = function(pageNumber) {
      return $http({
        url: urls.inquiries,
        method: "GET",
        params: {
          page: (pageNumber ? pageNumber : 1)
        }
      });
    };
    _m.getThreadMessages = function(threadId) {
      return $http({
        url: urls.inquire + threadId
      });
    };
    _m.getMessages = function(id, pageNumber) {
      return $http({
        url: urls.inquire + id + '/messages',
        method: "GET",
        params: {
          page: (pageNumber ? pageNumber : 1)
        }
      });
    };
    _m.replyToMessage = function(action, message) {
      return $http({
        url: action,
        method: "POST",
        data: message
      });
    };
    _m.getDeliveryMessages = function(pageNumber) {
      return $http({
        url: urls.delivered,
        method: "GET",
        params: {
          page: (pageNumber ? pageNumber : 1)
        }
      });
    };
    _m.updateToDeliver = function(threadId) {
      return $http({
        url: urls.updateDeliver,
        method: "POST",
        data: {
          id: threadId
        }
      });
    };
    _m.getMessageOffset = function(thread, offset, take) {
      return $http({
        url: urls.offset,
        method: "GET",
        params: {
          offset: offset,
          thread: thread,
          take: (take ? take : 5)
        }
      });
    };
    _m.sendMessage = function(url, data) {
      return $http({
        url: url,
        method: "POST",
        data: data
      });
    };
    _m.getInboxMessages = function(pageNumber) {
      return $http({
        url: urls.inbox,
        method: "GET",
        params: {
          page: (pageNumber ? pageNumber : 1)
        }
      });
    };
    return _m;
  });

}).call(this);
