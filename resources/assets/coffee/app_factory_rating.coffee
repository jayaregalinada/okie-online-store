_okie.factory 'RatingFactory', ( $http, $window )->

    _r = {}

    _r.sendRating = ( data, url, method, params )->
        $http
            url: url
            method: if method then method else "POST"
            params: params
            data: data

    ###*
     * Rate the item
     *
     * @param  {int} id
     * @param  {object} data
     * @param  {string} method
     *
     * @return $http
    ###
    _r.rateItem = ( id, data, url, method )->
        rateUrl = '/item/_ITEM_ID_/rate'
        $http
            url: if url then url else rateUrl.replace '_ITEM_ID_', id
            data: data
            method: if method then method else "POST"



    _r

