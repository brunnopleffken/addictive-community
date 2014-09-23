<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: newthread.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	// ---------------------------------------------------
	// Define access method
	// ---------------------------------------------------

	// Deny guest access
	$this->Session->NoGuest();

	// ---------------------------------------------------
	// Get user and room information
	// ---------------------------------------------------

	// Get member ID
	$m_id = $this->member['m_id'];

	// Get room ID
	$roomId = Html::Request("room", true);

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = "New Thread";
	$pageinfo['bc'] = array("New Thread");

?>