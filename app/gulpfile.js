/**
 * Created by Victor on 17/10/2016.
 */
/*
 * Dependencias
 */
var gulp = require('gulp'),
    concat = require('gulp-concat'),
    inject = require('gulp-inject'),
    replace = require('gulp-replace'),
    bowerFiles = require('main-bower-files');

/*
 * Tarea por defecto que inicia el proceso de compilacion.
 */
gulp.task('default', ['build', 'htmlIndex', 'components']);


gulp.task('build', function () {
    gulp.src(['src/app.js', 'src/components/**/*.js'])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('public/'))
});

gulp.task('htmlIndex', function () {
    //Moviendo los JS necesarios a la carpeta public
    gulp.src('bower_components/**/*.js')
        .pipe(gulp.dest('public/vendors'));

    //Injectando las dependencias js al archivo index
    gulp.src('src/index.html')
        .pipe(inject(gulp.src(bowerFiles(), {read: false}), {name: 'bower'}))
        .pipe(replace('/bower_components', 'vendors'))
        .pipe(gulp.dest('public/'));
});

gulp.task('components', function () {
    gulp.src('src/components/**/*.html')
        .pipe(gulp.dest('public/components/'));
});
