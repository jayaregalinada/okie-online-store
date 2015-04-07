_okie.factory 'ItemFactory', ( $http, $q )->

    _i = {}
    urls =
        base: '/items/'
        category: '/items/category/'
        inquiry: '/item/inquire'

    ###*
     * Get all items by page number
     *
     * @param  {integer} pageNumber
     *
     * @return $http
    ###
    _i.getAll = ( pageNumber )->
        $http(
            url: urls.base
            params:
                page: ( if ( pageNumber ) then pageNumber else 1 )
        )

    ###*
     * Get item by id
     *
     * @param  {integer} id
     *
     * @return $http
    ###
    _i.getItem = ( id )->
        $http(
            url: urls.base + id
        )

    ###*
     * Get all items by its category
     *
     * @param  {integer|string} category
     * @param  {integer} pageNumber
     *
     * @return $http
    ###
    _i.getAllByCategory = ( category, pageNumber )->
        $http(
            url: urls.category + category
            params:
                page: ( if ( pageNumber ) then pageNumber else 1 )
        )

    ###*
     * Get Item Url
     *
     * @return {string}
    ###
    _i.getItemUrl = ->
        urls.base

    ###*
     * Set Item url
     *
     * @param {string} url
     *
     * @return {string}
    ###
    _i.setItemUrl = ( url )->
        urls.base = url


    ###*
     * Send Inquiry message to the product
     *
     * @param  {object} message
     * @param  {object} params
     *
     * @return $http
    ###
    _i.sendInquiryMessage = ( message, params )->
        $http(
            url: urls.inquiry
            data: message
            params: params
            method: "POST"
        )



    _i