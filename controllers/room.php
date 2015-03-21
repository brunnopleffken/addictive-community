<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: rooms.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Get room ID and sorting
	// ---------------------------------------------------

	$roomId = Html::Request("id", true);
	$act = $this->Core->QueryString("act", "");

	// ---------------------------------------------------
	// Execute actions
	// ---------------------------------------------------

	$execute = Html::Request("execute");

	switch($execute) {
		case 'protected':
			$password = Html::Request("password");

			$this->Db->Query("SELECT password FROM c_rooms WHERE r_id = {$roomId}");
			$roomInfo = $this->Db->Fetch();

			if($password == $roomInfo['password']) {
				$sessionName = "room_" . $roomId;
				$this->Session->CreateCookie($sessionName, 1);

				header("Location: index.php?module=room&id=" . $roomId);
				exit;
			}
			else {
				header("Location: index.php?module=exception&errno=2&r_id=" . $roomId);
				exit;
			}

			exit;
			break;
	}

	// ---------------------------------------------------
	// Get general information
	// ---------------------------------------------------

	// Get room info

	$this->Db->Query("SELECT * FROM c_rooms WHERE r_id = {$roomId}");
	$roomInfo = $this->Db->Fetch();

	// Is the room protected?

	if($roomInfo['password'] != "") {
		$sessionName = "room_" . $roomInfo['r_id'];
		if(!$this->Session->GetCookie($sessionName)) {
			header("Location: index.php?module=exception&errno=2&r_id=" . $roomInfo['r_id']);
		}
	}

	// Check view permission

	$roomInfo['perm_view'] = unserialize($roomInfo['perm_view']);
	$permissionValue = "V_" . $this->member['usergroup'];

	if(!in_array($permissionValue, $roomInfo['perm_view'])) {
		header("Location: index.php?msg=1");
	}

	// Is the room invisible?
	// P.S.: avoid direct URL redirection

	if($roomInfo['invisible'] == 1 && $this->member['usergroup'] != 1) {
		header("Location: index.php?msg=1");
	}

	// Sort threads by...

	switch($act) {
		case "mythreads":
			$menu  = array("", "selected");
			$where = "AND author_member_id = '{$this->member['m_id']}'";
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

	// Is the user logged in?

	if(isset($this->member['m_id'])) {
		$myThreadsMenu = "<a href=\"index.php?module=room&amp;id={$roomId}&amp;act=mythreads\" class=\"transition " . $menu[1] . "\">My Threads</a>";
	}
	else {
		$myThreadsMenu = "";
	}

	// Get notification

	if($roomInfo['rules_visible'] == 1) {
		$notification = Html::Notification($roomInfo['rules_text'], "warning", $roomInfo['rules_title']);
	}

	// Get list of threads

	$threads = $this->Db->Query("SELECT c_threads.*, author.username AS author_name, author.email AS author_email, "
			. "author.photo_type AS author_type, author.photo AS author_photo, lastpost.username AS lastpost_name, "
			. "(SELECT post FROM c_posts WHERE thread_id = c_threads.t_id ORDER BY post_date LIMIT 1) as post "
			. "FROM c_threads INNER JOIN c_members AS author ON (c_threads.author_member_id = author.m_id) "
			. "INNER JOIN c_members AS lastpost ON (c_threads.lastpost_member_id = lastpost.m_id) "
			. "WHERE room_id = {$roomId} {$where} ORDER BY announcement DESC, {$order};");

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
		if($result['replies'] >= $this->Core->config['thread_posts_hot']) {
			$result['class'] .= "hot";
		}

		// Populate results on array
		$_thread[] = $result;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = $roomInfo['name'];
	$pageinfo['bc'] = array($roomInfo['name']);

?>
