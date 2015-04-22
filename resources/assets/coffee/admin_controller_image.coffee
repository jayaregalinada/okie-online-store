_okie.controller 'ImageController', ( $scope, $window, Lightbox, $rootScope, $http, localStorageService, Notification )->

    $scope.productKey = 'product_info'
    $scope.product = {}
    $scope.images = []

    ###*
     * Delete product image by id
     *
     * @param  {integer} index
     *
     * @return {void}
    ###
    $scope.deleteImage = ( index )->
        $scope.product = localStorageService.get( $scope.productKey )
        $http(
            url: '/product/' + $scope.product.id + '/images'
            method: "DELETE"
            params: 
                id: $rootScope.images[ index ].id
            ).success ( data, xhr )->
                Lightbox.closeModal()
                Notification.success data.success
                $( '.product-images .image-' + index ).remove()
                return
            .then ( data )->
                $scope.getImages()
                return

        return


    ###*
     * Set the image as primary thumbnail of the product
     *
     * @param {integer} index
    ###
    $scope.setAsPrimary = ( index )->
        $scope.product = localStorageService.get( $scope.productKey )
        $http(
            url: '/product/' + $scope.product.id + '/images'
            method: "POST"
            data:
                id: $rootScope.images[ index ].id
            ).success ( response, xhr )->
                Notification.success response.success
                Lightbox.closeModal()
                return 

        return

    ###*
     * Get images of the product
     *
     * @param  {integer} skip
     *
     * @return {void}
    ###
    $scope.getImages = ( skip )->
        $http(
                url: '/product/' + $scope.id + '/images'
                method: "GET"
                params: 
                    skip: ( if ( skip ) then skip else $scope.images.length )
            ).success ( data, status, headers, config )->
                angular.forEach data.data, ( value, key )->
                    # console.log value 
                    $scope.images.push value
                    $window.productImages.push value
                    return
            .then ( data )->
                if( $scope.images.length < data.data.total )
                    $scope.getImages( $scope.images.length )
                    # console.log 'yes'
                    return
                return

        return



    return
