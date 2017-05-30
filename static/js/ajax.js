/**
 * ADDICTIVE COMMUNITY
 * -------------------------------------------------------
 * Created by Brunno Pleffken Hosti
 * https://github.com/addictivehub/addictive-community
 *
 * File: main.js
 * License: GPLv2
 * Copyright: (c) 2017 - Addictive Community
 */


(function() {
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
	 * HTML5 FORM VALIDATION
	 */

	// Add 'novalidate' to all forms because we're using the custom one right below
	$('form').attr('novalidate', true);

	$(document).on('submit', 'form', function(event) {
		var stopSend = false;
		var form = this;

		// Add .error to all invalid text fields
		$(this).find('input:invalid').addClass('error');
		$(this).find('textarea:invalid').addClass('error');

		if(typeof tinymce.editors[0] != 'undefined' && tinymce.editors[0].id == 'post') {
			// Validate TinyMCE textarea
			if(tinymce.get('post').getContent() == "") {
				$(this).find('.mce-edit-area').addClass('error');
				stopSend = true;
			}
			else {
				$(this).find('.mce-edit-area').removeClass('error');
			}
		}

		// Check form validity
		if(!form.checkValidity()) {
			stopSend = true;
		}

		if(stopSend == true) {
			event.preventDefault();
			$(this).find('.error-message').fadeIn();
		}
	});

	/**
	 * REMOVE .error CLASS ON FOCUS (FOR input[type=text] ELEMENTS)
	 */

	$('form').on('focus', '.error', function() {
		$(this).removeClass('error');
		$(document).find('.error-message').fadeOut();
	});

}).call(this);
