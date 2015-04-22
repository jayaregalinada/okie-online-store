<nav class="navbar-category">
    <div class="container-fluid">
        <div class="navbar-header text-center">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#category_nav">
                <span>CATEGORIES</span>
                <span class="nav-down fa fa-angle-down"></span>
                <span class="nav-up fa fa-angle-up"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="category_nav">
            <ul class="nav nav-pills nav-justified">
                <li class="parent" ng-class="{ active: $state.is('index') }">
                    <a href="{{ route('index') }}/#/">ALL</a>
                </li>
                @foreach ( $categories as $key => $value )
                    @if( $value->navigation )
                        @if ( is_bool( $value->parent ) )
                            @if ( ! empty( $value->children ) )
                                {{-- */ $length = count( $value->children) /* --}}
                                <li class="dropdown parent">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">{{ strtoupper( $value->name ) }} 
                                        <span class="label label-default" ng-show="
                                        @foreach ($value->children as $childKey => $childValue)
                                            @if ( $childKey == $length - 1 )
                                                ( $stateParams.categoryId == {{ $childValue->id }} || $stateParams.categoryId == '{{ $childValue->slug }}' )
                                            @else
                                                ( $stateParams.categoryId == {{ $childValue->id }} || $stateParams.categoryId == '{{ $childValue->slug }}' ) ||
                                            @endif
                                        @endforeach
                                        ">{# categoryInfo.name | uppercase #}</span>
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        @foreach ( $value->children as $childKey => $childValue )
                                            <li ng-class="{ active: ( $stateParams.categoryId == {{ $childValue->id }} || $stateParams.categoryId == '{{ $childValue->slug }}' ) }">
                                                <a href="/#/category/{{ $childValue->slug }}">{{ strtoupper( $childValue->name ) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                   
                                </li>
                            @else
                                <li class="parent" ng-class="{ active: ( $stateParams.categoryId == {{ $value->id }} || $stateParams.categoryId == '{{ $value->slug }}' ) }">
                                    <a href="/#/category/{{ $value->slug }}">{{ strtoupper( $value->name ) }}</a>
                                </li>
                            @endif
                        @endif
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>
