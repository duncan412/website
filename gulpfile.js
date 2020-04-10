var gulp = require('gulp');
var sass = require('gulp-sass');
var paths = {
    source: './dist',
    destination: './public/assets/'
};

gulp.task('sass', function(cb) {
  gulp
    .src(paths.source + '/**/*.scss')
    .pipe(sass())
    .pipe(gulp.dest(paths.destination));
  cb();
});

gulp.task(
  'default',
  gulp.series('sass', function(cb) {
    gulp.watch(paths.source + '/**/*.scss', gulp.series('sass'));
    cb();
  })
);