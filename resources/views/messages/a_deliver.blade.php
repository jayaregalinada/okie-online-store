<div class="message-container messages messages-delivered">
    <div class="alert alert-default text-center" ng-show="deliverLoadingState">
        <p><i class="fa fa-3x fa-spinner fa-pulse"></i></p>
        <p>LOADING</p>
    </div>

    <div class="alert alert-danger text-center" ng-show="deliverErrorState">
        <p><i class="fa fa-3x fa-exclamation-circle"></i></p>
        <p>{# deliverErrorMessage | uppercase #}</p>
    </div>

    <div class="message media animate" ng-repeat="message in deliveries" ui-sref="delivered.viewDeliver({ deliverId: message.id })"
        data-id="{# message.id #}"
        data-user="{# message.user_id #}"
        data-product="{# message.product_id #}"
        data-title="{# message.title #}"
        data-confirm="{# message.confirm_id #}">
        <div class="media-left media-top">
            <a href="#" ui-sref="delivered.viewDeliver({ deliverId: message.id })">
                <img ng-src="{# message.product.thumbnail[2].url #}" alt="{# message.product.name #}">
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading">{# message.title #}<br />
                <span class="small font-light">{# message.latest.time #}</span></small>
            </h4>
            <div class="message-body" ng-bind-html="message.latest.body"></div>
        </div>
    </div>

</div>
