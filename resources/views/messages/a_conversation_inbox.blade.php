<div class="message-container conversation conversation-inbox"
    data-id="{# inboxInfo.id #}"
    data-sender="{# inboxInfo.sender_id #}"
    data-recipient="{# inboxInfo.recipient_id #}"
    data-title="{# inboxInfo.title #}" ng-hide="inboxErrorState">

    <div ng-class="{ 'reply-reply': message.reply, 'message-same-user': (inboxConversations[ $index - 1 ].user_id == message.user_id), 'message-mine': (message.user.id == me.user.id) }" class="message-type-{# message.type.replace('inbox-', '') #} message media animate" ng-repeat="message in inboxConversations"
        data-type="{# message.type #}"
        data-id="{# message.id #}"
        data-user="{# message.user_id #}"
        data-time="{# message.time #}">
        <div class="media-left media-top">
            <a href="javascript:void(0);">
                <img ng-if="message.type == 'inbox'" ng-src="{# (message.user.id == me.user.id) ? message.user.avatar : (me.user.is_permitted) ? message.user.avatar : '{{ url('/images/logo.png') }}' #}" alt="avatar" class="img-circle" />
                <img ng-if="message.type == 'inbox-reply'" ng-src="{# message.user.avatar  #}" alt="avatar" class="img-circle" />
            </a>
        </div>
        <div class="media-body content-description">
            @if( Auth::user()->isAdmin() )
            <button ng-click="destroyConversation( message.id, $index )" title="Delete this message?" type="button" class="btn" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            @endif
            <div class="message-body">
                <header class="time">{# message.time #}</header>
                <div class="message-body-content" ng-bind-html="message.body"></div>
            </div>
        </div>
    </div>

    <div class="message-reply container-fluid" id="reply">
        <alert ng-repeat="alert in alerts" type="danger" close="closeAlert( $index )"><i class="fa fa-exclamation-triangle"></i> {# alert.message #}</alert>
        {!! Form::open(['route' => 'inbox.reply', 'name' => 'form_reply', 'ng-submit' => 'form_reply.$valid && inboxReplySubmit( $event, form_reply )']) !!}
            <div serial="r3p1y" name="reply" class="content-description" ta-toolbar="[['bold', 'italics', 'underline', 'undo', 'redo', 'clear']]" ng-minlength="5" required ng-required="true" placeholder="Write a reply {{ Auth::user()->first_name }}" text-angular ng-model="reply"></div>
            <input ta-bind ng-model="reply" id="reply_bind" class="hidden" type="hidden" />
            <div class="buttons">
                <input ng-hide="replySubmitButton.state" type="submit" ng-class="{ 'btn-success': form_reply.$valid }" class="btn btn-primary" ng-disabled="form_reply.$invalid" value="SUBMIT" />
                <button ng-show="replySubmitButton.state" type="button" class="btn btn-success"><i class="fa fa-circle-o-notch fa-spin" ng-show="replySubmitButton.state"></i> SUBMITTING</button>
            </div>
        {!! Form::close() !!}
    </div>

</div>

<div class="alert alert-danger text-center" ng-show="inboxErrorState">
    <p>{# inboxErrorMessage #}</p>
</div>


