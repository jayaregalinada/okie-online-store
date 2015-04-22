<div class="message-container conversation conversation-inquiry"
    data-id="{# inquiryInfo.id #}"
    data-inquisition="{# inquiryInfo.inquisition_id #}"
    data-product="{# inquiryInfo.product_id #}"
    data-title="{# inquiryInfo.title #}" ng-hide="inquiryErrorState">

    @if ( Auth::user()->isPermitted() )
    <div class="marker">
        <a ng-class="{ inactive: inquiryStateReserve }" href="#" ng-click="moveToDelivered()" class="btn btn-info btn-sm">MARK AS DELIVERED</a>
        <a ng-class="{ inactive: inquiryStateReserve }" href="{{ route('product.index') }}/{# inquiryInfo.product_id #}" target="_blank" class="btn btn-info btn-sm">VIEW PRODUCT INQUIRED</a>
        <a ng-class="{ active: inquiryStateReserve }" href="#" ng-click="inquiryReserve( $event )" class="btn btn-info btn-sm">RESERVED ITEM</a>
    </div>
    <div class="inquiry-reserve-container" ng-show="inquiryStateReserve">
        <div class="col-md-4 text-center">
            <h4 style="color:#FFF;">CURRENTLY ITEM RESERVE: {# inquiryInfo.reserve + reserve #}</h4>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-btn">
                    <button type="button" ng-click="reserveButton('add')" class="btn btn-default" type="button"><i class="fa fa-plus"></i></button>
                </span>
                <input name="reserve" ng-readonly="true" type="text" class="text-center form-control" ng-model="reserve" />
                <span class="input-group-btn">
                    <button type="button" ng-click="reserveButton('minus')" class="btn btn-default" type="button"><i class="fa fa-minus"></i></button>
                </span>
                <span class="input-group-btn">
                    <button ng-click="reserveItem( reserve )" type="button" class="btn btn-primary">UPDATE</button>
                </span>
            </div>
        </div>
    </div>
    @endif

    <div ng-class="{ 'message-same-user': (inquiryConversations[ $index - 1 ].user_id == message.user_id), 'message-mine': (message.user.id == me.user.id) }" class="message-type-{# message.type.replace('inquiry-', '') #} message media animate" ng-repeat="message in inquiryConversations"
        data-type="{# message.type #}"
        data-id="{# message.id #}"
        data-user="{# message.user_id #}"
        data-time="{# message.time #}">
        <div class="media-left media-top">
            <a href="javascript:void(0);">
                <img ng-src="{# (message.type == 'inquiry-reply') ? (me.user.is_permitted) ? message.user.avatar : '{{ url('/images/logo.png') }}' : inquiryInfo.user.avatar  #}" alt="avatar" class="img-circle">
            </a>
        </div>
        <div class="media-body content-description">
            <div class="message-body">
                <header class="time">{# message.time #}</header>
                <div class="message-body-content" ng-bind-html="message.body"></div>
            </div>
        </div>
    </div>

    <div class="message-reply container-fluid" id="reply">
        <alert ng-repeat="alert in alerts" type="danger" close="closeAlert( $index )"><i class="fa fa-exclamation-triangle"></i> {# alert.message #}</alert>
        {!! Form::open(['route' => 'inquiry.reply', 'name' => 'form_reply', 'ng-submit' => 'form_reply.$valid && inquiryReplySubmit( $event, form_reply )']) !!}
            <div serial="r3p1y" name="reply" class="content-description" ta-toolbar="[['bold', 'italics', 'underline', 'undo', 'redo', 'clear']]" ng-minlength="5" required ng-required="true" placeholder="Write a reply {{ Auth::user()->first_name }}" text-angular ng-model="reply"></div>
            <div ta-bind ng-model="reply" id="reply_bind" class="hidden"></div>
            <div class="buttons">
                <input ng-hide="replySubmitButton.state" type="submit" ng-class="{ 'btn-success': form_reply.$valid }" class="btn btn-primary" ng-disabled="form_reply.$invalid" value="SUBMIT" />
                <button ng-show="replySubmitButton.state" type="button" class="btn btn-success"><i class="fa fa-circle-o-notch fa-spin" ng-show="replySubmitButton.state"></i> SUBMITTING</button>
            </div>
        {!! Form::close() !!}
    </div>

</div>

<div class="alert alert-danger text-center" ng-show="inquiryErrorState">
    <p>{# inquiryErrorMessage #}</p>
</div>


