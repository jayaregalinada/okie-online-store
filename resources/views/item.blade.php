@extends('home')


@section('main-content')

<div id="item_container" class="container">
    <hr class="animated fadeIn" />
    <div class="item-wrapper clearfix">
        <div id="item_content_left" class="col-md-5 item-left affected-with-affix">
            {{-- <div class="item-carousel animated fadeInLeft"> --}}
                @include( 'templates.item-carousel', [ 'images' => $product->images ] )
            {{-- </div> --}}
        </div>
        
    </div>

    
</div>

@overwrite

