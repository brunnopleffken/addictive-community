/**
 * ADDICTIVE COMMUNITY
 * -------------------------------------------------------
 * Created by Brunno Pleffken Hosti
 * https://github.com/addictivehub/addictive-community
 *
 * File: admin.js
 * License: GPLv2
 * Copyright: (c) 2017 - Addictive Community
 */

/* global $, CodeMirror */


$(document).ready(function() {
	/**
	 * THEMES: CodeMirror when editing CSS files
	 */
	try {
		var textarea = document.getElementById("css");
		CodeMirror.fromTextArea(textarea, {
			lineNumbers: true
		});
	} catch(e) {
		console.log(e);
	}

	/**
	 * Automatic confirmation message when using "data-confirm" attribute
	 */
	(function() {
		$('a[data-confirm]').on('click', function(event) {
			if(!confirm($(this).data("confirm"))) {
				event.preventDefault();
			}
		})
	}).call(this);

	/**
	 * Auto-select navigation bar item
	 */
	(function() {
		var links = document.querySelectorAll('.nav .nav-bottom a');
		[].forEach.call(links, function(link) {
			var elementUrl = new RegExp('(' + (link.href).replace('?', '\\?') + ')$');
			var browserUrl = window.location.href;

			if(elementUrl.test(browserUrl)) {
				link.className = 'active';
			}
		});
	}).call(this);
});

/**
 * Automatic check for updates using GitHub API
 */
function checkUpdates() {
	$.ajax("https://api.github.com/repos/brunnopleffken/addictive-community/releases/latest?access_token=a4b75336277bbeb8be2d004c614158836bbe655b", {
		dataType: 'json',
		beforeSend: function() {
			console.info("Checking for updates...");
			$('.loader').show();
		}
	})
	.done(function(data) {
		if(data) {
			if(versionCompare(data.tag_name.slice(1), $('#current-version').val()) == 1) {
				$('.update-message.done span').html(data.tag_name);
				$('.update-message.done').show();
			}
			else {
				$('.update-message.no-updates').show();
			}
		}
	})
	.fail(function() {
		$('.update-message.fail').show();
	})
	.always(function() {
		$('.loader').hide();
	});
}

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

/**
 * Simply compares two string version values.
 *
 * Example:
 * versionCompare('1.1', '1.2') => -1
 * versionCompare('1.1', '1.1') =>  0
 * versionCompare('1.2', '1.1') =>  1
 * versionCompare('2.23.3', '2.22.3') => 1
 *
 * Returns:
 * -1 = left is LOWER than right
 *  0 = they are equal
 *  1 = left is GREATER; right is LOWER
 *  And FALSE if one of input versions are not valid
 */
function versionCompare(left, right) {
	if (typeof left + typeof right != 'stringstring') {
		return false;
	}

	var a = left.split('.');
	var b = right.split('.');
	var len = Math.max(a.length, b.length);

	for (var i = 0; i < len; i++) {
		if ((a[i] && !b[i] && parseInt(a[i]) > 0) || (parseInt(a[i]) > parseInt(b[i]))) {
			return 1;
		} else if ((b[i] && !a[i] && parseInt(b[i]) > 0) || (parseInt(a[i]) < parseInt(b[i]))) {
			return -1;
		}
	}

	return 0;
}

/**
 * Select custom rules when creating a new room
 */
function CustomRulesSelect() {
	var checkbox = document.getElementById('rules_visible');
	var rules_title = document.getElementById('rules_title');
	var rules_text = document.getElementById('rules_text');

	if(checkbox.checked) {
		rules_title.disabled = false;
		rules_text.disabled = false;
	}
	if(checkbox.checked == false) {
		rules_title.disabled = true;
		rules_text.disabled = true;
	}
}
