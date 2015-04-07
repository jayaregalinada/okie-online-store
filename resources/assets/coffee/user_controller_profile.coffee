_okie.controller 'ProfileController', ( $scope, $http, $timeout, $rootScope, $state, $stateParams, UserFactory )->

    $scope.getHeading = ->
        UserFactory.getUser()
        .success ( data, xhr )->
            $scope.heading = data.user.first_name + ' ' + data.user.last_name + '<span>\'s profile</span>'

            return

        return


    $scope.getHeading()

    return