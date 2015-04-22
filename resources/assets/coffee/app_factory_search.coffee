_okie.factory 'SearchFactory', ( $http, $state, $stateParams, $rootScope, localStorageService )->

	_s = {}

	_s.url =
		base: '/search/'
		users: '/search/user/'
		products: '/search/product/'

	_s.getUser = ( user, params )->
		$http
			url: _s.url.users + user
			method: "GET"
			params: params

	_s.getProduct = ( product, params )->
		$http
			url: _s.url.products + product
			method: "GET"
			params: params

	_s

