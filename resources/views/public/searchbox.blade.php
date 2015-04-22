<div class="search-form">
    <form name="form_global_search" class="animate navbar-form navbar-left" role="search">
        <div class="form-group">
            <input ng-focus="onFocus( $event )" ng-change="onChange( $event )" ng-blur="onBlur( $event )" name="search" ng-model-options="{ debounce: 1000 }" ng-model="search" type="text" class="animate form-control" placeholder="Search product" />
        </div>
    </form>
    <div class="search-results" style="display: none">
        <ul class="list-group clearfix" ng-if="results.length" ng-hide="resultErrorState">
            <li ng-class="{ selected: result.selected }" ng-click="goTo('{{ route('index') }}{# $state.href('item', { itemId: result.id }) #}')" class="result list-group-item" ng-repeat="result in results">
                <a href="{{ route('index') }}{# $state.href('item', { itemId: result.id }) #}">
                    <img ng-src="{# result.thumbnail[1].url #}" alt="image" class="product-thumbnail" />
                    {# result.name #}
                </a>
            </li>
        </ul>
        <div class="alert alert-danger text-center" ng-show="resultErrorState">
            {# resultErrorMessage #}
        </div>
    </div>
</div>

