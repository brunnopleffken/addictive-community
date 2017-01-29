<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Application.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Core;
use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\i18n;
use \AC\Kernel\Session\SessionState;
use \AC\Kernel\Text;

class Application
{
	// True: show layout; false: hide layout and master page
	public $layout = true;

	// Define custom master page
	public $master = "Default";

	// Dictionary of keys and value to be shown on View
	public $view_data = array();

	// Kernel\Core class
	public $Core;

	/**
	 * --------------------------------------------------------------------
	 * APPLICATION CONSTRUCTOR
	 * --------------------------------------------------------------------
	 */
	public function __construct(Core $core_instance)
	{
		$this->Core = $core_instance;
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURNS THE VALUE OF SELF::$LAYOUT
	 * --------------------------------------------------------------------
	 */
	public function hasLayout()
	{
		return $this->layout;
	}

	/**
	 * --------------------------------------------------------------------
	 * DEFINE VARIABLE AND STORE IT IN VIEW_DATA
	 * --------------------------------------------------------------------
	 */
	public function set($name, $value)
	{
		// Of course, you can't name a variable as $this
		if($name == "this") {
			Html::throwError("The provided variable name ('" . $name . "') cannot be defined.");
			return false;
		}

		// Check if array key already exists
		if(array_key_exists($name, $this->view_data)) {
			Html::throwError(
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
	public function get()
	{
		return $this->view_data;
	}

	/**
	 * --------------------------------------------------------------------
	 * RUN APPLICATION CONTROLLER BEFORE RUNNING SPECIFIC MAIN() METHOD
	 * Methods or sidebar
	 * --------------------------------------------------------------------
	 */
	public function runApplication()
	{
		// SIDEBAR: member info
		$this->getMemberInfo();

		// SIDEBAR: get list of rooms
		$this->getRooms();

		// SIDEBAR: get members online
		$this->getMembersOnline();

		// SIDEBAR: get community statistics
		$this->getStats();

		// Get emoticons
		$this->getEmoticons();

		// Is community offline?
		if($this->Core->config['general_offline']) {
			if(!strstr($_SERVER['REQUEST_URI'], "error")) {
				$this->Core->redirect("failure?t=offline");
			}
		}

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
		$this->Set("is_admin", SessionState::isAdmin());
	}

	/**
	 * --------------------------------------------------------------------
	 * SIDEBAR: get member information (if logged in)
	 * --------------------------------------------------------------------
	 */
	private function getMemberInfo()
	{
		// If member is logged in
		if(SessionState::isMember()) {
			// Get user avatar
			SessionState::$user_data['avatar'] = $this->Core->getAvatar(SessionState::$user_data, 80);

			// Number of new private messages
			Database::query("SELECT COUNT(*) AS total FROM c_messages
					WHERE to_id = '" . SessionState::$user_data['m_id'] . "' AND status = 0;");

			$unread_messages = Database::fetch();

			$this->Set("member_info", SessionState::$user_data);
			$this->Set("member_id", SessionState::$user_data['m_id']); // Just a shortcut :)
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
	private function getRooms()
	{
		Database::query("SELECT c_rooms.r_id, c_rooms.name, c_rooms.password,
				(SELECT COUNT(*) FROM c_threads WHERE c_threads.room_id = c_rooms.r_id) AS threads
				FROM c_rooms WHERE invisible = 0;");

		$_sidebar_rooms = Database::fetchToArray();

		$this->Set("sidebar_rooms", $_sidebar_rooms);
	}

	/**
	 * --------------------------------------------------------------------
	 * SIDEBAR: get members and guests online
	 * --------------------------------------------------------------------
	 */
	private function getMembersOnline()
	{
		$online = array();

		$members_online = Database::query("SELECT s.*, m.username FROM c_sessions s
				INNER JOIN c_members m ON (s.member_id = m.m_id)
				WHERE s.member_id <> 0 AND s.anonymous = 0 AND s.activity_time > '" . SessionState::$session_activity_cut . "'
				ORDER BY s.activity_time DESC;");

		while($members = Database::fetch($members_online)) {
			$viewing = i18n::translate("SIDEBAR_MEMBER_VIEWING") . ": " . ucwords($members['location_controller']);
			$online[] = "<a href='profile/{$members['member_id']}' title='{$viewing}'>{$members['username']}</a>";
		}

		$member_count = count($online);
		$member_list  = Text::toList($online);
		$this->Set("member_count", $member_count);
		$this->Set("member_list", $member_list);

		// Number of guests
		Database::query("SELECT COUNT(session_token) AS count FROM c_sessions
				WHERE member_id = 0 AND activity_time > '" . SessionState::$session_activity_cut . "';");
		$guests_count = Database::fetch();
		$guests_count = $guests_count['count'];
		$this->Set("guests_count", $guests_count);
	}

	/**
	 * --------------------------------------------------------------------
	 * SIDEBAR: get community statistics
	 * --------------------------------------------------------------------
	 */
	private function getStats()
	{
		Database::query("SELECT * FROM c_stats;");
		$stats_result_temp = Database::fetch();

		$_stats['threads'] = $stats_result_temp['thread_count'];
		$_stats['posts'] = $stats_result_temp['post_count'];
		$_stats['members'] = $stats_result_temp['member_count'];

		Database::query("SELECT m_id, username FROM c_members ORDER BY m_id DESC LIMIT 1;");
		$stats_result_temp = Database::fetch();

		$_stats['lastmemberid']   = $stats_result_temp['m_id'];
		$_stats['lastmembername'] = $stats_result_temp['username'];

		$this->Set("stats", $_stats);
	}

	/**
	 * --------------------------------------------------------------------
	 * GET LIST OF EMOTICONS (REQUIRED FOR TINYMCE)
	 * --------------------------------------------------------------------
	 */
	private function getEmoticons()
	{
		$emoticons = array();
		Database::query("SELECT * FROM c_emoticons WHERE display = 1;");

		$add_quotes = function($value) {
			return "'{$value}'";
		};

		while($emoticon = Database::fetch()) {
			$emoticons[] = $emoticon['filename'];
		}

		$emoticons = array_map($add_quotes, $emoticons);

		$this->Set("emoticon_dir", $this->Core->config['emoticon_default_set']);
		$this->Set("emoticon_set", array_chunk($emoticons, 4));
	}
}
