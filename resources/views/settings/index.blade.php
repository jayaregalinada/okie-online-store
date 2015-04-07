@extends('templates.profile')

@section('profile.attr', 'ng-controller="UserSettingsController"')

@section('settings.heading', '{# header #}')

@section('navigate.settings', 'in')

@section('settings.content')

<div ui-view>

    <div class="well text-center">
        <h1 class="text-danger"><i class="fa fa-exclamation-triangle fa-4x"></i></h1>
        <h1 class="text-danger"> This page is currently unavailable</h1>
    </div>

</div>

@stop


@section('body.post')

@if( Auth::user()->isAdmin() )
    <script type="text/javascript" src="{{ asset('js/admin.js') }}"></script>
@endif
    <script type="text/javascript" src="{{ asset('js/user.js') }}"></script>

@stop