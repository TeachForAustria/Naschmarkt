var elixir = require('laravel-elixir');
var gulp = require('gulp');

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
    mix
        .sass('app.scss')
        .sass('pages/activate.scss', 'public/css/pages')
        .sass('pages/posts.scss', 'public/css/pages')
        .sass('pages/post.scss', 'public/css/pages')
        .sass('partials/editor.scss', 'public/css/partials');
});
