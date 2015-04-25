<ul id="navbar_navigation" class="nav navbar-nav visible-md visible-lg affected-with-okie-search">
	@foreach ( $categories as $key => $value )
        @if( $value->navigation )
            @if ( is_bool( $value->parent ) )
                @if ( ! empty( $value->children ) )
                {{-- */ $length = count( $value->children) /* --}}
                    <li class="dropdown parent"
                    ng-class="{
                    active: (
                    @foreach ( $value->children as $childKey => $childValue )
                    $stateParams.categoryId == '{{ $childValue->slug }}' ||
                    @endforeach
                    $stateParams.categoryId == '{{ $value->slug }}')
                    }">
                        <a class="dropdown-toggle collapsed" data-toggle="collapse" href="#collapse-{{ str_slug( $value->name ) }}" role="button" aria-expanded="false">{{ strtoupper( $value->name ) }} 
                            <span class="caret"></span>
                        </a>
                        <div id="collapse-{{ str_slug( $value->name ) }}" class="collapse collapse-navigation">
                            <ul class="nav nav-pills nav-justified" role="menu">
                                @foreach ( $value->children as $childKey => $childValue )
                                    @if( $childValue->navigation )
                                    <li ng-class="{ active: ( $stateParams.categoryId == {{ $childValue->id }} || $stateParams.categoryId == '{{ $childValue->slug }}' ) }">
                                        <a href="{{ route('index') }}{# $state.href( 'category', { categoryId: '{{ $childValue->slug }}' } ) #}">{{ strtoupper( $childValue->name ) }}</a>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @else
                    <li ng-click="collapseToggle()" class="parent" ng-class="{ active: ( $stateParams.categoryId == {{ $value->id }} || $stateParams.categoryId == '{{ $value->slug }}' ) }">
                        <a href="/#/category/{{ $value->slug }}">{{ strtoupper( $value->name ) }}</a>
                    </li>
                @endif
            @endif
        @endif
    @endforeach
</ul>
