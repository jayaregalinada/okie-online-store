_okie.factory 'DeliverFactory', ( $http, $window )->

    _d = {}

    _d.availableMethod = [ 'GET', 'POST' ]

    ###*
     * @param  {int} pageNumber
     * @param  {string} url
     * @param  {string} method
     *
     * @return {$http}
    ###
    _d.getAll = ( pageNumber, url, method )->
        $http
            url: if url then url else $window._url.deliver.all
            method: if method then method else "GET"
            params: 
                page: pageNumber

    ###*
     * @param  {int} id
     * @param  {int} pageNumber
     * @param  {string} method
     *
     * @return {$http}
    ###
    _d.getConversations = ( id, pageNumber, method )->
        $http
            url: $window._url.deliver.conversations.replace '_DELIVER_ID_', id
            method: if method then method else "GET"
            params:
                page: pageNumber

    ###*
     * @param  {object} data
     * @param  {string} url
     * @param  {string} method
     * @param  {object} params
     *
     * @return {$http}
    ###
    _d.reply = ( data, url, method, params )->
        $http
            url: if url then url else $window._url.deliver.reply
            data: data
            method: if method then method else "POST"
            params: params


    _d
