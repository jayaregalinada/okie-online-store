@extends('templates.profile')

@section('title', ' - Messages')

@section('settings.heading', '{# header #}')

@section('navigate.messages', 'in')

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
    
    LOADING...
    
</div>

@stop


@section('body.post')

@if( Auth::user()->isAdmin() )
    <script type="text/javascript" src="{{ asset('js/admin.js') }}"></script>
@endif
    <script type="text/javascript" src="{{ asset('js/user.js') }}"></script>



@stop

