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

use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\i18n;

class Room extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * RUN BEFORE MAIN()
	 * --------------------------------------------------------------------
	 */
	public function _BeforeAction()
	{
		if(Http::Request("act") == "load_more") {
			// Update session table with room ID
			$id = Http::Request("id", true);

			if($id == null && !is_numeric($id)) {
				$this->Core->Redirect("500");
			}

			$session = $this->Session->session_id;
			$this->Db->Update("c_sessions", "location_room_id = {$id}", "s_id = '{$session}'");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW ROOM (THREAD LIST)
	 * --------------------------------------------------------------------
	 */
	public function Main($id)
	{
		// Check if $id exists and is a number
		if($id == null && !is_numeric($id)) {
			$this->Core->Redirect("500");
		}

		// Get room information
		$this->Db->Query("SELECT * FROM c_rooms WHERE r_id = {$id}");
		$room_info = $this->Db->Fetch();

		// Redirect to Error 404 if the thread doesn't exist
		if($room_info == null) {
			$this->Core->Redirect("404");
		}

		// Is the room protected?
		if($room_info['password'] != "") {
			$room_session_name = "room_" . $room_info['r_id'];
			if(!$this->Session->GetCookie($room_session_name)) {
				$this->Core->Redirect("failure?t=protected_room&room=" . $id);
			}
		}

		// Check permissions
		$room_info['perm_view'] = unserialize($room_info['perm_view']);
		$permission_value = "V_" . $this->Session->member_info['usergroup'];
		if(!in_array($permission_value, $room_info['perm_view'])) {
			header("Location: index.php?msg=1");
		}

		// Get list of threads
		$threads = $this->_GetThreads($id);

		// Page info
		$page_info['title'] = $room_info['name'];
		$page_info['bc'] = array($room_info['name']);
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("room_id", $id);
		$this->Set("room_info", $room_info);
		$this->Set("threads", $threads);
		$this->Set("view", Http::Request("view"));
	}

	/**
	 * --------------------------------------------------------------------
	 * ROOM PAGINATION ("LOAD MORE..." LINK)
	 * --------------------------------------------------------------------
	 */
	public function LoadMore($id)
	{
		$this->layout = false;

		// Threads per page
		$page = Http::Request("page", true);
		$threads_per_page = $this->Core->config['threads_per_page'];

		// Calculate SQL offset
		$offset = $page * $threads_per_page;

		// Get threads
		$this->Db->Query("SELECT c_threads.*, author.username AS author_name, author.email AS email,
				author.photo_type AS photo_type, author.photo AS author_photo, lastpost.username AS last_post_name,
				(SELECT post FROM c_posts WHERE thread_id = c_threads.t_id ORDER BY post_date LIMIT 1) as post FROM c_threads
				INNER JOIN c_members AS author ON (c_threads.author_member_id = author.m_id)
				INNER JOIN c_members AS lastpost ON (c_threads.last_post_member_id = lastpost.m_id)
				WHERE room_id = {$id} ORDER BY announcement DESC, last_post_date DESC
				LIMIT {$threads_per_page} OFFSET {$offset};");

		// Process data
		while($result = $this->Db->Fetch()) {
			$_thread[] = $this->_ParseThread($result);
		}

		echo json_encode($_thread);
	}

	/**
	 * --------------------------------------------------------------------
	 * UNLOCK PROTECTED ROOMS
	 * --------------------------------------------------------------------
	 */
	public function Unlock()
	{
		$this->layout = false;

		$password = Http::Request("password");
		$room_id  = Http::Request("room", true);

		$this->Db->Query("SELECT password FROM c_rooms WHERE r_id = {$room_id}");
		$room_info = $this->Db->Fetch();

		if($password == $room_info['password']) {
			$room_session_name = "room_" . $room_id;
			$this->Session->CreateCookie($room_session_name, 1);
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
	 * GET LIST OF THREADS
	 * --------------------------------------------------------------------
	 */
	private function _GetThreads($room_id)
	{
		// Declare return variable
		$_thread = array();

		// Get query string (room/id?view=mythreads|topreplies|noreplies|bestanswered)
		$view = Http::Request("view");

		// Filter thread list
		switch($view) {
			case "mythreads":
				$menu  = array("", "selected");
				$where = "AND author_member_id = '{$this->Session->member_info['m_id']}'";
				$order = "last_post_date DESC";
				break;
			case "topreplies":
				$menu  = array("selected", "");
				$where = "";
				$order = "replies DESC";
				break;
			case "noreplies":
				$menu  = array("selected", "");
				$where = "AND replies = '1'";
				$order = "last_post_date DESC";
				break;
			case "bestanswered":
				$menu  = array("selected", "");
				$where = "AND with_best_answer = '1'";
				$order = "last_post_date DESC";
				break;
			case "polls":
				$menu  = array("selected", "");
				$where = "AND poll_question <> ''";
				$order = "last_post_date DESC";
				break;
			default:
				$menu  = array("selected", "");
				$where = "";
				$order = "last_post_date DESC";
		}

		// Return menu item
		$this->Set("menu", $menu);

		// If admin, then also select all invisible threads; and threads with an opening date
		$is_admin = ($this->Session->IsAdmin()) ? "" : "AND c_threads.start_date < " . time();

		// Execute query
		$threads = $this->Db->Query("SELECT c_threads.*, author.username AS author_name, author.email,
				author.photo_type, author.photo, lastpost.username AS last_post_name,
				(SELECT post FROM c_posts WHERE thread_id = c_threads.t_id ORDER BY post_date LIMIT 1) as post FROM c_threads
				INNER JOIN c_members AS author ON (c_threads.author_member_id = author.m_id)
				INNER JOIN c_members AS lastpost ON (c_threads.last_post_member_id = lastpost.m_id)
				WHERE room_id = {$room_id} {$where} {$is_admin} ORDER BY announcement DESC, {$order}
				LIMIT 10;");

		// Process data
		while($result = $this->Db->Fetch($threads)) {
			$_thread[] = $this->_ParseThread($result);
		}

		return $_thread;
	}

	/**
	 * --------------------------------------------------------------------
	 * PARSE AND PROCESS THREAD INFO
	 * --------------------------------------------------------------------
	 */
	private function _ParseThread($result)
	{
		// Check if thread has already been read
		$is_unread = false;
		$read_threads_cookie = $this->Session->GetCookie("addictive_community_read_threads");
		if($read_threads_cookie) {
			$login_time_cookie = $this->Session->GetCookie("addictive_community_login_time");
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
		$result['author_avatar'] = $this->Core->GetAvatar($result, 84);
		$result['author_avatar'] = Html::Crop($result['author_avatar'], 42, 42, "image");

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
