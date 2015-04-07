@extends('app')

@section('content')

<div ng-controller="ProductController" class="container product-container content-container product-create">
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('product.index') }}#/all">ALL PRODUCTS</a>
        </li>
        <li class="active">
            ADD PRODUCT
        </li>
    </ol>
    <header class="page-header">
        <h1>Add Product <small>~BASIC</small></h1>
    </header>
    <div class="product-left col-md-3">
        <h4>{# info #}</h4>
    </div>
    <div class="product-right col-md-9">
        
        <!-- START FORM HERE -->
        <form class="form-horizontal" ng-submit="product_create.$valid" name="product_create" action="{{ route('product.store') }}" method="POST">
           
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

            <div class="form-group">
                <label for="product_name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input name="name" ng-model="product.name" type="text" class="form-control" id="product_name" required ng-required="true" />
                </div>
            </div>
            <div class="form-group">
                <label for="product_code" class="col-sm-2 control-label">Code</label>
                <div class="col-sm-10">
                    <input name="code" ng-model="product.code" type="text" class="form-control" id="product_code" />
                </div>
            </div>
            <div class="form-group">
                <label for="product_description" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                    <input name="description" ng-model="product.description" type="text" class="form-control" id="product_description" required ng-required="true" />
                </div>
            </div>
            <div class="form-group">
                <label for="product_price" class="col-sm-2 control-label">Price</label>
                <div class="col-sm-10">
                    <input currency-symbol="Php" ng-currency ng-model="product.price" type="text" class="form-control" id="product_price" ng-required="true" required />
                    <input type="hidden" value="{# product.price #}" ng-model="product.price" name="price" />
                </div>
            </div>
            <div class="form-group">
                <label for="product_unit" class="col-sm-2 control-label">Unit</label>
                <div class="col-sm-10">
                    <input name="unit" ng-pattern="/^[0-9]+$/" ng-model="product.unit" type="number" class="form-control" id="product_unit" required />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <button type="submit" class="btn btn-primary btn-lg" ng-class="{ 'btn-success': product_create.$valid }">SUBMIT</button>
                </div>
            </div>
        </form>

        <!-- END FORM HERE -->

    </div>
</div>

@stop

@section('body.post')

<script type="text/javascript" src="{{ asset('js/admin.js') }}"></script>

@stop