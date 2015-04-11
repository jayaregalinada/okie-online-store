<div class="message-container messages">
    <div class="alert alert-warning text-center" ng-if="! messages.length">
        <p><i class="fa fa-3x fa-exclamation-circle"></i></p>
        <p>NO {# heading | uppercase #} FOUND</p>
    </div>

    <div class="message media animate" ng-repeat="message in messages" ui-sref="messages.thread({ threadId: message.id })">
        <div class="media-left media-top">
            <a href="#" ui-sref="messages.thread({ threadId: message.id })">
                <img ng-src="{# message.product.thumbnail[2].url #}" alt="{# message.product.name #}">
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading">{# message.title #}<br />
                <span class="small font-light">{# message.latest.time #}</span></small>
            </h4>
            <div class="message-body" ng-bind-html="message.latest.body">
            </div>
        </div>
    </div>

</div>