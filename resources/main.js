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

	$('.select2').select2();

	// ---------------------------------------------------
	// FORM VALIDATION - REQUIRED FIELDS
	// ---------------------------------------------------
	
	/**
	 * CSS CLASSES FOR VALIDATION
	 * .url		Validates http://xxx.com or http://xxx.com.br
	 * .email	Validates me@me.com or me@me.com.br
	 * .numeric	Validates if the value is numeric only
	 */

	$('form').on('submit', function(e) {
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
				e.preventDefault();
				$(this).find('.errorMessage').css('display', 'inline-block').hide().fadeIn();
			}

		}
	});

	// ---------------------------------------------------
	// This is hard: get member Facebook posts!
	// ---------------------------------------------------

	var fbUsername	= "brupleffken";
	var fbToken		= "CAACEdEose0cBAPUz5ZCqdgKMqg3pZAGGmWjZC5KMiZAGrOGoeq8JZBCD1yhHicHbSyq7tTUK0ESiBSbz3S7nJkQ6gtjVkcF48U3bnE7zSqzXbOyIZAXCVxB9463Yr8bXN41MZAo5RMHcNfcdcOigYz2pURoEQapwmbdl904WmlXCd3bPCdDC7pLZAJU0qHvnkqm4nZAwwP83jsQZDZD";

	var graphURL	= "https://graph.facebook.com/" + fbUsername + "/statuses?access_token=" + fbToken;

	var postItems = [];

	$.getJSON(graphURL, function(data, textStatus) {

		$.each(data, function(index, val) {
			var i = 1;
			$.each(val, function(i, v){

				// Avoid null posts to be shown (like "how you're feeling")
				if(v.message != null && i < 10) {

					// Format date and time
					var rawTime		= new Date(v.updated_time);
					var postTime	= rawTime.getDate() + "/" + (rawTime.getMonth() + 1) + "/" + rawTime.getFullYear() + ", " +
						rawTime.getHours() + ":" + rawTime.getMinutes();

					var content = v.message;
					content = content.replace(/\n/g, "<br>");

					var likesCount = 0;
					console.log(v.id);

					// Append to table
					//postHtml = "<tr><td><span style='color: #aaaaaa'>" + postTime + "</span><br><a href='https://www.facebook.com/" + fbUsername + "/posts/" + v.id + "' target='_blank'>" + content + "</a></td></tr>";

					postHtml = "<tr><td class=\"tLabel\">" + postTime + "</td><td><a href='https://www.facebook.com/" + fbUsername + "/posts/" + v.id + "' target='_blank'><b>0 likes - 0 comments</b></a></td></tr> <tr> <td colspan=\"2\" style=\"border-bottom: 1px solid #eee; padding-left: 20px; padding-bottom: 20px\">" + content + "</td></tr>";
					postItems.push(postHtml);
					i++;
				}

			})
		});
	}).done(function(){
		// Success? Ok, let's do it!
		$('#facebookNewsFeed').append(postItems);
	});


});