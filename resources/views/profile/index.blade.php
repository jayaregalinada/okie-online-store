@extends('templates.profile')

@section('profile.attr', 'ng-controller="ProfileController"')

@section('settings.content')

<div ui-view>
    
    <div class="jumbotron">
        <h2><i class="fa fa-quote-left"></i> {{ Inspiring::quote() }}</h2>
    </div>
    
</div>

@stop


@section('body.post')

@if( Auth::user()->isAdmin() )
    <script type="text/javascript" src="{{ asset('js/admin.js') }}"></script>
@endif
    <script type="text/javascript" src="{{ asset('js/user.js') }}"></script>

@stop