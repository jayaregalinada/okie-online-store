<footer id="footer" class="footer">

    <div class="container-fluid clearfix">
        <div class="col-md-5">
            <div class="panel panel-default company-footer _widget_company-footer _widget _footer_widget" data-widget="company-footer">
                <div class="panel-heading">
                    <h2 class="panel-title">{{ strtoupper( config('app.title') ) }}</h2>
                </div>
                <div class="panel-body clearfix row">
                    <div class="col-md-4">
                        <img src="{{ config('app.logo.img') }}" alt="logo" class="img-responsive" />
                    </div>
                    <div class="col-md-8 external-links">
                        <ul class="list-unstyled content-description">
                            <li>{{ config('app.description') }}</li>
                            <li>{{ config('app.url') }}/</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div data-widget="recent-items" class="_widget_recent-items _widget _footer_widget panel panel-default recent-items">
                <div class="panel-heading">
                    <h3 class="panel-title">RECENT ITEMS</h3>
                </div>
                <div class="panel-body">
                    @if ( $products )
                    <ul class="list-unstyled">
                            @foreach ( $products->get() as $key => $product )
                                @if( $product->images()->exists() )
                                <li class="product-item">
                                    <a href="/#/item/{{ $product->id }}">
                                        <img src="{{ $product->thumbnail[1]['url'] }}" alt="{{ $product->name }}" />
                                    </a>
                                </li>
                                @endif
                            @endforeach
                    </ul>
                    @else
                    <div class="alert alert-warning">
                        <h3>PLEASE UPLOAD PRODUCTS</h3>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default _widget_contact-us _widget _footer_widget contact-us" data-widget="contact-us">
                <div class="panel-heading">
                    <h2 class="panel-title">CONTACT US</h2>
                </div>
                <div class="panel-body clearfix row">
                    <div class="external-links">
                        <ul class="list-unstyled content-description">
                            <li>
                                <span class="fa-stack fa-lg icon">
                                    <i class="fa fa-circle-o fa-stack-2x"></i>
                                    <i class="fa fa-map-marker fa-stack-1x fa-inverse"></i>
                                </span>
                                {{ config('app.address') }}
                            </li>
                            <li>
                                <span class="fa-stack fa-lg icon">
                                    <i class="fa fa-circle-o fa-stack-2x"></i>
                                    <i class="fa fa-phone fa-stack-1x fa-inverse"></i>
                                </span>
                                {{ config('app.contact.phone') }}
                            </li>
                            <li>
                                <span class="fa-stack fa-lg icon">
                                    <i class="fa fa-circle-o fa-stack-2x"></i>
                                    <i class="fa fa-mobile fa-stack-1x fa-inverse"></i>
                                </span>
                                {{ config('app.contact.mobile') }}
                            </li>
                            <li>
                                <span class="fa-stack fa-lg icon">
                                    <i class="fa fa-circle-o fa-stack-2x"></i>
                                    <i class="fa fa-at fa-stack-1x fa-inverse"></i>
                                </span>
                                <a href="mailto:{{ config('mail.support') }}">{{ config('mail.support') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center copyright content-serif">
        {{ config( 'app.footer' ) }}
    </div>
</footer>


