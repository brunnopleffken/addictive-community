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
		plugins: ['link image'],
		menubar: false,
		selector: 'textarea',
		statusbar: false,
		toolbar: 'bold italic underline strikethrough | alignleft aligncenter alignright | link image | bullist numlist | blockquote | subscript superscript | removeformat'
	});
});
