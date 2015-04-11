_okie.controller 'UserSettingsController', ( $scope, $http, $state, $stateParams, $rootScope, $log, UserSettingsFactory )->

    $scope.alerts = []
    $scope.email = /^[a-z]+[a-z0-9._]+@[a-z]+\.[a-z.]{2,5}$/
    $scope.emails = []
    $scope.settings = {}

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
        $scope.emails = []
        UserSettingsFactory.getEmailSubscribe '/newsletter'
            .success ( response, xhr )->
                $log.info 'UserSettingsController.getEmailSubscribe::response', response
                angular.forEach response.success.data, ( value, key )->
                    $scope.emails.push value
                    return

                return
            .error ( response, xhr )->
                $scope.alerts.push response.error

                return

        return

    $scope.subscribeNewsletter = ( event, form )->
        event.preventDefault()

        $log.log 'event', event
        $log.log 'form', form

        UserSettingsFactory.subscribeToNewsletter( $scope.settings, event.target.action )
            .success ( response, xhr )->
                $log.log 'UserSettingsController.subscribeNewsletter::response', response
                $scope.emails = []
                angular.forEach response.success.emails, ( value, key )->
                    $scope.emails.push value
                    return

                return
            .error ( response, xhr )->
                $log.error 'UserSettingsController.subscribeNewsletter::response', response
                if response.email
                    res =
                        message: response.email.join( ' | ')
                        raw: response
                    $scope.alerts.push res
                else
                    $scope.alerts.push response.error

                return

            .then ( data, xhr )->
                $scope.settings.email = null

                return

        return

    return
