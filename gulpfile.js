var elixir = require('laravel-elixir');
var bower = './resources/assets/vendor/';

//elixir.config.registerWatcher("default", "resources/assets/**");
elixir.config.sourcemaps = false; //for live version
// elixir.config.sourcemaps = true; //for dev version

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
    // "metisMenu/dist/metisMenu.min.js",
    "chosen/chosen.jquery.js",
    // "js/custom-plugins/modernizr-custom.js",
    // "tinymce/tinymce.js",// or plain tinymce.js
    "js-modified-plugins/**",
    "tinymce/themes/modern/theme.js",
    // "js-uploaded-plugins/themes/modern/theme.js",
    "bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js",
    "moment/min/moment-with-locales.min.js",
    "eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js",
    // "bootstrap-treeview/public/js/bootstrap-treeview.js",
    "datatables/media/js/jquery.dataTables.js",
    "twitter-bootstrap-wizard/jquery.bootstrap.wizard.js",
    "js/**",
    //frontend
    
];
// dodat sass
elixir(function(mix) {
    mix
        .sass('app.scss', 'public/css/style.css')
        .styles(cmsScripts, 'public/js/script.js', bower)
        .version(['public/js/script.js','public/css/style.css'])
        .copy(bower + 'bootstrap/fonts/', 'public/build/fonts/')
        .copy(bower + 'font-awesome/fonts', 'public/build/fonts/')
        .copy(bower + 'jquery-ui/themes/base/images/', 'public/img/jquery-ui')
        .copy(bower + 'chosen/chosen-sprite.png', 'public/build/css/')
        .copy(bower + 'chosen/chosen-sprite@2x.png', 'public/build/css/')
        .copy(bower + 'tinymce/skins/lightgray/fonts/', 'public/style/css/fonts/')
        .copy(bower + 'tinymce/skins/lightgray/img/', 'public/build/css/img/')
        .copy(bower + 'tinymce/skins/lightgray/skin.min.css', 'public/css/style/')
        .copy(bower + 'tinymce/skins/lightgray/content.min.css', 'public/css/style/')
        .copy(bower + 'tinymce/skins/lightgray/fonts/', 'public/css/style/fonts/')
        // .copy(bower + 'tinymce/js/langs/', 'public/js/langs')
        .copy(bower + 'js-modified-plugins/tinymce-plugins/table/plugin.js', 'public/build/js/plugins/table/')
        .copy(bower + 'js-modified-plugins/tinymce-plugins/link/plugin.js', 'public/build/js/plugins/link/')
        .copy(bower + 'tinymce/plugins/spellchecker/', 'public/build/js/plugins/spellchecker/')
        .copy(bower + 'tinymce/plugins/anchor/', 'public/build/js/plugins/anchor/')
        // .copy(bower + 'js-modified-plugins/tinymce-plugins/nanospell/', 'public/build/js/plugins/nanospell/')
        /*.copy(bower + 'js-uploaded-plugins/imageupload/', 'public/build/js/plugins/imageupload/')
        .copy(bower + 'js-uploaded-plugins/reposnisve-file-manager/filemanager/', 'public/build/js/filemanager/')
        .copy(bower + 'js-uploaded-plugins/reposnisve-file-manager/filemanager/', 'public/js/filemanager/')
        .copy(bower + 'js-uploaded-plugins/reposnisve-file-manager/tinymce/plugins/responsivefilemanager/', 'public/build/js/plugins/responsivefilemanager/')
        .copy(bower + 'tinymce/plugins/media/', 'public/build/js/plugins/media/')
        .copy(bower + 'tinymce/plugins/advlist/', 'public/build/js/plugins/advlist/')
        .copy(bower + 'tinymce/plugins/lists/', 'public/build/js/plugins/lists/')
        .copy(bower + 'tinymce/plugins/charmap/', 'public/build/js/plugins/charmap/')
        .copy(bower + 'tinymce/plugins/autolink/', 'public/build/js/plugins/autolink/')
        .copy(bower + 'tinymce/plugins/print/', 'public/build/js/plugins/print/')
        .copy(bower + 'tinymce/plugins/pagebreak/', 'public/build/js/plugins/pagebreak/')
        .copy(bower + 'tinymce/plugins/preview/', 'public/build/js/plugins/preview/')
        .copy(bower + 'tinymce/plugins/hr/', 'public/build/js/plugins/hr/')
        .copy(bower + 'tinymce/plugins/searchreplace/', 'public/build/js/plugins/searchreplace/')
        .copy(bower + 'tinymce/plugins/wordcount/', 'public/build/js/plugins/wordcount/')
        .copy(bower + 'tinymce/plugins/nonbreaking/', 'public/build/js/plugins/nonbreaking/')
        .copy(bower + 'tinymce/plugins/noneditable/', 'public/build/js/plugins/noneditable/')
        .copy(bower + 'tinymce/plugins/contextmenu/', 'public/build/js/plugins/contextmenu/')
        .copy(bower + 'tinymce/plugins/directionality/', 'public/build/js/plugins/directionality/')
        .copy(bower + 'tinymce/plugins/visualblocks/', 'public/build/js/plugins/visualblocks/')
        .copy(bower + 'tinymce/plugins/visualchars/', 'public/build/js/plugins/visualchars/')
        .copy(bower + 'tinymce/plugins/paste/', 'public/build/js/plugins/paste/')
        .copy(bower + 'tinymce/plugins/textcolor/', 'public/build/js/plugins/textcolor/')
        .copy(bower + 'tinymce/plugins/emoticons/', 'public/build/js/plugins/emoticons/')*/
        
        .copy(bower + 'tinymce/plugins/image/', 'public/build/js/plugins/image/')
        // .copy(bower + 'tinymce/plugins/image/', 'public/build/js/plugins/image/')
        // .copy(bower + 'tinymce/plugins/image/', 'public/build/js/plugins/image/')
        // .copy(bower + 'js-modified-plugins/justboil-image-upload/plugin.js', 'public/build/js/plugins/')
        .copy(bower + 'datatables/media/images/', 'public/build/images/')
        .copy(bower + 'img/', 'public/img/');

});
