<ul class="container-fluid row list-unstyled product-container">
    <li ng-repeat="product in products" class="col-md-3 col-xs-6 product-item animate">
        <div class="product-item-container" ng-click="goToItem({# product.id #})">
            <div class="img">
                <img ng-src="{# product.thumbnail[2].url #}" alt="" class="img-responsive" />
            </div>
            <div class="description">
                {# product.name #}
            </div>
        </div>
    </li>
</ul>



<div class="alert alert-warning" ng-if="!products.length">
    <i class="fa fa-exclamation-triangle"></i> 
    <a ng-hide="categories.length" href="#category" ui-sref="products.category" class="alert-link"> Please add category first.</a>
    <a ng-show="categories.length" href="{{ route('product.add') }}" class="alert-link">NO PRODUCTS YET. Please add new products.</a>
</div>