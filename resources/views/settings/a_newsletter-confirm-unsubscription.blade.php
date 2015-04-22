<div class="modal-header">
    <h3 class="modal-title">Hold on?</h3>
</div>
<div class="modal-body content-description">
    <p>Are you sure you want to unsubscribe <strong>{# hotSeatNewsletterEmail #}</strong> to our newsletter?</p>
    <p class="small"><i class="fa fa-info-circle"></i> Unsubscribing to our newsletter will no longer get notify to your email inbox all of our news and announcements.</p>
</div>
<div class="modal-footer">
    {!! Form::open( [ 'ng-hide' => 'state.newsletterUnsubscribe', 'class' => 'form-inline', 'route' => 'settings.newsletter.unsubscribe', 'ng-submit' => 'newsletterConfirm( $event, form_unsubscribe )', 'name' => 'form_unsubscribe' ] ) !!}
    <button class="btn btn-primary">Yes I'm sure</button>
    <button class="btn btn-warning btn-sm" ng-click="newsletterCancel( $event )">Cancel</button>
    {!! Form::close() !!}
    <button ng-show="state.newsletterUnsubscribe" class="btn btn-primary">We're sorry to see you go</button>
</div>
