_okie.directive 'okieSearch', ( $window, $log, SearchFactory )->
    templateUrl: '/views/searchbox.html'
    restrict: 'AEC'
    link: ( scope, element, attrs, ngModel )->
        # $log.info 'scope', scope
        # $log.info 'element', element
        # $log.info 'attrs', attrs
        # $log.info 'ngModel', ngModel
    controller: ( $scope, $element, $attrs, $window, $timeout, $log, SearchFactory )->

        $scope.results = []
        $scope.resultErrorState = false
        $scope.selected = -1

        $scope.onFocus = ( event )->
            $element.find( 'input' ).animate
                width: '100%'
            , 500
            # $scope.selected = 0

            angular.element( $window).on 'keydown', ( e )->
                $log.log 'keydownEvent', e
                code = if e.which then e.which else e.keyCode
                # if( $scope.results.length )
                    # $log.log '$scope.results.length', $scope.results.length
                switch code
                    when 27
                        ## Escape
                        $scope.onBlur e
                    # when 40
                        ## TODO: Select results by keyDown
                        ## Arrow Down
                        # if( $scope.results.length )
                        #     if $scope.selected > ( $scope.results.length - 2 ) then return
                        #     $scope.selected++
                        #     $log.debug $scope.selected
                        #     if angular.isDefined( $scope.results[ $scope.selected - 1 ] ) then $scope.results[ $scope.selected - 1 ].selected = false
                        #     $scope.results[ $scope.selected ].selected = true
                        #     $log.log $scope.results[ $scope.selected ]
                    # when 38
                        ## TODO: Select results by keyDown
                        ## Arrow Up
                        # if( $scope.results.length )
                            # if $scope.selected <= $scope.results.length then return
                            # $scope.selected--
                            # $log.debug $scope.selected
                            # $log.log $scope.results[ $scope.selected ]
                            # $scope.results[ $scope.selected ].selected = true
                    else
                return

            return

        $scope.inputClone = ->
            $element.find( 'input' ).clone().css(
                width: 'auto'
                position: 'fixed'
                left: -9999999
                top: -9999999
            ).appendTo 'body'

        $scope.onBlur = ( event )->
            $element.find( '.search-results' ).slideUp()
            # $scope.selected = 0
            # $element.find( 'form' ).removeClass( 'searching' )
            if $scope.inputClone.length
                $element.find( 'input' ).animate
                    width: $scope.inputClone.css 'width'
                , 500
            
            return

        $scope.goTo = ( url )->
            $scope.search = ''
            $window.location.replace url

            return

        $scope.onChange = ( event )->
            $scope.inputClone()

            $element.find( 'input' ).animate
                width: '100%'
            , 500

            $log.info 'You are searching for', $scope.search

            if Boolean $scope.search
                $scope.onSearch event

            return

        $scope.onSearch = ( event )->
            leftPosition = $element.find( 'input' ).position().left
            topPosition = $element.find( 'input' ).position().top + $element.find( 'input' ).outerHeight( true )
            $element.find( '.search-results' ).css
                left: leftPosition
                top: topPosition
                width: $element.find( 'form' ).width()

            SearchFactory.getProduct $scope.search
                .success ( response )->
                    $scope.results = []
                    $scope.resultErrorState = false
                    $element.find( '.search-results' ).slideDown()

                    return
                .error ( error )->
                    $element.find( '.search-results' ).slideDown()
                    $scope.resultErrorState = true
                    $scope.resultErrorMessage = error.error.message

                    $timeout ->
                        $scope.onBlur()
                    , 5000

                    return
                .then ( data )->
                    angular.forEach data.data.success.data, ( value, key )->
                        $scope.results.push value
                        return
            return

        $scope.on_focus = ( event )->
            # $element.find( 'form' ).addClass( 'searching' )
            $scope.inputClone = $element.find( 'input' ).clone().css(
                width: 'auto'
                position: 'fixed'
                left: -9999999
                top: -9999999
            ).appendTo 'body'
            $element.find( 'input' ).animate
                width: '100%'
            , 500

            angular.element( $window).on 'keydown', ( e )->
                if e.keyCode is 27
                    $scope.onBlur e
                return

            $scope.$watch 'search',  ->
                
                $log.info $scope.search
                formPosition = $element.find( 'form' ).offset().left
                topPosition = $element.find( 'input' ).offset().top + $element.find( 'input' ).outerHeight( true )
                $element.find( '.search-results' ).css
                    left: formPosition + 15
                    top: topPosition
                    width: $element.find( 'form' ).width()


                SearchFactory.getProduct $scope.search
                    .success ( response )->
                        $scope.results = []
                        $scope.resultErrorState = false
                        $element.find( '.search-results' ).slideDown()

                        return
                    .error ( error )->
                        $element.find( '.search-results' ).slideDown()
                        $scope.resultErrorState = true
                        $scope.resultErrorMessage = error.error.message

                        $timeout ->
                            $scope.onBlur()
                        , 5000

                        return
                    .then ( data )->
                        angular.forEach data.data.success.data, ( value, key )->
                            $scope.results.push value
                            return
                return

            return

        ###*
         * Watch the scope of search 
         *
         * @return {void}
        ###
        $scope.$watch 'search',  ->

            leftPosition = $element.find( 'input' ).position().left
            topPosition = $element.find( 'input' ).position().top + $element.find( 'input' ).outerHeight( true )
            $element.find( '.search-results' ).css
                left: leftPosition
                top: topPosition
                width: $element.find( 'form' ).width()

            return
        # scope.$watch ->
        #     ngModel.$modelValue
        # , ( newValue )->
        #    $log.info 'scope', scope
        #    $log.info 'element', element
        #    $log.info 'attrs', attrs
        #    $log.info 'ngModel', ngModel
            # SearchFactory.getProduct newValue
            #     .success ( response )->
            #         $log.log response
            #     .error ( error )->
            #         $log.log error

