/// <reference path="../typings/jquery/jquery.d.ts"/>
/**
 * ADDICTIVE COMMUNITY
 * -------------------------------------------------------
 * Created by Brunno Pleffken Hosti
 * http://github.com/brunnopleffken/addictive-community
 *
 * File: admin.js
 * Release: v1.0.0
 * Copyright: (c) 2015 - Addictive Software
 */

/* global CodeMirror */


$(document).ready(function() {
	/**
	 * THEMES: CodeMirror when editing CSS files
	 */
	try {
		var textarea = document.getElementById("css");
		var textareaCodeMirror = CodeMirror.fromTextArea(textarea, {
			lineNumbers: true
		});
	} catch(e) {
		console.log(e);
	}

	/**
	 * Check updates
	 */
	(function() {
		$.ajax("https://api.github.com/repos/brunnopleffken/addictive-community/releases/latest", {
			dataType: 'json',
			beforeSend: function() {
				$('.loader').show();
			}
		})
		.done(function(data) {
			if(data) {
				$('.update-message.done span').html(data.name);
				$('.update-message.done').show();
			}
		})
		.fail(function() {
			$('.update-message.fail').show();
		})
		.always(function() {
			$('.loader').hide();
		});
	}).call(this);
});

/**
 * Delete report on Dashboard View
 */
function DeleteReport(id, thread) {
	if(confirm("Are you sure you want to delete the report ID #" + id + "?\nThis action is permanent and cannot be undone.")) {
		location.href = "process.php?do=deletereport&report=" + id + "&thread=" + thread;
	}
	else {
		return false;
	}
}

/**
 * Count remaining characters
 */
function counter(limit) {
	var field = document.getElementById('short_desc');
	var counter = document.getElementById('short_desc_stats');

	var char_number = limit - field.value.length;

	counter.innerHTML = char_number + " characters remaining";
}
