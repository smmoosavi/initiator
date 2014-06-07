var gulp = require('gulp');
var less = require('gulp-less');
var path = require('path');
var compiler = require('gulp-hogan-compile');
var shell = require('gulp-shell');


gulp.task('model', shell.task([
    'php packages/bin/doctrine orm:generate-entities src'
]));
gulp.task('proxies', shell.task([
    'php packages/bin/doctrine orm:generate-proxies'
]));
gulp.task('create-db', shell.task([
    'php packages/bin/doctrine orm:schema-tool:create'
]));
gulp.task('update-db', shell.task([
    'php packages/bin/doctrine orm:schema-tool:update --force'
]));
gulp.task('sql', shell.task([
    'echo "------------------- create ------------------- "',
    'php packages/bin/doctrine orm:schema-tool:create --dump-sql',
    'echo "------------------- update ------------------- "',
    'php packages/bin/doctrine orm:schema-tool:update --dump-sql',
    'echo "-------------------- drop -------------------- "',
    'php packages/bin/doctrine orm:schema-tool:drop --dump-sql'
]));


gulp.task('msgfmt', function () {
    return gulp.src('locale/*', {read: false})
        .pipe(shell([
            'msgfmt -cv -o <%= file.path %>/LC_MESSAGES/messages.mo <%= file.path %>/LC_MESSAGES/messages.po'
        ], {quiet: true}));
});

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
    gulp.watch('./locale/**/*', ['msgfmt']);
});
gulp.task('default', ['less', 'templates', 'msgfmt', 'watch'], function () {
    // place code for your default task here
});
