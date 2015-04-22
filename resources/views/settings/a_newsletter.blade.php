<div class="settings-container settings-newsletter clearfix">
    <div class="media">
        <div class="media-left media-middle">
            <i class="fa fa-envelope fa-5x pull-left"></i>
        </div>
        <div class="media-body">
            <h2 class="media-heading">SUBSCRIBE TO OUR NEWSLETTER</h2>
            <p class="content-description">Subscribe to our mailing list to get the updates to your email inbox.</p>
        </div>
    </div>
    <hr />
    {!! Form::open( [ 'route' => 'newsletter', 'class' => 'form clearfix row', 'ng-submit' => 'form_newsletter.$valid && subscribeNewsletter( $event, form_newsletter)', 'name' => 'form_newsletter']) !!}
        <div class="form-group col-md-9">
            <label for="email" class="sr-only">EMAIL</label>
            <input ng-model="settings.email" ng-required="true" required="required" class="form-control" id="email" name="email" type="email" placeholder="Enter e-mail address" />
        </div>
        <div class="form-group col-md-3">
            <button type="submit" class="form-control btn btn-primary" ng-class="{ 'btn-success': form_newsletter.$valid }">SUBSCRIBE</button>
        </div>
    {!! Form::close() !!}
    <alert ng-repeat="alert in alerts" type="danger" close="closeAlert( $index )"><i class="fa fa-exclamation-triangle"></i> {# alert.message #}</alert>
    <hr ng-if="emails.length" />
    <div class="panel panel-default" ng-if="emails.length">
        <div class="panel-heading">
            <h3 class="panel-title">EMAIL ADDRESS YOU HAVE BEEN SUBSCRIBED</h3>
        </div>
        <ul class="list-group">
            <li class="list-group-item " ng-repeat="email in emails">
                <span class="content-description">{# email.email #}</span>
                <a href="javascript:void(0);" class="font-light badge" ng-click="unsubscribing( email.email )">UNSUBSCRIBE</a href="javascript:void(0);">
            </li>
        </ul>
    </div>
</div>
