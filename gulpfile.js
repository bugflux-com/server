var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('default.scss');
    mix.browserify('default.js');

    mix.copy('resources/assets/img/', 'public/img/');
    mix.copy('resources/assets/lib/icomoon/style.css', 'public/css/icomoon.css');
    mix.copy('resources/assets/lib/icomoon/fonts/', 'public/css/fonts/');
    mix.copy('resources/assets/lib/roboto/', 'public/css/fonts/');
    mix.copy('resources/assets/lib/jquery/jquery-3.1.1.min.js', 'public/js/jquery.js');
    mix.copy('resources/assets/lib/chart/chart.min.js', 'public/js/chart.js');
    mix.copy('resources/assets/lib/moment/moment.min.js', 'public/js/moment.js');

    mix.version(['css/default.css', 'js/default.js']);
    mix.browserSync({ proxy: 'bugflux-server' });
});
