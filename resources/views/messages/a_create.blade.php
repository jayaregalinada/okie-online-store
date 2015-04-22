<div class="message-container create">
    <alert ng-repeat="alert in alerts" type="danger" close="closeAlert( $index )"><i class="fa fa-exclamation-triangle"></i> {# alert.message #}</alert>
    {!! Form::open([ 'route' => 'inbox.create', 'name' => 'form_message', 'ng-submit' => 'form_message.$valid && submitNewMessage( $event, form_message )' ]) !!}
        
        <div class="form-group">
            <label for="subject" class="sr-only">SUBJECT</label>
            <input ng-model="message.subject" type="text" class="form-control" id="subject" placeholder="Enter Subject" />
        </div>
        @if( Auth::user()->isPermitted() )
        <div class="form-group" style="position: relative">
            <label class="sr-only">TO USER</label>
            <div class="input-group">
                <input ng-require="true" required="required" type="text" ng-change="getUser( $event )" ng-model-options="{ debounce: 500 }" ng-model="message.send" class="form-control" id="user" placeholder="Search for user" />
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" ng-click="getUser( $event )">SEARCH</button>
                </span>
            </div>
            
            <input class="hidden" type="text" ng-require="true" required="required" ng-model="message.recipient" />
            <div class="search">
                <ul class="list-group" ng-show="search.length">
                    <li class="list-group-item" ng-click="sendWithUser( s )" ng-repeat="s in search">
                        <img ng-src="{# s.avatar #}" alt="avatar" />
                        {# s.first_name #} {# s.last_name #}
                    </li>
                </ul>
                <ul class="list-group" ng-show="searchError">
                    <li class="list-group-item list-group-item-danger">{# searchErrorMessage #}</li>
                </ul>
            </div>
        </div>
        @endif
        <div class="form-group">
            <label for="message" class="sr-only">MESSAGE</label>
            <div serial="m3ssag3" name="message" class="content-description" ta-toolbar="[['bold', 'italics', 'underline', 'undo', 'redo', 'clear']]" ng-minlength="5" required ng-required="true" placeholder="Go on {{ Auth::user()->first_name }}, compose a message" text-angular ng-model="message.message"></div>
        </div>
        
            
        <div ta-bind ng-model="message" id="message_bind" class="hidden"></div>
        <div class="form-group buttons">
            <input ng-hide="messageSubmitButton.state" type="submit" ng-class="{ 'btn-success': form_message.$valid }" class="btn btn-primary btn-lg" ng-disabled="form_message.$invalid" value="SUBMIT" />
            <button ng-show="messageSubmitButton.state" type="button" class="btn btn-success btn-lg"><i class="fa fa-circle-o-notch fa-spin" ng-show="messageSubmitButton.state"></i> SUBMITTING</button>
        </div>

    {!! Form::close() !!}

</div>
