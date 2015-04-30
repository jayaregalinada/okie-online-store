<carousel interval="bannerInterval">
    <slide ng-repeat="banner in banners" active="banner.active">
        <img ng-src="{# banner[0].url #}" style="margin:auto;" alt="banner" />
    </slide>
</carousel>
