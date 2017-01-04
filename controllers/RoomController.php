<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Room.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\i18n;
use \AC\Kernel\Session\SessionState;

class Room extends Application
{
	// Room ID
	private $room_id = 0;

	// Number of threads per page
	private $threads_per_page;

	/**
	 * --------------------------------------------------------------------
	 * Run before Main()
	 * --------------------------------------------------------------------
	 */
	public function _BeforeAction()
	{
		// Get and sanitize room ID
		$this->room_id = Http::Request("id", true);

		if(!$this->room_id) {
			$this->Core->Redirect("500");
		}

		// Update session table with room ID
		$session_token = SessionState::$session_token;
		Database::Update("c_sessions", "location_room_id = {$this->room_id}", "session_token = '{$session_token}'");
	}

	/**
	 * --------------------------------------------------------------------
	 * View Room (a.k.a. thread list)
	 * --------------------------------------------------------------------
	 */
	public function Index($id)
	{
		// Get room information
		Database::Query("SELECT * FROM c_rooms WHERE r_id = {$this->room_id}");
		$room_info = Database::Fetch();

		// Redirect to Error 404 if the room doesn't exist
		if($room_info == null) {
			$this->Core->Redirect("404");
		}

		// Is the room protected?
		if($room_info['password'] != "") {
			$room_session_name = "room_" . $room_info['r_id'];
			if(!SessionState::GetCookie($room_session_name)) {
				$this->Core->Redirect("failure?t=protected_room&room=" . $this->room_id);
			}
		}

		// Check permissions
		$room_info['perm_view'] = unserialize($room_info['perm_view']);
		$permission_value = "V_" . SessionState::$user_data['usergroup'];
		if(!in_array($permission_value, $room_info['perm_view'])) {
			header("Location: room/{$this->room_id}?msg=1");
		}

		// Get list of threads
		$threads = $this->_GetThreads();

		// Get number of pages
		$pages = ceil(count($threads) / $this->threads_per_page);

		// Page info
		$page_info['title'] = $room_info['name'];
		$page_info['bc'] = array($room_info['name']);
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("is_member", SessionState::IsMember());
		$this->Set("room_id", $this->room_id);
		$this->Set("room_info", $room_info);
		$this->Set("threads", $threads);
		$this->Set("view", Http::Request("view"));
		$this->Set("pages", $pages);
	}

	/**
	 * --------------------------------------------------------------------
	 * Unlock protected rooms
	 * --------------------------------------------------------------------
	 */
	public function Unlock()
	{
		$this->layout = false;

		$password = Http::Request("password");
		$room_id  = Http::Request("room", true);

		Database::Query("SELECT password FROM c_rooms WHERE r_id = {$room_id}");
		$room_info = Database::Fetch();

		if($password == $room_info['password']) {
			$room_session_name = "room_" . $room_id;
			SessionState::CreateCookie($room_session_name, 1);
			$this->Core->Redirect("room/" . $room_id);
			exit;
		}
		else {
			$this->Core->Redirect("failure?t=protected_room&room=" . $room_id);
			exit;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * Get list of threads
	 * --------------------------------------------------------------------
	 */
	private function _GetThreads()
	{
		// Declare return variable
		$thread = array();

		// Get query string (room/id?view=mythreads|topreplies|noreplies|bestanswered)
		$view = Http::Request("view");

		// Filter thread list
		switch($view) {
			case "mythreads":
				$menu  = array("", "active");
				$where = "AND author_member_id = '{$this->Session->member_info['m_id']}'";
				$order = "last_post_date DESC";
				break;
			case "topreplies":
				$menu  = array("active", "");
				$where = "";
				$order = "replies DESC";
				break;
			case "noreplies":
				$menu  = array("active", "");
				$where = "AND replies = '1'";
				$order = "last_post_date DESC";
				break;
			case "bestanswered":
				$menu  = array("active", "");
				$where = "AND with_best_answer = '1'";
				$order = "last_post_date DESC";
				break;
			case "polls":
				$menu  = array("active", "");
				$where = "AND poll_question <> ''";
				$order = "last_post_date DESC";
				break;
			default:
				$menu  = array("active", "");
				$where = "";
				$order = "last_post_date DESC";
		}

		// Return menu item
		$this->Set("menu", $menu);

		// If admin, then also select all invisible threads; and threads with an opening date
		$is_admin = (SessionState::IsAdmin()) ? "" : "AND c_threads.start_date < " . time();

		// Set SQL pagination (OFFSET)
		$this->threads_per_page = $this->Core->config['threads_per_page'];
		$page = Http::Request("p", true) ? Http::Request("p", true) : 1;
		$page = $page * $this->threads_per_page - $this->threads_per_page;

		// Execute query
		$threads = Database::Query("SELECT c_threads.*, author.username AS author_name, author.email,
				author.photo_type, author.photo, lastpost.username AS last_post_name,
				(SELECT post FROM c_posts WHERE thread_id = c_threads.t_id ORDER BY post_date LIMIT 1) as post FROM c_threads
				INNER JOIN c_members AS author ON (c_threads.author_member_id = author.m_id)
				INNER JOIN c_members AS lastpost ON (c_threads.last_post_member_id = lastpost.m_id)
				WHERE room_id = {$this->room_id} {$where} {$is_admin} ORDER BY announcement DESC, {$order}
				LIMIT {$page}, 10;");

		// Process returned data
		while($row = Database::Fetch($threads)) {
			$thread[] = $this->_ParseThread($row);
		}

		return $thread;
	}

	/**
	 * --------------------------------------------------------------------
	 * Parce and process thread information
	 * --------------------------------------------------------------------
	 */
	private function _ParseThread($result)
	{
		// Check if thread has already been read
		$is_unread = false;
		$read_threads_cookie = SessionState::GetCookie("addictive_community_read_threads");

		if($read_threads_cookie) {
			$login_time_cookie = SessionState::GetCookie("addictive_community_login_time");
			$read_threads = json_decode(html_entity_decode($read_threads_cookie), true);
			if(!in_array($result['t_id'], $read_threads) && $login_time_cookie < $result['last_post_date']) {
				$is_unread = true;
			}
		}

		$result['class'] = "";
		$result['description'] = strip_tags($result['post']);
		$result['mobile_start_date'] = $this->Core->DateFormat($result['start_date'], "short");
		$result['to_be_opened'] = ($result['start_date'] > time()) ? "to-be-opened" : "";
		$result['start_date'] = $this->Core->DateFormat($result['start_date']);
		$result['last_post_date'] = $this->Core->DateFormat($result['last_post_date']);

		// Author avatar
		$result['author_avatar'] = $this->Core->GetAvatar($result, 100);

		// Build phrases using internationalization
		$result['author_name'] = i18n::Translate("R_STARTED_BY", array($result['author_name']));
		$result['start_date'] = i18n::Translate("R_STARTED_ON", array($result['start_date']));
		$result['views'] = i18n::Translate("R_VIEWS", array($result['views']));
		$result['last_post_by'] = i18n::Translate("R_LAST_POST_BY", array($result['last_post_name']));

		// Get the number of replies, not total number of posts... ;)
		$result['replies']--;

		// Status: locked
		if($result['locked'] == 1 || ($result['lock_date'] != 0 && $result['lock_date'] < time())) {
			$result['class'] = "locked";
		}

		// Status: answered
		if($result['with_best_answer'] == 1) {
			$result['class'] = "answered";
		}

		// Status: announcement
		if($result['announcement'] == 1) {
			$result['class'] = "announcement";
		}

		// Status: hot
		if($result['replies'] >= $this->Core->config['thread_posts_hot']) {
			$result['class'] .= " hot";
		}

		// Status: unread
		if($is_unread) {
			$result['class'] .= " unread";
		}

		return $result;
	}
}
