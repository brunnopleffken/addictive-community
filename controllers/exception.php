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

	$errno = Html::Request("errno");

	switch($errno) {

		// Wrong username or password when logging in
		case 1:
			$notification = true;
			$message      = "Username or password is wrong. Please, try again.";
			$loginForm    = true;
			break;

		// The room is protected
		case 2:
			$notification = true;
			$message      = "This room is protected.";
			$loginForm    = false;
			$roomPassword = true;
			break;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------
	
	// Page information
	$pageinfo['title'] = "An error occoured";
	$pageinfo['bc'] = array("An error occoured");

?>