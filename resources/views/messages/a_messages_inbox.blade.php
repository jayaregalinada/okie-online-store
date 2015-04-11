<div class="message-container messages messages-inbox">
    <div class="alert alert-default text-center" ng-show="!inboxErrorState" ng-hide="inbox.length">
        <p><i class="fa fa-3x fa-spinner fa-pulse"></i></p>
        <p>LOADING</p>
    </div>

    <div class="alert alert-danger text-center" ng-show="inboxErrorState">
        <p><i class="fa fa-3x fa-exclamation-circle"></i></p>
        <p>{# inboxErrorMessage | uppercase #}</p>
    </div>

    <div class="message media animate" ng-repeat="message in inbox" ui-sref="messages.viewInbox({ inboxId: message.id })"
        data-id="{# message.id #}"
        data-sender="{# message.sender_id #}"
        data-recipient="{# message.recipient_id #}"
        data-title="{# message.title #}">
        <div class="media-left media-top">
            <a href="#" ui-sref="messages.viewInbox({ inboxId: message.id })">
                <img ng-src="{# (message.sender_id == me.user.id) ? (message.recipient) ? message.recipient.avatar : '{{ url('/images/logo.png') }}' : (me.user.is_permitted) ? message.sender.avatar : '{{ url('/images/logo.png') }}' #}" alt="avatar" />
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading">{# message.title #}<br />
                <span class="small font-light">{# message.latest.time #}</span></small>
            </h4>
            <div class="message-body clearfix">
                <em ng-if="me.user.is_permitted" class="pull-left">{# message.latest.user.first_name #}: &nbsp;&nbsp;</em>
                <div ng-bind-html="message.latest.body"></div>
            </div>
        </div>
    </div>

</div>
