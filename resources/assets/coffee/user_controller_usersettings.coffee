_okie.controller 'UserSettingsController', ( $scope, $http, $state, $stateParams, $rootScope, $modal, $log, UserSettingsFactory, Notification )->

    $scope.alerts = []
    $scope.email = /^[a-z]+[a-z0-9._]+@[a-z]+\.[a-z.]{2,5}$/
    $scope.emails = []
    $scope.settings = {}
    $scope.state =
        newsletterUnsubscribe: false


    ###*
     * Change the heading
     *
     * @param  {string} heading
     *
     * @return {void}
    ###
    $scope.changeHeading = ( heading, prepend )->
        $scope.heading = heading
        $('.profile-container .profile-full-name').text heading
        $('.profile-container .profile-full-name').prepend prepend

        return

    ###*
     * Close the alert
     *
     * @param  {integer} index
     *
     * @return {void}
    ###
    $scope.closeAlert = ( index )->
        $scope.alerts.splice( index, 1 )
        return

    $scope.getEmailSubscribe = ->
        $scope.heading = 'Newsletter Subscription'
        $scope.changeHeading 'Newsletter Subscription'
        $scope.emails = []
        UserSettingsFactory.getEmailSubscribe '/newsletter'
            .success ( response, xhr )->
                $log.info 'UserSettingsController.getEmailSubscribe::response', response
                $scope.pushToEmails response.success.data

                return
            .error ( response, xhr )->
                # $scope.alerts.push response.error
                Notification.error
                    title: 'Opps, somethings went wrong'
                    message: response.error.message

                return

        return

    $scope.pushToEmails = ( data )->
        $scope.emails = []
        angular.forEach data, ( value, key )->
            $scope.emails.push value

        return

    $scope.subscribeNewsletter = ( event, form )->
        event.preventDefault()
        $log.log 'event', event
        $log.log 'form', form

        UserSettingsFactory.subscribeToNewsletter( $scope.settings, event.target.action )
            .success ( response, xhr )->
                $log.log 'UserSettingsController.subscribeNewsletter::response', response
                Notification.success response.success
                $scope.emails = []
                angular.forEach response.success.emails, ( value, key )->
                    $scope.emails.push value
                    return

                return
            .error ( response, xhr )->
                $log.error 'UserSettingsController.subscribeNewsletter::response', response
                if response.email
                    res =
                        message: response.email.join( ' | ' )
                        raw: response
                        title: 'Opps, :('
                    # $scope.alerts.push res
                    Notification.error res
                else
                    # $scope.alerts.push response.error
                    Notification.error response.error

                return

            .then ( data, xhr )->
                $scope.settings.email = null

                return

        return

    $scope.unsubscribing = ( email )->
        $scope.hotSeatNewsletterEmail = email
        $scope.modalInstance = $modal.open
            templateUrl: '/views/settings/confirm-unsubscribe.html'
            controller: 'UserSettingsController'
            size: 'sm'
            scope: $scope

        $scope.modalInstance.result.then ( email )->
            $scope.getEmailSubscribe()
            $log.info 'Remove', email
            $scope.hotSeatNewsletterEmail = null
            return

        return

    $scope.unsubscribeNewsletter = ( event, form )->
        event.preventDefault()

        # UserSettingsFactory.unsubscribeToNewsletter
        return

    $scope.newsletterConfirm = ( event, form )->
        event.preventDefault()
        $log.log $scope.hotSeatNewsletterEmail
        $scope.state.newsletterUnsubscribe = true
        UserSettingsFactory.unsubscribeToNewsletter(
            email: $scope.hotSeatNewsletterEmail,
            event.target.action )
            .success ( successResponse, xhr )->
                $log.log 'UserSettingsController.newsletterConfirm::successResponse', successResponse
                Notification.success successResponse.success
                $scope.state.newsletterUnsubscribe = false

                return
            .error ( errorResponse, xhr )->
                $log.error 'UserSettingsController.newsletterConfirm::errorResponse', errorResponse
                Notification.error errorResponse.error

                return
            .then ( data )->
                $log.log 'UserSettingsController.newsletterConfirm::data', data
                $scope.modalInstance.close( $scope.hotSeatNewsletterEmail )
                $scope.hotSeatNewsletterEmail = null

                return

        return

    $scope.newsletterCancel = ( event )->
        event.preventDefault()
        $scope.modalInstance.dismiss( 'cancel' )

        return

    return
