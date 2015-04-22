<div id="item-carousel-carousel" data-ride="carousel" class="carousel slide item-carousel-slide">
    <!-- Indicators -->
  <ol class="carousel-indicators">
    @foreach ($images as $key => $value)
      <li data-target="#item-carousel-carousel" data-slide-to="{{ $key }}" @if( $key == 0 ) class="active" @endif></li>
    @endforeach
  </ol>
    <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    @foreach ($images as $key => $value)
    <div class="item" @if( $key == 0 ) class="active" @endif>
      <img src="{{ $value->sizes[2]['url'] }}" alt="{{ $value->caption }}" />
    </div>
    @endforeach
  </div>
    <!-- Controls -->
  <a class="left carousel-control" href="#item-carousel-carousel" role="button">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#item-carousel-carousel" role="button">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
