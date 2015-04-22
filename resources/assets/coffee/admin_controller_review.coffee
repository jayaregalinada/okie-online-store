_okie.controller 'ReviewController', ( $scope, $state, $stateParams, $http, $log, Notification, $window, $timeout )->

    $scope.reviews = []
    $scope.hoverApproved = false

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

    $scope.__get = ( url, params )->
        $http
            url: url
            method: "GET"
            params: params

    $scope.__post = ( url, data, method, params )->
        $http
            url: url
            data: data
            method: if method then method else "POST"
            params: params

    $scope.pushToReviews = ( data )->
        angular.forEach data, ( value, key )->
            $scope.reviews.push value
            return
        $scope.loadingState = false
        $timeout(->
            $scope.errorState = false
            return
        , 3000 )

        return

    $scope.getAllReviews = ( page )->
        $scope.loadingState = true
        $scope.errorState = false
        $scope.changeHeading 'Loading...'
        $scope.__get( $window._url.reviews.all, 
            page: page
        ).success ( success )->
            $scope.changeHeading 'Reviews'
            $log.log 'ReviewController.getAllReviews:success', success
            $scope.errorState = false
            if Boolean( success.next_page_url )
                $scope.getAllReviews success.current_page + 1

            return
        .error ( error )->
            $log.log 'ReviewController.getAllReviews:error', error
            $scope.errorState = true
            $scope.loadingState = false
            $scope.errorMessage = error.error.message

            return
        .then ( data )->
            $scope.pushToReviews data.data.data

            return

        return

    $scope.approveReview = ( id, index )->
        $scope.__post $window._url.reviews.approved.replace '_REVIEW_ID_', id 
        .success ( success )->
            Notification.success success.success
            $scope.reviews[ index ] = success.success.data

            return
        .error ( error )->
            Notification.error error.error

            return

        return

    $scope.hoverApprovedChange = ( index, boolean )->
        $scope.reviews[ index ].hoverApproved = boolean

        return

    $scope.unapproveReview = ( id, index )->
        $scope.__post $window._url.reviews.unapproved.replace '_REVIEW_ID_', id 
        .success ( success )->
            Notification.success success.success
            $scope.reviews[ index ] = success.success.data

            return
        .error ( error )->
            Notification.error error.error

            return

        return

    return
