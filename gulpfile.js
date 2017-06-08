const gulp = require('gulp');
const sass = require('gulp-sass');
const rename = require('gulp-rename');
const sourcemaps = require('gulp-sourcemaps');
const path = require('path');
const fs = require('fs');
const production_mode = process.env.NODE_ENV === 'production';
const frontend_sass_file = path.join(__dirname, 'resources', 'assets', 'sass', 'frontend', 'style.scss');
const frontend_dest_path = path.join(__dirname, 'public', 'css', 'frontend');

// Compile Frontend Sass files from selected themes
gulp.task('css:frontend', function() {

    "use strict";

    let ret_val = null;

    // Compile the sass file if the file exists
    if(fs.existsSync(frontend_sass_file))
    {
        ret_val = gulp.src([frontend_sass_file]);

        if(!production_mode)
            ret_val = ret_val.pipe(sourcemaps.init());

        ret_val = ret_val.pipe(sass({ outputStyle: production_mode ? 'compressed' : 'expanded' }).on('error', sass.logError)).pipe(rename('combined.min.css'));

        if(!production_mode)
            ret_val = ret_val.pipe(sourcemaps.write('.'));

        ret_val = ret_val.pipe(gulp.dest(frontend_dest_path));
    }
    else
    {
        console.log('No style.scss file found.');
    }

    return ret_val;
});

// Watch frontend sass file
gulp.task('css:frontend:watch', function() {
    "use strict";
    return gulp.watch(path.join('resources', 'assets', 'sass', 'frontend', '**', '*.scss'), ['css:frontend']);
});