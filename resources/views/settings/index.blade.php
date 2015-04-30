@extends('templates.profile')

@section('title', ' - Settings')

@section('settings.heading', 'Settings')

@section('navigate.settings', 'in')

@section('body.pre')
<div class="dropzone-indicator" id="DZINDICATOR">
    <div class="top"></div>
    <div class="right"></div>
    <div class="bottom"></div>
    <div class="left"></div>
</div>
@stop

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
