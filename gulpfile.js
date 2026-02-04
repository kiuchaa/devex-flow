const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const sassGlob = require('gulp-sass-glob');

gulp.task('compile-scss', function () {
    return gulp.src('style.scss') // Change to your main entry file path
        .pipe(sassGlob())        // Translates @import "components/**/*"
        .pipe(sass({
            silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'legacy-js-api'],
            quietDeps: true
        }).on('error', sass.logError))
        .pipe(gulp.dest('./'));  // Outputs the compiled CSS
});

gulp.task('watch', function () {
    gulp.watch('assets/scss/**/*.scss', gulp.series('compile-scss'));
    gulp.watch('style.scss', gulp.series('compile-scss'));
});
