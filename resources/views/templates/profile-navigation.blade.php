<div class="panel-group affected-with-affix" id="profile_nav" aria-multiselectable="true" role="navigation" data-spy="affix" data-offset-top="206">
    <!-- START MESSAGES -->
    <div class="panel panel-default">
        <div class="panel-heading" id="panel_heading_one">
            <a data-target="#messages" href="{{ route( 'messages.index' ) }}#inquiries" class="h4 display-block panel-title" data-toggle="collapse" data-parent="#profile_nav" aria-expanded="true">
                <i class="profile-nav-icon fa fa-inbox"></i> Messages
            </a>
        </div>
        <div id="messages" class="panel-collapse collapse @yield('navigate.messages')">
            <div class="panel-body">
                <div class="list-group">
                    <a ng-class="{ active: $state.is('messages.create') }" ui-sref="messages.create" href="#create" id="create_new_message" class="create-new list-group-item">
                        <i class="profile-nav-icon fa fa-pencil"></i> Create
                    </a>
                    <a ng-class="{ active: $state.is('messages.inbox') }" ui-sref="messages.inbox" href="#inbox" data-notify="inbox" class="list-group-item">
                        <span class="badge"></span>
                        <i class="profile-nav-icon fa fa-inbox"></i> Inbox
                    </a>
                    <a ng-class="{ active: $state.is('messages.inquiries') }" ui-sref="messages.inquiries" href="#inquiries" data-notify="inquiry" class="list-group-item">
                        <span class="badge"></span>
                        <i class="profile-nav-icon fa fa-star"></i> Inquiries
                    </a>
                    @if ( Auth::user()->isAdmin() )
                    <a ng-class="{ active: $state.is('messages.delivered') }" ui-sref="messages.delivered" href="#delivered" data-notify="delivered" class="list-group-item">
                        <span class="badge"></span>
                        <i class="profile-nav-icon fa fa-thumbs-up"></i> Delivered
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div> 
    <!-- END MESSAGES -->
    
    <!-- START SETTINGS -->
    <div class="panel panel-default">
        <div class="panel-heading" id="panel_heading_two">
            <a data-target="#settings" href="{{ route( 'settings.index' ) }}" class="h4 display-block panel-title" data-toggle="collapse" data-parent="#profile_nav" aria-expanded="true">
                <i class="profile-nav-icon fa fa-cogs"></i> Settings
            </a>
        </div>
        <div id="settings" class="panel-collapse collapse @yield('navigate.settings')">
            <div class="panel-body">
                <div class="list-group">
                    <a href="#email_notification" class="list-group-item">
                        <span class="badge"></span>
                        <i class="profile-nav-icon fa fa-envelope-o"></i> Email Notification
                    </a>
                    <a href="#password" class="list-group-item">
                        <span class="badge"></span>
                        <i class="profile-nav-icon fa fa-key"></i> Password
                    </a>
                    @if ( Auth::user()->isAdmin() )
                    <a href="#permission" class="list-group-item">
                        <span class="badge">3</span>
                        <i class="profile-nav-icon fa fa-users"></i> Permissions
                    </a>
                    
                    @endif
                </div>
            </div>
        </div>
    </div> 
    <!-- END SETTINGS -->
    
    @if ( Auth::user()->isAdmin() )
    <!-- START PRODUCTS -->
    <div class="panel panel-default">
        <div class="panel-heading" id="panel_heading_three">
            <a href="{{ route( 'products.index' ) }}#all" data-target="#products" class="h4 display-block panel-title" data-toggle="collapse" data-parent="#profile_nav" aria-expanded="true">
                <i class="profile-nav-icon fa fa-shopping-cart"></i> Products
            </a>
        </div>
        <div id="products" class="panel-collapse collapse @yield('navigate.products')">
            <div class="panel-body">
                <div class="list-group">
                    <a href="{{ route('product.add') }}" class="list-group-item create-new" id="create_new_item">
                        <i class="profile-nav-icon fa fa-pencil"></i> Create
                    </a>
                    <a ui-sref="products.all" href="#all" class="list-group-item" ng-class="{ active: $state.is('products.all') }">
                        <span class="badge"></span>
                        <i class="profile-nav-icon fa fa-database"></i> All Products
                    </a>
                    <a href="#category" ui-sref="products.category" class="list-group-item" ng-class="{ active: $state.is('products.category') }">
                        <span class="badge"></span>
                        <i class="profile-nav-icon fa fa-cubes"></i> Category
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- END PRODUCTS -->
    @endif
</div> 