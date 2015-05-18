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


$(document).ready(function() {
	/**
	 * REPLACE ALL REGULAR <select> FIELDS WITH A FANCY ONE
	 */

	$('.select2').select2({ 'width': 'element' });
	$('.select2-no-search').select2({ 'minimumResultsForSearch': -1, 'width': 'element' });

	/**
	 * LOGIN - VALIDATE USERNAME AND PASSWORD BEFORE SEND
	 */

	$('#memberLoginForm').on('submit', function(event) {
		var $form      = $(this),
		    $userField = $('#memberLoginForm .username'),
		    $passField = $('#memberLoginForm .password'),
		    $submit    = $('#memberLoginForm input[type=submit]'),
		    stopSend   = false,
		    timer;

		// Prevent form's default behavior
		event.preventDefault();

		$.ajax({
			url: 'login/validate',
			type: 'post',
			dataType: 'json',
			data: { username: $userField.val(), password: $passField.val() },
			context: $form
		})
		.done(function(data){
			if(data.authenticated == false) {
				stopSend = true;

				$userField.addClass('error');
				$passField.addClass('error');

				$submit.attr('disabled', 'disabled').val(data.message);

				clearTimeout(timer);
				timer = setTimeout(function() {
					$('#memberLoginForm input[type=submit]').removeAttr('disabled');
					$('#memberLoginForm input[type=submit]').val('Log In');
				}, 3000);
			}
		})
		.fail(function(jqXHR, textStatus) {
			// In case of errors, show on console
			console.error(textStatus);
		})
		.always(function() {
			// If there is no errors, submit!
			if(stopSend == false) {
				this.off('submit');
				this.submit();
			}
		});
	});

	/**
	 * CSS CLASSES FOR VALIDATION
	 * input.url      Validates http://xxx.com or http://xxx.com.br
	 * input.email    Validates me@me.com or me@me.com.br
	 * input.numeric  Validates if the value is numeric only
	 */

	$('form').on('submit', function(event) {
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
				var pattern = new RegExp(/^[a-z0-9_\.\-]+\@[a-z0-9_\.\-]+\.[a-z]{2,3}$/gm);
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

			// Alphanumerics ONLY

			$(this).find('.alphanumeric').filter('.required').each(function(){
				var str = this.value;
				var pattern = new RegExp(/([a-zA-Z0-9\s]*)/);
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
				event.preventDefault();
				$(this).find('.error-message').css('display', 'inline-block').hide().fadeIn();
			}
		}
	});

	/**
	 * REMOVE .error CLASS ON FOCUS (FOR input[type=text] ELEMENTS)
	 */

	$(document).on('focus', '.error', function() {
		$(this).removeClass('error');
	});
});
