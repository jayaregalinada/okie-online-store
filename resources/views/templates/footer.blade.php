<footer id="footer" class="footer">

    <div class="container-fluid clearfix">
        <div class="col-md-4">
            <div data-widget="recent-items" class="_widget_recent-items _widget _footer_widget panel panel-default recent-items">
                <div class="panel-heading">
                    <h3 class="panel-title">RECENT ITEMS</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                    @foreach( \Okie\Product::latest()->take( 12 )->get() as $key => $product )

                        <li class="product-item">
                            <a href="/#/item/{{ $product->id }}">
                                <img src="{{ $product->thumbnail[1]['url'] }}" alt="{{ $product->name }}" />
                            </a>
                        </li>
                        
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

</footer>


