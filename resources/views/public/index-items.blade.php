<ul class="container-fluid list-unstyled item-container" ng-if="!errorMessage">
    <li ng-repeat="product in items" class="items-animation animate product-{# product.id #} col-md-4 col-lg-3 col-sm-6 col-xs-12 product-item"
        data-product-id="{# product.id #}"
        data-product-name="{# product.name #}"
        data-product-code="{# product.code #}">
        
        <a class="product-item-container" href="#" ui-sref="item({ itemId: product.id })">
            <div class="ribbon {# product.badge.class #} ribbon-default" ng-if="product.badge.title">
                <span>{# product.badge.title #}</span>
            </div>
            <div class="img">
                <img src="{{ asset('/images/defaults/product_thn.jpg') }}" ng-src="{# product.thumbnail[2].url #}" alt="" class="img-responsive" />
            </div>
            <div class="description">
                <em class="small">{# product.categories[0].name #}</em>
                <br />{# product.name #}
            </div>
        </a>
    </li>
</ul>

<div id="item_container_error" ng-if="errorMessage">
    <div class="jumbotron text-center container">
        <h1>{# errorMessage #}</h1>
    </div>
</div>

