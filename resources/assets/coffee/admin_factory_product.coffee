_okie.factory 'ProductFactory', ( $http, $rootScope, $window )->

    _p = {}

    _p.getAllProducts = ( pageNumber, url, method, params )->
        defaultParams = 
            page: pageNumber
        $http
            url: if url then url else $window._url.products.all
            params: if params then params else defaultParams
            method: if method then method else "GET"

    _p.updateBadge = ( data, url, method, params )->
        $http
            url: url
            params: params
            method: if method then method else "PUT"
            data: data

    _p

