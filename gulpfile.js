const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const sassGlob = require('gulp-sass-glob');
const cleanCSS = require('gulp-clean-css');

gulp.task('compile-main', function () {
    return gulp.src('style.scss') // Main entry file
        .pipe(sassGlob())        // Translates @import "components/**/*"
        .pipe(sass({
            silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'legacy-js-api'],
            quietDeps: true
        }).on('error', sass.logError))
        .pipe(cleanCSS({ compatibility: 'ie11' }))
        .pipe(gulp.dest('./'));  // Outputs the compiled CSS
});

gulp.task('compile-blocks', function () {
    // Compile individual block styles to /assets/css/blocks/
    // Excludes partials starting with _
    return gulp.src(['assets/scss/blocks/*.scss', '!assets/scss/blocks/_*.scss'])
        .pipe(sassGlob())
        .pipe(sass({
            silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'legacy-js-api'],
            quietDeps: true
        }).on('error', sass.logError))
        .pipe(cleanCSS({ compatibility: 'ie11' }))
        .pipe(gulp.dest('assets/css/blocks/'));
});

gulp.task('compile-scss', gulp.parallel('compile-main', 'compile-blocks'));

gulp.task('watch', function () {
    gulp.watch('assets/scss/**/*.scss', gulp.series('compile-scss'));
    gulp.watch('style.scss', gulp.series('compile-scss'));
});
