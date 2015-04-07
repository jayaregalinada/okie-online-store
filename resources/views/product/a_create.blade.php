<div class="container product-container content-container product-create">
    <header class="page-header">
        <h1>Add Product</h1>
    </header>
    <div class="product-left col-md-3">
        <h4>{# info #}</h4>
    </div>
    <div class="product-right col-md-9">
        
        <!-- START FORM HERE -->
        <form class="form-horizontal">
            <div class="form-group">
                <label for="product_name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input ng-model="product.name" type="text" class="form-control" id="product_name" required />
                </div>
            </div>
            <div class="form-group">
                <label for="product_code" class="col-sm-2 control-label">Code</label>
                <div class="col-sm-10">
                    <input ng-model="product.code" type="text" class="form-control" id="product_code" />
                </div>
            </div>
            <div class="form-group">
                <label for="product_description" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                    <input ng-model="product.description" type="text" class="form-control" id="product_description" required />
                </div>
            </div>
            <div class="form-group">
                <label for="product_price" class="col-sm-2 control-label">Price</label>
                <div class="col-sm-10">
                    <input currency-symbol="Php" ng-currency ng-model="product.price" type="text" class="form-control" id="product_price" required />
                </div>
            </div>
        </form>

        <!-- END FORM HERE -->

    </div>
</div>
