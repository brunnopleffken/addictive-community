<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: app.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	// --------------------------------------------
	// Application specific constants
	// --------------------------------------------

	define("VERSION", "1.0-Alpha");

	// --------------------------------------------
	// Timing definition constants
	// --------------------------------------------

	define("SECONDS", 1);
	define("MINUTE", 60);
	define("HOUR", 3600);
	define("DAY", 86400);
	define("WEEK", 604800);
	define("MONTH", 2592000);
	define("YEAR", 31536000);

	// ---------------------------------------------------
	// List of mobile browsers user agents
	// ---------------------------------------------------

	$mobileBrowser = array(
		"Android", "Windows Phone", "iPhone",
		"MeeGo", "Symbian", "SymbianOS", "Opera Mini"
		);
	
?>