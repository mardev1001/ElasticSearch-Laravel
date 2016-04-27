var elixir = require('laravel-elixir');
var bower = './resources/assets/vendor/';

//elixir.config.registerWatcher("default", "resources/assets/**");
//elixir.config.sourcemaps = false; //for live version
elixir.config.sourcemaps = true; //for dev version

var gulp = require('gulp'),
    watch = require('gulp-watch');
    
    gulp.task('stream', function () {
        return gulp.src('./resources/assets/sass/*.scss')
            .pipe(watch('scss/**/*.scss'))
            .pipe(gulp.dest('build'));
    });

var cmsScripts = [
    "jquery/dist/jquery.min.js",
    "jquery-ui/jquery-ui.min.js",
    "bootstrap/dist/js/bootstrap.min.js",
    "metisMenu/dist/metisMenu.min.js",
    "chosen/chosen.jquery.js",
    "tinymce/jquery.tinymce.js",
    "js/**",
    //frontend
    
];
// dodat sass
elixir(function(mix) {
    mix
        .sass('app.scss', 'public/css/style.css')
        .scripts(cmsScripts, 'public/js/script.js', bower)
        .version(['public/js/script.js','public/css/style.css'])
        .copy(bower + 'bootstrap/fonts/', 'public/build/fonts/')
        .copy(bower + 'font-awesome/fonts/', 'public/build/fonts/')
        .copy(bower + 'jquery-ui/themes/base/images/', 'public/img/jquery-ui')
        .copy(bower + 'chosen/chosen-sprite.png', 'public/build/css/')
        .copy(bower + 'chosen/chosen-sprite@2x.png', 'public/build/css/')
        .copy(bower + 'img/', 'public/img/');

});