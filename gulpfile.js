var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir.config.sourcemaps = false;

var paths = {
  vendorDir: './vendor/bower_components/',
  storage: {
    js: './storage/framework/resources/js/'
  }
};

elixir(function(mix)
{
  mix.coffee('*.coffee', paths.storage.js );
});

elixir(function(mix)
{
  // ALL SCRIPTS
  // This includes build from coffeescript, vendor js
  mix.scripts([
      paths.vendorDir + 'angular-animate/angular-animate.js',
      paths.vendorDir + 'nprogress/nprogress.js',
      paths.vendorDir + 'angular-ui-router/release/angular-ui-router.js',
      paths.vendorDir + 'angular-bootstrap/ui-bootstrap-tpls.js',
      paths.vendorDir + 'dropzone/dist/dropzone.js',
      paths.vendorDir + 'ng-currency/dist/ng-currency.js',
      paths.vendorDir + 'angular-touch/angular-touch.js',
      paths.vendorDir + 'angular-loading-bar/src/loading-bar.js',
      paths.vendorDir + 'angular-bootstrap-lightbox/dist/angular-bootstrap-lightbox.js',
      paths.vendorDir + 'angular-local-storage/dist/angular-local-storage.js',
      paths.vendorDir + 'angular-slugify/angular-slugify.js',
      paths.vendorDir + 'textAngular/dist/textAngular-rangy.min.js',
      paths.vendorDir + 'textAngular/dist/textAngular-sanitize.min.js',
      paths.vendorDir + 'textAngular/dist/textAngular.min.js',
      paths.vendorDir + 'angular-ui-notification/src/angular-ui-notification.js',
      paths.vendorDir + 'angular-ui-select/dist/select.js',
      paths.vendorDir + 'ng-tags-input/ng-tags-input.js',
      paths.vendorDir + 'angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.js'
    ], 'public/js/vendor.js', paths.vendorDir )

  .scripts([
    paths.storage.js + 'namespace.js',
    paths.storage.js + 'app_routes.js',
    paths.storage.js + 'app_*.js',
  ], 'public/js/scripts.js', paths.storage.js )
  .scripts([
    paths.storage.js + 'admin_*.js'
  ], 'public/js/admin.js', paths.storage.js )
  .scripts([
    paths.storage.js + 'user_*.js'
  ], 'public/js/user.js', paths.storage.js );

});

elixir(function(mix)
{
  // ALL STYLES 
  // This includes less, sass and native css
  mix.less( 'app.less', 'public/css/' )
    .styles([
      paths.vendorDir + 'animate.css/animate.css',
      paths.vendorDir + 'dropzone/dist/dropzone.css',
      paths.vendorDir + 'angular-bootstrap-lightbox/dist/angular-bootstrap-lightbox.css',
      paths.vendorDir + 'angular-loading-bar/src/loading-bar.css',
      paths.vendorDir + 'angular-ui-select/dist/select.css',
      paths.vendorDir + 'select2/select2.css',
      paths.vendorDir + 'ng-tags-input/ng-tags-input.css',
      paths.vendorDir + 'ng-tags-input/ng-tags-input.bootstrap.css'
    ], 'public/css/vendor.css', paths.vendorDir );
});


elixir(function(mix)
{
  // All public vendors
  // ALL COPY
  mix.copy( paths.vendorDir + 'jquery/dist/', 'public/vendor/jquery/' )
     .copy( paths.vendorDir + 'angular/', 'public/vendor/angular/' )
     .copy( paths.vendorDir + 'bootstrap/dist/', 'public/vendor/bootstrap/' )
     .copy( paths.vendorDir + 'font-awesome/', 'public/vendor/font-awesome/' );
     // .copy( 'storage/framework/resources/css/', 'public/css/' );
});
