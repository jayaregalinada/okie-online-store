<ul class="container-fluid list-unstyled item-container" ng-if="!errorMessage">
    <li ng-if="featured.length > 2" ng-repeat="feature in featured" class="items-animation animate product-featured-item product-featured-{# feature.id #} col-md-4">
        <a href="#" ui-sref="item({ itemId: feature.id })" class="product-featured-item-container">
            <div class="img">
                <img class="img-responsive" ng-src="{# feature.thumbnail[2].url #}" alt="img" />
            </div>
            <div class="description">
                <em class="small content-description">Featured Item</em>
                <br />{# feature.name #}
            </div>
        </a>
    </li>
    <li ng-repeat="product in items" class="items-animation animate product-{# product.id #} col-md-4 col-lg-3 col-sm-6 col-xs-12 product-item"
        data-product-id="{# product.id #}"
        data-product-name="{# product.name #}"
        data-product-code="{# product.code #}">
        
        <a class="product-item-container" href="#" ui-sref="item({ itemId: product.id })">
            <div ng-style="{ 'background-color': product.badge.color }" class="ribbon {# product.badge.class #} ribbon-default" ng-if="product.badge.title">
                <span>{# product.badge.title #}</span>
            </div>
            <div class="img">
                <img src="{{ asset('/images/defaults/product_thn.jpg') }}" ng-src="{# product.thumbnail[2].url #}" alt="" class="img-responsive" />
            </div>
            <div class="description">
                <em class="small content-description">{# product.categories[0].name #}</em>
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

