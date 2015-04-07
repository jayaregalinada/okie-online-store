<div class="message-container inbox">
    <div class="alert alert-warning text-center" ng-if="! threadInboxes.length">
        <p><i class="fa fa-3x fa-exclamation-circle"></i></p>
        <p>NO {# heading | uppercase #} FOUND</p>
    </div>

    <div class="message media animate" ng-repeat="message in threadInboxes" ui-sref="messages.thread({ threadId: message.id })">
        <div class="media-left media-top">
            <a href="#" ui-sref="messages.thread({ threadId: message.id })">
                <img ng-src="{# message.user.avatar #}" alt="{# message.user.first_name #}">
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading">{# message.name #}<br />
                <span class="small font-light">{# message.latest.time #}</span></small>
            </h4>
            <div class="message-body" ng-bind-html="message.latest.body">
            </div>
        </div>
    </div>

</div>