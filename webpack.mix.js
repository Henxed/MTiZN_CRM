const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css').options({
        autoprefixer: { remove: false },
        postCss: [
            require('postcss-import'),
            require('postcss-nested'),
            
            require("tailwindcss"),
        ],
    })
    // .browserSync('a635-145-255-21-216.ngrok.io')
    .webpackConfig(require('./webpack.config'))
if (mix.inProduction()) {
    mix.version();
}
