<div class="message-container conversation conversation-inbox">

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


    <div class="message-reply container-fluid" id="reply">
        <alert ng-repeat="alert in alerts" type="danger" close="closeAlert( $index )"><i class="fa fa-exclamation-triangle"></i> {# alert.message #}</alert>
        {!! Form::open(['route' => 'messages.inbox.reply', 'name' => 'form_inbox_reply', 'ng-submit' => 'form_inbox_reply.$valid && inboxReplySubmit( $event, form_inbox_reply )']) !!}
            <div serial="inboxr3p1y" name="inbox_reply" class="content-description" ta-toolbar="[['bold', 'italics', 'underline', 'undo', 'redo', 'clear']]" ng-minlength="5" required ng-required="true" placeholder="Write a reply {{ Auth::user()->first_name }}" text-angular ng-model="inbox.reply"></div>
            <div ta-bind ng-model="inbox_reply" id="inbox_reply_bind" class="hidden"></div>
            <div class="buttons">
                <input ng-hide="inboxReplySubmitButton.state" type="submit" ng-class="{ 'btn-success': form_inbox_reply.$valid }" class="btn btn-primary" ng-disabled="form_inbox_reply.$invalid" value="SUBMIT" />
                <button ng-show="inboxReplySubmitButton.state" type="button" class="btn btn-success"><i class="fa fa-circle-o-notch fa-spin" ng-show="inboxReplySubmitButton.state"></i> SUBMITTING</button>
            </div>
        {!! Form::close() !!}
    </div>

</div>