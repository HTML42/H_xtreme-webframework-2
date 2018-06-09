//Config
var config = {
    css: [
        'less/html42/html42_mixins.less',
        'less/settings.less',
        'less/base.less',
        'less/elements/header.less',
        'less/elements/navigation.less',
        'less/elements/box.less',
        'less/elements/buttons.less'
    ],
    js: [
        'js/jquery/jquery-3.1.0.js',
        'js/html42/xtreme.js',
        'js/html42/technologies/list_length.js',
        'js/html42/technologies/ajax_links.js',
        'js/project.js'
    ]
};

//
var gulp = require('gulp');
var watch = require('gulp-watch');
var path = require('path');
var concat = require('gulp-concat');
//
var jshint = require('gulp-jshint');
var jsmin = require('gulp-jsmin');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var gutil = require('gulp-util');
var imagemin = require('gulp-imagemin');
var clean = require('gulp-clean');
var minifyhtml = require('gulp-minify-html');
//
var autoprefixer = require('gulp-autoprefixer');
var minifyCSS = require('gulp-minify-css');
var less = require('gulp-less');
var sourcemaps = require('gulp-sourcemaps');
var base64 = require('gulp-base64');
var imageminPngquant = require('imagemin-pngquant');
var imageminZopfli = require('imagemin-zopfli');
var imageminGiflossy = require('imagemin-giflossy');
var imageminGuetzli = require('imagemin-guetzli');
gulp.task('default', ['clean'], function () {
    gulp.start('default2');
});
gulp.task('default2', ['images'], function () {
    gulp.start('less');
    gulp.start('js');
    console.log('Assets built!');
});

//Gulp Tasks
gulp.task('images', function () {
    var png_1 = imageminPngquant({
        speed: 1,
        quality: 94
    });
    var png_2 = imageminPngquant({
        speed: 1,
        quality: 94
    });
    var png_3 = imageminZopfli({
        more: true
    });
    var gif = imageminGiflossy({
        optimizationLevel: 3,
        optimize: 3,
        lossy: 2
    });
    var svg = imagemin.svgo({
        plugins: [{
                removeViewBox: false
            }]
    });
    var jpg_1 = imagemin.jpegtran({
        progressive: true
    });
    var jpg_2 = imageminGuetzli();
    return gulp.src('./images/**/*')
            .pipe(imagemin([
                png_1, png_2, png_3,
                gif,
                svg,
                jpg_1, jpg_2
            ])).pipe(gulp.dest('./assets/images/'));
});
gulp.task('less', function () {
    return gulp.src(config.css)
            .pipe(concat('styles.css'))
            .pipe(sourcemaps.init())
            .pipe(less())
            .pipe(base64({
                baseDir: 'assets/images/',
                maxImageSize: 10 * 1024
            }))
            .pipe(sourcemaps.write())
            .pipe(autoprefixer(["last 8 version", "> 1%", "ie 8", "ie 7"]), {cascade: true})
            .pipe(gulp.dest('./assets/css/'));
});
gulp.task('js', function () {
    return gulp.src(config.js)
            .pipe(sourcemaps.init())
            .pipe(concat('script.js'))
            .pipe(sourcemaps.write())
            .pipe(gulp.dest('./assets/js/'));
});
//
gulp.task('css-min', function () {
    return gulp.src('./assets/css/styles.css')
            .pipe(concat('styles.min.css'))
            .pipe(minifyCSS())
            .pipe(gulp.dest('./assets/css/'));
});
gulp.task('js-min', function () {
    return gulp.src('./assets/js/*.js')
            .pipe(jsmin())
            .pipe(uglify())
            .pipe(gulp.dest('./assets/js/min/'));
});
gulp.task('clean', function () {
    return gulp.src('./assets/*', {read: false}).pipe(clean());
});
gulp.task('build', ['clean'], function () {
    gulp.start('build2');
});
gulp.task('build2', ['images'], function () {
    gulp.start('build3');
});
gulp.task('build3', ['less', 'js'], function () {
        gulp.start('css-min');
        gulp.start('js-min');
});
gulp.task('watch', ['watch-less', 'watch-js', 'watch-images'], function () {
    console.log('Watching LESS, JS, Images');
});
gulp.task('watch-less', function () {
    gulp.watch('./less/**/*.less', ['less']);
});
gulp.task('watch-js', function () {
    gulp.watch('./js/**/*.js', ['js']);
});
gulp.task('watch-images', function () {
    gulp.watch('./images/**/*', ['images']);
});