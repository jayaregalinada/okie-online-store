@extends('app')

@section('content')

<div ng-controller="ProductController" class="container product-container content-container product-create">
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('products.index') }}#/all">ALL PRODUCTS</a>
        </li>
        <li class="active">
            ADD PRODUCT
        </li>
    </ol>
    <header class="page-header">
        <h1>Add Product <small>~BASIC</small></h1>
    </header>
    <div class="product-left col-md-3">
        <h3>{# info #}</h3>
        <div class="content-description">
            <div class="animate" ng-show="create.basic">
                <p>Hi {{ Auth::user()->first_name }}, here you will encode the basic information of the product.</p>
                <p>After this you will be redirect to a page where you can upload images for this product and update more</p>
            </div>
            <div class="animate" ng-show="create.name">
                <p>Enter the name of the product.</p>
                <p>It is best if you enter a specific name so customers will never confuse.</p>
            </div>
            <div class="animate" ng-show="create.code">
                <p>Enter the code of the product.</p>
                <p>This can be either no space or SKU of the product.</p>
            </div>
            <div class="animate" ng-show="create.description">
                <p>Enter the description of the product.</p>
                <p>Make more words but simplify it, so customers will understand.</p>
            </div>
            <div class="animate" ng-show="create.price">
                <p>Enter the price of the product.</p>
                <p>Just enter a number and it will automatically render a currency.</p>
            </div>
            <div class="animate" ng-show="create.unit">
                <p>Enter the unit or how many left/available.</p>
                <p>You know numbers right? 1, 2, 3, 4 and 5</p>
            </div>
        </div>

    </div>
    <div class="product-right col-md-9">
        
        <!-- START FORM HERE -->
        {!! Form::open( ['route' => 'product.store', 'class' => 'form-horizontal', 'ng-submit' => 'product_create.$valid', 'name' => 'product_create' ] ) !!}

            <div class="form-group">
                <label for="product_name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input ng-blur="autoChangeProductCode()" ng-focus="changeDescription('name')" name="name" ng-model="product.name" type="text" class="form-control" id="product_name" required ng-required="true" />
                </div>
            </div>
            <div class="form-group">
                <label for="product_code" class="col-sm-2 control-label">Code</label>
                <div class="col-sm-10">
                    <input ng-focus="changeDescription('code')" name="code" ng-model="product.code" type="text" class="form-control" id="product_code" />
                </div>
            </div>
            <div class="form-group">
                <label for="product_description" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                    <div ng-focus="changeDescription('description')" id="product_description" ta-toolbar="[['bold','italics', 'underline', 'ul', 'ol', 'undo', 'redo', 'clear', 'insertLink', 'charcount']]" name="description" class="content-description" ng-minlength="5" required="required" ng-required="true" placeholder="Write a description" text-angular ng-model="product.description"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="product_price" class="col-sm-2 control-label">Price</label>
                <div class="col-sm-10">
                    <input ng-focus="changeDescription('price')" currency-symbol="Php" ng-currency ng-model="product.price" type="text" class="form-control" id="product_price" ng-required="true" required />
                    <input type="hidden" value="{# product.price #}" ng-model="product.price" name="price" />
                </div>
            </div>
            <div class="form-group">
                <label for="product_unit" class="col-sm-2 control-label">Unit</label>
                <div class="col-sm-10">
                    <input ng-focus="changeDescription('unit')" name="unit" ng-pattern="/^[0-9]+$/" ng-model="product.unit" type="number" class="form-control" id="product_unit" required />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <button type="submit" class="btn btn-primary btn-lg" ng-class="{ 'btn-success': product_create.$valid }">SUBMIT</button>
                </div>
            </div>
        {!! Form::close() !!}

        <!-- END FORM HERE -->

    </div>
</div>

@stop

@section('body.post')

<script type="text/javascript" src="{{ asset('js/admin.js') }}"></script>

@stop
