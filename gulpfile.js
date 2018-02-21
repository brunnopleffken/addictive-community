'use strict';

var gulp = require('gulp');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');
var concat = require('gulp-concat');

gulp.task('styles', () => {
	let files = [
		'static/scss/framework.scss',
		'static/scss/wireframe.scss'
	];

	gulp.src(files)
		.pipe(plumber({
			errorHandler: function(error) {
				console.log(error.message);
				this.emit('end');
			}
		}))
		.pipe(concat('app.min.scss'))
		.pipe(sass({ outputStyle: 'compressed' }))
		.pipe(gulp.dest('static/css/'))
});

gulp.task('default', function(){
	gulp.watch('static/scss/*.scss', ['styles']);
});
