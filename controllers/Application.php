<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Application.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
## -------------------------------------------------------

class Application
{
	// True: show layout; false: hide layout and master page
	public $layout = true;

	// Define custom master page
	public $master = "Default";

	// Dictionary of keys and value to be shown on View
	public $view_data = array();

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
		// Of course, you can't name a variable as $this
		if($name == "this") {
			Html::Error("The provided variable name ('" . $name . "') cannot be defined.");
			return false;
		}

		// Check if array key already exists
		if(array_key_exists($name, $this->view_data)) {
			Html::Error(
				"The provided variable name <b>" . $name . "</b> already exists. " .
				"Overwriting output variables is a bad practice."
			);
			return false;
		}

		$this->view_data[$name] = $value;
		return true;
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
		// SIDEBAR: member info
		$this->_GetMemberInfo();

		// SIDEBAR: get list of rooms
		$this->_GetRooms();

		// SIDEBAR: get members online
		$this->_GetMembersOnline();

		// SIDEBAR: get community statistics
		$this->_GetStats();

		// Is community offline?
		if($this->Core->config['general_offline'] == "true") {
			if(!strstr($_SERVER['REQUEST_URI'], "error")) {
				$this->Core->Redirect("error?t=offline");
			}
		}

		// Check if user is Admin
		$is_admin = ($this->Session->member_info['usergroup'] == 1) ? true : false;

		// RETURN COMMON VARIABLES
		// This variables will be returned in all controllers
		// Treat them as reserved words when declaring variables! ;)
		$this->Set("community_name", $this->Core->config['general_community_name']);
		$this->Set("community_url", $this->Core->config['general_community_url']);
		$this->Set("theme", $this->Core->config['theme']);
		$this->Set("meta_description", $this->Core->config['seo_description']);
		$this->Set("meta_keywords", $this->Core->config['seo_keywords']);
		$this->Set("website_name", $this->Core->config['general_website_name']);
		$this->Set("website_url", $this->Core->config['general_website_url']);
		$this->Set("show_members_online", $this->Core->config['general_sidebar_online']);
		$this->Set("show_statistics", $this->Core->config['general_sidebar_stats']);
		$this->Set("is_admin", $is_admin);
	}

	/**
	 * --------------------------------------------------------------------
	 * SIDEBAR: get member information (if logged in)
	 * --------------------------------------------------------------------
	 */
	private function _GetMemberInfo()
	{
		// If member is logged in
		if($this->Session->IsMember()) {
			// Get user avatar
			$this->Session->member_info['avatar'] = $this->Core->GetAvatar($this->Session->member_info, 60);

			// Number of new private messages
			$this->Db->Query("SELECT COUNT(*) AS total FROM c_messages
					WHERE to_id = '{$this->Session->member_info['m_id']}' AND status = 1;");

			$unread_messages = $this->Db->Fetch();

			$this->Set("member_id", $this->Session->member_info['m_id']);
			$this->Set("member_info", $this->Session->member_info);
			$this->Set("unread_messages", $unread_messages['total']);
		}
		else {
			$this->Set("member_id", 0);
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * SIDEBAR: get list of rooms and count number of threads in each
	 * --------------------------------------------------------------------
	 */
	private function _GetRooms()
	{
		$rooms = $this->Db->Query("SELECT c_rooms.r_id, c_rooms.name, c_rooms.password,
				(SELECT COUNT(*) FROM c_threads WHERE c_threads.room_id = c_rooms.r_id) AS threads
				FROM c_rooms WHERE invisible = 0;");

		while($result = $this->Db->Fetch($rooms)) {
			$_sidebar_rooms[] = $result;
		}
		$this->Set("sidebar_rooms", $_sidebar_rooms);
	}

	/**
	 * --------------------------------------------------------------------
	 * SIDEBAR: get members and guests online
	 * --------------------------------------------------------------------
	 */
	private function _GetMembersOnline()
	{
		$online = array();
		$session_expiration = $this->Core->config['general_session_expiration'];

		$members_online = $this->Db->Query("SELECT s.*, m.username FROM c_sessions s
				INNER JOIN c_members m ON (s.member_id = m.m_id)
				WHERE s.member_id <> 0 AND s.activity_time > '{$session_expiration}' AND s.anonymous = 0
				ORDER BY s.activity_time DESC;");

		while($members = $this->Db->Fetch($members_online)) {
			$viewing = i18n::Translate("SIDEBAR_MEMBER_VIEWING") . ": " . ucwords($members['location_type']);
			$online[] = "<a href='profile/{$members['member_id']}' title='{$viewing}'>{$members['username']}</a>";
		}

		$member_count = count($online);
		$member_list  = String::ToList($online);
		$this->Set("member_count", $member_count);
		$this->Set("member_list", $member_list);

		// Number of guests
		$this->Db->Query("SELECT COUNT(s_id) AS count FROM c_sessions WHERE member_id = 0;");
		$guests_count = $this->Db->Fetch();
		$guests_count = $guests_count['count'];
		$this->Set("guests_count", $guests_count);
	}

	/**
	 * --------------------------------------------------------------------
	 * SIDEBAR: get community statistics
	 * --------------------------------------------------------------------
	 */
	private function _GetStats()
	{
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
