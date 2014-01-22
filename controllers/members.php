<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: members.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	// ---------------------------------------------------
	// Build header!
	// ---------------------------------------------------

	// Letters index!
	
	$letters = array(
		"a", "b", "c", "d", "e",
		"f", "g", "h", "i", "j",
		"k", "l", "m", "n", "o",
		"p", "q", "r", "s", "t",
		"u", "v", "w", "x", "y",
		"z"
		);
	
	$selected = "";
	$letterList = "";
	
	// If there is a letter selected, do not select "All"
	
	if(isset($_REQUEST['letter'])) {
		$first = "<a href=\"index.php?module=members\">All</a>\n";
	}
	else {
		$first = "<a href=\"index.php?module=members\" class=\"page-selected\">All</a>\n";
	}
	
	// Make letter list
	
	foreach($letters as $value) {
		$label = strtoupper($value);
		
		if(isset($_REQUEST['letter']) and $_REQUEST['letter'] == $value) {
			$selected = "class=\"page-selected\"";
		}
		else {
			$selected = "";
		}
		
		$letterList .= "<a href=\"index.php?module=members&amp;letter={$value}\" {$selected}>{$label}</a>\n";
	}
	
	// ---------------------------------------------------
	// The SQL query
	// ---------------------------------------------------
	
	// Sort by username, join date or number of posts
	
	if(isset($_REQUEST['order'])) {
		switch($_REQUEST['order']) {
			case "join":
				$order = "ORDER BY joined DESC";
				break;
			case "post":
				$order = "ORDER BY posts DESC";
				break;
		}
	}
	else {
		$order = "ORDER BY username";
	}
	
	// Filter by first letter
	
	if(isset($_REQUEST['letter'])) {
		$letter = $_REQUEST['letter'];
		$this->Db->Query("SELECT * FROM c_members
			LEFT JOIN c_usergroups ON (c_members.usergroup = c_usergroups.g_id)
			WHERE username LIKE '{$letter}%' {$order};");
	}
	else {
	 		$this->Db->Query("SELECT * FROM c_members
			LEFT JOIN c_usergroups ON (c_members.usergroup = c_usergroups.g_id)
			{$order};");
	}

	while($result = $this->Db->Fetch()) {
		// Text::PR($result);
		$result['avatar'] = $this->Core->GetGravatar($result['email'], $result['photo'], 36, $result['photo_type']);
		$result['joined'] = $this->Core->DateFormat($result['joined'], "short");

		$_result[] = $result;
	}

	$numResults = $this->Db->Rows();
	

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = "Member List";
	$pageinfo['bc'] = array("Member List");


?>