// ---------------------------------------------------
//  ADDICTIVE COMMUNITY
// ---------------------------------------------------
// Developed by Brunno Pleffken Hosti
// File: post.js
// Release: v1.0.0
// Copyright: (c) 2014 - Addictive Software
// ---------------------------------------------------


$(document).ready(function() {
	tinymce.init({
		entity_encoding: 'raw',
		link_title: false,
		plugins: ['link image'],
		menubar: false,
		selector: 'textarea',
		statusbar: false,
		target_list: [
			{title: 'New page', value: '_blank'},
		],
		toolbar: 'bold italic underline strikethrough | link image | subscript superscript | removeformat'
	});
});
