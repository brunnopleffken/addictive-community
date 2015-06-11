/**
 * ADDICTIVE COMMUNITY
 * -------------------------------------------------------
 * Created by Brunno Pleffken Hosti
 * http://github.com/brunnopleffken/addictive-community
 *
 * File: diagnostics.js
 * License: GPLv2
 * Copyright: (c) 2015 - Addictive Software
 */


$(document).ready(function($) {
	// Failure tests and error messages
	var tests = [
		{ name: 'config-exists', error: 'Have you deleted this file? Upload it again.' },
		{ name: 'config-has-data', error: 'Maybe your config.php file is not writtable.' },
		{ name: 'db-connect', error: 'The data you informed during installation are not valid. Try to reinstall.' },
		{ name: 'db-database', error: 'The database server exists, but the database name doesn\'t match. Try to reinstall.' },
		{ name: 'db-tables', error: 'The database server exists, there are missing tables. Delete all remaining tables (if any) and try to reinstall.' },
		{ name: 'env-apache', error: 'Unfortunately Addictive Community is compatible with Apache server only.' },
		{ name: 'env-php', error: 'You\'re running an outdated version of PHP. You need at least PHP 5.3 or higher.' },
		{ name: 'env-mysql', error: 'You\'re running an outdated version of MySQL. You need at least MySQL v5.5 or higher.' },
		{ name: 'env-mod-rewrite', error: 'You MUST enable \'mod_rewrite\' in order to run Addictive Community.' }
	];

	// Iterate tests
	function run(step) {
		console.log('Running ' + step + ' of ' + tests.length);
		$.ajax("run.php?task=" + tests[step].name, {
			method: 'get',
			dataType: 'json'
		})
		.done(function(data) {
			showResults(tests[step].name, tests[step].error, data.status);
			if(step == tests.length - 1) {
				$('#everything-ok').fadeIn();
			} else {
				if(data.status) {
					step++;
					run(step);
				}
			}
		});
	}

	// Mark SPAN tag as success or failure
	function showResults(step, errorMessage, result) {
		if(result) {
			$('span#' + step).addClass('yes').html('Passed.');
		}
		else {
			$('span#' + step).addClass('no').html(errorMessage);
		}
	}

	// Ok, let's go!
	run(0);
});
