@extends('app')

@section('content')

<div class="content-container home-container">
	
	<nav class="navbar-category">
		<div class="container-fluid">
			<div class="navbar-header text-center">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#category_nav">
					<span>CATEGORIES</span>
					<span class="nav-down fa fa-angle-down"></span>
					<span class="nav-up fa fa-angle-up"></span>
				</button>
			</div>

			<div class="collapse navbar-collapse" id="category_nav">
				<ul class="nav nav-pills nav-justified">
					<li ng-class="{ active: $state.is('index') }">
						<a href="{{ route('index') }}/#/">ALL</a>
					</li>
					@foreach ( \Okie\Category::all() as $key => $value )
						<li ng-class="{ active: ( $stateParams.categoryId == {{ $value->id }} || $stateParams.categoryId == '{{ $value->slug }}' ) }">
							<a href="/#/category/{{ $value->slug }}">{{ strtoupper( $value->name ) }}</a>
						</li>
					@endforeach
				</ul>
			</div>
		</div>
	</nav>


	<div ui-view></div>
	<div ui-view="items">
		
		<div class="center-block text-center page-header">
			<h1>LOADING</h1>
		</div>

	</div>

</div>

@endsection
