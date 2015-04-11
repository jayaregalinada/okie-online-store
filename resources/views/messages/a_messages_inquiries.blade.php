<div class="message-container messages messages-inquiries">
    <div class="alert alert-default text-center" ng-show="inquiryLoadingState">
        <p><i class="fa fa-3x fa-spinner fa-pulse"></i></p>
        <p>LOADING</p>
    </div>

    <div class="alert alert-danger text-center" ng-show="inquiryErrorState">
        <p><i class="fa fa-3x fa-exclamation-circle"></i></p>
        <p>{# inquiryErrorMessage | uppercase #}</p>
    </div>

    <div class="message media animate" ng-repeat="message in inquiries" ui-sref="messages.viewInquiry({ inquiryId: message.id })"
        data-id="{# message.id #}"
        data-inquisition="{# message.inquisition_id #}"
        data-product="{# message.product_id #}"
        data-title="{# message.title #}">
        <div class="media-left media-top">
            <a href="#" ui-sref="messages.viewInquiry({ inquiryId: message.id })">
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
