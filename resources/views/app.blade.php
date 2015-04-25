<!DOCTYPE html>
<html lang="en" @yield('html.attr')>
<head @yield('head.attr')>
	@yield('head.pre')
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>{{ config('app.title') }} @yield('title')</title>
	
	<link href="{{ asset('/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css" />

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	@yield('head.post')
</head>
<body @yield('body.attr')>
	@yield('body.pre')
	<nav class="navbar navbar-default navbar-fixed-top" id="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a ng-click="collapseToggle()" class="navbar-brand" href="{{ route('index') }}/#/">
					<img src="{{ asset('images/logo.png') }}" alt="logo" /> {{ config('app.logo.name') }}
				</a>
			</div>

			<div class="collapse navbar-collapse" id="navbar-collapse-1">
				<div class="okie-search">LOADING...</div>
				
				@include( 'templates.navigation', [ 'categories' => \Okie\Category::all() ] )

				<ul class="nav navbar-nav navbar-right text-center">
					@if ( Auth::guest() )
						<li><a class="btn facebook color" href="{{ url( '/login/facebook' ) }}"><i class="fa fa-facebook-official"></i> Login with Facebook</a></li>
					@else
						@if( Auth::user()->isAdmin() )
						<li>
							<a href="{{ route('product.add') }}" data-toggle="tooltip" data-placement="bottom" title="Add new product"><i class="fa fa-plus text-success"></i></a>
						</li>
						@endif
						<li class="with-notification">
							<a data-notify="message" class="with-tooltip" data-toggle="tooltip" data-placement="bottom" title="See messages only" href="{{ route('messages.index') }}#inquiries">
								<i class="fa fa-envelope"></i><span class="badge badge-danger"></span>
							</a>
						</li>
						@if( Auth::user()->isAdmin() )
						<li class="with-notification">
							<a id="notify_notifications" data-toggle="tooltip" data-placement="bottom" title="Check your notifications" href="/#!/notifications">
								<i class="fa fa-bell"></i><span class="badge"></span>
							</a>
						</li>
						@endif
						<li class="dropdown">
							<a href="{{ route('me') }}" class="user user-avatar dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								<img src="{{ Auth::user()->avatar}}" alt="avatar" />{{ Auth::user()->first_name }} 
								<i class="fa fa-caret-up icon-up"></i>
								<i class="fa fa-caret-down icon-down"></i>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li role="presentation" class="text-center dropdown-header">Account</li>
								<li><a href="{{ route('me') }}">Profile</a></li>
								<li><a href="{{ route('messages.index') }}{# $state.href('messages.inquiries') #}">Messages</a></li>
								<li><a href="{{ route('settings.index') }}{# $state.href('asettings.general') #}">Settings</a></li>
								@if ( Auth::user()->isAdmin() )
								<li><a href="{{ route('products.index') }}{# $state.href('products.all') #}">Products</a></li>
								@endif
								<li class="divider"></li>
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@include('templates.heading')
	
	@yield('content')

@include('templates.footer', [ 'products' => \Okie\Product::getFeatured( 20 ) ])

	<!-- Scripts -->
	<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
	<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> -->
	<script src="{{ asset('/vendor/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('/vendor/angular/angular.js') }}"></script>
	<script src="{{ asset('/js/vendor.js') }}"></script>
	<script src="{{ asset('/js/scripts.js') }}"></script>

	@yield('body.post')

	@if( Session::has( 'message' ) )
	<script type="text/javascript">
	angular.element( 'body' ).ready(function(){ Notification.success({ title: 'Hi!', message: '{{ Session::get( 'message' ) }}' }); });
	</script>
	@endif
</body>
</html>
