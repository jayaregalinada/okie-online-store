@extends('app')

@section('content')

<div class="content-container home-container">
	
	@include( 'templates.navigation-categories', [ 'categories' => \Okie\Category::all() ] )

	@section( 'main-content' )
	<div ui-view></div>
	<div ui-view="banner" class="banner container"></div>
	<div ui-view="items">
		
		<div class="center-block text-center page-header">
			<h1>LOADING</h1>
		</div>

	</div>
	@show

</div>

@endsection
