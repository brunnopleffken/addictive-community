<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: post.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Define access method
	// ---------------------------------------------------

	// Deny guest access
	$this->Session->NoGuest();

	// ---------------------------------------------------
	// Get thread information
	// ---------------------------------------------------

	$id = Html::Request("id");

	$this->Db->Query("SELECT t.t_id, t.title, r.r_id, r.name FROM c_threads t "
				. "LEFT JOIN c_rooms r ON (t.t_id = r.r_id) "
				. "WHERE t_id = {$id};");

	$threadInfo = $this->Db->Fetch();

	// ---------------------------------------------------
	// Get action
	// ---------------------------------------------------

	$act = Html::Request("act");

	switch($act) {
		case 'add':
			$roomId = Html::Request("room_id", true);

			$post = array(
				"author_id"   => $this->member['m_id'],
				"thread_id"   => Html::Request("id", true),
				"post_date"   => time(),
				"ip_address"  => $_SERVER['REMOTE_ADDR'],
				"post"        => String::Sanitize(Html::Request("post")),
				"best_answer" => 0,
				"first_post"  => 0
			);

			// Insert new post into DB

			$this->Db->Insert("c_posts", $post);

			// Update tables
		
			$this->Db->Query("COMMIT;");

			$this->Db->Query("UPDATE c_threads SET replies = replies + 1,
				lastpost_date = '{$post['post_date']}', lastpost_member_id = '{$post['author_id']}'
				WHERE t_id = '{$post['thread_id']}';");

			$this->Db->Query("UPDATE c_rooms SET lastpost_date = '{$post['post_date']}',
				lastpost_thread = '{$post['thread_id']}', lastpost_member = '{$post['author_id']}'
				WHERE r_id = '{$roomId}';");

			$this->Db->Query("UPDATE c_members SET posts = posts + 1, lastpost_date = '{$post['post_date']}'
				WHERE m_id = '{$post['author_id']}';");

			$this->Db->Query("UPDATE c_stats SET total_posts = total_posts + 1;");

			// Redirect back to thread

			header("Location: index.php?module=thread&id=" . $post['thread_id']);
			exit;

			break;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = "Add Reply";
	$pageinfo['bc'] = array($threadInfo['name'], $threadInfo['title'], "Add Reply");

?>