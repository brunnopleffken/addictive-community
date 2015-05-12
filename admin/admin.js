/**
 * ADDICTIVE COMMUNITY
 * -------------------------------------------------------
 * Created by Brunno Pleffken Hosti
 * http://github.com/brunnopleffken/addictive-community
 *
 * File: main.js
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
});