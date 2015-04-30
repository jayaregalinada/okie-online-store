_okie.controller 'BannerController', ( $scope, $log, $http, $state, $stateParams )->

    $scope.banners = []
    $scope.bannerInterval = 3000

    $scope.checkState = ->
        $log.info 'BannerController@checkState', $state.current
        switch $state.current.name
            when 'index' then $scope.getAllBanads()
            else $log.log 'Nothing will return'

        return

    $scope.getAllBanads = ->
        $scope.banners = []
        $http.get 'banners'
            .success ( success )->
                $log.log 'ItemController@getAllBanads::success', success
                $scope.bannerInterval = success.success.data.interval
                angular.forEach success.success.data.banners, ( val, key )->
                    $scope.banners.push val

                    return

                return

        return

    $scope.initializeDropzone = ( url, token )->
        $log.info 'ProductController.initializeDropzone', url
        $scope.dropzoneInit = new Dropzone( document.body,
            url: url
            previewsContainer: '#productPreview'
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
            $('#product_add_image_form header.drag').fadeIn()
            $('#product_add_image_form header.dropping').hide()
            return
        $scope.dropzoneInit.on 'dragenter', ( file, xhr )->
            $log.info 'DROPZONE DRAG ENTER'
            $('#product_add_image_form header.drag').hide()
            $('#product_add_image_form header.dropping').fadeIn()
            return
        $scope.dropzoneInit.on 'drop', ( file, xhr )->
            $('#product_add_image_form header').hide()
            return


        return

    $scope.checkState()

    return
