var gulp         = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    concat       = require('gulp-concat'),
    pug          = require('gulp-pug'),
    jshint       = require('gulp-jshint'),
    minifycss    = require('gulp-clean-css'),
    rename       = require('gulp-rename'),
    sass         = require('gulp-ruby-sass'),
    uglify       = require('gulp-uglify'),
    del          = require('del'),
    rmEmptyDirs  = require('remove-empty-directories'),
    argv         = require('yargs').argv,

    gulpif       = require('gulp-if');

var offline      = argv.offline,
    isProduction = argv.production;

var config = {
  styles : {
    src      : 'src/styles/main.sass',
    dest     : 'dist/styles',
    watch    : 'src/styles/**/*.{sass,scss}',
    loadPath : [
      'node_modules/foundation-sites/scss',
      'node_modules/font-awesome/scss',
      'node_modules/toastr',
    ],
  },
  scripts : {
    src      : 'src/scripts/**/*.js',
    dest     : 'dist/scripts',
    name     : 'main.min.js',
    loadPath : 'node_modules/toastr/toastr.js',
    offline  : 'src/offline-scripts/*.js',
  },
  templates : {
    src   : [
      'src/**/*.pug',
      '!src/**/_*.pug',
    ],
    dest  : 'dist',
    watch : 'src/**/*.pug',
  },
  static : {
    src  : [
      'src/**/*',
      'src/**/.*',
      '!src/**/*.{sass,scss,js,pug}',
    ],
    dest : 'dist',
  },
  fonts : {
    src  : 'node_modules/font-awesome/fonts/*',
    dest : 'dist/fonts',
  },
  dest : 'dist',
};

gulp.task('styles', function() {
  return sass(config.styles.src, { loadPath: config.styles.loadPath })
    .on('error', sass.logError)
    .pipe(rename({suffix: '.min'}))
    .pipe(autoprefixer())
    .pipe(gulpif(isProduction, minifycss()))
    .pipe(gulp.dest(config.styles.dest));
});

gulp.task('scripts', function() {
  var src = [];
  if (offline) {
    src.push(config.scripts.offline);
  }
  src.push(config.scripts.loadPath, config.scripts.src);

  return gulp.src(src)
    .pipe(concat(config.scripts.name))
    .pipe(gulpif(isProduction, uglify()))
    .pipe(gulp.dest(config.scripts.dest));
});

gulp.task('jsLint', function() {
  return gulp.src(config.scripts.src)
    .pipe(jshint('.jshintrc'))
    .pipe(jshint.reporter('default'));
});

gulp.task('templates', function() {
  var options = {};

  if (!isProduction) {
    options.pretty = '    ';
  }

  return gulp.src(config.templates.src)
    .pipe(pug(options))
    .pipe(gulp.dest(config.templates.dest));
});

gulp.task('static', ['copy'], function() {
  return rmEmptyDirs(config.static.dest);
});

gulp.task('copy', ['copyStatic'], function() {
  return gulp.src(config.fonts.src)
    .pipe(gulp.dest(config.fonts.dest));
});

gulp.task('copyStatic', function() {
  return gulp.src(config.static.src)
    .pipe(gulp.dest(config.static.dest));
});

gulp.task('clean', function() {
  return del(config.dest);
});

gulp.task('default', ['build', 'watch']);

gulp.task('build', ['clean'], function() {
  return gulp.start(['styles', 'jsLint', 'scripts', 'templates', 'static']);
});

gulp.task('watch', function() {
  gulp.watch(config.styles.watch, ['styles']);
  gulp.watch(config.scripts.src, ['scripts']);
  gulp.watch(config.templates.watch, ['templates']);
  gulp.watch(config.static.src, ['static']);
});
