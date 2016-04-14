var elixir = require('laravel-elixir');

// relative path base on gulpfile location
elixir.config.viewPath = 'views';
elixir.config.assetsPath = 'assets';

elixir.config.css.outputFolder = '../../../public/assets/app/authenticate/css';
elixir.config.js.outputFolder = '../../../public/assets/app/authenticate/js';

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
    mix.sass('app.scss');
});
