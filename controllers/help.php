<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: help.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	// ---------------------------------------------------
	// Build help topics
	// ---------------------------------------------------

	$id = (Html::Request("id")) ? $this->Core->QueryString("id", 1, true) : null;

	// If there is an ID, get topic

	if(!$id) {
		$this->Db->Query("SELECT h_id, title, short_desc FROM c_help ORDER BY title ASC;");

		while($help = $this->Db->Fetch()) {
			$_topics[] = $help;
		}

		$breadcrumbTitle = "Help Topics";
	}
	else {
		$this->Db->Query("SELECT * FROM c_help WHERE h_id = '{$id}';");
		$_help = $this->Db->Fetch();

		$breadcrumbTitle = "Help Topics: " . $_help['title'];
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------
	
	// Page information
	$pageinfo['title'] = $breadcrumbTitle;
	$pageinfo['bc'] = array($breadcrumbTitle);

?>