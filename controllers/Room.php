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
			$session_name = "room_" . $room_info['r_id'];
			if(!$this->Session->GetCookie($session_name)) {
				header("Location: exception/2");
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
				WHERE room_id = {$room_id} {$where} ORDER BY announcement DESC, {$order};");

		// Process data
		while($result = $this->Db->Fetch($threads)) {
			$result['class'] = "";
			$result['description'] = strip_tags($result['post']);
			$result['mobile_start_date'] = $this->Core->DateFormat($result['start_date'], "short");
			$result['start_date'] = $this->Core->DateFormat($result['start_date']);
			$result['lastpost_date'] = $this->Core->DateFormat($result['lastpost_date']);

			// Author avatar
			$result['author_avatar'] = $this->Core->GetGravatar($result['author_email'], $result['author_photo'], 84, $result['author_type']);
			$result['author_avatar'] = Html::Crop($result['author_avatar'], 42, 42, "image");

			// Get the number of replies, not total number of posts... ;)
			$result['replies']--;

			// Status: unread
				/**
				 * TO DO
				 */

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

			// Populate results on array
			$_thread[] = $result;
		}

		return $_thread;
	}
}
