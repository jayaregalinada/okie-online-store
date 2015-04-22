_okie.directive 'BadgeClasshelper', ( $http, $log )->

    restrict: 'AE'
    scope:
        selectedTags: '=model'
    templateUrl: 'badge-class-helper.html'
    link: ( scope, elem, attrs )->

        scope.suggestions = []
        scope.selectedTags = []
        scope.selectedIndex = -1

        scope.removeTag = ( index )->
            scope.selectedTags.splice index, 1

            return

        scope.search = ->
            $log.log 'SearchingText: ', scope.searchText
            $log.log 'SearchingSuggestions: ', scope.suggestions
            $log.log 'SearchingIndex: ', scope.selectedIndex
            data = attrs.scope
            if data.indexOf( scope.searchText ) == -1
                data.unshift scope.searchText
                
                scope.suggestions = data;
                scope.selectedIndex = -1;

            return

        scope.addToSelectedTags = ( index ) ->
            if scope.selectedTags.indexOf( scope.suggestions[index] ) == -1
                scope.selectedTags.push scope.suggestions[index]
                scope.searchText = ''
                scope.suggestions = []

            return

        scope.checkKeyDown = ( event ) ->
            if event.keyCode == 40 #down key, increment selectedIndex
                event.preventDefault()
                if scope.selectedIndex + 1 != scope.suggestions.length
                    scope.selectedIndex++
            else if event.keyCode == 38 #up key, decrement selectedIndex
                event.preventDefault()
                if scope.selectedIndex - 1 != -1
                    scope.selectedIndex--
            else if event.keyCode == 13 #enter pressed
                scope.addToSelectedTags scope.selectedIndex

            return

        scope.$watch 'selectedIndex', ( val ) ->
            if val != -1
                scope.searchText = scope.suggestions[ scope.selectedIndex ]

            return

        scope.$watch 'searchText', ( val )->
            $log.info 'SearchText', val

            return

        return


