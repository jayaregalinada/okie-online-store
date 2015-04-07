{!! Form::open(['ng-submit' => 'addCategory( $event )', 'class' => 'form clearfix', 'name' => 'form_category']) !!}
    
    <div class="form-group clearfix col-md-4">
        <label for="category" class="sr-only">New category</label>
        <input name="category" ng-model="category.category" ng-minlength="2" ng-required="true" required type="text" class="form-control" id="category" placeholder="Create new Category" />
    </div>
    <button ng-click="addCategory( $event )" ng-show="form_category.$valid" class="btn btn-success" type="button">ADD CATEGORY</button>

{!! Form::close() !!}
<div class="panel panel-primary">
    <div class="panel-heading">
        <span ng-hide="categories.length">NO CATEGORY FOUND</span>
        <span ng-show="categories.length">ALL CATEGORIES</span>
    </div>
    <ul class="list-group">
        <li class="list-group-item clearfix" ng-repeat="category in categories">
            <a ng-click="editCategory( {# category.id #} )" data-target="#modal_category_edit" data-toggle="modal" href="#" class="badge badge-edit btn">EDIT</a>
            <a ng-click="deleteCategory( {# category.id #}, $event )" href="#" class="badge badge-delete btn">DELETE</a>
            <span class="name">{# category.name #}</span>
        </li>
    </ul>
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
                <form name="form_category_update" ng-submit="form_category_update.$valid && updateCategory( $event )" class="form clearfix">
                    <div ng-hide="stateCategory">
                    <div class="form-group">
                        <label for="name" class="sr-only">Name</label>
                        <input ng-required="true" required placholder="Category name" type="text" class="form-control" id="name" name="name" ng-model="hotSeatCategory.name" />
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
                </form>

            </div>
        </div>
    </div>
</div>

