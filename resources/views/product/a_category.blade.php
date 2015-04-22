<div class="category-container clearfix categories">

{!! Form::open(['ng-submit' => 'addCategory( $event )', 'class' => 'row form clearfix', 'name' => 'form_category']) !!}
    
    <div class="form-group clearfix col-md-4">
        <label for="category" class="sr-only">New category</label>
        <input name="category" ng-model="category.category" ng-minlength="2" ng-required="true" required type="text" class="form-control" id="category" placeholder="Create new Category" />
    </div>
    <button ng-click="addCategory( $event )" ng-show="form_category.$valid" class="btn btn-success" type="button">ADD CATEGORY</button>

{!! Form::close() !!}
<div class="panel panel-primary categories" ng-show="categories.length">
    <div class="panel-heading">
        ALL CATEGORIES
    </div>
    <ul class="list-group">
        <li class="animate list-group-item clearfix category-item" ng-repeat="category in categories">
            <a ng-click="editCategory( category.id )" data-target="#modal_category_edit" data-toggle="modal" href="javascript:void(0);" class="badge badge-edit btn">EDIT</a>
            <a ng-click="deleteCategory( category.id, $event )" href="javascript:void(0);" class="badge badge-delete btn">DELETE</a>
            <span class="name">{# category.name #}</span>
        </li>
    </ul>
</div>

<div class="alert alert-default text-center" ng-show="condition.categories.loading">
    <p><i class="fa fa-3x fa-spinner fa-pulse"></i></p>
    <p>LOADING</p>
</div>
<div class="alert alert-danger text-center" ng-show="condition.categories.error">
    <p><i class="fa fa-3x fa-exclamation-circle"></i></p>
    <p>{# condition.categories.errorMessage.message | uppercase #}</p>
</div>

<div class="modal fade" id="modal_category_edit" tabindex="-1" aria-hidden="true" aria-labelledby="MyModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" ng-hide="stateCategory">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="MyModalLabel"><small>EDIT</small> {# hotSeatCategory.name #}</h3>
            </div>
            <div class="modal-body">
                <span class="loading" ng-show="stateCategory">LOADING...</span>
                {!! Form::open( [ 'route' => 'products.categories.post', 'name' => 'form_category_update', 'ng-submit' => 'form_category_update.$valid && updateCategory( $event )', 'class' => 'form clearfix' ] ) !!}
                    <div ng-hide="stateCategory">
                        <div class="form-group include-navigation">
                            <label>Include to Navigation?</label>
                            <div class="btn-group">
                                <label class="btn btn-danger btn-sm" ng-model="hotSeatCategory.navigation" btn-radio="true" uncheckable>YES</label>
                                <label class="btn btn-danger btn-sm" ng-model="hotSeatCategory.navigation" btn-radio="false" uncheckable>NO</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only">Parent</label>
                            <ui-select ng-model="hotSeatCategory.parent_info" theme="select2" ng-disabled="disabled">
                                <ui-select-match placeholder="Select a category parent in the list or search name/slug">{# $select.selected.name #}</ui-select-match>
                                <ui-select-choices repeat="cat in categories | filter: { name: $select.search, slug: $select.search }">
                                    <div class="name">
                                        <span ng-bind-html="cat.name | highlight: $select.search"></span>
                                        <small class="text-info label font-light small" ng-if="cat.parent">PARENT</small>
                                    </div>
                                    <small class="content-description">
                                        slug: <span ng-bind-html="''+ cat.slug | highlight: $select.search"></span>
                                    </small>
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div class="form-group">
                            <label for="name" class="sr-only">Name</label>
                            <input ng-required="true" required placholder="Category name" type="text" class="form-control" id="name" name="name" ng-model="hotSeatCategory.name" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="slug">Slug</label>
                            <input placeholder="Category Slug" type="text" class="form-control" ng-model="hotSeatCategory.slug" id="slug" name="slug" ng-required="true" required />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="description">Description</label>
                            <textarea placeholder="{# (hotSeatCategory.name) ? hotSeatCategory.name + '\'s' : 'Category' #} description" style="resize:none;" ng-model="hotSeatCategory.description" name="description" id="description" cols="30" rows="6" class="form-control"></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer" ng-hide="stateCategory">
                <button ng-click="form_category_update.$valid && updateCategory( $event )" type="submit" class="btn" ng-class="{ 'btn-success': form_category_update.$valid, 'btn-danger': form_category_update.$invalid }">OK</button>
                <button type="button" class="btn btn-warning" data-dismiss="modal">CANCEL</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


</div>
