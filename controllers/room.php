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
			$order = "AND author_member_id = '{$main->info['member_id']}' ORDER BY lastpost_date DESC";
			break;
		case "topreplies":
			$order = "ORDER BY replies DESC";
			break;
		case "noreplies":
			$order = "AND replies = '1' ORDER BY lastpost_date DESC";
			break;
		case "bestanswered":
			$order = "AND with_bestanswer = '1' ORDER BY lastpost_date DESC";
			break;
		default:
			$order = "ORDER BY lastpost_date DESC";
	}

	// Get list of threads

	$this->Db->Query("SELECT c_threads.*, author.username AS author_name, lastpost.username AS lastpost_name,
		(SELECT post FROM c_posts WHERE thread_id = c_threads.t_id ORDER BY post_date LIMIT 1) as post FROM c_threads
		INNER JOIN c_members AS author ON (c_threads.author_member_id = author.m_id)
		INNER JOIN c_members AS lastpost ON (c_threads.lastpost_member_id = lastpost.m_id)
		WHERE room_id = {$roomId} {$order};");

	// Process data

	while($result = $this->Db->Fetch()) {
		$result['class'] = "";
		$result['description'] = Text::RemoveBBcode($result['post']);

		// Status: unread

		// Status: hot

		if($result['replies'] >= $this->Core->config['thread_posts_hot']) {
			$result['class'] .= "hot";
		}

		// Status: locked

		// Status: answered

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