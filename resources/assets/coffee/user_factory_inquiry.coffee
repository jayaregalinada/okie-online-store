_okie.factory 'InquiryFactory', ( $http, $window )->

    _i = {}

    _i.availableMethod = [ 'GET', 'POST' ]

    ###*
     * @param  {integer} id
     * @param  {integer} pageNumber
     * @param  {string} method
     *
     * @return {$http}
    ###
    _i.getInquiry = ( id, pageNumber, method )->
        $http
            url: $window._url.inquiry.find.replace '_INQUIRY_ID_', id
            method: if method then method else "GET"
            params:
                page: pageNumber

    ###*
     * @param  {integer} pageNumber
     * @param  {string} method
     *
     * @return {$http}
    ###
    _i.getAllInquiries = ( pageNumber, method )->
        $http
            url: $window._url.inquiry.all
            method: if method then method else "GET"
            params:
                page: if pageNumber then pageNumber else 1

    ###*
     * @param  {integer} id
     * @param  {integer} pageNumber
     * @param  {string} method
     *
     * @return {$http}
    ###
    _i.getConversations = ( id, pageNumber, method )->
        $http
            url: $window._url.inquiry.conversations.replace '_INQUIRY_ID_', id
            method: if method then method else "GET"
            params:
                page: pageNumber

    ###*
     * @param  {object} data
     * @param  {string} url
     * @param  {string} method
     * @param  {data} params
     *
     * @return {$http}
    ###
    _i.replyInquiry = ( data, url, method, params )->
        $http
            url: if url then url else $window._url.inquiry.reply
            data: data
            method: if method then method else "POST"
            params: params

    ###*
     * @param  {object} data
     * @param  {string} url
     * @param  {string} method
     * @param  {object} params
     *
     * @return {$http}
    ###
    _i.markAsDeliver = ( data, url, method, params )->
        $http
            url: if url then url else $window._url.inquiry.delivered
            data: data
            method: if method then method else "POST"
            params: params

    ###*
     * @param  {object} data
     * @param  {string} url
     * @param  {string} method
     * @param  {object} params
     *
     * @return {$http}
    ###
    _i.reserveInquiry = ( data, url, method, params )->
        $http
            url: if url then url else $window._url.inquiry.reserve
            data: data
            method: if method then method else "POST"
            params: params

    ###*
     * @param  {int} id
     * @param  {int|object} url
     * @param  {string} method
     * @param  {object} params
     *
     * @return {$http}
    ###
    _i.getByProduct = ( id, params, url, method )->
        defaultParams = 
            page: params
        $http
            url: if url then url else $window._url.inquiry.byProduct.replace '_INQUIRY_ID_', id
            method: if method then method else "GET"
            params: if angular.isNumber( params ) then defaultParams else params

    ###*
     * @param  {int} id
     * @param  {string|boolean} value
     * @param  {string} url
     * @param  {object} params
     * @param  {string} method
     *
     * @return {$http}
    ###
    _i.updateReceiptUploads = ( id, value, url, params, method )->
        $http
            url: if url then url else $window._url.inquiry.receiptAllowness
            method: if method then method else "POST"
            params: params
            data:
                inquiry: id
                uploads: value


    _i
