'use strict';

var gulp = require('gulp');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');

gulp.task('styles', () => {
	gulp.src(['static/scss/**/*.scss'])
		.pipe(plumber({
			errorHandler: function(error) {
				console.log(error.message);
				this.emit('end');
			}
		}))
		.pipe(sass({outputStyle: 'compressed'}))
		.pipe(gulp.dest('static/css/'))
});

gulp.task('default', function(){
	gulp.watch('static/scss/**/*.scss', ['styles']);
});
