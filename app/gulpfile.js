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
    str = require('string-to-stream'),
    source = require('vinyl-source-stream'),
    bowerFiles = require('main-bower-files');

/*
 * Tarea por defecto que inicia el proceso de compilacion.
 */
gulp.task('default', ['build', 'htmlIndex', 'components', 'shared', 'assets', 'htaccess']);


gulp.task('build', function () {
    gulp.src(['src/app.js', 'src/components/**/*.js', 'src/shared/**/*.js'])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('public/'))
});

gulp.task('htmlIndex', function () {
    //Moviendo los JS necesarios a la carpeta public
    gulp.src(['bower_components/**/*.js', 'bower_components/**/*.map', 'bower_components/**/*.css'])
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

gulp.task('shared', function () {
    gulp.src('src/shared/**/*.html')
        .pipe(gulp.dest('public/shared/'));
});

gulp.task('assets', function () {
    gulp.src('assets/**/*.*')
        .pipe(gulp.dest('public/assets'));
});

//Tarea que crea el archivo htaccess en la carpeta public
gulp.task('htaccess', function () {
    var str_text = "RewriteEngine On\n"
        + "  # If an existing asset or directory is requested go to it as it is\n"
        + "  RewriteCond %{DOCUMENT_ROOT}%{REQUEST_URI} -f [OR]\n"
        + "  RewriteCond %{DOCUMENT_ROOT}%{REQUEST_URI} -d\n"
        + "  RewriteRule ^ - [L]\n"
        + "  # If the requested resource doesn't exist, use index.html\n"
        + "  RewriteRule ^ /index.html";
    str(str_text).pipe(source('.htaccess')).pipe(gulp.dest('public'));
});