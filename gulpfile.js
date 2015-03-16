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

elixir(function(mix)
{
    // ALL STYLES 
    // This includes less, sass and native css
    mix.less( 'app.less', 'public/css/' )
       .copy( 'vendor/animate.css/animate.min.css', 'public/css/animate.min.css' );
       
});

elixir(function(mix)
{
    // ALL COPY
    mix.copy( 'vendor/jquery/dist/', 'public/vendor/jquery/' )
       .copy( 'vendor/angular/', 'public/vendor/angular/' )
       .copy( 'vendor/bootstrap/dist/', 'public/vendor/bootstrap/' )
       .copy( 'vendor/font-awesome/', 'public/vendor/font-awesome/' );
       // .copy( 'storage/framework/resources/css/', 'public/css/' );
});