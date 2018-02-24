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

	$('.alert').not('.persistent').delay(3000).fadeOut(1000);

	/**
	 * TOGGLE SIDEBAR ON MOBILE DEVICES
	 */

	(function() {
		$('#toggle-sidebar').on('click', function() {
			$('.overlay').fadeIn();
			$('nav').addClass('open').show().find('.close').fadeIn();
		});
		$('nav .close').on('click', function() {
			$('.overlay').fadeOut();
			$('nav').removeClass('open').hide().find('.close').fadeOut();
		});
	}).call(this);

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
		var links = document.querySelectorAll('.nav .nav-bottom a');
		[].forEach.call(links, function(link) {
			var elementUrl = new RegExp('(' + (link.href).replace('?', '\\?') + ')$');
			var browserUrl = window.location.href;

			if(elementUrl.test(browserUrl)) {
				link.className = 'active';
			}
		});
	}).call(this);

	/**
	 * BUILD LIGHTBOX WHEN LINK HAS .fancybox
	 */

	(function() {
		try {
			$('[data-type="ajax"]').fancybox({
				modal: true
			});
		} catch(e) {
			console.log(e);
		}
	}).call(this);

	/**
	 * USER CONTROL PANEL FUNCTIONS
	 */

	(function() {
		if($('.photo-select:checked').val() == 'gravatar') {
			$('#gravatar').show();
		}
		else if($('.photo-select:checked').val() == 'custom') {
			$('#custom').show();
		}

		$('.photo-select').on('change', function() {
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
	 * HTML5 FORM VALIDATION
	 */

	// Add 'novalidate' to all forms because we're using the custom one right below
	$('form').attr('novalidate', true);

	$(document).on('submit', 'form:not(.no-validate)', function(event) {
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

	/**
	 * REPLACE INPUT[FILE] FIELDS WITH A CUSTOM ONE
	 */

	(function() {
		$('input[type=file]').each(function() {
			var $field = $(this);
			var isRequired = '';

			// Hide input[file] field
			$field.hide();

			// Check if file field has "required" attr
			if($(this).attr('required')) {
				isRequired = 'required';
			}

			// Add an text field and a button
			$field.parent().append('<input type="text" name="attachment_filename" id="attachment_filename" class="form-control" style="width: calc(100% - 110px)" ' + isRequired + '>');
			$field.parent().append('<button id="attachment_button" class="btn btn-default" style="margin-left: 10px; width: 100px"><i class="fa fa-upload"></i> Upload</button>');
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
				plugins: ['textcolor lists link image media emoticons'],
				menubar: false,
				selector: '#post',
				statusbar: false,
				media_alt_source: false,
				toolbar: 'bold italic underline strikethrough | forecolor | alignleft aligncenter alignright | bullist numlist | emoticons | link image media | subscript superscript | removeformat'
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
				element_format: 'html',
				entity_encoding: 'raw',
				link_title: false,
				default_link_target: "_blank",
				target_list: false,
				plugins: ['textcolor lists link image'],
				menubar: false,
				selector: '#signature',
				statusbar: false,
				toolbar: 'bold italic underline strikethrough | forecolor | link image | subscript superscript | removeformat'
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

	/**
	 * SOCIAL LINKS
	 */

	(function() {
		$('.thread-post-author-share-box a').on('click', function(event) {
			var url = $(event.currentTarget).attr('href');
			window.open(url, 'socialLinks', 'toolbar=no,width=550,height=550');
			event.preventDefault();
		});
	}).call(this);

});


/**
 * OUT-OF-SCOPE UTILS FUNCTIONS FOR GENERAL USAGE
 */

// Check passwords when registering a new member

var register = {
	checkUsernamePassed: false,
	checkPasswordPassed: false,
	checkUsername: function() {
		var username = $('#username').val();
		if(username.length < 3 || username.length > 20) {
			register.checkUsernamePassed = false;
		}
		else {
			register.checkUsernamePassed = true;
		}

		register.enableSubmit();
	},
	checkPassword: function() {
		var password = $('#password').val();
		var confirm = $('#password_conf').val();

		if(password != confirm) {
			$('#passwdMatch').fadeIn().css('display', 'inline-block');
			register.checkPasswordPassed = false;
		}
		else {
			$('#passwdMatch').fadeOut().css('display', 'none');
			register.checkPasswordPassed = true;
		}

		register.enableSubmit();
	},
	enableSubmit: function() {
		if(register.checkUsernamePassed && register.checkPasswordPassed) {
			$('#formSubmit').attr('disabled', false);
		}
		else {
			$('#formSubmit').attr('disabled', 'disabled');
		}
	}
};
