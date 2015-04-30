(function() {
  _okie.controller('MessageController', function($scope, $document, $window, $log, $interval, $http, $state, $stateParams, $rootScope, $sce, $timeout, MessageFactory, textAngularManager, UserFactory, SearchFactory, InquiryFactory, localStorageService, InboxFactory, Notification, RatingFactory) {
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
    $scope.inquiries = [];
    $scope.inquiryConversations = [];
    $scope.inquiryInfo = {};
    $scope.inquiryState = false;
    $scope.inquiryLoadingState = false;
    $scope.inquiryErrorState = false;
    $scope.inquiriesKey = 'inquiries';
    $scope.inquiryStateReserve = false;
    $scope.reserve = 0;
    $scope.inquiryProduct = {};
    $scope.inbox = [];
    $scope.inboxConversations = [];
    $scope.inboxInfo = {};
    $scope.inboxState = false;
    $scope.inboxLoadingState = false;
    $scope.inboxErrorState = false;
    $scope.inboxKey = 'inbox';
    $scope.threadDeliveries = [];
    $scope.threadInboxes = [];
    $scope.intervalSeconds = 3000;
    $scope.alerts = [];
    $scope.message = {};
    $scope.search = [];
    $scope.searchError = false;
    $scope.url = $window._url;
    $scope.storage = localStorageService;
    $scope.autoSubmitConversation = false;

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
     * Store to localStorage in JSON.stringify
     *
     * @param  {string} key
     * @param  {object|array} data
     *
     * @return {void}
     */
    $scope.store = function(key, data) {
      $scope.storage.set(key, JSON.stringify(data));
    };
    $scope.autoSubmit = function() {
      $scope.autoSubmitConversation = !$scope.autoSubmitConversation;
      localStorageService.set('auto_submit', $scope.autoSubmitConversation);
      $log.log('MessageController@autoSubmit', $scope.autoSubmitConversation);
    };
    $scope.checkIfAutoSubmit = function() {
      $log.log('MessageController@checkIfAutoSubmit', localStorageService.get('auto_submit'));
      if (!localStorageService.get('auto_submit')) {
        localStorageService.set('auto_submit', true);
      }
      return localStorageService.get('auto_submit');
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

    /**
     * Get all inquiries by page
     *
     * @param  {integer} page [Page number]
     *
     * @return {void}
     */
    $scope.getAllInquiries = function(page) {
      $scope.inquiries = [];
      $scope.changeHeading('Inquiries');
      page = page ? page : 1;
      $scope.inquiryState = true;
      $scope.inquiryLoadingState = true;
      $scope.inquiryErrorState = false;
      $scope.inquiryConversations = [];
      InquiryFactory.getAllInquiries(page).success(function(data, xhr) {
        $log.log('getAllInquiries::data', data);
        $scope.inquiryErrorState = false;
        if (Boolean(data.next_page_url)) {
          $scope.getAllInquiries(data.current_page + 1);
        }
      }).error(function(data, xhr) {
        $scope.inquiryErrorState = true;
        $scope.inquiryLoadingState = false;
        $scope.inquiryErrorMessage = data.error.message.replace('[INQUIRY] ', '');
      }).then(function(data, xhr) {
        $scope.pushToInquiries(data.data.data);
      });
    };

    /**
     * Push data to $scope.inquiries
     *
     * @param  {array} data
     *
     * @return {void}
     */
    $scope.pushToInquiries = function(data) {
      angular.forEach(data, function(value, key) {
        $scope.inquiries.push(value);
      });
      $scope.inquiryLoadingState = false;
      $timeout(function() {
        $scope.inquiryState = false;
        return $scope.inquiryErrorState = false;
      }, 3000);
    };

    /**
     * Push data to $scope.inbox
     *
     * @param  {array} data
     *
     * @return {void}
     */
    $scope.pushToInbox = function(data) {
      angular.forEach(data, function(value, key) {
        $scope.inbox.push(value);
      });
      $scope.inboxLoadingState = false;
      return $timeout(function() {
        return $scope.inboxState = false;
      }, 3000);
    };

    /**
     * All shortcuts
     * instead of refreshing the whole page
     * just alt + r
     *
     * @return {void}
     */
    $scope.keyBinder = function() {
      $document.bind('keyup', function(event) {
        if (event.keyCode === 82 && event.altKey) {
          event.preventDefault();
          switch ($state.current.name) {
            case 'messages.inquiries':
              if (!$scope.inquiryState) {
                $scope.inquiries = [];
                return $scope.getAllInquiries();
              }
              break;
            case 'messages.viewInquiry':
              if (!$scope.inquiryState) {
                $scope.inquiryConversations = [];
                return $scope.getToInquiryMessages($rootScope.$stateParams.inquiryId);
              }
              break;
            case 'messages.inbox':
              if (!$scope.inboxState) {
                $scope.inbox = [];
                return $scope.getAllInboxes();
              }
              break;
            case 'messages.viewInbox':
              if (!$scope.inboxState) {
                $scope.inboxConversations = [];
                return $scope.getToInboxMessages($rootScope.$stateParams.inboxId, 1);
              }
          }
        }
      });
    };

    /**
     * Reply on inquiry
     *
     * @param  {object} event
     * @param  {object} form
     *
     * @return {mixed}
     */
    $scope.inquiryReplySubmit = function(event, form) {
      var data, tA;
      event.preventDefault();
      $scope.replySubmitButton.state = !$scope.replySubmitButton.state;
      tA = textAngularManager.retrieveEditor('reply');
      data = {
        message: form.reply.$modelValue,
        item: $scope.inquiryInfo.product_id,
        inquisition: $scope.inquiryInfo.inquisition_id,
        inquiry: $rootScope.$stateParams.inquiryId
      };
      InquiryFactory.replyInquiry(data).success(function(d, xhr) {
        $log.log('inquiryReplySubmit::data', d);
        tA.scope.$parent.reply = '';
        $scope.inquiryConversations.push(d.success.data);
      }).error(function(data, xhr) {
        $scope.alerts.push(data.error);
        $timeout(function() {
          $scope.alerts = [];
          $scope.inquiryConversations = [];
          $scope.getToInquiryMessages($rootScope.$stateParams.inquiryId);
          $scope.replySubmitButton.state = false;
        }, 4000);
      }).then(function(d) {
        $scope.replySubmitButton.state = !$scope.replySubmitButton.state;
        $scope.backToTextArea(500);
        tA.scope.displayElements.text.trigger('focus');
      });
    };

    /**
     * Reply on Inbox
     *
     * @param  {object} event
     * @param  {object} form
     *
     * @return {mixed}
     */
    $scope.inboxReplySubmit = function(event, form) {
      var data, tA;
      event.preventDefault();
      $scope.replySubmitButton.state = !$scope.replySubmitButton.state;
      tA = textAngularManager.retrieveEditor('reply');
      data = {
        message: form.reply.$modelValue,
        inbox: $scope.inboxInfo.id
      };
      InboxFactory.reply(data).success(function(d, xhr) {
        $log.log('inboxReplySubmit::data', d);
        tA.scope.$parent.reply = '';
        $scope.inboxConversations.push(d.success.data);
      }).error(function(data, xhr) {
        $scope.alerts.push(data.error);
        $timeout(function() {
          $scope.alerts = [];
          $scope.getToInboxMessages($rootScope.$stateParams.inboxId);
        }, 4000);
      }).then(function(d) {
        $scope.replySubmitButton.state = !$scope.replySubmitButton.state;
        $scope.backToTextArea(500);
        tA.scope.displayElements.text.trigger('focus');
      });
    };

    /**
     * Get to INQUIRY conversation
     *
     * @param  {integer} inquiryId
     * @param  {integer} pageNumber
     *
     * @return {mixed}
     */
    $scope.getToInquiryMessages = function(inquiryId, pageNumber) {
      $scope.changeHeading('Loading conversations');
      $scope.inquiryState = true;
      InquiryFactory.getConversations(inquiryId, pageNumber).success(function(data, xhr) {
        $log.log('getToInquiryMessages::data', data);
        $scope.changeHeading(data.inquiry.title, '<span>INQUIRY: &nbsp;</span>');
        $scope.inquiryErrorState = false;
        if (Boolean(data.conversations.next_page_url)) {
          $scope.getToInquiryMessages($rootScope.$stateParams.inquiryId, data.conversations.current_page + 1);
        }
        $scope.autoSubmitConversation = localStorageService.get('auto_submit');
      }).error(function(data, xhr) {
        $scope.inquiryErrorState = true;
        $log.error('getToInquiryMessages::data', data);
        $scope.changeHeading('ERROR');
        $scope.inquiryErrorMessage = data.error.message;
      }).then(function(data, xhr) {
        angular.forEach(data.data.conversations.data, function(value, key) {
          $scope.inquiryConversations.push(value);
        });
        $scope.inquiryInfo = data.data.inquiry;
        $scope.backToTextArea();
        $timeout(function() {
          return $scope.inquiryState = false;
        }, 3000);
      });
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
        $scope.backToTextArea();
        tA.scope.displayElements.text.trigger('focus');
      });
    };
    $scope.moveToDelivered = function() {
      InquiryFactory.markAsDeliver({
        inquiry: $rootScope.$stateParams.inquiryId
      }).success(function(data, xhr) {
        $log.log('moveToDelivered::data', data);
        return $log.info('moveToDelivered()::Checkuser', $rootScope.user.is_permitted);
      });
    };

    /**
     * Create INBOX
     *
     * @return {void}
     */
    $scope.createMessage = function() {
      $log.info('Create a message');
      $scope.changeHeading('Create');
      UserFactory.getNotify($scope.creatingMessage);
    };
    $scope.creatingMessage = function() {
      var name;
      name = $rootScope.me.user.is_permitted ? $rootScope.me.user.first_name + ' ' + $rootScope.me.user.last_name : 'You';
      $scope.message.subject = 'Message from ' + name;
    };

    /**
     * Searching the user in INBOX
     *
     * @param  {object} event
     *
     * @return {void}
     */
    $scope.getUser = function(event) {
      $scope.search = [];
      $scope.message.recipient = null;
      if (Boolean($scope.message.send)) {
        $scope.searchUserByFilter($scope.message.send);
      }
    };

    /**
     * The user Searched in INBOX
     *
     * @param  {object} user
     *
     * @return {void}
     */
    $scope.sendWithUser = function(user) {
      $scope.message.recipient = user.id;
      $scope.message.send = user.full_name;
      $scope.search = [];
      $log.log($scope.message);
    };

    /**
     * Search user in INBOX now on progress
     *
     * @param  {string} value
     *
     * @return {void}
     */
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
      $scope.alerts = [];
      InboxFactory.createMessage(event.target.action, $scope.message).success(function(data, xhr) {
        $log.log('submitNewMessage::data', data);
        $scope.messageSubmitButton.state = !$scope.messageSubmitButton.state;
        $state.go('messages.viewInbox', {
          inboxId: data.success.data.inbox.id
        });
      }).error(function(error, xhr) {
        $scope.message.recipient = null;
        $scope.message.send = null;
        $scope.messageSubmitButton.state = !$scope.messageSubmitButton.state;
        Notification.error(error.error);
      });
    };

    /**
     * Get all INBOX
     *
     * @param  {integer} page
     *
     * @return {void}
     */
    $scope.getAllInboxes = function(page) {
      $scope.inbox = [];
      $scope.changeHeading('Inbox');
      page = page ? page : 1;
      $scope.inboxState = true;
      $scope.inboxLoadingState = true;
      $scope.inboxErrorState = false;
      $scope.inboxConversations = [];
      InboxFactory.getAllInbox(page).success(function(data, xhr) {
        $log.log('getAllInboxes::data', data);
        $scope.inboxErrorState = false;
        if (Boolean(data.next_page_url)) {
          $scope.getAllInboxes(data.current_page + 1);
        }
      }).error(function(data, xhr) {
        $scope.inboxErrorState = true;
        $scope.inboxLoadingState = false;
        $scope.inboxErrorMessage = data.error.message.replace('[INBOX] ', '');
      }).then(function(data, xhr) {
        angular.forEach(data.data.data, function(value, key) {
          $scope.inbox.push(value);
        });
        $scope.inboxLoadingState = false;
        $scope.inboxState = false;
        $scope.inboxErrorState = false;
      });
    };

    /**
     * Get INBOX Conversation
     *
     * @param  {integer} inboxId
     * @param  {integer} pageNumber
     *
     * @return {void}
     */
    $scope.getToInboxMessages = function(inboxId, pageNumber) {
      $scope.changeHeading('Loading conversations');
      $scope.inboxState = true;
      InboxFactory.getConversations(inboxId, pageNumber).success(function(data, xhr) {
        $log.log('getToInboxMessages::data', data);
        $scope.changeHeading(data.inbox.title, '<span>INBOX: &nbsp;</span>');
        $scope.inboxErrorState = false;
        if (Boolean(data.conversations.next_page_url)) {
          $scope.getToInboxMessages($rootScope.$stateParams.inboxId, data.conversations.current_page + 1);
        }
      }).error(function(data, xhr) {
        $scope.inboxErrorState = true;
        $log.error('getToInboxMessages::data', data);
        $scope.changeHeading('ERROR');
        $scope.inboxErrorMessage = data.error.message;
      }).then(function(data, xhr) {
        angular.forEach(data.data.conversations.data, function(value, key) {
          $scope.inboxConversations.push(value);
        });
        $scope.inboxInfo = data.data.inbox;
        $scope.backToTextArea();
        $timeout(function() {
          return $scope.inboxState = false;
        }, 3000);
      });
    };
    $scope.inquiryReserve = function(event) {
      event.preventDefault();
      $scope.inquiryStateReserve = !$scope.inquiryStateReserve;
    };
    $scope.reserveItem = function() {
      InquiryFactory.reserveInquiry({
        inquiry: $scope.inquiryInfo.id,
        reserve: $scope.reserve
      }).success(function(success) {
        $log.log('MessageController.reserveItem::success', success);
        $log.log($scope.reserve);
        $scope.inquiryStateReserve = !$scope.inquiryStateReserve;
        Notification.success(success.success);
        $scope.reserve = 0;
        $scope.inquiryInfo = success.success.data.inquiry;
        $log.log($scope.reserve);
      }).error(function(error) {
        Notification.error(error.error);
      }).then(function(data) {
        $scope.reserve = 0;
        $log.log($scope.reserve);
      });
    };
    $scope.destroyConversation = function(id, index) {
      InboxFactory.removeConversation(id).success(function(success) {
        Notification.success(success.success);
        $scope.inboxConversations.splice(index, 1);
      }).error(function(error) {
        Notification.error(error.error);
      });
    };
    $scope.reserveButton = function(e) {
      switch (e) {
        case 'minus':
          $scope.reserve = $scope.reserve > 1 ? $scope.reserve - 1 : 0;
          break;
        case 'add':
          $scope.reserve = $scope.reserve < $scope.inquiryInfo.product.unit ? $scope.reserve + 1 : $scope.reserve + 0;
          break;
      }
    };
    $scope.conversationShortcuts = function(event, form) {
      var tA;
      tA = textAngularManager.retrieveEditor('reply');
      if (event.keyCode === 13 && event.altKey) {
        $log.log('MessageController@conversationShortcuts.form.$valid', form.$valid);
        $log.log('MessageController@conversationShortcuts.tA', tA);
        $log.log('MessageController@conversationShortcuts.form', form);
        if (form.$valid) {
          event.preventDefault();
          $log.log(tA.scope.html = '');
        }
        event.preventDefault();
      }
    };
    $scope.emptyTextArea = function() {
      var tA;
      tA = textAngularManager.retrieveEditor('reply');
      tA.scope.$parent.reply = '';
    };
    $scope.getInquiriesByProductId = function(id, page) {
      $scope.changeHeading('Loading inquiries');
      $scope.inquiryState = true;
      $scope.inquiryLoadingState = true;
      $scope.inquiryErrorState = false;
      $scope.inquiryConversations = [];
      InquiryFactory.getByProduct(id, page).success(function(success) {
        $log.log('MessageController@getInquiriesByProductId::success', success);
        $scope.inquiryErrorState = false;
        $scope.inquiryProduct = success.success.data.product;
        if (Boolean(success.next_page_url)) {
          $scope.getInquiriesByProductId($stateParams.productId, success.current_page + 1);
        }
      }).error(function(error) {
        $scope.inquiryErrorState = true;
        $scope.inquiryLoadingState = false;
        $scope.inquiryErrorMessage = error.error.message.replace('[INQUIRY] ', '');
      }).then(function(data) {
        $scope.changeHeading('Inquiries by ' + data.data.success.data.product.name);
        $scope.pushToInquiries(data.data.success.data.inquiries.data);
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
  _okie.controller('UserSettingsController', function($scope, $http, $state, $stateParams, $rootScope, $modal, $log, UserSettingsFactory, Notification) {
    $scope.alerts = [];
    $scope.email = /^[a-z]+[a-z0-9._]+@[a-z]+\.[a-z.]{2,5}$/;
    $scope.emails = [];
    $scope.settings = {};
    $scope.state = {
      newsletterUnsubscribe: false
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
    $scope.getEmailSubscribe = function() {
      $scope.heading = 'Newsletter Subscription';
      $scope.changeHeading('Newsletter Subscription');
      $scope.emails = [];
      UserSettingsFactory.getEmailSubscribe('/newsletter').success(function(response, xhr) {
        $log.info('UserSettingsController.getEmailSubscribe::response', response);
        $scope.pushToEmails(response.success.data);
      }).error(function(response, xhr) {
        Notification.error({
          title: 'Opps, somethings went wrong',
          message: response.error.message
        });
      });
    };
    $scope.pushToEmails = function(data) {
      $scope.emails = [];
      angular.forEach(data, function(value, key) {
        return $scope.emails.push(value);
      });
    };
    $scope.subscribeNewsletter = function(event, form) {
      event.preventDefault();
      $log.log('event', event);
      $log.log('form', form);
      UserSettingsFactory.subscribeToNewsletter($scope.settings, event.target.action).success(function(response, xhr) {
        $log.log('UserSettingsController.subscribeNewsletter::response', response);
        Notification.success(response.success);
        $scope.emails = [];
        angular.forEach(response.success.emails, function(value, key) {
          $scope.emails.push(value);
        });
      }).error(function(response, xhr) {
        var res;
        $log.error('UserSettingsController.subscribeNewsletter::response', response);
        if (response.email) {
          res = {
            message: response.email.join(' | '),
            raw: response,
            title: 'Opps, :('
          };
          Notification.error(res);
        } else {
          Notification.error(response.error);
        }
      }).then(function(data, xhr) {
        $scope.settings.email = null;
      });
    };
    $scope.unsubscribing = function(email) {
      $scope.hotSeatNewsletterEmail = email;
      $scope.modalInstance = $modal.open({
        templateUrl: '/views/settings/confirm-unsubscribe.html',
        controller: 'UserSettingsController',
        size: 'sm',
        scope: $scope
      });
      $scope.modalInstance.result.then(function(email) {
        $scope.getEmailSubscribe();
        $log.info('Remove', email);
        $scope.hotSeatNewsletterEmail = null;
      });
    };
    $scope.unsubscribeNewsletter = function(event, form) {
      event.preventDefault();
    };
    $scope.newsletterConfirm = function(event, form) {
      event.preventDefault();
      $log.log($scope.hotSeatNewsletterEmail);
      $scope.state.newsletterUnsubscribe = true;
      UserSettingsFactory.unsubscribeToNewsletter({
        email: $scope.hotSeatNewsletterEmail
      }, event.target.action).success(function(successResponse, xhr) {
        $log.log('UserSettingsController.newsletterConfirm::successResponse', successResponse);
        Notification.success(successResponse.success);
        $scope.state.newsletterUnsubscribe = false;
      }).error(function(errorResponse, xhr) {
        $log.error('UserSettingsController.newsletterConfirm::errorResponse', errorResponse);
        Notification.error(errorResponse.error);
      }).then(function(data) {
        $log.log('UserSettingsController.newsletterConfirm::data', data);
        $scope.modalInstance.close($scope.hotSeatNewsletterEmail);
        $scope.hotSeatNewsletterEmail = null;
      });
    };
    $scope.newsletterCancel = function(event) {
      event.preventDefault();
      $scope.modalInstance.dismiss('cancel');
    };
  });

}).call(this);

(function() {
  _okie.factory('InboxFactory', function($http, $rootScope, $window) {
    var _i;
    _i = {};
    _i.createMessage = function(url, data, method) {
      return $http({
        url: url,
        data: data,
        method: method ? method : "POST"
      });
    };
    _i.getAllInbox = function(pageNumber, method) {
      return $http({
        url: $window._url.inbox.all,
        method: method ? method : "GET",
        params: {
          page: pageNumber
        }
      });
    };
    _i.getConversations = function(id, pageNumber, method) {
      return $http({
        url: $window._url.inbox.conversations.replace('_INQUIRY_ID_', id),
        method: method ? method : "GET",
        params: {
          page: pageNumber
        }
      });
    };
    _i.reply = function(data, url, method, params) {
      url = url ? url : $window._url.inbox.reply;
      return $http({
        url: url,
        data: data,
        method: method ? method : "POST",
        params: params
      });
    };
    _i.removeConversation = function(id, method) {
      return $http({
        url: $window._url.inbox.removeConversation.replace('_CONVERSATION_ID_', id),
        method: method ? method : "POST"
      });
    };
    return _i;
  });

}).call(this);

(function() {
  _okie.factory('InquiryFactory', function($http, $window) {
    var _i;
    _i = {};
    _i.availableMethod = ['GET', 'POST'];

    /**
     * @param  {integer} id
     * @param  {integer} pageNumber
     * @param  {string} method
     *
     * @return {$http}
     */
    _i.getInquiry = function(id, pageNumber, method) {
      return $http({
        url: $window._url.inquiry.find.replace('_INQUIRY_ID_', id),
        method: method ? method : "GET",
        params: {
          page: pageNumber
        }
      });
    };

    /**
     * @param  {integer} pageNumber
     * @param  {string} method
     *
     * @return {$http}
     */
    _i.getAllInquiries = function(pageNumber, method) {
      return $http({
        url: $window._url.inquiry.all,
        method: method ? method : "GET",
        params: {
          page: pageNumber ? pageNumber : 1
        }
      });
    };

    /**
     * @param  {integer} id
     * @param  {integer} pageNumber
     * @param  {string} method
     *
     * @return {$http}
     */
    _i.getConversations = function(id, pageNumber, method) {
      return $http({
        url: $window._url.inquiry.conversations.replace('_INQUIRY_ID_', id),
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
     * @param  {data} params
     *
     * @return {$http}
     */
    _i.replyInquiry = function(data, url, method, params) {
      return $http({
        url: url ? url : $window._url.inquiry.reply,
        data: data,
        method: method ? method : "POST",
        params: params
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
    _i.markAsDeliver = function(data, url, method, params) {
      return $http({
        url: url ? url : $window._url.inquiry.delivered,
        data: data,
        method: method ? method : "POST",
        params: params
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
    _i.reserveInquiry = function(data, url, method, params) {
      return $http({
        url: url ? url : $window._url.inquiry.reserve,
        data: data,
        method: method ? method : "POST",
        params: params
      });
    };

    /**
     * @param  {int} id
     * @param  {int|object} url
     * @param  {string} method
     * @param  {object} params
     *
     * @return {$http}
     */
    _i.getByProduct = function(id, params, url, method) {
      var defaultParams;
      defaultParams = {
        page: params
      };
      return $http({
        url: url ? url : $window._url.inquiry.byProduct.replace('_INQUIRY_ID_', id),
        method: method ? method : "GET",
        params: angular.isNumber(params) ? defaultParams : params
      });
    };
    return _i;
  });

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

(function() {
  _okie.factory('UserSettingsFactory', function($http, $window) {
    var _u;
    _u = {};

    /**
     * HTTP Post request to subscribe email newsletter
     *
     * @param  {object} data
     * @param  {string} url
     * @param  {string} method
     * @param  {object} params
     *
     * @return {$http}
     */
    _u.subscribeToNewsletter = function(data, url, method, params) {
      return $http({
        url: url,
        method: method ? method : "POST",
        data: data,
        params: params
      });
    };
    _u.getEmailSubscribe = function(url, method, params) {
      return $http({
        url: url,
        method: method ? method : "GET",
        params: params
      });
    };
    _u.unsubscribeToNewsletter = function(data, url, method, params) {
      return $http({
        url: url ? url : $window._url.settings.unsubscribeNewsletter,
        method: method ? method : "POST",
        data: data,
        params: params
      });
    };
    return _u;
  });

}).call(this);
