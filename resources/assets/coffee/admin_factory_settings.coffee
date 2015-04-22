_okie.factory 'SettingsFactory', ( $http, $rootScope, $state, $stateParams, $window )->

	_s = {}
	_s.url =
		base: '/me/settings'
		categories: '/me/products/categories'
		product: '/product/'
	_s.availableMethod = [ "GET", "POST" ]

	_s.getAllUsers = ( pageNumber, url, method, params )->
		defaultParams = 
			page: pageNumber
		$http
			url: if url then url else $window._url.settings.users
			method: if method then method else "GET"
			params: if params then params else defaultParams

	###*
	 * CATEGORIES
	###

	_s.addCategory = ( data, url, method, params )->
		defaultParams = 
			create: data.category
		$http
			url: if url then url else _s.url.categories
			method: if method then method else "POST"
			params: if params then params else defaultParams
			data: data

	_s.getAllCategories = ( pageNumber, url, method, defaultParams )->
		defaultParams = 
			page: if pageNumber then pageNumber else 1
		$http
			url: if url then url else _s.url.categories
			method: if method then method else "GET"
			params: defaultParams

	_s.getCategoryById = ( id, url, method, params )->
		defaultParams = 
			find: id
		$http
			url: if url then url else _s.url.categories
			method: if method then method else "GET"
			params: if params then params else defaultParams

	_s.updateCategory = ( data, url, method, params )->
		defaultParams =
			update: true
		$http
			url: if url then url else _s.url.categories
			method: if method then method else "POST"
			params: if params then params else defaultParams
			data: data

	_s.deleteCategory = ( id, url, data, method, params )->
		defaultParams =
			delete: true
		defaultData =
			id: id
		$http
			url: if url then url else _s.url.categories
			params: if params then params else defaultParams
			method: if method then method else "POST"
			data: if data then data else defaultData

	###*
	 * PERMISSIONS
	###

	_s.changePermission = ( data, url, method, params )->
		$http
			url: if url then url else $window._url.settings.permissions
			method: if method then method else "PATCH"
			data: data
			params: params

	_s.changeGeneral = ( data, url, method, params )->
		$http
			url: if url then url else $window._url.settings.general
			method: if method then method else "POST"
			data: data
			params: params


	_s
