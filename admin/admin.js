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
	var textarea = document.getElementById("css");
	var textareaCodeMirror = CodeMirror.fromTextArea(textarea, {
		lineNumbers: true
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
});