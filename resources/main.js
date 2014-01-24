// ---------------------------------------------------
//  ADDICTIVE COMMUNITY
// ---------------------------------------------------
// Developed by Brunno Pleffken Hosti
// File: main.js
// Release: v1.0.0
// Copyright: (c) 2014 - Addictive Software
// ---------------------------------------------------


jQuery(document).ready(function($) {
	
	// ---------------------------------------------------
	// Replace regular <select> with a nice one!
	// ---------------------------------------------------

	$('.select2').select2();

	// ---------------------------------------------------
	// FORM VALIDATION - REQUIRED FIELDS
	// ---------------------------------------------------
	
	/**
	 * CSS CLASSES FOR VALIDATION
	 * .url		Validates http://xxx.com or http://xxx.com.br
	 * .email	Validates me@me.com or me@me.com.br
	 * .numeric	Validates if the value is numeric only
	 */

	$('form').on('submit', function(e) {
		if($(this).hasClass('validate')) {

			var stopSend = false;
			
			// Validade INPUT text, INPUT password and TEXTAREAs

			$(this).find('input[type=text], input[type=password], textarea, select').filter('.required').each(function(){
				if(this.value == '') {
					$(this).addClass('error');
					stopSend = true;
				}
				else {
					$(this).removeClass('error');
				}
			});
			
			// Is the URL valid?

			$(this).find('.url').filter('.required').each(function(){
				var str = this.value;
				var pattern = new RegExp(/(http:\/\/)([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2,3}/);
				var result = pattern.test(str);

				if(!result) {
					$(this).addClass('error');
					stopSend = true;
				}
				else {
					$(this).removeClass('error');
				}
			});

			// Is the e-mail address valid?

			$(this).find('.email').filter('.required').each(function(){
				var str = this.value;
				var pattern = new RegExp(/[a-z0-9_\.\-]+\@[a-z0-9_\.\-]+\.[a-z]{2,3}/);
				var result = pattern.test(str);

				if(!result) {
					$(this).addClass('error');
					stopSend = true;
				}
				else {
					$(this).removeClass('error');
				}
			});
			
			// Numeric only field

			$(this).find('.numeric').filter('.required').each(function(){
				var str = this.value;
				var pattern = new RegExp(/([0-9]*)/);
				var result = pattern.test(str);

				if(!result) {
					$(this).addClass('error');
					stopSend = true;
				}
				else {
					$(this).removeClass('error');
				}
			});

			// IF there is an error, show message!
			// ...otherwise, send form as it should be

			if(stopSend == true) {
				e.preventDefault();
				$(this).find('.errorMessage').css('display', 'inline-block').hide().fadeIn();
			}

		}
	});

});