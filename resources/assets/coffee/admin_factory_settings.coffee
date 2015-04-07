_okie.factory 'SettingsFactory', ( $http, $rootScope, $state, $stateParams )->

	_s = {}
	_s.url =
		base: '/me/settings'
		categories: '/me/products/categories'
		product: '/product/'

	###*
	 * CATEGORIES
	###

	_s.addCategory = ( data )->
		$http
			url: _s.url.categories
			method: "POST"
			params:
				create: data.category
			data: data

	_s.getAllCategories = ->
		$http
			url: _s.url.categories
			method: "GET"
			params:
				all: true

	_s.getCategoryById = ( id )->
		$http
			url: _s.url.categories
			method: "GET"
			params:
				find: id

	_s.updateCategory = ( data )->
		$http
			url: _s.url.categories
			method: "POST"
			params:
				update: true
			data: data

	_s.deleteCategory = ( id )->
		$http
			url: _s.url.categories
			params:
				delete: true
			method: "POST"
			data: 
				id: id

	_s
