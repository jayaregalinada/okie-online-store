_okie.factory 'ProductFactory', ( $http, $q, $rootScope, $window )->

    prod = {}
    products = {}

    prod.getProducts = ->
        products

    prod.setProducts = ( p )->
        products = p


    prod

