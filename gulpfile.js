var gulp         = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    concat       = require('gulp-concat'),
    jshint       = require('gulp-jshint'),
    minifycss    = require('gulp-minify-css'),
    rename       = require('gulp-rename'),
    sass         = require('gulp-ruby-sass'),
    uglify       = require('gulp-uglify'),
    del          = require('del');

gulp.task('styles', function() {
  return sass('public/styles/main.sass', { loadPath: ['node_modules/foundation-sites/scss'] })
    .pipe(rename({suffix: '.min'}))
    .pipe(autoprefixer())
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

gulp.task('watch', ['default'], function() {
  gulp.watch('public/styles/**/*.sass', ['styles']);
  gulp.watch('public/scripts/**/*.js', ['scripts']);
});
