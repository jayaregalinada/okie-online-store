_okie.factory 'ClassFactory', ( $http, $q )->

    _c = {}

    _c.badgeClass = [
        'ribbon-info'
        'ribbon-success'
        'ribbon-info'
        'ribbon-warning'
        'ribbon-danger'
        'content-description'
        'content-serif'
        'font-light'
        'font-bold'
        'font-normal'
        'letter-spacing-1'
        'letter-spacing-2'
        'letter-spacing-3'
        'letter-spacing-4'
        'letter-spacing-5'
    ]

    _c.load = ->
        deferred = $q.defer()
        deferred.resolve _c.badgeClass
        deferred.promise

    _c
