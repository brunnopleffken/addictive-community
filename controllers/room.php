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

	$roomId = $this->Core->QueryString("id");
	$act = $this->Core->QueryString("act", "");
	
	// ---------------------------------------------------
	// Get general information
	// ---------------------------------------------------

	// Get room info

	$this->Db->Query("SELECT * FROM c_rooms WHERE r_id = {$roomId}");
	$roomInfo = $this->Db->Fetch();

	// Is the room protected?

	// Sort threads by...

	switch($act) {
		case "mythreads":
			$where = "AND author_member_id = '{$main->info['member_id']}'";
			$order = "lastpost_date DESC";
			break;
		case "topreplies":
			$where = "";
			$order = "replies DESC";
			break;
		case "noreplies":
			$where = "AND replies = '1'";
			$order = "lastpost_date DESC";
			break;
		case "bestanswered":
			$where = "AND with_bestanswer = '1'";
			$order = "lastpost_date DESC";
			break;
		default:
			$where = "";
			$order = "lastpost_date DESC";
	}

	// Get list of threads

	$this->Db->Query("SELECT c_threads.*, author.username AS author_name, lastpost.username AS lastpost_name,
		(SELECT post FROM c_posts WHERE thread_id = c_threads.t_id ORDER BY post_date LIMIT 1) as post FROM c_threads
		INNER JOIN c_members AS author ON (c_threads.author_member_id = author.m_id)
		INNER JOIN c_members AS lastpost ON (c_threads.lastpost_member_id = lastpost.m_id)
		WHERE room_id = {$roomId} {$where} ORDER BY announcement DESC, {$order};");

	// Process data

	while($result = $this->Db->Fetch()) {
		$result['class'] = "";
		$result['description'] = String::RemoveBBcode($result['post']);
		$result['lastpost_date'] = $this->Core->DateFormat($result['lastpost_date']);

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