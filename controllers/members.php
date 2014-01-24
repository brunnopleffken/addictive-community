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

	// The awesome alphabet
	
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
	
	// If there is a letter selected, don't let "All" selected
	
	if(Html::Request("letter")) {
		$first = "<a href=\"index.php?module=members\">All</a>\n";
	}
	else {
		$first = "<a href=\"index.php?module=members\" class=\"page-selected\">All</a>\n";
	}
	
	// Make letter list
	
	foreach($letters as $value) {
		$label = strtoupper($value);
		
		if(Html::Request("letter") == $value) {
			$selected = "class=\"page-selected\"";
		}
		else {
			$selected = "";
		}

		if(Html::Request("order")) {
			$order = "&amp;order=" . $_REQUEST['order'];
		}
		else {
			$order = "";
		}
		
		$letterList .= "<a href=\"index.php?module=members&amp;letter={$value}{$order}\" {$selected}>{$label}</a>\n";
	}
	
	// ---------------------------------------------------
	// The SQL query
	// ---------------------------------------------------
	
	// Sort by username, join date or number of posts
	
	if(Html::Request("order")) {
		switch(Html::Request("order")) {
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
	
	if(Html::Request("letter")) {
		$letter = Html::Request("letter");
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