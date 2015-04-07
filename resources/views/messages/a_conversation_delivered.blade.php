<div class="message-container conversation">
    
    @if ( Auth::user()->isAdmin() )
    <div class="marker">
        <a href="#" class="btn btn-info btn-sm">MARK AS INQUIRIES</a>
        <a href="{{ route('product.index') }}/{# messageInfo.product.id #}" target="_blank" class="btn btn-info btn-sm">VIEW PRODUCT INQUIRED</a>
    </div>
    @endif

    <div class="message-type-{# message.type #} message media animate" ng-repeat="message in conversation" 
        data-type="{# message.type #}"
        data-id="{# message.id #}"
        data-user="{# message.user_id #}"
        data-time="{# message.time #}">
        <div class="media-left media-top">
            <a href="#">
                <img ng-src="{# (message.type == 'reply') ? (me.user.permission < 1) ? me.user.avatar : 'http://okie.app/images/logo.png' : messageInfo.user.avatar #}" alt="avatar" class="img-circle">
            </a>
        </div>
        <div class="media-body content-description">
            <div class="message-body">
                <header class="time">{# message.time #}</header>
                <div class="message-body-content" ng-bind-html="message.body"></div>
            </div>
        </div>
    </div>

    @if ( Auth::user()->isAdmin() )
    <div class="message-reply container-fluid" id="reply">
        {!! Form::open(['route' => 'messages.inquiries.reply', 'name' => 'form_reply', 'ng-submit' => 'form_reply.$valid && replySubmit( $event, form_reply )']) !!}
            <div serial="r3p1y" name="reply" class="content-description" ta-toolbar="[['bold', 'italics', 'underline', 'undo', 'redo', 'clear']]" ng-minlength="5" required ng-required="true" placeholder="Write a reply {{ Auth::user()->first_name }}" text-angular ng-model="reply"></div>
            <div ta-bind ng-model="reply" id="reply_bind" class="hidden"></div>
            <div class="buttons">
                <input ng-hide="replySubmitButton.state" type="submit" ng-class="{ 'btn-success': form_reply.$valid }" class="btn btn-primary" ng-disabled="form_reply.$invalid" value="SUBMIT" />
                <button ng-show="replySubmitButton.state" type="button" class="btn btn-success"><i class="fa fa-circle-o-notch fa-spin" ng-show="replySubmitButton.state"></i> SUBMITTING</button>
            </div>
        {!! Form::close() !!}
    </div>
    @endif

</div>