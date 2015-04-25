<div id="item_container" class="container" ng-if="!errorMessage">
    <hr class="animated fadeIn" />
    <div class="item-wrapper clearfix">
        <div id="item_content_left" class="col-md-5 item-left affected-with-affix">
            <div class="item-carousel animated fadeInLeft">
                <carousel interval="carouselInterval">
                    <slide ng-repeat="image in item.images" active="image.active">
                        <img ng-click="clickImage( image.sizes[0].url )" ng-src="{# image.sizes[2].url #}" alt="{# image.caption #}" />
                    </slide>
                </carousel>
            </div>
        </div>
        <div class="col-md-7 item-right">
            <div class="page-header" id="header">
                <h1>{# item.name #} <span class="small">{# item.badge.title #}</span></h1>
                <div class="item-rating">
                    <div class="rating-star"
                    @if( Auth::check() )
                    ng-click="ratingItem()" data-toggle="collapse" data-target="#ratingCollapse" aria-expanded="false" aria-controls="ratingCollapse"
                    @endif
                    >
                        <rating readonly="true" ng-model="item.rating.average" max="rating.maximum" popover-append-to-body="true" popover-placement="right" popover="The rating of the product. @if( Auth::check() ) Click if you want to rate this product @endif" popover-title="F.A.Q" title="F.A.Q" popover-trigger="mouseenter"></rating> <span class="small" ng-if="!item.rating.count">NOT YET RATED</span>
                    </div>
                
                    @if( Auth::check() )
                    <div class="collapse" id="ratingCollapse">
                        {!! Form::open(['ng-show' => 'ratingState', 'class' => 'animate', 'name' => 'form_rate_item', 'ng-submit' => 'form_rate_item.$valid && rateTheItem( $event, item.id )', 'route' => ['item.rate', '_ITEM_ID_']]) !!}
                        <div class="rating-star">
                            <rating ng-if="!item.review" required="required" ng-required="true" ng-model="item.rating.rating" max="rating.maximum" popover-append-to-body="true" popover-placement="right" popover="Rate this product" popover-title="F.A.Q" title="F.A.Q" popover-trigger="mouseenter"></rating> <span class="small" ng-if="!item.rating.count">BE THE FIRST TO RATE THIS PRODUCT</span>
                            <rating ng-if="item.review" required="required" ng-required="true" ng-model="item.review.rating" max="rating.maximum" popover-append-to-body="true" popover-placement="right" popover="Rate this product" popover-title="F.A.Q" title="F.A.Q" popover-trigger="mouseenter"></rating> <span ng-if="item.review" class="small">YOU CAN UPDATE YOUR RATING</span>
                        </div>
                        <div ng-if="!item.review" class="no-buttons content-description" ta-toolbar="[['p', 'undo', 'redo', 'clear']]" ng-minlength="5" placeholder="Tell us about this product" text-angular ng-model="item.rating.message"></div>
                        <div ng-if="item.review" class="no-buttons content-description" ta-toolbar="[['p', 'undo', 'redo', 'clear']]" ng-minlength="5" placeholder="Tell us about this product" text-angular ng-model="item.review.message"></div>
                        <div class="buttons">
                            <button type="button" ng-show="form_rate_item.$invalid && ! rateSubmittingState" class="btn btn-warning">SUBMIT</button>
                            <button type="submit" ng-click="form_rate_item.$valid && rateTheItem( $event, item.id )" ng-show="form_rate_item.$valid && ! rateSubmittingState" class="btn btn-success">SUBMIT</button>
                            <button type="button" ng-show="rateSubmittingState" class="btn btn-success">LOADING</button>
                            <button ng-click="ratingItem()" ng-hide="rateSubmittingState" type="button" data-toggle="collapse" data-target="#ratingCollapse" aria-expanded="false" aria-controls="ratingCollapse" class="btn btn-warning">CANCEL</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    @endif
                </div>
            </div>
            <div class="item-info" ng-hide="inquireState">
                <div class="container-fluid row animated fadeInUp">
                    {{-- <div class="input-group input-group-sm item-price col-md-3 clearfix">
                        <span class="input-group-addon" id="sizing-addon2">PRICE</span>
                        <span class="form-control">{# item.price | currency:"Php" #}</span>
                    </div> --}}
                    <p ng-class="{ 'item-sale': item.sale_price }" class="item-price text-primary">
                        <span class="sale-price">{# item.sale_price | currency: "Php" #}</span>
                        <span class="orig-price">{# item.price | currency:"Php" #}</span>
                    </p>
                    <br />
                    <div class="item-description content-description">
                        <div ng-bind-html="item.description"></div>
                    </div>
                    <hr />
                    <div class="input-group input-group-sm item-unit col-md-3">
                        <span class="input-group-addon" id="sizing-addon2">REMAINING
                            <a href="javascript:void(0)" popover-append-to-body="true" popover-placement="right" popover="The availability of the product" class="fa fa-question-circle" popover-title="F.A.Q" title="F.A.Q" data-content="How many items are available" popover-trigger="mouseenter"></a>
                        </span>
                        <span class="form-control">{# item.unit #}</span>
                    </div>
                    <br />
                    @if( Auth::check() )
                        @if( Auth::user()->isUser() )
                    <div class="item-inquire">
                        <a ng-if="!item.inquiry" href="javascript:void(0)" ng-click="inquireItem()" class="btn btn-primary">INQUIRE FOR THIS PRODUCT</a>
                        <a ng-if="item.inquiry" href="{{ route('messages.index') }}{# $state.href('messages.viewInquiry', { inquiryId: item.inquiry.id }) #}" class="btn btn-success"><i class="fa fa-envelope-o"></i> READ MESSAGES</a>
                    </div>
                        @else
                    <div class="item-inquire">
                        <a href="javascript:void(0)" class="btn btn-primary">CHECK FOR INQUIRIES<span class="hidden-xs hidden-sm"> ON THIS PRODUCT</span></a>
                        @if( Auth::user()->isAdmin() )
                        <a href="{{ route('product.show', '') }}/{# item.id #}" class="btn btn-info">EDIT THIS PRODUCT</a>
                        @endif
                    </div>
                        @endif

                    @else
                    <div class="item-inquire">
                        <a ng-click="redirectInItem()" href="{{ route('auth.login') }}?redirect_to_item={# $stateParams.itemId #}" class="btn btn-warning">LOGIN TO INQUIRE</a>
                    </div>
                    @endif
                </div>
            </div>
            <div class="item-inquire" ng-show="inquireState">
                <alert class="animated fadeIn" ng-repeat="inquireMessage in inquireMessages" close="closeInquireMessage( $index )" type="{# inquireMessage.type #}">
                    {# inquireMessage.message #}
                </alert>
                <div class="container-fluid row animated fadeIn">
                    {!! Form::open(['route' => 'item.inquire', 'name' => 'form_inquire', 'ng-submit' => 'form_inquire.$valid && inquireSubmit( $event )']) !!}
                        <div class="col-md-8">
                            <h4>Do you want to reseve? How many do you want?</h4>
                        </div>
                        <div class="input-group col-md-4">
                            <div class="input-group-btn">
                                <button ng-click="inquire.reserve = inquire.reserve + 1" type="button" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                            </div>
                            <input type="text" ng-disabled="true" ng-model="inquire.reserve" class="text-center form-control" />
                            <div class="input-group-btn">
                                <button ng-click="inquire.reserve = ( inquire.reserve > 1 ) ? inquire.reserve - 1 : inquire.reserve = 0" type="button" class="btn btn-primary"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <br />
                        <div class="content-description" ta-toolbar="[['bold', 'italics', 'underline', 'undo', 'redo', 'clear']]" ng-minlength="5" required ng-required="true" placeholder="I would like to inquire about this item" text-angular ng-model="inquire.message"></div>
                        <input ng-hide="inquireSubmitButton.state" type="submit" ng-class="{ 'btn-success': form_inquire.$valid }" class="btn btn-primary btn-lg" ng-disabled="form_inquire.$invalid" value="SUBMIT" />
                        <button ng-show="inquireSubmitButton.state" type="button" class="btn btn-success btn-lg"><i class="fa fa-circle-o-notch fa-spin" ng-show="inquireSubmitButton.state"></i> SUBMITTING</button>
                        <button ng-hide="inquireSubmitButton.state" class="btn-warning btn" type="button" ng-click="inquireItem()">CANCEL</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="item-related clearfix">
        <hr />
        <header class="page-header">
            <h2>You may also looking for this</h2>
        </header>
        <ul class="container-fluid list-unstyled item-container">
            <li ng-repeat="product in item.related" class="items-animation animate product-{# product.id #} col-md-3 product-item"
                data-product-id="{# product.id #}"
                data-product-name="{# product.name #}"
                data-product-code="{# product.code #}">
                
                <a class="product-item-container" href="#" ui-sref="item({ itemId: product.id })">
                    <div class="ribbon {# product.badge.class #} ribbon-default" ng-if="product.badge.title">
                        <span>{# product.badge.title #}</span>
                    </div>
                    <div class="img">
                        <img ng-src="{# product.thumbnail[2].url #}" alt="" class="img-responsive" />
                    </div>
                    <div class="description">
                        <em class="small content-description">{# product.categories[0].name #}</em>
                        <br />{# product.name #}
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<div id="item_container_error" ng-if="errorMessage">
    <div class="jumbotron text-center">
        <h1>{# errorMessage #}</h1>
        <img src="{{ asset('/images/errors/404.jpg') }}" alt="error" />
    </div>
</div>
