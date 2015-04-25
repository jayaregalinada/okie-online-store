@extends('templates.profile')

@section('title', ' - Products')

@section('settings.heading', '{# header #}')

@section('navigate.products', 'in')

@section('settings.content')

<div ui-view>
    
    LOADING...
    
</div>

@stop


@section('body.post')

@if( Auth::user()->isAdmin() )
    <script type="text/javascript" src="{{ asset('js/admin.js') }}"></script>
@endif

@if( Session::has( 'success' ) )
<script type="text/javascript">
angular.element( 'body' ).ready(function(){ Notification.success({ title: 'Nice!', message: '{{ Session::get( 'success.message' ) }}' }); });
</script>
@endif

@stop
