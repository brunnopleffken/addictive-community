<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Application.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

class Application
{
	// True: show layout; false: hide layout and master page
	public $layout = true;

	// Define custom master page
	public $master = "Default";

	// Dictionary of keys and value to be shown on View
	public $view_data = array();

	// Community settings
	public $Config = array();

	/**
	 * --------------------------------------------------------------------
	 * CLASSES INSTANCES
	 * --------------------------------------------------------------------
	 */
	public $Db;      // Database abstraction layer class
	public $Core;    // Main core functions
	public $Session; // Session management and member information

	/**
	 * --------------------------------------------------------------------
	 * RETURNS THE VALUE OF SELF::$LAYOUT
	 * --------------------------------------------------------------------
	 */
	public function HasLayout()
	{
		return $this->layout;
	}

	/**
	 * --------------------------------------------------------------------
	 * DEFINE VARIABLE AND STORE IT IN VIEW_DATA
	 * --------------------------------------------------------------------
	 */
	public function Set($name, $value)
	{
		if($name == "this") {
			Html::Error("The provided variable name ('" . $name . "') cannot be defined.");
			return false;
		}
		$this->view_data[$name] = $value;
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURN VIEW_DATA
	 * --------------------------------------------------------------------
	 */
	public function Get()
	{
		return $this->view_data;
	}

	/**
	 * --------------------------------------------------------------------
	 * RUN APPLICATION CONTROLLER BEFORE RUNNING SPECIFIC MAIN() METHOD
	 * Methods or sidebar
	 * --------------------------------------------------------------------
	 */
	public function Run()
	{
		// SIDEBAR: get list of rooms

		$rooms = $this->Db->Query("SELECT c_rooms.r_id, c_rooms.name, c_rooms.password,
				(SELECT COUNT(*) FROM c_threads WHERE c_threads.room_id = c_rooms.r_id) AS threads
				FROM c_rooms WHERE invisible = 0;");

		while($result = $this->Db->Fetch($rooms)) {
			$_sidebar_rooms[] = $result;
		}
		$this->Set("sidebar_rooms", $_sidebar_rooms);


		// SIDEBAR: get members online

		$online = array();
		$session_expiration = $this->config['general_session_expiration'];

		$members_online = $this->Db->Query("SELECT s.*, m.username FROM c_sessions s
				INNER JOIN c_members m ON (s.member_id = m.m_id)
				WHERE s.member_id <> 0 AND s.activity_time > '{$session_expiration}' AND s.anonymous = 0
				ORDER BY s.activity_time DESC;");

		while($members = $this->Db->Fetch($members_online)) {
			$online[] = "<a href='profile/{$members['member_id']}'>{$members['username']}</a>";
		}

		$member_count = count($online);
		$member_list  = implode(", ", $online);
		$this->Set("member_count", $member_count);
		$this->Set("member_list", $member_list);

		// Number of guests
		$this->Db->Query("SELECT COUNT(s_id) AS count FROM c_sessions WHERE member_id = 0;");
		$guests_count = $this->Db->Fetch();
		$guests_count = $guests_count['count'];
		$this->Set("guests_count", $guests_count);


		// SIDEBAR: get community statistics

		$this->Db->Query("SELECT * FROM c_stats;");
		$stats_result_temp = $this->Db->Fetch();

		$_stats['threads'] = $stats_result_temp['total_threads'];
		$_stats['replies'] = $stats_result_temp['total_posts'];
		$_stats['members'] = $stats_result_temp['member_count'];

		$this->Db->Query("SELECT m_id, username FROM c_members ORDER BY m_id DESC LIMIT 1;");
		$stats_result_temp = $this->Db->Fetch();

		$_stats['lastmemberid']   = $stats_result_temp['m_id'];
		$_stats['lastmembername'] = $stats_result_temp['username'];

		$this->Set("stats", $_stats);
	}
}

?>
