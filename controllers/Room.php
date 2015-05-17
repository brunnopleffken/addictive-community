<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Room.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

class Room extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * RUN BEFORE MAIN()
	 * --------------------------------------------------------------------
	 */
	public function _beforeFilter()
	{
		if(Html::Request("act") == "load_more") {
			// Update session table with room ID
			$id = Html::Request("id");
			$session = $this->Session->session_id;
			$this->Db->Query("UPDATE c_sessions SET location_room_id = {$id} WHERE s_id = '{$session}';");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW ROOM (THREAD LIST)
	 * --------------------------------------------------------------------
	 */
	public function Main($id)
	{
		// Get room information
		$this->Db->Query("SELECT * FROM c_rooms WHERE r_id = {$id}");
		$room_info = $this->Db->Fetch();

		// Is the room protected?
		if($room_info['password'] != "") {
			$room_session_name = "room_" . $room_info['r_id'];
			if(!$this->Session->GetCookie($room_session_name)) {
				$this->Core->Redirect("error?t=protected_room&room=" . $id);
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
		$this->Set("view", Html::Request("view"));
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
		$page = Html::Request("page");
		$threads_per_page = $this->config['threads_per_page'];

		// Calculate SQL offset
		$offset = $page * $threads_per_page;

		// Get threads
		$this->Db->Query("SELECT c_threads.*, author.username AS author_name, author.email AS author_email,
				author.photo_type AS author_type, author.photo AS author_photo, lastpost.username AS lastpost_name,
				(SELECT post FROM c_posts WHERE thread_id = c_threads.t_id ORDER BY post_date LIMIT 1) as post FROM c_threads
				INNER JOIN c_members AS author ON (c_threads.author_member_id = author.m_id)
				INNER JOIN c_members AS lastpost ON (c_threads.lastpost_member_id = lastpost.m_id)
				WHERE room_id = {$id} ORDER BY announcement DESC, lastpost_date DESC
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

		$password = Html::Request("password");
		$room_id  = Html::Request("room");

		$this->Db->Query("SELECT password FROM c_rooms WHERE r_id = {$room_id}");
		$room_info = $this->Db->Fetch();

		if($password == $room_info['password']) {
			$room_session_name = "room_" . $room_id;
			$this->Session->CreateCookie($room_session_name, 1);
			$this->Core->Redirect("room/" . $room_id);
			exit;
		}
		else {
			$this->Core->Redirect("error?t=protected_room&room=" . $room_id);
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
		$view = Html::Request("view");

		// Filter thread list
		switch($view) {
			case "mythreads":
				$menu  = array("", "selected");
				$where = "AND author_member_id = '{$this->Session->member_info['m_id']}'";
				$order = "lastpost_date DESC";
				break;
			case "topreplies":
				$menu  = array("selected", "");
				$where = "";
				$order = "replies DESC";
				break;
			case "noreplies":
				$menu  = array("selected", "");
				$where = "AND replies = '1'";
				$order = "lastpost_date DESC";
				break;
			case "bestanswered":
				$menu  = array("selected", "");
				$where = "AND with_bestanswer = '1'";
				$order = "lastpost_date DESC";
				break;
			default:
				$menu  = array("selected", "");
				$where = "";
				$order = "lastpost_date DESC";
		}

		// Return menu item
		$this->Set("menu", $menu);

		// Execute query
		$threads = $this->Db->Query("SELECT c_threads.*, author.username AS author_name, author.email AS author_email,
				author.photo_type AS author_type, author.photo AS author_photo, lastpost.username AS lastpost_name,
				(SELECT post FROM c_posts WHERE thread_id = c_threads.t_id ORDER BY post_date LIMIT 1) as post FROM c_threads
				INNER JOIN c_members AS author ON (c_threads.author_member_id = author.m_id)
				INNER JOIN c_members AS lastpost ON (c_threads.lastpost_member_id = lastpost.m_id)
				WHERE room_id = {$room_id} {$where} ORDER BY announcement DESC, {$order}
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
		$result['class'] = "";
		$result['description'] = strip_tags($result['post']);
		$result['mobile_start_date'] = $this->Core->DateFormat($result['start_date'], "short");
		$result['start_date'] = $this->Core->DateFormat($result['start_date']);
		$result['lastpost_date'] = $this->Core->DateFormat($result['lastpost_date']);

		// Author avatar
		$result['author_avatar'] = $this->Core->GetGravatar($result['author_email'], $result['author_photo'], 84, $result['author_type']);
		$result['author_avatar'] = Html::Crop($result['author_avatar'], 42, 42, "image");

		// Build phrases using internationalization
		$result['author_name'] = i18n::Translate("R_STARTED_BY", array($result['author_name']));
		$result['start_date'] = i18n::Translate("R_STARTED_ON", array($result['start_date']));
		$result['views'] = i18n::Translate("R_VIEWS", array($result['views']));
		$result['lastpost_by'] = i18n::Translate("R_LAST_POST_BY", array($result['lastpost_name']));

		// Get the number of replies, not total number of posts... ;)
		$result['replies']--;

		// Status: locked
		if($result['locked'] == 1) {
			$result['class'] = "locked ";
		}

		// Status: answered
		if($result['with_bestanswer'] == 1) {
			$result['class'] = "answered ";
		}

		// Status: announcement
		if($result['announcement'] == 1) {
			$result['class'] = "announcement ";
		}

		// Status: hot
		if($result['replies'] >= $this->config['thread_posts_hot']) {
			$result['class'] .= "hot";
		}

		return $result;
	}
}
