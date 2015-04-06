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
	// REPLACE ALL REGULAR <select> FIELDS WITH A FANCY ONE
	$('.select2').select2({ 'width': 'element' });
	$('.select2-no-search').select2({ 'minimumResultsForSearch': -1, 'width': 'element' });

	// FADE OUT ALL NOTIFICATIONS AFTER 3 SECONDS IF HAS NOT .persistent CLASS
	$('.notification').not('.persistent').delay(3000).fadeOut(1000);

	// REMOVE .error CLASS ON FOCUS (FOR input[type=text] ELEMENTS)
	$(document).on('focus', '.error', function() {
		$(this).removeClass('error');
	});

	// LOGIN - VALIDATE USERNAME AND PASSWORD BEFORE SEND
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
	});

	// USER CONTROL PANEL
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

	// LOAD COMPLETE TINYMCE ON THREAD/REPLY POSTS
	tinymce.init({
		entity_encoding: 'raw',
		link_title: false,
		plugins: ['link image'],
		menubar: false,
		selector: '#post',
		statusbar: false,
		target_list: [
			{title: 'New page', value: '_blank'},
		],
		toolbar: 'bold italic underline strikethrough | alignleft aligncenter alignright | link image | bullist numlist | blockquote | subscript superscript | removeformat'
	});

	// LOAD REDUCED TINYMCE FOR MEMBER SIGNATURES
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
});
