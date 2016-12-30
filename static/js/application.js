/**
 * ADDICTIVE COMMUNITY
 * -------------------------------------------------------
 * Created by Brunno Pleffken Hosti
 * http://github.com/brunnopleffken/addictive-community
 *
 * File: main.js
 * License: GPLv2
 * Copyright: (c) 2016 - Addictive Community
 */

/* global $, tinymce */


$(document).ready(function($) {
	'use strict';

	/**
	 * REPLACE ALL REGULAR <select> FIELDS WITH A FANCY ONE
	 */

	$('.select2').select2({ 'width': 'element' });
	$('.select2-no-search').select2({ 'minimumResultsForSearch': -1, 'width': 'element' });

	/**
	 * FADE OUT ALL NOTIFICATIONS AFTER 3 SECONDS IF HAS NOT .persistent CLASS
	 */

	$('.notification').not('.persistent').delay(3000).fadeOut(1000);

	/**
	 * TOGGLE CATEGORIES
	 */

	(function() {
		$('.category').on('click', function() {
			var catId = $(this).data('toggle-id');
			var icon = $(this).find('i');

			$('#category-' + catId).slideToggle();

			if(icon.hasClass('fa-angle-down')) {
				icon.removeClass('fa-angle-down').addClass('fa-angle-left');
			}
			else {
				icon.removeClass('fa-angle-left').addClass('fa-angle-down');
			}
		});
	}).call(this);

	/**
	 * TOGGLE BOXES
	 */

	(function() {
		$('*[data-toggle]').on('click', function() {
			var $this = $(this);
			var arrow = $this.find('i');
			var targetId = $this.data('toggle');

			$('#' + targetId).slideToggle();

			if(arrow.hasClass('fa-angle-down')) {
				arrow.removeClass('fa-angle-down');
				arrow.addClass('fa-angle-right');
			}
			else {
				arrow.removeClass('fa-angle-right');
				arrow.addClass('fa-angle-down');
			}
		});
	}).call(this);

	/**
	 * AUTOMATICALLY SELECT TABS IN THE NAVIGATION BAR
	 */

	(function() {
		$('.navigation .subnav a').each(function() {
			var elementUrl = new RegExp('(' + ($(this).attr('href')).replace('?', '\\?') + ')$');
			var browserUrl = window.location.href;

			if(elementUrl.test(browserUrl)) {
				$(this).addClass('selected');
			}
		});
	}).call(this);

	/**
	 * BUILD LIGHTBOX WHEN LINK HAS .fancybox
	 */

	try {
		$('.fancybox').fancybox({
			autoSize: true,
			closeBtn: false,
			modal: false,
			padding: 2
		});
	} catch(e) {
		console.log(e);
	}

	/**
	 * USER CONTROL PANEL FUNCTIONS
	 */

	(function() {
		if($('.photoSelect:checked').val() == 'gravatar') {
			$('#gravatar').show();
		}
		else if($('.photoSelect:checked').val() == 'custom') {
			$('#custom').show();
		}

		$('.photoSelect').on('change', function() {
			var value = $(this).val();

			if(value == 'custom') {
				$('#gravatar').fadeOut();
				$('#custom').delay(400).fadeIn();
			}
			else if(value == 'gravatar') {
				$('#custom').fadeOut();
				$('#gravatar').delay(400).fadeIn();
			}
		});
	}).call(this);

	/**
	 * CSS CLASSES FOR VALIDATION
	 * input.url      Validates http://xxx.com or http://xxx.com.br
	 * input.email    Validates me@me.com or me@me.com.br
	 * input.numeric  Validates if the value is numeric only
	 */

	$('form').attr('novalidate', true);

	$(document).on('submit', 'form', function(event) {
		var stopSend = false;
		var form = this;

		$('input:invalid').addClass('error');

		try {
			// Validate TinyMCE textarea
			if(tinymce.get('post').getContent() == "") {
				$('.mce-edit-area').addClass('error');
				stopSend = true;
			}
			else {
				$('.mce-edit-area').removeClass('error');
			}
		}
		catch(e) {
			console.log(e);
		}

		// Check form validity
		if(!form.checkValidity()) {
			stopSend = true;
		}

		if(stopSend == true) {
			event.preventDefault();
			$(this).find('.error-message').css('display', 'inline-block').hide().fadeIn();
		}
	});

	/**
	 * REMOVE .error CLASS ON FOCUS (FOR input[type=text] ELEMENTS)
	 */

	$(document).on('focus', '.error', function() {
		$(this).removeClass('error');
	});

	/**
	 * REPLACE INPUT[FILE] FIELDS WITH A CUSTOM ONE
	 */

	(function() {
		$('input[type=file]').each(function() {
			var $field = $(this);

			// Hide input[file] field
			$field.hide();

			// Add an text field and a button
			$field.parent().append('<input type="text" name="attachment_filename" id="attachment_filename" class="form-control" style="width: calc(100% - 110px)" readonly>');
			$field.parent().append('<button id="attachment_button" class="btn btn-default" style="margin-left: 10px; width: 100px"><i class="fa fa-upload"></i> Upload</button>');

			// Also add .required if input[file] has .required class
			if($field.hasClass('required')) {
				$('#attachment_filename').addClass('required');
			}
		});

		$(document).on('click', 'button#attachment_button', function(event) {
			$(this).parent().find('input[type=file]').click();
			event.preventDefault();
		});
		$(document).on('change', 'input[type=file]', function() {
			var $val = $(this).val().split('\\');
			$('input#attachment_filename').val($val[$val.length - 1]);
		});
	}).call(this);

	/**
	 * LOAD COMPLETE TINYMCE ON THREAD/REPLY POSTS
	 */

	(function() {
		try {
			tinymce.init({
				element_format: 'html',
				entity_encoding: 'raw',
				link_title: false,
				default_link_target: "_blank",
				target_list: false,
				plugins: ['textcolor lists link image media'],
				menubar: false,
				selector: '#post',
				statusbar: false,
				media_alt_source: false,
				toolbar: 'bold italic underline strikethrough | forecolor | alignleft aligncenter alignright | link image media | bullist numlist | subscript superscript | removeformat'
			});
		} catch(e) {
			console.log(e);
		}
	}).call(this);

	/**
	 * LOAD REDUCED TINYMCE FOR MEMBER SIGNATURES
	 */

	(function() {
		try {
			tinymce.init({
				entity_encoding: 'raw',
				link_title: false,
				plugins: ['link image'],
				menubar: false,
				selector: '#signature',
				statusbar: false,
				target_list: [
					{title: 'New page', value: '_blank'},
				],
				toolbar: 'bold italic underline strikethrough | link image | subscript superscript | removeformat'
			});
		} catch(e) {
			console.log(e);
		}
	}).call(this);

	/**
	 * MESSENGER: DELETE SELECTED MESSAGES
	 */

	$('#delete-messages').on('click', function(event) {
		if($('.del-message-checkbox:checked').length == 0) {
			alert('You need to select at least one message.');
			event.preventDefault();
		}
		else {
			$('form.personal-messenger').submit();
		}
	});

	/**
	 * MESSENGER: FIND MEMBER BY USERNAME
	 */

	$('.pm-to').select2({
		minimumInputLength: 2,
		ajax: {
			url: 'messenger/get_usernames',
			dataType: 'json',
			type: 'POST',
			delay: 500,
			data: function(params) {
				return {
					term: params.term
				};
			},
			processResults: function(data) {
				return {
					results: $.map(data, function(item) {
						return {
							text: item.username,
							slug: item.username,
							id: item.m_id
						};
					})
				};
			}
		}
	});

	/**
	 * DELETE POST: CONFIRMATION MESSAGE
	 */

	(function() {
		$('a.delete-post-button').on('click', function() {
			var postId   = $(this).data('post'),
			    threadId = $(this).data('thread'),
			    memberId = $(this).data('member');

			$('input#delete_post_id').val(postId);
			$('input#delete_thread_id').val(threadId);
			$('input#delete_member_id').val(memberId);
		});
	}).call(this);

	/**
	 * TOGGLE MODERATION PANEL
	 */

	(function() {
		// Toggle moderation box
		$('.thread-moderation > a').on('click', function(event) {
			event.preventDefault();
			$('.thread-moderation > .box').slideToggle();
			$('.thread-moderation > a i').toggleClass('fa-angle-down').toggleClass('fa-angle-up');
		});

		// Show confirm message if clicked on "Delete Thread"
		$('#thread-delete').on('click', function(event) {
			if(!confirm($(this).data('confirm'))) {
				event.preventDefault();
			}
		});
	}).call(this);

	/**
	 * COUNT NUMBER OF CHOICES WHEN CREATING NEW POLL
	 * DON'T ALLOW IF IT EXCEEDS 15 CHOICES
	 */

	(function() {
		$('#poll-choices').on('keyup', function() {
			var value = $(this).val();
			if(value.split(/\r*\n/).length > 15) {
				$(this).addClass('error');
				$('input[type=submit]').attr('disabled', true);
			}
			else {
				$(this).removeClass('error');
				$('input[type=submit]').removeAttr('disabled');
			}
		});
	}).call(this);

});


/**
 * OUT-OF-SCOPE UTILS FUNCTIONS FOR GENERAL USAGE
 */

/**
 * Check if passwords match
 */
function CheckPassword() {
	var password = $('#password').val();
	var confirm = $('#password_conf').val();
	if(password != confirm) {
		$('#passwdMatch').fadeIn().css('display', 'inline-block');
		$('#formSubmit').attr('disabled', 'disabled');
	}
	else {
		$('#passwdMatch').fadeOut().css('display', 'none');
		$('#formSubmit').attr('disabled', false);
	}
}

/**
 * Check username length (greater than 3 chars, enable submit button)
 */
function CheckUsername() {
	var username = $('#username').val();
	if(username.length < 3 || username.length > 20) {
		$('#formSubmit').attr('disabled', 'disabled');
	}
	else {
		$('#formSubmit').attr('disabled', false);
	}
}
