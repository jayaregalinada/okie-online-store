@extends('templates.profile')

@section('settings.heading', 'Categories')

@section('profile.attr', 'ng-controller="SettingsController"')

@section('settings.content')

{!! Form::open(['class' => 'form clearfix', 'name' => 'form_category']) !!}

<div class="form-group clearfix col-md-4">
    <label for="category" class="sr-only">New category</label>
    <input name="category" ng-model="category" ng-min="2" ng-required="true" required type="text" class="form-control" id="category" placeholder="Create new Category" />
</div>
<button class="btn btn-primary">NEW CATEGORY</button>

{!! Form::close() !!}
{# info #}

<div class="panel panel-primary">
    <div class="panel-heading">All Categories</div>
    <ul class="list-group">
        <li class="list-group-item"></li>
    </ul>
</div>


@stop