@extends('app')

@section('title', ' - Categories')

@section('content')

<div class="container-fluid profile-container content-container" @yield('profile.attr')>
    <div class="profile-full-name" ng-bind-html="heading">
        @yield('settings.heading', Auth::user()->getFullName() . "<span>'s profile</span>")
    </div>
    <div class="profile-left col-md-3">
        <div class="profile-block">
            <a class="profile-photo" href="{{ route('me') }}">
                <img src="{{ Auth::user()->avatar }}" alt="profile" class="img-circle" />
            </a>
        </div>
        <hr class="clearfix" />
        <div class="profile-navigation">
            @include('templates.profile-navigation')
        </div>
    </div>
    <div class="profile-right profile-content col-md-9">
        @yield('settings.content')
    </div>
</div>
@endsection
