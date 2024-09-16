const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .vue() // Ensure Vue.js support is enabled here
   .sass('resources/sass/app.scss', 'public/css');
