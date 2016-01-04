var gulp       = require('gulp'),
    sass       = require('gulp-ruby-sass'),
    minifycss  = require('gulp-minify-css'),
    jshint     = require('gulp-jshint'),
    uglify     = require('gulp-uglify'),
    rename     = require('gulp-rename'),
    concat     = require('gulp-concat'),
    del        = require('del');

gulp.task('styles', function() {
  return sass('public/styles/main.sass')
    .pipe(rename({suffix: '.min'}))
    .pipe(minifycss())
    .pipe(gulp.dest('public/assets'));
});

gulp.task('scripts', function() {
  return gulp.src('public/scripts/**/*.js')
    .pipe(jshint('.jshintrc'))
    .pipe(jshint.reporter('default'))
    .pipe(concat('main.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('public/assets'));
});

gulp.task('clean', function() {
  return del(['public/assets/css', 'public/assets/js']);
});

gulp.task('default', ['clean'], function() {
  gulp.start('styles', 'scripts');
});

gulp.task('watch', function() {
  gulp.watch('public/styles/**/*.sass', ['styles']);
  gulp.watch('public/scripts/**/*.js', ['scripts']);
});
