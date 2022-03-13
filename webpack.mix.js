const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .css('node_modules/@jcubic/tagger/tagger.css', 'public/css')
    .sass('node_modules/sweetalert2/src/sweetalert2.scss', 'public/css')
    .sass('node_modules/bootstrap-colorpicker/src/sass/colorpicker.scss', 'public/css')
    .css('node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css', 'public/css')
    .sourceMaps();

mix.scripts([
  'node_modules/sweetalert2/dist/sweetalert2.all.min.js',
  'node_modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js',
], 'public/js/libraries.js');

mix.copy('vendor/proengsoft/laravel-jsvalidation/resources/views', 'resources/views/vendor/jsvalidation')
    .copy('vendor/proengsoft/laravel-jsvalidation/public', 'public/vendor/jsvalidation');
