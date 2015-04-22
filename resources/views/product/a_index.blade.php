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

<div class="alert alert-default text-center" ng-show="condition.products.loading">
    <p><i class="fa fa-3x fa-spinner fa-pulse"></i></p>
    <p>LOADING</p>
</div>
<div class="alert alert-danger text-center" ng-show="condition.products.error">
    <p><i class="fa fa-3x fa-exclamation-circle"></i></p>
    <p>{# condition.products.errorMessage.message | uppercase #}</p>
    <p>
        <a ng-hide="condition.products.errorMessage.data.categories.length" href="#category" ui-sref="products.category" class="btn btn-primary"><i class="fa fa-plus"></i> Please add category first.</a>
        <a ng-show="condition.products.errorMessage.data.categories.length" href="{{ route('product.add') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Please add new products.</a>
    </p>
</div>

