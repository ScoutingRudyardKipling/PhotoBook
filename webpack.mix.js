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

mix.js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css')
  .copyDirectory('resources/assets/img', 'public/img')
  .copyDirectory('resources/assets/fonts', 'public/fonts')
  .copy('node_modules/magnific-popup/dist/magnific-popup.css', 'public/css/packages')
  .copy('node_modules/bootstrap-material-design/dist/css/bootstrap-material-design.css', 'public/css/packages')
  .copy('node_modules/bootstrap/dist/css/bootstrap.css', 'public/css/packages')
  // Select2
  .copy('node_modules/select2/dist/js/select2.full.min.js', 'public/js/packages')
  .copy('node_modules/select2/dist/css/select2.min.css', 'public/css/packages');
