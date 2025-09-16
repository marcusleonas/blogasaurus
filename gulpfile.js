"use strict";

const gulp = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const cleanCSS = require("gulp-clean-css");
const sourcemaps = require("gulp-sourcemaps");

function buildStyles() {
  return gulp
    .src("./sass/**/*.scss")
    .pipe(sourcemaps.init())
    .pipe(
      sass({
        outputStyle: "expanded",
      }).on("error", sass.logError)
    )
    .pipe(cleanCSS({ level: 2 }))
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest("./css"));
}

exports.buildStyles = buildStyles;
exports.watch = function () {
  gulp.watch("./sass/**/*.scss", buildStyles);
};
