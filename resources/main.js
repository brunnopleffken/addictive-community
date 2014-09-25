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
			$('#tooltip_' + i).fadeIn();
		});
		$element.on('mouseleave', function() {
			$('#tooltip_' + i).stop().fadeOut();
		});
	});

	$('.tooltip').each(function(i, v) {
		var $tooltip = $(this),
		    width    = $tooltip.outerWidth() / 2 - 15;

		$tooltip.css('margin-left', '-' + width + 'px');
	});

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
				event.preventDefault();
				$(this).find('.errorMessage').css('display', 'inline-block').hide().fadeIn();
			}

		}
	});

	// Remove .error class on focus

	$('body').on('focus', '.error', function() {
		$(this).removeClass('error');
	});

	// ---------------------------------------------------
	// USER CP - GRAVATAR OR CUSTOM PHOTO
	// ---------------------------------------------------

	if($('.photoSelect:checked').val() == "gravatar") {
		$('#gravatar').show();
	} else if($('.photoSelect:checked').val() == "custom") {
		$('#custom').show();
	}

	$('.photoSelect').on('change', function(){
		var value = $(this).val();

		if(value == "custom") {
			$('#gravatar').fadeOut();
			$('#custom').delay(400).fadeIn();
		} else {
			$('#custom').fadeOut();
			$('#gravatar').delay(400).fadeIn();
		}
	});

	// ---------------------------------------------------
	// MESSENGER - ERASE MESSAGES
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

});
