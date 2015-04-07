_okie.controller 'SettingsController', ( $scope, $log, $window, $http, localStorageService, $stateParams, $state, $location, $modal, SettingsFactory ) ->

    $scope.categories = []
    $scope.category = {}
    $scope.hotSeatCategory = 
        name: 'LOADING'
    $scope.stateCategory = false
    $scope.modalId = '#modal_category_edit'
    $scope.heading = 'Settings'

    $scope.checkState = ->
        $log 'Welcome to Settings Controller. Now checking state', $state.current

        switch $state.current.name
            when 'products.category' then $scope.getCategories()

        return

    $scope.addCategory = ( event )->
        event.preventDefault()
        SettingsFactory.addCategory $scope.category
            .success ( data, xhr )->
                console.info 'addCategory::data', data
                $scope.getCategories()

                return
            .then ( data )->
                $scope.category = {}

                return

        return

    $scope.getCategories = ->
        $scope.categories = []
        SettingsFactory.getAllCategories()
            .success ( data, xhr )->
                console.log 'getCategories::data', data
                $scope.categories.push data

                return

        return

    $scope.getCategoryById = ( id )->
        $http(
            url: '/me/settings/categories'
            params:
                find: id
            ).success ( data, xhr )->
                $scope.stateCategory = false
                $scope.hotSeatCategory = data
                return

        return


    $scope.editCategory = ( id )->
        $scope.hotSeatCategory = {}
        $scope.stateCategory = true
        $scope.getCategoryById id 
        return

    $scope.updateCategory = ( event )->
        $http(
            url: '/me/settings/categories'
            params: 
                update: true
            method: 'POST'
            data: $.param( $scope.hotSeatCategory )
            headers: 
                'Content-Type': 'application/x-www-form-urlencoded'
            ).success ( data, xhr )->
                $( $scope.modalId ).modal('hide')
                $scope.getCategories()
                return

        event.preventDefault()
        return

    $scope.deleteCategory = ( id, event )->
        $http(
            url: '/me/settings/categories'
            params:
                delete: true
            method: 'POST'
            data: $.param( { 'id': id } )
            headers: 
                'Content-Type': 'application/x-www-form-urlencoded'
            ).success ( data, xhr )->
                $scope.getCategories()
                return

        return

    #########
    $scope.checkState()

    return
