<div class="settings-container settings-banner clearfix" ng-init="initializeDropzone( '{{ csrf_token() }}' )">
    <div class="alert alert-info">
        <em>It is advisable if your banner with a minimum width of: <strong>1140px</strong> and minimum height of: <strong>422px</strong></em>
    </div>
    <div ng-if="bannerError" class="settings-error text-center alert alert-danger">
        <h1>{# bannerErrorMessage #}</h1>
    </div>
    <div class="settings-carousel">
        <carousel interval="bannerInterval">
            <slide ng-repeat="banner in banners" active="banner.active">
                <img ng-src="{# banner.value[0].url #}" style="margin:auto;" alt="banner" />
                <div class="options carousel-caption">
                    <button ng-click="removeBanad( banner.id )" class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash-o"></i> REMOVE</button>
                </div>
            </slide>
        </carousel>
    </div>
    <div class="settings-preview dropzone" id="bannerPreview">
        <div class="banner-preview"></div>
    </div>
    <hr />
    <form class="form-horizontal">
        <fieldset>
            <legend>Settings</legend>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Slide Interval</label>
                <div class="col-sm-10">
                    <input ng-init="bannerInterval = {{ config('okie.banner.interval') }}" data-default="{{ config('okie.banner.interval') }}" ng-model-options="{ debounce: 2000 }" ng-change="changeValue({ key: 'okie.banner.interval', value: bannerInterval })" type="number" ng-model="bannerInterval" class="content-description form-control" placeholder="Banner slide milliseconds interval" />
                </div>
            </div>
        </fieldset>
    </form>
</div>
