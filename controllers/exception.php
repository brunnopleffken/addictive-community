<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: exception.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------


	// ---------------------------------------------------
	// Get error code
	// ---------------------------------------------------

	$errno = Html::Request("errno", true);

	switch($errno) {

		// Wrong username or password when logging in
		case 1:
			$success      = false;
			$notification = true;
			$message      = "Username or password is wrong. Please, try again.";
			$loginForm    = true;
			break;

		// The room is protected
		case 2:
			$success      = false;
			$notification = true;
			$message      = "This room is protected.";
			$loginForm    = false;
			$roomPassword = true;
			break;
	}

	// ---------------------------------------------------
	// Get success code
	// ---------------------------------------------------

	$errno = Html::Request("message", true);

	switch($errno) {

		// Wrong username or password when logging in
		case 1:
			$success      = true;
			$notification = false;
			$message      = "Your e-mail address has been successfully validated.";
			$loginForm    = true;
			break;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------
	
	// Page information
	$pageinfo['title'] = "An error occoured";
	$pageinfo['bc'] = array("An error occoured");

?>