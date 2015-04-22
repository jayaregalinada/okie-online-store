_okie.factory 'UserSettingsFactory', ( $http, $window )->

    _u = {}

    ###*
     * HTTP Post request to subscribe email newsletter
     *
     * @param  {object} data
     * @param  {string} url
     * @param  {string} method
     * @param  {object} params
     *
     * @return {$http}
    ###
    _u.subscribeToNewsletter = ( data, url, method, params )->
        $http
            url: url
            method: if method then method else "POST"
            data: data
            params: params

    _u.getEmailSubscribe = ( url, method, params )->
        $http
            url: url
            method: if method then method else "GET"
            params: params

    _u.unsubscribeToNewsletter = ( data, url, method, params )->
        $http
            url: if url then url else $window._url.settings.unsubscribeNewsletter
            method: if method then method else "POST"
            data: data
            params: params


    _u
