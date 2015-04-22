<div class="review-container reviews reviews-all">
    <div class="alert alert-default text-center" ng-show="loadingState">
        <p><i class="fa fa-3x fa-spinner fa-pulse"></i></p>
        <p>LOADING</p>
    </div>

    <div class="alert alert-danger text-center" ng-show="errorState">
        <p><i class="fa fa-3x fa-exclamation-circle"></i></p>
        <p>{# errorMessage | uppercase #}</p>
    </div>

    <div class="review media animate" ng-repeat="review in reviews"
        data-id="{# review.id #}"
        data-user="{# review.user_id #}"
        data-product="{# review.product_id #}"
        data-approved="{# review.approved_by #}"
        data-review="{# review.rating #}"
        ng-class="{ approved: review.approved_by }">
        <div class="media-button">
            <button type="button" ng-click="approveReview( review.id, $index )" ng-show="!review.approved_by" class="btn btn-sm btn-warning">NOT YET APPROVED</button>
            <button type="button" ng-click="unapproveReview( review.id, $index )" ng-mouseleave="hoverApprovedChange( $index, false )" ng-mouseenter="hoverApprovedChange( $index, true )" ng-show="review.approved_by" class="btn btn-sm btn-primary">
                <span ng-if="review.hoverApproved">UNAPPROVED THIS REVIEW</span>
                <span ng-if="!review.hoverApproved">APPROVED BY {# ( me.user.id == review.approved_by ) ? 'YOU': review.approved.full_name | uppercase #}</span>
            </button>
        </div>
        <div class="media-left media-middle">
            <a href="javascript:void(0)">
                <img ng-src="{# review.product.thumbnail[2].url #}" alt="{# review.product.name #}">
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading">{# review.user.full_name #}<br />
                <span class="small content-description">{# review.time #}</span></small>
            </h4>
            <div class="review-body">
                <div class="rating-star">
                    <rating readonly="true" ng-model="review.rating"></rating>
                </div>
                <accordion ng-show="review.message">
                    <accordion-group heading="View Review">
                        <div class="message-body" ng-bind-html="review.message"></div>
                    </accordion>
                </accordion>
                
            </div>
        </div>
    </div>

</div>
