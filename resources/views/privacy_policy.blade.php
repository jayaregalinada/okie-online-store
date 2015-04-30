@extends('app')

@section('content')

<div class="content-container home-container home-privacy-policy privacy-policy">
	
	@include( 'templates.navigation-categories', [ 'categories' => \Okie\Category::all() ] )
	
	<div class="container content-description">
		<header class="no-margin-top page-header">
			<h1 class="no-margin">{{ $title }}</h1>
		</header>
		{!! $contents !!}
	</div>

</div>

@endsection
