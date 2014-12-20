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
		$first = "<a href=\"?module=members\">" . i18n::Translate("M_ALL") . "</a>\n";
	}
	else {
		$first = "<a href=\"?module=members\" class=\"page-selected\">" . i18n::Translate("M_ALL") . "</a>\n";
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

		$letterList .= "<a href=\"?module=members&amp;letter={$value}{$order}\" {$selected}>{$label}</a>\n";
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

	if(Html::Request("username")) {
		$username = Html::Request("username");
		$order = "WHERE username LIKE '%{$username}%' {$order};";
	}

	// Filter by first letter

	if(Html::Request("letter")) {
		$letter = Html::Request("letter");
		$members = $this->Db->Query("SELECT * FROM c_members
				LEFT JOIN c_usergroups ON (c_members.usergroup = c_usergroups.g_id)
				WHERE username LIKE '{$letter}%' {$order};");
	}
	else {
		$members = $this->Db->Query("SELECT * FROM c_members
				LEFT JOIN c_usergroups ON (c_members.usergroup = c_usergroups.g_id)
				{$order};");
	}

	while($result = $this->Db->Fetch($members)) {
		$result['avatar'] = $this->Core->GetGravatar($result['email'], $result['photo'], 72, $result['photo_type']);
		$result['joined'] = $this->Core->DateFormat($result['joined'], "short");

		$_result[] = $result;
	}

	$numResults = $this->Db->Rows();


	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = i18n::Translate("M_TITLE");
	$pageinfo['bc'] = array(i18n::Translate("M_TITLE"));


?>
