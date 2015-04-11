<div class="message-container conversation conversation-inquiry"
    data-id="{# inquiryInfo.id #}"
    data-inquisition="{# inquiryInfo.inquisition_id #}"
    data-product="{# inquiryInfo.product_id #}"
    data-title="{# inquiryInfo.title #}" ng-hide="inquiryErrorState">

    @if ( Auth::user()->isPermitted() )
    <div class="marker">
        <a href="#" ng-click="moveToDelivered()" class="btn btn-info btn-sm">MARK AS DELIVERED</a>
        <a href="{{ route('product.index') }}/{# inquiryInfo.product_id #}" target="_blank" class="btn btn-info btn-sm">VIEW PRODUCT INQUIRED</a>
    </div>
    @endif

    <div ng-class="{ 'message-mine': (message.user.id == me.user.id) }" class="message-type-{# message.type.replace('inquiry-', '') #} message media animate" ng-repeat="message in inquiryConversations"
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

