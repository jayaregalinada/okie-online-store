_okie.animation '.items-animation', ( $timeout )->

    queue =
        enter: []
        leave: []

    queueAnimation = ( event, delay, fn )->
        timeouts = []
        index = queue[ event ].length
        queue[ event ].push( fn )
        queue[ event ].timer && $timeout.cancel queue[ event ].timer 
        queue[ event ].timer = $timeout ->
            angular.forEach queue[ event ], ( fn, index )->
                timeouts[ index ] = $timeout fn, index * delay * 1000, false
                return
            queue[ event ] = []
            return
        , 10, false

        ->
            if timeouts[ index ]
                $timeout.cancel timeouts[ index ]
            else
                queue[ index ] = angular.noop

            return


    enter: ( element, done )->
        element = $( element[ 0 ] )
        cancel = queueAnimation 'enter', 0.1, ->
            element.css
                bottom: -20
                opacity: 0
            element.animate
                bottom: 0
                opacity: 1
            , done
            element.addClass 'enter'
            cancelFn = cancel
            cancel = ->
                cancelFn()
                element.stop()
                element.css
                    bottom: 0
                    opacity: 1

                return
            return

        onClose = ( cancelled )->
            cancelled && cancel()

            return

    leave: ( element, done )->
        element = $( element[0] )
        cancel = queueAnimation 'leave', 0.1, ->
            element.css
                bottom: 0
                opacity: 1
            element.animate
                bottom: -20
                opacity: 0
            , done
            element.addClass 'leave'
            cancelFn = cancel
            cancel = ->
                cancelFn()
                element.stop()
                return
        onClose = ( cancelled )->
            cancelled && cancel()
            return






