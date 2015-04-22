_okie.controller 'AdminSettingsController', ( $scope, $log, $window, $rootScope, Notification, SettingsFactory, $state, $stateParams, $timeout )->

    $scope.users = []
    $scope.settings =
        permission:
            error: false
    $scope.updatingState = []

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

    $scope.checkState = ->
        $log.info 'AdminSettingsController.checkState()', $state.current
        return

    $scope.getPermissions = ( pageNumber )->
        $scope.changeHeading 'Permissions'
        $log.log 'Getting permissions'
        $scope.settings.permission.error = false
        SettingsFactory.getAllUsers( pageNumber )
            .success ( response, xhr )->
                $log.info 'AdminSettingsController.getPermissions::response', response
                $scope.settings.permission.error = false
                if Boolean( response.next_page_url )
                    $scope.getPermissions response.current_page + 1

                return
            .error ( error )->
                $scope.settings.permission.error = true
                $scope.settings.permission.errorMessage = error.error.message
                Notification.error error.error

                return
            .then ( thenResponse, xhr )->
                angular.forEach thenResponse.data.data, ( value, key )->
                    $scope.users.push value
                    return

                return

        return

    $scope.changePermission = ( user, permission )->
        $log.log user
        $log.log permission
        user.permissionState = true
        SettingsFactory.changePermission(
            user: user
            user_id: user.id
            permission: permission )
            .success ( response, xhr )->
                $log.info 'AdminSettingsController.changePermission', response
                user.permissionState = false
                Notification.success response.success
                return

        return

    $scope.getGeneral = ->
        $scope.changeHeading 'General Settings'

        
        return

    $scope.changeValue = ( value )->
        SettingsFactory.changeGeneral value
            .success ( success )->
                $log.info 'AdminSettingsController.changeValue::success', success
                Notification.success success.success

                return
            .error ( error )->
                $log.info 'AdminSettingsController.changeValue::error', error
                Notification.error error.error

                return
        return


    $scope.checkState()

    return
