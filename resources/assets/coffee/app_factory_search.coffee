_okie.factory 'SearchFactory', ( $http, $state, $stateParams, $rootScope, localStorageService )->

	_s = {}

	_s.url =
		base: '/search/'
		users: '/search/user/'

	_s.getUser = ( user, params )->
		$http
			url: _s.url.users + user
			method: "GET"
			params: params

	_s

