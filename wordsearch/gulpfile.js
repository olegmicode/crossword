const gulp = require("gulp");
const sass = require("gulp-sass");
const concat = require("gulp-concat");
const minify = require("gulp-minify");
const uglifyes = require("gulp-uglify-es").default;
const cleanCss = require("gulp-clean-css");
const autoprefixer = require("gulp-autoprefixer");
const del = require("del");

gulp.task("clean-css", function () {
  return del(["css/*.css"]);
});

gulp.task("clean-js", function () {
  return del(["js/*.js"]);
});

gulp.task("pack-css", function () {
  return gulp
    .src(["scss/**/*.scss"])
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(cleanCss())
    .pipe(gulp.dest("css"));
});

gulp.task("pack-js", function () {
  return (
    gulp
      .src(["scripts/vendor/*.js", "scripts/game/*.js", "scripts/*.js"])
      .pipe(concat("bundle.min.js"))
      // .pipe(uglifyes())
      .pipe(gulp.dest("js"))
  );
});

gulp.task("watch", function () {
  gulp.watch("scripts/**/*.js", gulp.series("pack-js"));
  gulp.watch("scss/**/*.scss", gulp.series("pack-css"));
});

gulp.task("default", gulp.series("watch"));
