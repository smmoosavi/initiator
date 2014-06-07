var gulp = require('gulp');
var less = require('gulp-less');
var path = require('path');
var compiler = require('gulp-hogan-compile');

gulp.task('templates', function () {
    gulp.src('private/templates/*.hbs')
        .pipe(compiler('templates.js', {wrapper: false}))
        .pipe(gulp.dest('statics/js/'));
});

gulp.task('less', function () {
    gulp.src('./private/less/style.less')
        .pipe(less({
            paths: [path.join(__dirname, 'private/less')]
        }))
        .pipe(gulp.dest('./statics/css/'))
});
gulp.task('watch', function () {
    gulp.watch('./private/less/style.less', ['less']);
    gulp.watch('./private/templates/**/*', ['templates']);
});
gulp.task('default', ['less','templates', 'watch'], function () {
    // place code for your default task here
});
