<div id="item_container" class="container">
    <hr class="animated fadeIn" />
    <div class="item-wrapper clearfix">
        <div class="col-md-5 item-left">
            <div class="item-carousel animated fadeInLeft">
                <carousel interval="carouselInterval">
                    <slide ng-repeat="image in item.images" active="image.active">
                        <img ng-src="{# image.sizes[2].url #}" alt="{# image.caption #}" />
                    </slide>
                </carousel>
            </div>
        </div>
        <div class="col-md-7 item-right">
            <div class="page-header" id="header">
                <h1>{# item.name #}</h1>
            </div>
            <div class="item-info" ng-hide="inquireState">
                <div class="container-fluid row animated fadeInUp">
                    <div class="input-group input-group-sm item-price col-md-3 clearfix">
                        <span class="input-group-addon" id="sizing-addon2">PRICE</span>
                        <span class="form-control">{# item.price | currency:"Php" #}</span>
                    </div>
                    <br />
                    <div class="item-description content-description">
                        <p><strong class="small">The details:</strong></p>
                        <p>{# item.description #}</p>
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
                        <a href="javascript:void(0)" ng-click="inquireItem()" class="btn btn-primary">INQUIRE FOR THIS PRODUCT</a>
                    </div>
                        @else
                    <div class="item-inquire">
                        <a href="javascript:void(0)" class="btn btn-primary">CHECK FOR INQUIRIES ON THIS PRODUCT</a>
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
                        <div class="content-description" ta-toolbar="[['bold', 'italics', 'underline', 'undo', 'redo', 'clear']]" ng-minlength="5" required ng-required="true" placeholder="I would like to inquire about this item" text-angular ng-model="inquire"></div>
                        <input ng-hide="inquireSubmitButton.state" type="submit" ng-class="{ 'btn-success': form_inquire.$valid }" class="btn btn-primary btn-lg" ng-disabled="form_inquire.$invalid" value="SUBMIT" />
                        <button ng-show="inquireSubmitButton.state" type="button" class="btn btn-success btn-lg"><i class="fa fa-circle-o-notch fa-spin" ng-show="inquireSubmitButton.state"></i> SUBMITTING</button>
                        <button ng-hide="inquireSubmitButton.state" class="btn-warning btn" type="button" ng-click="inquireState = false">CANCEL</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
