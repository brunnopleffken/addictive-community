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


// Agree to the EULA

function eula() {
	var checkbox = document.getElementById("agree");

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
