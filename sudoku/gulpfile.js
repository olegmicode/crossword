const gulp = require("gulp");
const sass = require("gulp-sass");
const autoprefixer = require("gulp-autoprefixer");
const browserSync = require("browser-sync");
// const webpack = require("webpack-stream");

const paths = {
  styles: {
    src: "./scss/**/*.scss",
    dest: "./css",
  },
  scripts: {
    src: "./js/**/*.js",
    dest: "./js",
  },
};

gulp.task("scss", function () {
  return gulp
    .src(paths.styles.src)
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gulp.dest(paths.styles.dest));
});

// gulp.task("script", function () {
//   return gulp
//     .src(paths.scripts.src)
//     .pipe(webpack(require("./webpack.config.js")))
//     .pipe(gulp.dest(paths.scripts.dest));
// });

gulp.task("serve", function () {
  browserSync.init({
    server: {
      baseDir: "./",
    },
  });

  // gulp.watch(paths.scripts.src, gulp.series("script"));

  gulp.watch(paths.styles.src, gulp.series("scss"));
  gulp.watch(paths.styles.dest + "/*.css").on("change", browserSync.reload);
  gulp.watch(paths.scripts.dest + "/*.js").on("change", browserSync.reload);
  gulp.watch("./*.html").on("change", browserSync.reload);
});
