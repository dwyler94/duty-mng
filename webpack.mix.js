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

mix
    .browserSync({
        files: ['public/**/*.*'],
        proxy: 'localhost'
    })
    .disableNotifications()
    .js('resources/js/app.js', 'public/js')
    .js('resources/childcare/js/app.js', 'public/childcare/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/childcare/sass/app.scss', 'public/childcare/css')
    .sourceMaps()
    .vue();
