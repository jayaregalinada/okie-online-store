@extends('app')

@section('title', ' - ' . $product->name)

@section('body.attr', 'ng-controller="ProductController"')
@section('content')

<div class="container product-container content-container product-add-image">
    
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('product.index') }}#/all">ALL PRODUCTS</a>
        </li>
        <li class="active">
            {# product.name | uppercase #}
        </li>
    </ol>

    <header class="page-header">
        <h1> {# product.name #} <small class="font-light">{# product.code #}</small></h1>
    </header>
    <div class="product-left col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {# info #} 
                    @if ( Auth::user()->isAdmin() )
                    <button data-toggle="tooltip" data-placement="right" title="Edit Product Information" ng-hide="editState" ng-click="editInfo()" class="text-right label label-sm btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></button>
                    <button data-toggle="tooltip" data-placement="right" title="Update Product Information" ng-show="editState" ng-click="updateInfo()" class="text-right label label-sm btn btn-warning btn-sm"><i class="glyphicon glyphicon-edit"></i></button>
                    @endif
                </h3>
            </div>

            <ul class="list-group" ng-class="{ 'edit-mode': editState }">
                <li class="list-group-item">
                    <span ng-hide="editState"><em class="small">NAME: </em>{# product.name #}</span>
                    <input ng-show="editState" name="name" ng-model="product.name" type="text" class="form-control" id="product_name" required ng-required="true" />
                </li>
                <li class="list-group-item">
                    <span ng-hide="editState"><em class="small">CODE: </em>{# product.code #}</span>
                    <input ng-show="editState" name="code" ng-model="product.code" type="text" class="form-control" id="product_code" />
                </li>
                <li class="list-group-item">
                    <span ng-hide="editState"><em class="small">PRICE: </em>{# product.price | currency:"Php" #}</span>
                    <input ng-show="editState" currency-symbol="Php" ng-currency ng-model="product.price" type="text" class="form-control" id="product_price" ng-required="true" required />
                    <input type="hidden" value="{# product.price #}" ng-model="product.price" name="price" />
                </li>
                <li class="list-group-item">
                    <span ng-hide="editState"><em class="small">REMAINING: </em>{# product.unit #}</span>
                    <input ng-show="editState" name="unit" ng-pattern="/^[0-9]+$/" ng-model="product.unit" type="number" class="form-control" id="product_unit" required />
                </li>
                <li class="list-group-item product-description">
                    <span ng-hide="editState">{# product.description #}</span>
                    <textarea ng-show="editState" placeholder="{# (product.name) ? product.name + '\'s' : 'Product' #} description" style="resize:none;border:none;" ng-model="product.description" name="description" id="description" cols="30" rows="6" class="form-control"></textarea>
                </li>
            </ul>
        </div>

        <div class="panel panel-info product-categories">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Product Categories
                    @if ( Auth::user()->isAdmin() )
                    <button data-toggle="tooltip" data-placement="right" title="Edit Product Category" ng-hide="editStateCategory" ng-click="editProductCategory()" class="text-right label label-sm btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></button>
                    <button data-toggle="tooltip" data-placement="right" title="Update Product Category" ng-show="editStateCategory" ng-click="updateProductCategory()" class="text-right label label-sm btn btn-warning btn-sm"><i class="glyphicon glyphicon-edit"></i></button>
                    @endif
                </h3>
            </div>
            <div class="panel-body">
                <div class="clearfix" ng-hide="editStateCategory">
                    <span ng-repeat="category in product.categories" class="category label label-primary">{# category.name #}</span>
                    <span ng-if="product.categories < 1" class="category label label-warning"><i class="fa fa-exclamation-circle"></i> No category found</span>
                </div>
                <form name="form_product_category" ng-show="editStateCategory">
                    <select name="categories" class="form-control" multiple ng-model="product.categories">
                        @foreach ( $category as $key => $value )
                            @if( in_array( $value, $product->categories->lists('id', 'name') ) )
                                <option selected="selected" checked value="{{ $value }}">{{ $key }}</option>
                            @else
                                <option value="{{ $value }}">{{ $key }}</option>
                            @endif
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Danger Zone
                </h3>
            </div>
            <div class="panel-body">
                <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#attempting_to_delete_product"><i class="fa fa-exclamation-triangle"></i> DELETE PRODUCT</button>
            </div>
        </div>

    </div>


    <div class="product-right col-md-9" ng-init="id = {{ $product->id }}; getImages(); getInformation()">
        @if ( Auth::user()->isAdmin() )
            @if ( empty( $product->images->toArray() ) )
            {!! Form::open( [ 'route' => [ 'product.add_image.post', $product->id ], 'dropzone' => 'dropzoneConfig', 'files' => true, 'class' => 'dropzone no-item text-center', 'id' => 'product_add_image_form' ] ) !!}
            {!! Form::close() !!}
            @else
            {!! Form::open( [ 'route' => [ 'product.add_image.post', $product->id ], 'dropzone' => 'dropzoneConfig', 'files' => true, 'class' => 'dropzone with-items text-center', 'id' => 'product_add_image_form' ] ) !!}
            {!! Form::close() !!}
            @endif
        <hr />
        @endif
        <ul class="product-images list-unstyled">
            <li class="animate image-{# $index #} product-image" ng-repeat="image in images">
                <a href="#" ng-click="openLightboxModal( $index )">
                    <img ng-src="{# image.sizes[2].url #}" alt="" class="img-responsive" />
                </a>
            </li>
        </ul>


    </div>
</div>

@stop

@section('body.post')


<div class="modal fade" id="attempting_to_delete_product" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete Product</h4>
            </div>
            <div class="modal-body">
                <h2>ARE YOU SURE YOU WANT TO DELETE</h2>
                <h3><strong>{{ $product->name }}</strong></h3>
                <br />
                <div class="alert alert-info">
                    <em class="small"><i class="fa fa-exclamation-circle"></i> Deleting this product will be temporary hidden in the product lists.</em>
                </div>

            </div>
            {!! Form::open(['route' => [ 'product.destroy', $product['id'] ], 'name' => 'form_product_delete', 'id' => 'form_product_delete', 'method' => 'DELETE' ]) !!}
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">DELETE</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('js/admin.js') }}"></script>

@stop