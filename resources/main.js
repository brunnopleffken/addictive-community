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

	$('.select2').select2({ 'width': 'element' });
	$('.select2-no-search').select2({ 'minimumResultsForSearch': -1, 'width': 'element' });

	// ---------------------------------------------------
	// Fade out notifications after 3 seconds
	// ---------------------------------------------------

	$('.notification').not('.persistent').delay(3000).fadeOut(1000);

	// ---------------------------------------------------
	// Build lightbox when .fancybox exists
	// ---------------------------------------------------

	if(typeof $.fancybox != 'undefined') {
		$('.fancybox').fancybox({
			autoSize: true,
			closeBtn: false,
			modal: false,
			padding: 2
		});
	}

	// ---------------------------------------------------
	// Automatic tooltips
	// ---------------------------------------------------

	$('*[data-tooltip]').each(function(i, v) {
		var $element = $(this),
		    position = $element.position(),
		    height   = $element.outerHeight() + 8;

		$element.attr('data-tooltip-identifier', i);
		$element.after('<div class="tooltip" id="tooltip_' + i + '" style="left: ' + position.left +'px; top: ' + height + 'px">' + $element.data('tooltip') + '</div>');

		$element.on('mouseenter', function() {
			$('#tooltip_' + i).stop(true).delay(500).fadeIn();
		});
		$element.on('mouseleave', function() {
			$('#tooltip_' + i).stop(true).fadeOut();
		});
	});

	$('.tooltip').each(function(i, v) {
		var $tooltip = $(this),
		    width    = $tooltip.outerWidth() / 2 - 15;

		$tooltip.css('margin-left', '-' + width + 'px');
	});

	// ---------------------------------------------------
	// Open lightbox when clicking in "Delete Post"
	// ---------------------------------------------------

	$('a.deleteButton').on('click', function() {
		var postId   = $(this).data('post'),
		    threadId = $(this).data('thread'),
		    memberId = $(this).data('member');

		$('input#deletePostId').val(postId);
		$('input#deleteThreadId').val(threadId);
		$('input#deleteMemberId').val(memberId);
	});

	// ---------------------------------------------------
	// INSERT MARKDOWN TAGS TO TEXTAREA
	// ---------------------------------------------------

	$('#textareaToolbar').each(function(i, v) {
		var $toolbar  = $(this),
		    $textarea = $toolbar.parent().find('textarea');

		$toolbar.find('i').on('click', function() {
			var $button = $(this);

			if($button.hasClass('fa-bold')) { InsertMarkdown('post', '**', '**'); }
			if($button.hasClass('fa-italic')) { InsertMarkdown('post', '*', '*'); }
			if($button.hasClass('fa-underline')) { InsertMarkdown('post', '__', '__'); }
			if($button.hasClass('fa-strikethrough')) { InsertMarkdown('post', '~~', '~~'); }
			if($button.hasClass('fa-link')) { InsertMarkdown('post', '[[', ']]'); }
			if($button.hasClass('fa-image')) { InsertMarkdown('post', '{{', '}}'); }
			if($button.hasClass('fa-paint-brush')) { InsertMarkdown('post', '[color:red]', '[/color]'); }
			if($button.hasClass('fa-code')) { InsertMarkdown('post', '``', '``'); }
			if($button.hasClass('fa-list-ul')) { InsertMarkdown('post', '\n* ', ''); }
		})
	});

	function InsertMarkdown(elementID, openTag, closeTag) {
		var textArea = document.getElementById(elementID),
			contentPos = openTag.length;

		if (typeof(textArea.selectionStart) != "undefined") {
			var begin = textArea.value.substr(0, textArea.selectionStart);
			var selection = textArea.value.substr(textArea.selectionStart, textArea.selectionEnd - textArea.selectionStart);
			var end = textArea.value.substr(textArea.selectionEnd);
			textArea.value = begin + openTag + selection + closeTag + end;

			textArea.focus();
			textArea.setSelectionRange(textArea.selectionStart + contentPos, textArea.selectionStart + contentPos)
		}
	}

	// ---------------------------------------------------
	// FORM VALIDATION - REQUIRED FIELDS
	// ---------------------------------------------------

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

			// IF there is an error, show message!
			// ...otherwise, send form as it should be

			if(stopSend == true) {
				event.preventDefault();
				$(this).find('.errorMessage').css('display', 'inline-block').hide().fadeIn();
			}

		}
	});

	// ---------------------------------------------------
	// LOGIN - Validates username and password
	// ---------------------------------------------------

	$('#memberLoginForm').on('submit', function(event) {
		var error      = false,
		    $userField = $('#memberLoginForm .username'),
		    $passField = $('#memberLoginForm .password'),
		    $submit    = $('#memberLoginForm input[type=submit]'),
		    timer;

		event.preventDefault();

		$.ajax({
			url: 'index.php?module=login&act=validate',
			type: 'post',
			dataType: 'json',
			data: { username: $userField.val(), password: $passField.val() }
		})
		.done(function(data){
			if(data.authenticated == 'false') {
				error = true;

				$userField.addClass('error');
				$passField.addClass('error');

				$submit.attr('disabled', 'disabled').val(data.message);

				clearTimeout(timer);
				timer = setTimeout(function() {
					$('#memberLoginForm input[type=submit]').removeAttr('disabled');
					$('#memberLoginForm input[type=submit]').val('Log In');
				}, 2000);
			}
		})
		.fail(function(jqXHR, textStatus) {
			throw new Error(textStatus);
		})
		.always(function() {
			if(error == false) {
				var form = document.getElementById('memberLoginForm');
				form.submit();
			}
		});
	})

	// ---------------------------------------------------
	// Remove .error class on focus
	// ---------------------------------------------------

	$(document).on('focus', '.error', function() {
		$(this).removeClass('error');
	});

	// ---------------------------------------------------
	// USER CP - GRAVATAR, FACEBOOK OR CUSTOM PHOTO
	// ---------------------------------------------------

	if($('.photoSelect:checked').val() == "gravatar") {
		$('#gravatar').show();
	} else if($('.photoSelect:checked').val() == "facebook") {
		$('#facebook').show();
	} else if($('.photoSelect:checked').val() == "custom") {
		$('#custom').show();
	}

	$('.photoSelect').on('change', function(){
		var value = $(this).val();

		if(value == "custom") {
			$('#gravatar').fadeOut();
			$('#facebook').fadeOut();
			$('#custom').delay(400).fadeIn();
		} else if(value == "gravatar") {
			$('#custom').fadeOut();
			$('#facebook').fadeOut();
			$('#gravatar').delay(400).fadeIn();
		}
		else {
			$('#custom').fadeOut();
			$('#gravatar').fadeOut();
			$('#facebook').delay(400).fadeIn();
		}
	});

	// ---------------------------------------------------
	// MESSENGER
	// ---------------------------------------------------

	$('#messengerDeleteMessages').on('click', function(event){
		if($('.checkDeleteMessage:checked').length == 0) {
			alert('You need to select at least one message.');
			event.preventDefault();
		}
		else {
			$('form.personalMessenger').submit();
		}
	});

	$('a[data-check]').on('click', function() {
		$('.' + $(this).data('check')).each(function(index, value){
			$(this).attr('checked', 'checked');
		});
	});

	$('#pmTo').select2({
		minimumInputLength: 2,
		ajax: {
			url: 'api/usernames.php',
			dataType: 'json',
			type: "POST",
			quietMillis: 500,
			data: function (term) {
				return {
					term: term
				};
			},
			results: function (data) {
				return {
					results: $.map(data, function (item) {
						return {
							text: item.username,
							slug: item.username,
							id: item.m_id
						}
					})
				};
			}
		}
	});

	// ---------------------------------------------------
	// SET MONTH AND YEAR ON CALENDAR
	// ---------------------------------------------------

	$('#calendarSetDate').submit(function(event) {
		var data = $(this).serializeArray();
		window.location.href = 'calendar/' + data[0].value + '/' + data[1].value;

		event.preventDefault();
	})

});
