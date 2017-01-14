<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Members.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\Http;
use \AC\Kernel\i18n;

class Members extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * MEMBER LIST
	 * --------------------------------------------------------------------
	 */
	public function index()
	{
		$letter_list = "";

		// Yes. The alphabet...
		$letters = array(
			"a", "b", "c", "d", "e", "f",
			"g", "h", "i", "j", "k", "l",
			"m", "n", "o", "p", "q", "r",
			"s", "t", "u", "v", "w", "x",
			"y", "z"
		);

		// If there is a search, letter or number selected, don't let "All" selected
		if(Http::request("term") || Http::request("letter") || Http::request("numbers")) {
			$first = "<li><a href='members'>" . i18n::translate("M_ALL") . "</a></li>\n";
		}
		else {
			$first = "<li class='active'><a href='members'>" . i18n::translate("M_ALL") . "</a></li>\n";
		}

		// Build letter list
		foreach($letters as $value) {
			$label = strtoupper($value);
			$selected = (Http::request("letter") == $value) ? "class='active'" : "";
			$order = (Http::request("order")) ? "&order=" . Http::request("order") : "";

			$letter_list .= "<li {$selected}><a href='members?letter={$value}{$order}'>{$label}</a></li>\n";
		}

		// If there is a number selected, don't let "0-9" selected
		if(Http::request("numbers")) {
			$numbers = "<li class='active'><a href='members?numbers=true'>0-9</a></li>\n";
		}
		else {
			$numbers = "<li><a href='members?numbers=true'>0-9</a></li>\n";
		}

		// Get member list
		$results = $this->getMemberList();

		// Page info
		$page_info['title'] = i18n::translate("M_TITLE");
		$page_info['bc'] = array(i18n::translate("M_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("letter_first", $first);
		$this->Set("letter_list", $letter_list);
		$this->Set("numbers", $numbers);
		$this->Set("results", $results);
	}

	/**
	 * --------------------------------------------------------------------
	 * GET MEMBER LIST
	 * --------------------------------------------------------------------
	 */
	private function getMemberList()
	{
		// Declare return variable
		$term = false;
		$_result = array();

		// Sort by username, join date or number of posts
		switch(Http::request("order")) {
			case "join":
				$order = "ORDER BY joined DESC";
				break;
			case "post":
				$order = "ORDER BY posts DESC";
				break;
			case "name":
			default:
				$order = "ORDER BY username";
				break;
		}

		// Filter is blank ("all")
		$filter = $letter_param = "";

		if(Http::request("term")) {
			// Search by username
			$term = Http::request("term");
			$filter = "AND username LIKE '%{$term}%'";
		}
		elseif(Http::request("letter")) {
			// Filter by first letter and execute query!
			$letter = Http::request("letter");
			$filter = "AND username LIKE '{$letter}%'";
			$letter_param = "letter={$letter}&";
		}
		elseif(Http::request("numbers")) {
			// Filter by numbers
			$filter = "AND username REGEXP '^[0-9]'";
		}

		$sql = "SELECT * FROM c_members
				LEFT JOIN c_usergroups ON (c_members.usergroup = c_usergroups.g_id)
				WHERE usergroup <> 0 {$filter} {$order};";

		$members = Database::query($sql);

		// Iterate between results
		while($result = Database::fetch($members)) {
			$result['avatar'] = $this->Core->getAvatar($result, 80);
			$result['joined'] = $this->Core->dateFormat($result['joined'], "short");
			$_result[] = $result;
		}

		// Return variables
		$this->Set("term", $term);
		$this->Set("letter_param", $letter_param);

		return $_result;
	}
}
