<div class="settings-container settings-permissions clearfix">
    <div ng-if="!settings.permission.error">
        
        <div class="media animate user show-when-hover" ng-repeat="user in users">
            <div class="media-left">
                <a href="javascript:void(0);">
                    <img class="media-object" ng-src="{# user.avatar #}" alt="{# user.full_name #} avatar" />
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading clearfix">
                    {# user.full_name #} <small class="show-when-hover">ID: {# user.id #}</small> <small class="pull-right content-description font-light text-right">LAST UPDATE: {# user.last_update #}
                </small></h4>
                <div class="content">
                    <div class="btn-group">
                        <a class="btn fa fa-envelope fa-fw" target="_BLANK" ng-href="mailto:{# user.email #}" ></a>
                        <a class="btn fa fa-facebook fa-fw" target="_BLANK" ng-href="{# user.link #}"></a>
                    </div>
                    <div class="btn-group permission-radio" ng-hide="user.permissionState">
                        <label ng-click="changePermission(user, 0)" ng-model="user.permission" class="btn btn-sm btn-info" btn-radio="0">ADMIN</label>
                        <label ng-click="changePermission(user, 1)" ng-model="user.permission" class="btn btn-sm btn-info" btn-radio="1">USER</label>
                        <label ng-click="changePermission(user, 2)" ng-model="user.permission" class="btn btn-sm btn-info" btn-radio="2">MODERATOR</label>
                    </div>
                    <button ng-show="user.permissionState" class="btn btn-sm btn-warning disabled"><i class="fa fa-cog fa-spin"></i> LOADING</button>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-danger text-center" ng-if="settings.permission.error">
        <h4>{# settings.permission.errorMessage #}</h4>
    </div>

</div>
