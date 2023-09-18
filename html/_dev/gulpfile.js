import gulp from 'gulp';
import postcss from 'gulp-postcss';
import livereload from 'gulp-livereload';
import babel from 'gulp-babel';

import gulpSass from 'gulp-sass';
import * as dartSass from 'sass';

const sass = gulpSass(dartSass);

function devSCSS() {
  return gulp.src('scss/*.scss')
    .pipe(sass({
      //includePaths: ['node_modules']
    }).on('error', sass.logError))
    .pipe(postcss())
    .pipe(gulp.dest('../static/css'))
    .pipe(livereload())
}

function devJS() {
  return gulp.src('js/*.js')
    .pipe(babel())
    .pipe(gulp.dest('../static/js'))
    .pipe(livereload())
}

function devHTML() {
  return gulp.src('html/**/*.html.twig')
    .pipe(livereload())
}

function devMaterialIcons() {
  return gulp.src('node_modules/material-design-icons/iconfont/*.{css,eot,woff,woff2,svg,ttf}')
    .pipe(gulp.dest('../static/ico'))
    .pipe(livereload())
}

function livereloadListen(cb) {
  livereload.listen({ port: 35729 });
  cb();
}

function watch(cb) {
  gulp.watch('scss/**/*.scss', gulp.series(devSCSS));
  gulp.watch('js/**/*.js', gulp.series(devJS));
  gulp.watch('html/**/*.html.twig', gulp.series(devHTML));

  cb();
}

const dev = gulp.parallel(devJS, devSCSS, devMaterialIcons, devHTML);

export default  gulp.series(dev, watch, livereloadListen);
