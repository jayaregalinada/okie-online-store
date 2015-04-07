<ul class="container-fluid list-unstyled item-container">
    <li ng-repeat="product in items" class="animate product-{# product.id #} col-md-3 product-item"
        data-product-id="{# product.id #}"
        data-product-name="{# product.name #}"
        data-product-code="{# product.code #}">
        <a class="product-item-container" href="#" ui-sref="item({ itemId: product.id })">
            <div class="img">
                <img ng-src="{# product.thumbnail[2].url #}" alt="" class="img-responsive" />
            </div>
            <div class="description">
                <em class="small">{# product.categories[0].name #}</em>
                <br />{# product.name #}
            </div>
        </a>
    </li>
</ul>

