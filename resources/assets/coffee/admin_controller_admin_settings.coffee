_okie.controller 'AdminSettingsController', ( $scope, $log, $window, $rootScope, Notification, SettingsFactory, $state, $stateParams, $timeout, $http )->

    $scope.users = []
    $scope.settings =
        permission:
            error: false
    $scope.updatingState = []
    $scope.banners = []
    $scope.bannerInterval = 3000
    $scope.bannerError = false

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

    $scope.getAllBanads = ->
        $scope.changeHeading 'Banner'
        $scope.banners = []
        $http.get 'banners'
            .success ( success )->
                $log.log 'AdminSettingsController@getAllBanads::success', success
                $scope.bannerError = false
                $scope.bannerInterval = success.success.data.interval
                angular.forEach success.success.data.banners, ( val, key )->
                    $scope.banners.push val

                    return

                return
            .error ( error )->
                $scope.bannerError = true
                $scope.bannerErrorMessage = error.error.message
                
                return

        return

    $scope.removeBanad = ( id )->
        $log.log 'AdminSettingsController@removeBanads', id 
        $http
            url: $window._url.settings.deleteBanner.replace '_BANNER_ID_', id
            method: "DELETE"
        .success ( success )->
            $scope.banners = []
            $log.log 'AdminSettingsController@removeBanad::success', success
            Notification.success success.success
            $scope.bannerInterval = success.success.data.interval
            angular.forEach success.success.data.banners, ( val, key )->
                $scope.banners.push val

                return

            return
        .error ( error )->
            Notification.error error.error

            return

        return

    $scope.initializeDropzone = ( token )->
        $scope.dropzoneInit = new Dropzone( document.body,
            url: $window._url.settings.banner
            previewsContainer: '#bannerPreview .banner-preview'
            clickable: false
            acceptedFiles: 'image/*'
            params: 
                '_token': token
        )
        $scope.dropzoneInit.on 'queuecomplete', ( file, xhr )->
            $scope.getAllBanads()
            Notification.success
                title: 'Hooray!'
                message: 'Uploading complete'
            @.removeAllFiles()
            $( '#DZINDICATOR' ).fadeOut()
            $( '#bannerPreview ' ).hide()

            return
        $scope.dropzoneInit.on 'dragenter', ( file, xhr )->
            $log.info 'DROPZONE DRAG ENTER'
            $( '#DZINDICATOR' ).fadeIn()

            return
        $scope.dropzoneInit.on 'drop', ( file, xhr )->
            $log.info 'DROPZONE DROP'
            $( '#DZINDICATOR' ).animate(
                opacity: .5
            , 1000 )
            $( '#bannerPreview ' ).fadeIn()


            return


        return


    $scope.checkState()

    return
