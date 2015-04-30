<div class="settings-container settings-general clearfix">
    <form class="form-horizontal">
        <fieldset>
            <legend class="content-description h2">WEBSITE</legend>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Site Title</label>
                <div class="col-sm-10">
                    <input ng-init="general.title = '{{ config('app.title') }}'" data-default="{{ config('app.title') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'app.title', value: general.title })" type="text" ng-model="general.title" class="content-description form-control" placeholder="Your website title" />
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                    <textarea rows="5" style="resize:none;" ng-init="general.description = '{{ config('app.description') }}'" ng-model-options="{ debounce: 2000 }" data-default="{{ config('app.title') }}" ng-change="changeValue({ key: 'app.description', value: general.description })" type="text" ng-model="general.description" class="content-description form-control" placeholder="Your website description">
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Address</label>
                <div class="col-sm-10">
                    <input ng-init="general.address = '{{ config('app.address') }}'" data-default="{{ config('app.address') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'app.address', value: general.address })" type="text" ng-model="general.address" class="content-description form-control" placeholder="Your website address" />
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Site URL</label>
                <div class="col-sm-10">
                    <input ng-init="general.url = '{{ config('app.url') }}'" data-default="{{ config('app.url') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'app.url', value: general.url })" type="text" ng-model="general.url" class="content-description form-control" placeholder="Your website URL" />
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Footer text</label>
                <div class="col-sm-10">
                    <input ng-init="general.footer = '{{ config('app.footer') }}'" data-default="{{ config('app.footer') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'app.footer', value: general.footer })" type="text" ng-model="general.footer" class="content-description form-control" placeholder="Your footer text" />
                </div>
                <div class="col-sm-10 col-sm-push-2 input-description">
                    <em>You can use helpers like, __YEAR__ and __TITLE__</em>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend class="content-description h2">BRAND</legend>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Logo Brand Name</label>
                <div class="col-sm-10">
                    <input ng-init="general.brand_name = '{{ config('app.logo.name') }}'" data-default="{{ config('app.logo.name') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'app.logo.name', value: general.brand_name })" type="text" ng-model="general.brand_name" class="content-description form-control" placeholder="Your Brand Name" />
                </div>
            </div>
            <div class="form-group logo-brand-image">
                <label for="" class="col-sm-2 control-label">Logo Brand Image</label>
                <div class="col-sm-10">
                    <input ng-init="general.brand_image = '{{ config('app.logo.img') }}'" data-default="{{ config('app.logo.img') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'app.logo.img', value: general.brand_image })" type="text" ng-model="general.brand_image" class="content-description form-control" placeholder="Your Brand Image" />
                </div>
                <div class="col-sm-10 col-sm-push-2 input-description">
                    <em>PREVIEW:</em><br />
                    <img ng-src="{# general.brand_image #}" alt="" />
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend class="content-description h2">CONTACT</legend>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Telephone</label>
                <div class="col-sm-10">
                    <input ng-init="general.contact_phone = '{{ config('app.contact.phone') }}'" data-default="{{ config('app.contact.phone') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'app.contact.phone', value: general.contact_phone })" type="text" ng-model="general.contact_phone" class="content-description form-control" placeholder="Your telephone number" />
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Mobile</label>
                <div class="col-sm-10">
                    <input ng-init="general.contact_mobile = '{{ config('app.contact.mobile') }}'" data-default="{{ config('app.contact.mobile') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'app.contact.mobile', value: general.contact_mobile })" type="text" ng-model="general.contact_mobile" class="content-description form-control" placeholder="Your telephone number" />
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Support Email</label>
                <div class="col-sm-10">
                    <input ng-init="general.mail_support = '{{ config('mail.support') }}'" data-default="{{ config('mail.support') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'mail.support', value: general.mail_support })" type="text" ng-model="general.mail_support" class="content-description form-control" placeholder="You support email address" />
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend class="content-description h2">PRODUCT</legend>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Include Remaining</label>
                <div class="col-sm-10">
                    <div class="btn-group" ng-init="general.item_remaining = {{ config( 'product.item.remaining' ) }}">
                        <label ng-click="changeValue({ key: 'product.item.remaining', value: true })" ng-class="{ 'btn-success': general.item_remaining }" class="btn btn-default" ng-model="general.item_remaining" btn-radio="true" uncheckable>YES</label>
                        <label ng-click="changeValue({ key: 'product.item.remaining', value: false })" ng-class="{ 'btn-success': !general.item_remaining }" class="btn btn-default" ng-model="general.item_remaining" btn-radio="false" uncheckable>NO</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Other Items msg.</label>
                <div class="col-sm-10">
                    <input ng-init="general.other_items = '{{ config('responses.other_items') }}'" data-default="{{ config('responses.other_items') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'responses.other_items', value: general.other_items })" type="text" ng-model="general.other_items" class="content-description form-control" placeholder="Message for other products found in every item" />
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Inquiry msg.</label>
                <div class="col-sm-10">
                    <input ng-init="general.inquiry_message = '{{ config('responses.inquiry') }}'" data-default="{{ config('responses.inquiry') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'responses.inquiry', value: general.inquiry_message })" type="text" ng-model="general.inquiry_message" class="content-description form-control" placeholder="Message for other products found in every item" />
                </div>
            </div>
        </fieldset>

    </form>
</div>
