<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: newthread.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Define access method
	// ---------------------------------------------------

	// Deny guest access
	$this->Session->NoGuest();

	// ---------------------------------------------------
	// Get action
	// ---------------------------------------------------

	$act = Html::Request("act");

	switch($act) {
		case 'add':
			// Insert new thread item
			$thread = array(
				"title"              => Html::Request("title"),
				"author_member_id"   => $this->member['m_id'],
				"replies"            => 1,
				"views"              => 0,
				"start_date"         => time(),
				"room_id"            => Html::Request("room_id", true),
				"announcement"       => Html::Request("announcement", true),
				"lastpost_date"      => time(),
				"lastpost_member_id" => $this->member['m_id'],
				"locked"             => Html::Request("locked", true),
				"approved"           => 1,
				"with_bestanswer"    => 0
			);
			$this->Db->Insert("c_threads", $thread);

			// Insert first post

			$post = array(
				"author_id"   => $this->member['m_id'],
				"thread_id"   => $this->Db->GetLastID(),
				"post_date"   => $thread['lastpost_date'],
				"ip_address"  => $_SERVER['REMOTE_ADDR'],
				"post"        => $_POST['post'],
				"best_answer" => 0,
				"first_post"  => 1
			);

			$Upload = new Upload($this->Db);
			$post['attach_id'] = $Upload->Attachment(Html::File("attachment"), $post['author_id']);

			$this->Db->Insert("c_posts", $post);

			// Update tables

			$this->Db->Query("UPDATE c_rooms SET lastpost_date = '{$post['post_date']}',
				lastpost_thread = '{$post['thread_id']}', lastpost_member = '{$post['author_id']}'
				WHERE r_id = '{$thread['room_id']}';");

			$this->Db->Query("UPDATE c_stats SET total_posts = total_posts + 1, total_threads = total_threads + 1;");

			$this->Db->Query("UPDATE c_members SET posts = posts + 1, lastpost_date = '{$post['post_date']}'
				WHERE m_id = '{$post['author_id']}';");

			// Redirect to the thread

			header("Location: index.php?module=thread&id=" . $post['thread_id']);
			exit;

			break;
	}

	// ---------------------------------------------------
	// Get user and room information
	// ---------------------------------------------------

	// Get room ID
	$roomId = Html::Request("id", true);

	$this->Db->Query("SELECT r_id, name FROM c_rooms WHERE r_id = {$roomId};");
	$roomInfo = $this->Db->Fetch();

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = i18n::Translate("T_TITLE");
	$pageinfo['bc'] = array(i18n::Translate("T_TITLE"));

?>
