var gulp         = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    concat       = require('gulp-concat'),
    jade         = require('gulp-jade'),
    jshint       = require('gulp-jshint'),
    minifycss    = require('gulp-minify-css'),
    rename       = require('gulp-rename'),
    sass         = require('gulp-ruby-sass'),
    uglify       = require('gulp-uglify'),
    del          = require('del'),
    rmEmptyDirs  = require('remove-empty-directories'),

    gulpif       = require('gulp-if');

var applicationEnv = process.env.APPLICATION_ENV || 'production',
    isProduction   = applicationEnv == 'production';


var config = {
  styles: {
    src: 'src/styles/main.sass',
    dest: 'dist/styles',
    watch: 'src/styles/**/*.{sass,scss}',
  },
  scripts: {
    src: 'src/scripts/**/*.js',
    dest: 'dist/scripts',
    name: 'main.min.js',
  },
  templates: {
    src: 'src/**/*.jade',
    dest: 'dist',
  },
  static: {
    src: ['src/**/*', 'src/**/.*', '!src/**/*.{sass,scss,js,jade}'],
    dest: 'dist',
  },
  dest: 'dist',
};

gulp.task('styles', function() {
  return sass(config.styles.src, { loadPath: ['node_modules/foundation-sites/scss'] })
    .on('error', sass.logError)
    .pipe(rename({suffix: '.min'}))
    .pipe(autoprefixer())
    .pipe(gulpif(isProduction, minifycss()))
    .pipe(gulp.dest(config.styles.dest));
});

gulp.task('scripts', function() {
  return gulp.src(config.scripts.src)
    .pipe(jshint('.jshintrc'))
    .pipe(jshint.reporter('default'))
    .pipe(concat(config.scripts.name))
    .pipe(gulpif(isProduction, uglify()))
    .pipe(gulp.dest(config.scripts.dest));
});

gulp.task('templates', function() {
  var options = {}

  if (!isProduction) {
    options.pretty = '    ';
  }

  return gulp.src(config.templates.src)
    .pipe(jade(options))
    .pipe(gulp.dest(config.templates.dest));
});

gulp.task('static', ['copyStatic'], function() {
  return rmEmptyDirs(config.static.dest);
});

gulp.task('copyStatic', function() {
  return gulp.src(config.static.src)
    .pipe(gulp.dest(config.static.dest));
});

gulp.task('clean', function() {
  return del(config.dest);
});

gulp.task('default', ['build', 'watch']);

gulp.task('build', ['styles', 'scripts', 'templates', 'static', 'clean']);

gulp.task('watch', function() {
  gulp.watch(config.styles.watch, ['styles']);
  gulp.watch(config.scripts.src, ['scripts']);
  gulp.watch(config.templates.src, ['templates']);
  gulp.watch(config.static.src, ['static']);
});
