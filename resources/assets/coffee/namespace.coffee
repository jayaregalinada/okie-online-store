# ui-notification
# angular.module('ui-notification').run [
#   '$templateCache'
#   ($templateCache) ->
#     $templateCache.put 'angular-ui-notification.html', '<div class="ui-notification"><h3 ng-show="title" ng-bind-html="title"></h3><div class="message" ng-bind-html="message"></div></div>'
#     return
# ]

###*
 * OKIE Angularjs application module
 *
 * @type {object}
###
window._okie = angular.module( 'Okie', [ 'ui.bootstrap', 'ngAnimate', 'ui.router', 'ng-currency', 'bootstrapLightbox', 'LocalStorageModule', 'slugifier', 'textAngular', 'ui-notification', 'ui.select', 'ngTagsInput', 'colorpicker.module' ])

###*
 * OKIE Configuration
###
window._okie.config ( $interpolateProvider, $locationProvider, LightboxProvider, localStorageServiceProvider, $httpProvider, $animateProvider )->
    
    $interpolateProvider.startSymbol( '{#' )
    $interpolateProvider.endSymbol( '#}' )
    # $locationProvider.html5Mode( 
    #     enabled: true
    #     requireBase: false
    # ).hashPrefix( '!' )

    LightboxProvider.getImageUrl = ( image )->
        image.sizes[0].url

    LightboxProvider.getImageCaption = ( image )->
        image.caption

    LightboxProvider.calculateModalDimensions = ( dimensions )->
        width = Math.max 400, dimensions.imageDisplayWidth - 8

        if width >= dimensions.windowWidth - 20 or dimensions.windowWidth < 768
            width = 'auto'
        {
            'width': width
            'height': 'auto'
        }

    # LightboxProvider.calculateImageDimensionLimits = (dimensions) ->
    #     'maxWidth': if dimensions.windowWidth >= 768 then dimensions.windowWidth - 92 else dimensions.windowWidth - 52
    #     'maxHeight': 1600

    # LightboxProvider.templateUrl = '/views/product/lightbox.html'
    LightboxProvider.templateUrl = 'views/lightbox.html'

    localStorageServiceProvider.setPrefix( 'okie' )

    $httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"

    $animateProvider.classNameFilter( /carousel|animate/ )

    return

window._okie.run ( $rootScope, $state, $stateParams, UserFactory, $templateCache, Notification, $window, $location, $log )->
    'use strict'

    $rootScope.$state = $state
    $rootScope.$stateParams = $stateParams
    $rootScope.$messagesCount = UserFactory.messages
    $rootScope.notification = Notification
    $window.Notification = Notification
    $rootScope.location = $window.location
    $rootScope.collapseToggle = ->
        $( '#navbar_navigation .dropdown .collapse' ).collapse 'hide'

        return

    $rootScope.$on '$stateChangeStart', ( event, toState, toParams, fromState, fromParams )->
        UserFactory.getNotify()

        return

    $rootScope.$on 'cfpLoadingBar:loading', ( loading )->
        $log.log 'cfpLoadingBar:loading', loading

        return

    $rootScope.$on 'cfpLoadingBar:started', ( started )->
        $log.log 'cfpLoadingBar:started', started

        return

    $rootScope.$on 'cfpLoadingBar:completed', ( completed )->
        $log.log 'cfpLoadingBar:completed', completed

        return

    $templateCache.put 'angular-ui-notification.html', '<div class="ui-notification"><h3 ng-show="title" ng-bind-html="title"></h3><div class="message" ng-bind-html="message"></div></div>'
        
    return
    

###*
 * For Dropzone autodiscovery
 * 
 * @type {boolean}
###
Dropzone.autoDiscover = false

###*
 * Initialize if document is ready
 *
 * @return {void}
###
angular.element( document ).ready( ->
    angular.bootstrap( document, [ 'Okie' ] )

    $( '[data-toggle="tooltip"]' ).tooltip(
        container: 'body'
    )
  
    $( '[data-toggle="popover"]' ).popover()

    $( '.content-container' ).css
        minHeight: ( $( window ).height() - ( $('#navigation').outerHeight() + ( $('#navigation').outerHeight() / 2 ) + $('#footer').outerHeight() ) ) - 28
    
    return

)
