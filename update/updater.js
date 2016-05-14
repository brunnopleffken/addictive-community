/**
 * ADDICTIVE COMMUNITY
 * -------------------------------------------------------
 * Created by Brunno Pleffken Hosti
 * http://github.com/brunnopleffken/addictive-community
 *
 * File: updater.js
 * License: GPLv2
 * Copyright: (c) 2015 - Addictive Software
 */


// Run updater
$(document).ready(function() {

	function updateModule(id) {
		var updateData = {
			migration_array: $('#migration_array').val(),
			version_from: $('#version_from').val()
		}

		console.log('Called updateModule ' + id);

		$.ajax({
			url: 'updater.php?step=' + id,
			dataType: 'json',
			type: 'post',
			data: updateData,
			beforeSend: function() {
				$('.step' + id).show();
			}
		})
		.done(function(data) {
			if(data.status == 1) {
				$('.step' + id + ' .ok').show();
				id++;
				updateModule(id);
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

	// Run updater
	// updateModule(1);

});

function updater_eula() {
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
