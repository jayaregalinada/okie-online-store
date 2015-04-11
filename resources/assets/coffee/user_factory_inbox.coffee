_okie.factory 'InboxFactory', ( $http, $rootScope, $window )->

	_i = {}

	_i.createMessage = ( url, data, method )->
		$http
			url: url
			data: data
			method: if method then method else "POST"

	_i.getAllInbox = ( pageNumber, method )->
		$http
			url: $window._url.inbox.all
			method: if method then method else "GET"
			params:
				page: pageNumber

	_i.getConversations = ( id, pageNumber, method )->
		$http
			url: $window._url.inbox.conversations.replace '_INQUIRY_ID_', id
			method: if method then method else "GET"
			params:
				page: pageNumber

	_i.reply = ( data, url, method, params )->
		url = if url then url else $window._url.inbox.reply
		$http
			url: url
			data: data
			method: if method then method else "POST"
			params: params

	_i
