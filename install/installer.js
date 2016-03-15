/**
 * ADDICTIVE COMMUNITY
 * -------------------------------------------------------
 * Created by Brunno Pleffken Hosti
 * http://github.com/brunnopleffken/addictive-community
 *
 * File: installer.js
 * License: GPLv2
 * Copyright: (c) 2016 - Addictive Community
 */


$(document).ready(function($) {

	// Check if database is up using Ajax

	$('#database-form').on('submit', function(event) {
		var $form = $(this);
		var isDatabaseUp = false;
		var proceed = true;

		$('input.required').each(function() {
			if($(this).val() == "") {
				$(this).addClass('error');
				proceed = false;
			}
			else {
				$(this).removeClass('error');
			}
		});

		if(proceed) {
			$.ajax("tests.php?task=test_database", {
				method: 'post',
				dataType: 'json',
				data: $form.serialize(),
				context: $form,
				beforeSend: function() {
					$('input[type=submit]').attr('disabled', true);
				}
			})
			.done(function(data) {
				if(data.status == 0) {
					isDatabaseUp = false;
					alert('ERROR: Could not establish connection to database.\n' + data.message);
				}
				else {
					isDatabaseUp = true;
				}
			})
			.always(function() {
				$('input[type=submit]').removeAttr('disabled');

				// If there is no errors, submit!
				if(isDatabaseUp == true) {
					this.off('submit');
					this.submit();
				}
			});
		}

		event.preventDefault();
	});
});


// Agree to the EULA

function eula() {
	var checkbox = document.getElementById("agree");

	console.log(checkbox.checked);

	if(checkbox.checked == false) {
		alert("You must agree to the EULA to proceed with installation.");
		return false;
	}
	else {
		window.location.replace("index.php?step=2");
	}
}

// Check if typed passwords are equal

function checkPasswordMatch() {
	var password = document.getElementById("adm_password"),
	    confirm = document.getElementById("adm_password2");

	if(confirm.value != "") {
		if(confirm.value != password.value) {
			alert("Administrator passwords does not match!");
			confirm.style.background = "#FFE4E1";
		}
		else {
			confirm.style.background = "transparent";
		}
	}
}

// Run installer

function installModule(id) {
	var installData = {
		db_server: $('#db_server').val(),
		db_database: $('#db_database').val(),
		db_username: $('#db_username').val(),
		db_password: $('#db_password').val(),
		db_port: $('#db_port').val(),
		community_name: $('#community_name').val(),
		community_path: $('#community_path').val(),
		community_url: $('#community_url').val(),
		default_language: $('#default_language').val(),
		default_timezone: $('#default_timezone').val(),
		admin_username: $('#admin_username').val(),
		admin_password: $('#admin_password').val(),
		admin_email: $('#admin_email').val()
	};

	$.ajax({
		url: 'installer.php?step=' + id,
		dataType: 'json',
		type: 'post',
		data: installData,
		beforeSend: function() {
			$('.step' + id).show();
		}
	})
	.done(function(data) {
		if(data.status == 1) {
			$('.step' + id + ' .ok').show();
			id++;
			installModule(id);
		}
		else {
			$('.step' + id + ' .failed').show();
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.log('Failed in step ' + id);
		console.log(jqXHR);
		console.log(textStatus);
		console.log(errorThrown);
		$('.step' + id + ' .failed').show();
	})
	.always(function(data) {
		if(id == 7) {
			$('#log input').fadeIn();
		}
	});
}
