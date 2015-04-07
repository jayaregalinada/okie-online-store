@extends('templates.profile')

@section('profile.attr', 'ng-controller="MessageController"')

@section('settings.heading', '{# header #}')

@section('navigate.messages', 'in')

@section('settings.content')

<div ui-view>
    
    LOADING...
    
</div>

@stop


@section('body.post')

@if( Auth::user()->isAdmin() )
    <script type="text/javascript" src="{{ asset('js/admin.js') }}"></script>
@endif
    <script type="text/javascript" src="{{ asset('js/user.js') }}"></script>

@stop