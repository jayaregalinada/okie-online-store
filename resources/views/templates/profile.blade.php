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

@section('head.post')
<script type="text/javascript">
window._url = {
   'inquiry': {
        'all': '{{ route( 'messages.inquiries' ) }}',
        'find': '{{ route( 'inquiry', '_INQUIRY_ID_' ) }}',
        'conversations': '{{ route( 'inquiry.conversations', '_INQUIRY_ID_' ) }}',
        'reply': '{{ route( 'inquiry.reply' ) }}',
        'delivered': '{{ route( 'inquiry.delivered' ) }}'
   },
   'inbox': {
        'all': '{{ route( 'messages.inbox' ) }}',
        'conversations': '{{ route( 'inbox.conversations', '_INQUIRY_ID_' ) }}',
        'find': '{{ route( 'inbox', '_INQUIRY_ID_' ) }}',
        'reply': '{{ route( 'inbox.reply' ) }}'
   }
@if ( Auth::user()->isPermitted() )
  ,'deliver': {
    'all': '{{ route( 'messages.delivered' ) }}',
    'find': '{{ route( 'delivered', '_DELIVER_ID' ) }}',
    'conversations': '{{ route( 'delivered.conversations', '_DELIVER_ID' ) }}',
    'reply': '{{ route( 'delivered.reply' ) }}'
  }
@endif
}
</script>

@stop
