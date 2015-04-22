@extends('app')

@section('title', ' - ' . $product->name)

@section('body.attr', 'ng-controller="ProductController"')

@section('body.pre')
<div class="dropzone-indicator">
    <div class="top"></div>
    <div class="right"></div>
    <div class="bottom"></div>
    <div class="left"></div>
</div>

@stop

@section('content')
<div class="container product-container content-container product-add-image">
    
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('products.index') }}#/all">ALL PRODUCTS</a>
        </li>
        <li class="active">
            {# product.name | uppercase #}
        </li>
    </ol>

    <header class="page-header">
        <h1> {# product.name #} <small class="font-light">{# product.code #}</small></h1>
    </header>
    <div class="product-left" ng-class="{ 'col-md-6': editState, 'col-md-3': !editState }">
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
                    <span ng-hide="editState"><em class="small">SALE: </em>{# product.sale_price | currency:"Php" #}</span>
                    <input ng-show="editState" currency-symbol="Php" ng-currency ng-model="product.sale_price" type="text" class="form-control" id="product_sale_price" />
                    <input type="hidden" value="{# product.sale_price #}" ng-model="product.sale_price" name="price" />
                </li>
                <li class="list-group-item">
                    <span ng-hide="editState"><em class="small">REMAINING: </em>{# product.unit #}</span>
                    <input ng-show="editState" name="unit" ng-pattern="/^[0-9]+$/" ng-model="product.unit" type="number" class="form-control" id="product_unit" required />
                </li>
                <li class="list-group-item content-description product-description">
                    <span ng-hide="editState" ng-bind-html="product.description"></span>
                    <div ng-show="editState" ta-toolbar="[['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'quote', 'bold', 'italics', 'underline', 'strikeThrough', 'ul', 'ol', 'redo', 'undo', 'clear', 'justifyLeft', 'justifyCenter', 'justifyRight', 'indent', 'outdent','html', 'insertImage', 'insertLink', 'insertVideo']]" placeholder="{# (product.name) ? product.name + '\'s' : 'Product' #} description" id="description" style="resize:none;border:none;" name="product_description" class="buttons-sm with-background content-description" ng-minlength="5" required="required" ng-required="true" text-angular ng-model="product.description"></div>
                    <!-- <textarea ng-show="editState" placeholder="{# (product.name) ? product.name + '\'s' : 'Product' #} description" style="resize:none;border:none;" ng-model="product.description" name="description" id="description" cols="30" rows="6" class="form-control"></textarea> -->
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

        <div class="panel panel-info product-badge">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Product Badge
                    @if ( Auth::user()->isAdmin() )
                    <button data-toggle="tooltip" data-placement="right" title="Edit Product Badge" ng-hide="editStateBadge" ng-click="editProductBadge()" class="text-right label label-sm btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></button>
                    <button data-toggle="tooltip" data-placement="right" title="Update Product Badge" ng-show="editStateBadge" ng-click="form_product_badge.$valid && updateProductBadge( $event, form_product_badge )" class="text-right label label-sm btn btn-warning btn-sm"><i class="glyphicon glyphicon-edit"></i></button>
                    @endif
                </h3>
            </div>
            <div class="panel-body" ng-hide="editStateBadge">
                <div ng-if="!product.badge.title" class="label label-danger font-light">Do not have badge yet</div>
                <div ng-if="product.badge.title" class="ribbon ribbon-relative ribbon-default {# product.badge.class #}">
                    <span>{# product.badge.title #}</span>
                </div>
            </div>
            <ul class="list-group" ng-show="editStateBadge">
                <li class="preview list-group-item">
                    <div ng-if="product.badge" class="ribbon ribbon-relative ribbon-default {# product.badge.class #}">
                        <span>{# product.badge.title || 'INSERT BADGE' #}</span>
                    </div>
                </li>
                {!! Form::open( [ 'route' => [ 'product.update.badge', $product->id ], 'method' => 'PUT', 'ng-submit' => 'form_product_badge.$valid && updateProductBadge( $event, form_product_badge )', 'name' => 'form_product_badge' ] ) !!}
                <li class="list-group-item">
                    <div class="has-feedback" ng-class="{ 'has-success': form_product_badge.title.$valid, 'has-error': form_product_badge.title.$invalid && form_product_badge.title.$touched }" >
                        <label for="badge_title" class="control-label sr-only">Title</label>
                        <input ng-required="true" required="required" type="text" ng-model="product.badge.title" name="title" placeholder="Badge Title" class="form-control" id="badge_title" />
                        <span ng-class="{ 'glyphicon-ok': form_product_badge.title.$valid, 'glyphicon-remove': form_product_badge.title.$invalid && form_product_badge.title.$touched }" class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </li>
                <li class="list-group-item">
                    <textarea style="resize:none;" rows="5" ng-model="product.badge.description" placeholder="Badge description" class="form-control" id="badge_description"></textarea>
                </li>
                <li class="list-group-item">
                    <div class="has-feedback" ng-class="{ 'has-success': form_product_badge.class.$valid, 'has-error': form_product_badge.class.$invalid && form_product_badge.class.$touched }" >
                        <label for="badge_class" class="control-label sr-only">Class</label>
                        <tags-input min-length="1" placeholder="Add a custom class" class="bootstrap product-classes" ng-model="product.badge.class_array">
                            <auto-complete min-length="1" source="loadClass( $query )"></auto-complete>
                        </tags-input>
                        {{-- <input badge-class-helper scope="classFactory.badgeClass" type="text" model="product.badge.class" ng-model="product.badge.class" name="class" placeholder="Badge class helper" class="form-control" id="badge_class" /> --}}
                        <span ng-class="{ 'glyphicon-ok': form_product_badge.class.$valid, 'glyphicon-remove': form_product_badge.class.$invalid && form_product_badge.class.$touched }" class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </li>
                {!! Form::close() !!}
                <li class="list-group-item">
                    {!! Form::open( [ 'route' => [ 'product.update.badge.remove', $product->id ], 'method' => 'PUT', 'ng-submit' => 'form_product_badge_remove.$valid && removeProductBadge( $event, form_product_badge_remove )', 'name' => 'form_product_badge_remove' ] ) !!}
                        <button ng-click="form_product_badge_remove.$valid && removeProductBadge( $event, form_product_badge_remove )" type="button" class="form-control btn btn-warning">REMOVE BADGE</button>
                    {!! Form::close() !!}
                </li>
            </ul>
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

    <div class="product-preview col-md-3" ng-show="editState">
        <div class="item-container">
            <div class="preview-product-item">
                <a target="_blank" href="{{ route('index') }}/{# $state.href('item', { itemId: product.id }) #}" class="preview-product-item-container">
                    <div class="img">
                        <img ng-src="{# product.thumbnail[2].url #}" class="img-responsive" alt="image" />
                    </div>
                    <div class="description">
                        <em class="small">{# product.categories[0].name #}</em>
                        <br />{# product.name #}
                    </div>
                </a>
            </div>
        </div>
    </div>


    <div class="product-right col-md-9" ng-init="id = {{ $product->id }}; getImages(); getInformation(); initializeDropzone('{{ route( 'product.add_image.post', $product->id ) }}','{{ csrf_token() }}')">
        <div id="product_add_image_form" class="dropzone text-center">
            <div id="productPreview"></div>
            <header class="drag">DRAG AND DROP FILES ANYWHERE</header>
            <header class="dropping">OK NOW DROP THE FILES</header>
        </div>
        
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

@if ( Auth::user()->isPermitted() )
<script type="text/javascript" src="{{ asset('js/admin.js') }}"></script>
@endif

@stop

