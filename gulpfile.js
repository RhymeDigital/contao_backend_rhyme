'use strict';

var gulp = require('gulp'),
    csso = require('gulp-csso'),
    ignore = require('gulp-ignore'),
    rename = require('gulp-rename'),
    svgo = require('gulp-svgo'),
    uglify = require('gulp-uglify'),
    pump = require('pump');


gulp.task('minify-theme-js', function (cb) {
    pump(
        [
            gulp.src('src/Resources/contao/themes/rhyme/*.js'),
            ignore.exclude('*.min.js'),
            uglify(),
            rename({
                suffix: '.min'
            }),
            gulp.dest('src/Resources/contao/themes/rhyme')
        ],
        cb
    );
});

gulp.task('minify-theme-css', function (cb) {
    pump(
        [
            gulp.src('src/Resources/contao/themes/rhyme/*.css'),
            ignore.exclude('*.min.css'),
            csso({
                comments: false,
                restructure: false
            }),
            rename({
                suffix: '.min'
            }),
            gulp.dest('src/Resources/contao/themes/rhyme')
        ],
        cb
    );
});

gulp.task('minify-theme-icons', function (cb) {
    pump(
        [
            gulp.src('src/Resources/contao/themes/rhyme/icons/*.svg'),
            svgo(),
            gulp.dest('src/Resources/contao/themes/rhyme/icons')
        ],
        cb
    );
});

gulp.task('watch', function () {

    gulp.watch(
        [
            'src/Resources/contao/themes/rhyme/*.js',
            '!src/Resources/contao/themes/rhyme/*.min.js'
        ],
        gulp.series('minify-theme-js')
    );

    gulp.watch(
        [
            'src/Resources/contao/themes/rhyme/*.css',
            '!src/Resources/contao/themes/rhyme/*.min.css'
        ],
        gulp.series('minify-theme-css')
    );

    gulp.watch('src/Resources/contao/themes/rhyme/icons/*.svg', gulp.series('minify-theme-icons'));
});

gulp.task('default', gulp.parallel('minify-theme-js', 'minify-theme-css', 'minify-theme-icons'));