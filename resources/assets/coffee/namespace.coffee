window._okie = angular.module( 'Okie', [ 'ui.bootstrap', 'ngAnimate', 'ui.router', 'ng-currency', 'bootstrapLightbox', 'LocalStorageModule', 'slugifier', 'textAngular' ])
    
###########
## CONFIG 
###########
window._okie.config ( $interpolateProvider, LightboxProvider, localStorageServiceProvider, $httpProvider, $animateProvider )->
    
    $interpolateProvider.startSymbol('{#')
    $interpolateProvider.endSymbol('#}')

    LightboxProvider.getImageUrl = ( image )->
        image.sizes[0].url

    LightboxProvider.getImageCaption = ( image )->
        image.caption

    LightboxProvider.calculateModalDimensions = ( dimensions )->
        width = Math.max 400, dimensions.imageDisplayWidth + 32

        if width >= dimensions.windowWidth - 20 or dimensions.windowWidth < 768
            width = 'auto'
        {
            'width': width
            'height': 'auto'
        }

    # LightboxProvider.calculateImageDimensionLimits = (dimensions) ->
    #     'maxWidth': if dimensions.windowWidth >= 768 then dimensions.windowWidth - 92 else dimensions.windowWidth - 52
    #     'maxHeight': 1600

    LightboxProvider.templateUrl = '/views/product/lightbox.html'

    localStorageServiceProvider.setPrefix( 'okie' )

    $httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"

    $animateProvider.classNameFilter(/carousel|animate/)

    

    return

window._okie.run ( $rootScope, $state, $stateParams, UserFactory )->
    'use strict'

    $rootScope.$state = $state
    $rootScope.$stateParams = $stateParams
    $rootScope.$messagesCount = UserFactory.messages
    $rootScope.$on '$stateChangeStart', ( event, toState, toParams, fromState, fromParams )->
        UserFactory.getNotify()

        return
        
    return
    


Dropzone.autoDiscover = false


angular.element( document ).ready( ->
    angular.bootstrap( document, [ 'Okie' ] )

    $( '[data-toggle="tooltip"]' ).tooltip(
        container: 'body'
    )
  
    $( '[data-toggle="popover"]' ).popover()

    $( '.content-container' ).css
        minHeight: ( $( window ).height() - ( $('#navigation').outerHeight() + $('#footer').outerHeight() ) ) - 28
    
)
