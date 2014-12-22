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
			$message      = i18n::Translate("E_MESSAGE_LOGIN_ERROR");
			$loginForm    = true;
			break;

		// The room is protected
		case 2:
			$success      = false;
			$notification = true;
			$message      = i18n::Translate("E_MESSAGE_PROTECTED");
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
			$message      = i18n::Translate("E_MESSAGE_VALIDATED");
			$loginForm    = true;
			break;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = i18n::Translate("E_TITLE");
	$pageinfo['bc'] = array(i18n::Translate("E_TITLE"));

?>
