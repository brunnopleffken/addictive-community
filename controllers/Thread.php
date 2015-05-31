<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Thread.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
## -------------------------------------------------------

class Thread extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * SHOW THREAD
	 * --------------------------------------------------------------------
	 */
	public function Main($id)
	{
		// Define messages
		$message_id = Html::Request("m");
		$notification = array("",
			Html::Notification(i18n::Translate("T_MESSAGE_1"), "success"),
			Html::Notification(i18n::Translate("T_MESSAGE_2"), "success"),
			Html::Notification(i18n::Translate("T_MESSAGE_3"), "success"),
			Html::Notification(i18n::Translate("T_MESSAGE_4"), "success"),
			Html::Notification(i18n::Translate("T_MESSAGE_5"), "success"),
			Html::Notification(i18n::Translate("T_MESSAGE_6"), "success"),
			Html::Notification(i18n::Translate("T_MESSAGE_7"), "success")
		);

		// Get thread information
		$thread_info = $this->_GetThreadInfo($id);

		// Update session table with room ID
		$this->_UpdateSessionTable($thread_info);

		// Avoid page navigation from incrementing visit counter
		$_SERVER['HTTP_REFERER'] = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : false;
		if(!strstr($_SERVER['HTTP_REFERER'], "thread")) {
			$this->Db->Query("UPDATE c_threads SET views = views + 1 WHERE t_id = '{$id}';");
		}

		// Get emoticons
		$emoticons = $this->Db->Query("SELECT * FROM c_emoticons
				WHERE emoticon_set = '" . $this->config['emoticon_default_set'] . "' AND display = '1';");
		$emoticons = $this->Db->FetchToArray($emoticons);

		// Get first post
		$first_post_info = $this->_GetFirstPost($id, $emoticons);

		// Get replies
		$pages = $this->_GetPages($thread_info);
		$replies = $this->_GetReplies($id, $emoticons, $pages, $thread_info);

		// Build pagination links
		$pagination = $this->_BuildPaginationLinks($pages, $id);

		// Get related threads
		$related_thread_list = $this->_RelatedThreads($id, $thread_info['title']);

		// Page info
		$page_info['title'] = $thread_info['title'];
		$page_info['bc'] = array($thread_info['name'], $thread_info['title']);
		$this->Set("page_info", $page_info);

		$this->Set("thread_id", $id);
		$this->Set("thread_info", $thread_info);
		$this->Set("notification", $notification[$message_id]);
		$this->Set("enable_signature", $this->config['general_member_enable_signature']);
		$this->Set("first_post_info", $first_post_info);
		$this->Set("reply", $replies);
		$this->Set("pagination", $pagination);
		$this->Set("related_thread_list", $related_thread_list);
		$this->Set("is_moderator", $this->_IsModerator($thread_info['moderators']));
	}

	/**
	 * --------------------------------------------------------------------
	 * REPLY THREAD
	 * --------------------------------------------------------------------
	 */
	public function Reply($id)
	{
		// Do not allow guests
		$this->Session->NoGuest();

		$this->Db->Query("SELECT t.t_id, t.title, r.r_id, r.name, r.upload FROM c_threads t
				INNER JOIN c_rooms r ON (t.room_id = r.r_id) WHERE t_id = {$id};");
		$thread_info = $this->Db->Fetch();

		// Page info
		$page_info['title'] = i18n::Translate("P_ADD_REPLY") . ": " . $thread_info['title'];
		$page_info['bc'] = array($thread_info['name'], i18n::Translate("P_ADD_REPLY") . ": " . $thread_info['title']);
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("thread_id", $id);
		$this->Set("thread_info", $thread_info);
		$this->Set("allow_uploads", $thread_info['upload']);
	}

	/**
	 * --------------------------------------------------------------------
	 * ADD NEW THREAD
	 * --------------------------------------------------------------------
	 */
	public function Add($room_id)
	{
		// Do not allow guests
		$this->Session->NoGuest();

		$this->Db->Query("SELECT r_id, name, upload, moderators FROM c_rooms WHERE r_id = {$room_id};");
		$room_info = $this->Db->Fetch();

		// Page info
		$page_info['title'] = i18n::Translate("P_NEW_THREAD") . ": " . $room_info['name'];
		$page_info['bc'] = array($room_info['name'], i18n::Translate("P_NEW_THREAD"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("room_info", $room_info);
		$this->Set("allow_uploads", $room_info['upload']);
		$this->Set("is_moderator", $this->_IsModerator($room_info['moderators']));
		$this->Set("is_poll", Html::Request("poll"));
	}

	/**
	 * --------------------------------------------------------------------
	 * EDIT AN EXISTING POST
	 * --------------------------------------------------------------------
	 */
	public function EditPost($post_id)
	{
		// Don't allow guests
		$this->Session->NoGuest();

		// Get post info
		$this->Db->Query("SELECT * FROM c_posts WHERE p_id = {$post_id};");
		$post_info = $this->Db->Fetch();

		// Check if the author is the user currently logged in
		if($this->Session->session_info['member_id'] != $post_info['author_id']) {
			Html::Error("You cannot edit a post that you did not publish.");
		}

		// Get thread info
		$this->Db->Query("SELECT title FROM c_threads WHERE t_id = {$post_info['thread_id']};");
		$thread_info = $this->Db->Fetch();

		// Return variables
		$this->Set("thread_info", $thread_info);
		$this->Set("post_info", $post_info);
	}

	/**
	 * --------------------------------------------------------------------
	 * INSERT NEW REPLY INTO DATABASE
	 * --------------------------------------------------------------------
	 */
	public function Save($id)
	{
		$this->layout = false;

		// Get room ID
		$room_id = Html::Request("room_id");

		// Format new post array
		$post = array(
			"author_id"   => $this->Session->member_info['m_id'],
			"thread_id"   => Html::Request("id", true),
			"post_date"   => time(),
			"ip_address"  => $_SERVER['REMOTE_ADDR'],
			"post"        => $_POST['post'],
			"best_answer" => 0,
			"first_post"  => 0
		);

		// Send attachments
		$Upload = new Upload($this->Db);
		$post['attach_id'] = $Upload->Attachment(Html::File("attachment"), $post['author_id']);

		// Insert new post into DB
		$this->Db->Insert("c_posts", $post);

		// Update: thread stats
		$this->Db->Query("UPDATE c_threads SET replies = replies + 1,
				lastpost_date = '{$post['post_date']}', lastpost_member_id = '{$post['author_id']}'
				WHERE t_id = '{$post['thread_id']}';");

		// Update: room stats
		$this->Db->Query("UPDATE c_rooms SET lastpost_date = '{$post['post_date']}',
				lastpost_thread = '{$post['thread_id']}', lastpost_member = '{$post['author_id']}'
				WHERE r_id = '{$room_id}';");

		// Update: member stats
		$this->Db->Query("UPDATE c_members SET posts = posts + 1, lastpost_date = '{$post['post_date']}'
				WHERE m_id = '{$post['author_id']}';");

		// Update: community stats
		$this->Db->Query("UPDATE c_stats SET total_posts = total_posts + 1;");

		// Redirect back to post
		$this->Core->Redirect("thread/" . $id);
	}

	/**
	 * --------------------------------------------------------------------
	 * INSERT NEW THREAD INTO DATABASE
	 * --------------------------------------------------------------------
	 */
	public function SaveThread($room_id)
	{
		$this->layout = false;

		// If we're adding a poll, build poll array
		if(Html::Request("poll_question")) {
			// Transform list of choices in an array
			$questions = explode("\r\n", trim(Html::Request("poll_choices")));
			$questions = array_filter($questions, "trim");

			 // For each question, add a corresponding number of votes...
			 // ...in this case: zero!
			for($i = 0; $i < count($questions); $i++) {
				$replies[] = 0;
			}

			// Put everything together
			$poll_data = array(
				"questions" => $questions,
				"replies"   => $replies,
				"voters"    => array()
			);

			// Serialize poll data array into JSON
			$poll_data = json_encode($poll_data);
		}
		else {
			$poll_data = "";
		}

		// Insert new thread item
		$thread = array(
			"title"               => Html::Request("title"),
			"slug"                => String::Slug(Html::Request("title")),
			"author_member_id"    => $this->Session->member_info['m_id'],
			"replies"             => 1,
			"views"               => 0,
			"start_date"          => time(),
			"room_id"             => Html::Request("room_id", true),
			"announcement"        => Html::Request("announcement", true),
			"lastpost_date"       => time(),
			"lastpost_member_id"  => $this->Session->member_info['m_id'],
			"locked"              => Html::Request("locked", true),
			"approved"            => 1,
			"with_bestanswer"     => 0,
			"poll_question"       => Html::Request("poll_question"),
			"poll_data"           => $poll_data,
			"poll_allow_multiple" => (isset($_POST['poll_allow_multiple'])) ? 1 : 0
		);
		$this->Db->Insert("c_threads", $thread);

		// Insert first post
		$post = array(
			"author_id"   => $this->Session->member_info['m_id'],
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

		// Redirect
		$this->Core->Redirect("thread/" . $post['thread_id'] . "-" . $thread['slug']);
	}

	/**
	 * --------------------------------------------------------------------
	 * SAVE EDITED POST
	 * --------------------------------------------------------------------
	 */
	public function SaveEdit($post_id)
	{
		$this->layout = false;

		// Do not allow guests
		$this->Session->NoGuest();

		// Check if the author is the user currently logged in
		if($this->Session->session_info['member_id'] != Html::Request("member_id")) {
			Html::Error("You cannot edit a post that you did not publish.");
		}

		$post = array(
			"post"        => $_REQUEST['post'],
			"edit_time"   => time(),
			"edit_author" => $this->Session->member_info['m_id']
		);

		// Insert edited post on DB
		$this->Db->Update("c_posts", $post, "p_id = {$post_id}");

		// Redirect
		$this->Core->Redirect("thread/" . Html::Request("thread_id") . "#post-" . $post_id);
	}

	/**
	 * --------------------------------------------------------------------
	 * DELETE A POST
	 * --------------------------------------------------------------------
	 */
	public function DeletePost()
	{
		$this->layout = false;

		// Do not allow guests
		$this->Session->NoGuest();

		// Get post information
		$author_id = Html::Request("mid");
		$thread_id = Html::Request("tid");
		$post_id = Html::Request("pid");

		// Check if the author is the user currently logged in member
		if($this->Session->session_info['member_id'] != $author_id) {
			Html::Error("You cannot delete a post that you did not publish.");
		}

		// Remove post
		$this->Db->Query("DELETE FROM c_posts WHERE p_id = {$post_id};");

		// Update thread statistics
		$this->Db->Query("UPDATE c_threads SET replies = replies - 1 WHERE t_id = {$thread_id};");

		// Update member statistics
		$this->Db->Query("UPDATE c_members SET posts = posts - 1 WHERE m_id = {$author_id};");

		// Update community statistics
		$this->Db->Query("UPDATE c_stats SET total_posts = total_posts - 1;");

		// Redirect back to post
		$this->Core->Redirect("thread/" . $thread_id . "?m=3");
	}

	/**
	 * --------------------------------------------------------------------
	 * MODERATION OPTIONS: LOCK THREAD
	 * --------------------------------------------------------------------
	 */
	public function Lock($thread_id)
	{
		$this->layout = false;

		// Do not allow guests
		$this->Session->NoGuest();

		// Lock thread
		$this->Db->Query("UPDATE c_threads SET locked = 1 WHERE t_id = {$thread_id};");

		// Register Moderation log in DB
		$log = array(
			"member_id"  => $this->Session->session_info['member_id'],
			"time"       => time(),
			"act"        => "Locked thread: ID #" . $thread_id,
			"ip_address" => $_SERVER['REMOTE_ADDR']
		);
		$this->Db->Insert("c_logs", $log);

		header("Location: " . preg_replace("/(\?m=[0-9])/", "", $_SERVER['HTTP_REFERER']) . "?m=4");
		exit;
	}

	/**
	 * --------------------------------------------------------------------
	 * MODERATION OPTIONS: UNLOCK THREAD
	 * --------------------------------------------------------------------
	 */
	public function Unlock($thread_id)
	{
		$this->layout = false;

		// Do not allow guests
		$this->Session->NoGuest();

		// Lock thread
		$this->Db->Query("UPDATE c_threads SET locked = 0 WHERE t_id = {$thread_id};");

		// Register Moderation log in DB
		$log = array(
			"member_id"  => $this->Session->session_info['member_id'],
			"time"       => time(),
			"act"        => "Unlocked thread: ID #" . $thread_id,
			"ip_address" => $_SERVER['REMOTE_ADDR']
		);
		$this->Db->Insert("c_logs", $log);

		header("Location: " . preg_replace("/(\?m=[0-9])/", "", $_SERVER['HTTP_REFERER']) . "?m=5");
		exit;
	}

	/**
	 * --------------------------------------------------------------------
	 * MODERATION OPTIONS: SET THREAD AS ANNOUNCEMENT
	 * --------------------------------------------------------------------
	 */
	public function AnnouncementSet($thread_id)
	{
		$this->layout = false;

		// Do not allow guests
		$this->Session->NoGuest();

		// Lock thread
		$this->Db->Query("UPDATE c_threads SET announcement = 1 WHERE t_id = {$thread_id};");

		// Register Moderation log in DB
		$log = array(
			"member_id"  => $this->Session->session_info['member_id'],
			"time"       => time(),
			"act"        => "Set thread ID #" . $thread_id . " as announcement",
			"ip_address" => $_SERVER['REMOTE_ADDR']
		);
		$this->Db->Insert("c_logs", $log);

		header("Location: " . preg_replace("/(\?m=[0-9])/", "", $_SERVER['HTTP_REFERER']) . "?m=6");
		exit;
	}

	/**
	 * --------------------------------------------------------------------
	 * MODERATION OPTIONS: REMOVE THREAD AS ANNOUNCEMENT
	 * --------------------------------------------------------------------
	 */
	public function AnnouncementUnset($thread_id)
	{
		$this->layout = false;

		// Do not allow guests
		$this->Session->NoGuest();

		// Lock thread
		$this->Db->Query("UPDATE c_threads SET announcement = 0 WHERE t_id = {$thread_id};");

		// Register Moderation log in DB
		$log = array(
			"member_id"  => $this->Session->session_info['member_id'],
			"time"       => time(),
			"act"        => "Removed thread ID #" . $thread_id . " as announcement",
			"ip_address" => $_SERVER['REMOTE_ADDR']
		);
		$this->Db->Insert("c_logs", $log);

		header("Location: " . preg_replace("/(\?m=[0-9])/", "", $_SERVER['HTTP_REFERER']) . "?m=7");
		exit;
	}

	/**
	 * --------------------------------------------------------------------
	 * MODERATION OPTIONS: COMPLETELY REMOVE THREAD AND ITS CONTENT
	 * --------------------------------------------------------------------
	 */
	public function Delete($thread_id)
	{
		$this->layout = false;

		// Do not allow guests
		$this->Session->NoGuest();

		// Get room ID
		$this->Db->Query("SELECT room_id FROM c_threads WHERE t_id = {$thread_id};");
		$thread_info = $this->Db->Fetch(); // $thread_info['room_id']
		$room_id = $thread_info['room_id'];

		// Delete all posts in this thread
		$this->Db->Query("DELETE FROM c_posts WHERE thread_id = {$thread_id};");
		$deleted_posts = $this->Db->AffectedRows();

		// Delete thread itself
		$this->Db->Query("DELETE FROM c_threads WHERE t_id = {$thread_id};");

		// Update community/room statistics
		$this->Db->Query("UPDATE c_stats SET
				total_threads = total_threads - 1,
				total_posts = total_posts - {$deleted_posts};");

		// Register Moderation log in DB
		$log = array(
			"member_id"  => $this->Session->session_info['member_id'],
			"time"       => time(),
			"act"        => "Deleted thread: ID #" . $thread_id,
			"ip_address" => $_SERVER['REMOTE_ADDR']
		);
		$this->Db->Insert("c_logs", $log);

		$this->Core->Redirect("room/" . $room_id . "?m=7");
		exit;
	}

	/**
	 * --------------------------------------------------------------------
	 * SAVE POLL VOTES
	 * --------------------------------------------------------------------
	 */
	public function SavePoll($thread_id)
	{
		$this->layout = false;

		// Do not allow guests
		$this->Session->NoGuest();

		// Get updated poll info in DB
		$this->Db->Query("SELECT poll_data, poll_allow_multiple FROM c_threads WHERE t_id = {$thread_id};");
		$thread_info = $this->Db->Fetch();
		$poll_data = json_decode($thread_info['poll_data'], true);

		if($thread_info['poll_allow_multiple'] == 0) {
			// Increase vote count
			$poll_data['replies'][Html::Request("chosen_option")] += 1;

			// Add member ID to voters list
			array_push($poll_data['voters'], $this->Session->member_info['m_id']);
		}
		else {
			// If poll allow multiple choice
			// ...
		}

		// Update thread information with encoded data
		$encoded = json_encode($poll_data);
		$this->Db->Query("UPDATE c_threads SET poll_data = '{$encoded}' WHERE t_id = {$thread_id};");

		// Redirect
		$this->Core->Redirect("HTTP_REFERER");
	}

	/**
	 * --------------------------------------------------------------------
	 * GET GENERAL THREAD INFORMATION, LIKE NUMBER OF REPLIES, CHECK IF
	 * IT'S AN OBSOLETE THREAD AND PERMISSIONS
	 * --------------------------------------------------------------------
	 */
	private function _GetThreadInfo($id)
	{
		// Select thread info from database
		$thread = $this->Db->Query("SELECT t.title, t.room_id, t.author_member_id, t.locked, t.announcement,
				t.lastpost_date, t.poll_question, t.poll_data, t.poll_allow_multiple,
				r.r_id, r.name, r.perm_view, r.perm_reply, r.moderators,
				(SELECT COUNT(*) FROM c_posts p WHERE p.thread_id = t.t_id) AS post_count FROM c_threads t
				INNER JOIN c_rooms r ON (r.r_id = t.room_id) WHERE t.t_id = '{$id}';");
		$thread_info = $this->Db->Fetch($thread);

		// Calculate the number of actual replies (first post is not a reply)
		$thread_info['post_count_display'] = $thread_info['post_count'] - 1;

		// Check if thread has a poll
		$thread_info['poll'] = $this->_GetPoll($thread_info);

		// Check permission to read
		$thread_info['perm_view'] = unserialize($thread_info['perm_view']);
		$permission_value = "V_" . $this->Session->member_info['usergroup'];
		if(!in_array($permission_value, $thread_info['perm_view'])) {
			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit;
		}

		// Check permission to reply
		$thread_info['perm_reply'] = unserialize($thread_info['perm_reply']);
		$permission_value = "V_" . $this->Session->member_info['usergroup'];
		if(in_array($permission_value, $thread_info['perm_reply'])) {
			$thread_info['allow_to_reply'] = true;
		}
		else {
			$thread_info['allow_to_reply'] = false;
		}

		// Check if it's an obsolete thread
		$obsolete_notification = "";
		$obsolete_seconds = $this->config['thread_obsolete_value'] * DAY;
		if(($thread_info['lastpost_date'] + $obsolete_seconds) < time()) {
			$thread_info['obsolete'] = true;
			$obsolete_notification = Html::Notification(
				i18n::Translate("T_OBSOLETE", array($this->config['thread_obsolete_value'])), "warning", true
			);
		}
		else {
			$thread_info['obsolete'] = false;
		}

		return $thread_info;
	}

	/**
	 * --------------------------------------------------------------------
	 * CHECK IF THREAD HAS POLL. IF TRUE, BUILD AND RETURN ELEMENTS.
	 * --------------------------------------------------------------------
	 */
	private function _GetPoll($thread_info)
	{
		// Get poll information if poll exists
		if($thread_info['poll_question'] != "") {
			$poll_data = json_decode($thread_info['poll_data'], true);

			// Get total of votes
			$total = array_sum($poll_data['replies']);
			$ratio = 100 / $total;

			// Get percentage of votes for each option
			foreach($poll_data['replies'] as $k => $v) {
				$poll_data['percentage'][$k] = $v * $ratio;
			}

			$poll_info = array(
				"has_poll"  => true,
				"question"  => $thread_info['poll_question'],
				"choices"   => $poll_data['questions'],
				"multiple"  => $thread_info['poll_allow_multiple'],
				"replies_n" => $poll_data['replies'],
				"replies_p" => $poll_data['percentage'],
				"voters"    => $poll_data['voters'],
				"total"     => $total
			);

			// Check if user has already voted in this poll
			$poll_info['has_voted'] = in_array($this->Session->member_info['m_id'], $poll_info['voters']);
		}
		else {
			// If not, return false
			$poll_info = array(
				"has_poll" => false
			);
		}

		return $poll_info;
	}

	/**
	 * --------------------------------------------------------------------
	 * UPDATE SESSION TABLE WITH THREAD ID IN 'location_room_id'
	 * --------------------------------------------------------------------
	 */
	private function _UpdateSessionTable($thread_info)
	{
		// Update session table with room ID
		$session = $this->Session->session_id;
		$this->Db->Query("UPDATE c_sessions SET location_room_id = {$thread_info['room_id']} WHERE s_id = '{$session}';");
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURNS AN ARRAY OF BADWORDS
	 * --------------------------------------------------------------------
	 */
	private function _FilterBadWords($text)
	{
		if($this->config['language_bad_words'] != "") {
			$bad_words = explode("\n", $this->config['language_bad_words']);
			$bad_words_list = preg_replace("/(\r|\n)/i", "", "/(" . implode("|", $bad_words) . ")/i");

			return preg_replace($bad_words_list, $this->config['language_bad_words_replacement'], $text);
		}
		else {
			return $text;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * GET FIRST POST CONTENT
	 * --------------------------------------------------------------------
	 */
	private function _GetFirstPost($id, $emoticons)
	{
		// Declare return array
		$first_post_info = array();

		$first_post = $this->Db->Query("SELECT c_posts.*, c_threads.t_id, c_threads.tags, c_threads.room_id,
				c_attachments.*, c_threads.title, c_threads.locked, c_members.* FROM c_posts
				INNER JOIN c_threads ON (c_posts.thread_id = c_threads.t_id)
				INNER JOIN c_members ON (c_posts.author_id = c_members.m_id)
				LEFT JOIN c_attachments ON (c_posts.attach_id = c_attachments.a_id)
				WHERE thread_id = '{$id}' AND first_post = '1' LIMIT 1;");

		$first_post_info = $this->Db->Fetch($first_post);

		// Format first thread
		$first_post_info['avatar'] = $this->Core->GetGravatar($first_post_info['email'], $first_post_info['photo'], 96, $first_post_info['photo_type']);
		$first_post_info['post_date'] = $this->Core->DateFormat($first_post_info['post_date']);

		// Block bad words
		$first_post_info['post'] = $this->_FilterBadWords($first_post_info['post']);

		// Get emoticons
		$first_post_info['post'] = $this->Core->ParseEmoticons($first_post_info['post'], $emoticons);

		// Get attachment link, if there is one
		if($first_post_info['attach_id'] != 0) {
			$first_post_info['attachment_link'] = $this->_GetAttachment($first_post_info);
		}

		return $first_post_info;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET REPLIES
	 * --------------------------------------------------------------------
	 */
	private function _GetReplies($id, $emoticons, $pages, $thread_info)
	{
		$reply_result = array();

		$replies = $this->Db->Query("SELECT c_posts.*, c_attachments.*, c_members.*, edit.username AS edit_username FROM c_posts
				INNER JOIN c_members ON (c_posts.author_id = c_members.m_id)
				LEFT JOIN c_members AS edit ON (c_posts.edit_author = edit.m_id)
				LEFT JOIN c_attachments ON (c_posts.attach_id = c_attachments.a_id)
				WHERE thread_id = '{$id}' AND first_post = '0'
				ORDER BY best_answer DESC, post_date ASC
				LIMIT {$pages['for_sql']},{$pages['items_per_page']};");

		while($result = $this->Db->Fetch($replies)) {
			// Is this a best answer or a regular reply?
			$result['bestanswer_class'] = ($result['best_answer'] == 1) ? "best-answer" : "";

			// Member information
			$result['avatar'] = $this->Core->GetGravatar($result['email'], $result['photo'], 192, $result['photo_type']);
			$result['joined'] = $this->Core->DateFormat($result['joined'], "short");
			$result['post_date'] = $this->Core->DateFormat($result['post_date']);

			// Block bad words
			$result['post'] = $this->_FilterBadWords($result['post']);

			// Get emoticons
			$result['post'] = $this->Core->ParseEmoticons($result['post'], $emoticons);

			// Get attachment link, if there is one
			if($result['attach_id'] != 0) {
				$result['attachment_link'] = $this->_GetAttachment($result);
			}

			// Show label if post was edited
			if(isset($result['edit_time'])) {
				$result['edit_time'] = $this->Core->DateFormat($result['edit_time']);
				$result['edited']    = "(" . i18n::Translate("T_EDITED", array($result['edit_time'], $result['edit_username'])) . ")";
			}
			else {
				$result['edited'] = "";
			}

			// Build post/thread action controls
			$result['post_controls'] = "";
			$result['thread_controls'] = "";

			// Post controls
			if($result['author_id'] == $this->Session->member_info['m_id']) {
				$result['post_controls'] = "<a href='thread/edit_post/{$result['p_id']}' class='small-button grey'>" . i18n::Translate("T_EDIT") . "</a> "
					. "<a href='#deleteThreadConfirm' data-post='{$result['p_id']}' data-thread='{$id}' data-member='{$result['author_id']}' class='fancybox delete-post-button small-button grey'>" . i18n::Translate("T_DELETE") . "</a>";
			}

			// Thread controls
			if($thread_info['author_member_id'] == $this->Session->member_info['m_id'] && $result['author_id'] != $this->Session->member_info['m_id']) {
				if($result['best_answer'] == 0) {
					// Set post as Best Answer
					$result['thread_controls'] = "<a href='thread/setbestanswer/{$result['p_id']}' class='small-button grey'>" . i18n::Translate("T_BEST_SET") . "</a>";
				}
				else {
					// Unset post as Best Answer
					$result['thread_controls'] = "<a href='thread/unsetbestanswer/{$result['p_id']}' class='small-button grey'>" . i18n::Translate("T_BEST_UNSET") . "</a>";
				}
			}

			$reply_result[] = $result;
		}

		return $reply_result;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET ATTACHMENT LINK
	 * --------------------------------------------------------------------
	 */
	private function _GetAttachment($post_info) {
		return sprintf(
			"public/attachments/%s/%s/%s",
			$post_info['member_id'],
			$post_info['date'],
			$post_info['filename']
		);
	}

	/**
	 * --------------------------------------------------------------------
	 * GET NUMBER OF PAGES AND VALUES FOR SQL QUERY
	 * --------------------------------------------------------------------
	 */
	private function _GetPages($thread_info)
	{
		$pages['items_per_page'] = $this->config['thread_posts_per_page'];
		$total_posts    = $thread_info['post_count'] - 1;

		// page number for SQL sentences
		$pages['for_sql'] = (Html::Request("p")) ? Html::Request("p") * $pages['items_per_page'] - $pages['items_per_page'] : 0;

		// page number for HTML page numbers
		$pages['display'] = (isset($_REQUEST['p'])) ? $_REQUEST['p'] : 1;
		$pages['total'] = ceil($total_posts / $pages['items_per_page']);

		return $pages;
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURN PAGINATION HTML
	 * --------------------------------------------------------------------
	 */
	private function _BuildPaginationLinks($pages, $thread_id)
	{
		$html = "";
		if($pages['total'] != 0) {
			$html .= "<div class='pages'>" . i18n::Translate("T_PAGES") . ": ";

			// If it is not the first page, show link "Back"
			if($pages['display'] != 1) {
				$prev = $pages['display'] - 1;
				$html .= "<a href='thread/{$thread_id}?p={$prev}'>&laquo;</a>\n";
			}

			// Page numbers
			for($i = 1; $i <= $pages['total']; $i++) {
				if($i == $pages['display']) {
					$html .= "<a href='thread/{$thread_id}?p={$i}' class='page-selected'>{$i}</a>\n";
				}
				else {
					$html .= "<a href='thread/{$thread_id}?p={$i}'>{$i}</a>\n";
				}
			}

			// If it is not the last page, show link "Next"
			if($pages['display'] != $i - 1) {
				$next = $pages['display'] + 1;
				$html .= "<a href='thread/{$thread_id}?p={$next}'>&raquo;</a>\n";
			}

			$html .= "</div>";
		}

		return $html;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET LIST OF RELATED THREADS
	 * --------------------------------------------------------------------
	 */
	private function _RelatedThreads($id, $thread_title)
	{
		$thread_list = "";
		$thread_search = explode(" ", strtolower($thread_title));
		$related_thread_list = array();

		foreach($thread_search as $key => $value) {
			if(strlen($value) < 4) {
				unset($thread_search[$key]);
			}
		}

		$thread_search = implode(" ", $thread_search);
		$this->Db->Query("SELECT *, MATCH(title) AGAINST ('{$thread_search}') AS relevance FROM c_threads
				WHERE t_id <> {$id} AND MATCH(title) AGAINST ('{$thread_search}');");

		while($thread = $this->Db->Fetch()) {
			$thread['thread_date'] = $this->Core->DateFormat($thread['lastpost_date'], "short");
			$related_thread_list[] = $thread;
		}

		return $related_thread_list;
	}

	/**
	 * --------------------------------------------------------------------
	 * WHEN POSTING, CHECK IF MEMBER IS A MODERATOR
	 * --------------------------------------------------------------------
	 */
	private function _IsModerator($moderators_serialized = "") {
		// Get array of moderators
		$moderators_array = unserialize($moderators_serialized);

		// Check if room has moderators and if
		// the current member is a moderator
		if(!empty($moderators_array) && in_array($this->Session->member_info['m_id'], $moderators_array)) {
			return true;
		}
		else {
			// If member is not a moderator, check if is an Administrator
			if($this->Session->member_info['usergroup'] == 1) {
				return true;
			}
			else {
				return false;
			}
		}
	}
}
