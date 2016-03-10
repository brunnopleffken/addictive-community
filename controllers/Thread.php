<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Thread.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

class Thread extends Application
{
	// Thread information
	private $thread_info = array();

	/**
	 * --------------------------------------------------------------------
	 * SHOW THREAD
	 * --------------------------------------------------------------------
	 */
	public function Main($id)
	{
		// Define messages
		$message_id = Http::Request("m", true);
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
		$this->thread_info = $this->_GetThreadInfo($id);

		// Define notification if the thread has a locking date
		$has_date_notification = null;

		if($this->Session->IsAdmin() && $this->thread_info['lock_date'] > time()) {
			$formatted_date = $this->Core->DateFormat($this->thread_info['lock_date']);
			$has_date_notification = Html::Notification(
				"This thread will be locked in <b>" . $formatted_date . "</b>", "warning", true
			);
		}

		if($this->Session->IsAdmin() && $this->thread_info['start_date'] > time()) {
			$formatted_date = $this->Core->DateFormat($this->thread_info['start_date']);
			$has_date_notification = Html::Notification(
				"This thread is invisible and will be opened in <b>" . $formatted_date . "</b>", "info", true
			);
		}

		// Check and update cookie for read/unread threads
		$this->_CheckUnread();

		// Update session table with room ID
		$this->_UpdateSessionTable();

		// Avoid page navigation from incrementing visit counter
		$_SERVER['HTTP_REFERER'] = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : false;
		if(!strstr($_SERVER['HTTP_REFERER'], "thread")) {
			$this->Db->Update("c_threads", "views = views + 1", "t_id = '{$id}'");
		}

		// Get emoticons
		$emoticons = $this->Db->Query("SELECT * FROM c_emoticons
				WHERE emoticon_set = '" . $this->Core->config['emoticon_default_set'] . "' AND display = '1';");
		$emoticons = $this->Db->FetchToArray($emoticons);

		// Get first post
		$first_post_info = $this->_GetFirstPost($id, $emoticons);

		// Get replies
		$pages = $this->_GetPages();
		$replies = $this->_GetReplies($id, $emoticons, $pages);

		// Build pagination links
		$pagination = $this->_BuildPaginationLinks($pages, $id);

		// Get related threads
		$related_thread_list = $this->_RelatedThreads($id);

		// Page info
		$page_info['title'] = $this->thread_info['title'];
		$page_info['bc'] = array($this->thread_info['name'], $this->thread_info['title']);
		$this->Set("page_info", $page_info);

		$this->Set("thread_id", $id);
		$this->Set("thread_info", $this->thread_info);
		$this->Set("notification", $notification[$message_id]);
		$this->Set("has_date_notification", $has_date_notification);
		$this->Set("enable_signature", $this->Core->config['general_member_enable_signature']);
		$this->Set("first_post_info", $first_post_info);
		$this->Set("reply", $replies);
		$this->Set("pagination", $pagination);
		$this->Set("related_thread_list", $related_thread_list);
		$this->Set("is_moderator", $this->_IsModerator($this->thread_info['moderators']));
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

		// Get thread info
		$this->Db->Query("SELECT t.t_id, t.title, t.lock_date, t.locked, r.r_id, r.name, r.upload FROM c_threads t
				INNER JOIN c_rooms r ON (t.room_id = r.r_id) WHERE t_id = {$id};");
		$thread_info = $this->Db->Fetch();

		// Check if thread is locked
		if($thread_info['locked'] == 1 || ($thread_info['lock_date'] != 0 && $thread_info['lock_date'] < time())) {
			$this->Core->Redirect("failure?t=thread_locked");
		}

		// If member is replying another post (quote)
		if(Http::Request("quote")) {
			$quote_post_id = Http::Request("quote", true);
			$this->Db->Query("SELECT p_id, post FROM c_posts WHERE p_id = {$quote_post_id};");
			$quote = $this->Db->Fetch();
		}
		else {
			$quote = array();
		}

		// Page info
		$page_info['title'] = i18n::Translate("P_ADD_REPLY") . ": " . $thread_info['title'];
		$page_info['bc'] = array($thread_info['name'], i18n::Translate("P_ADD_REPLY") . ": " . $thread_info['title']);
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("thread_id", $id);
		$this->Set("thread_info", $thread_info);
		$this->Set("quote", $quote);
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
		$this->Set("is_poll", Http::Request("poll"));
		$this->Set("timezone_offset", $this->Core->config['date_default_offset']);
	}

	/**
	 * --------------------------------------------------------------------
	 * EDIT AN EXISTING POST
	 * --------------------------------------------------------------------
	 */
	public function Edit($post_id)
	{
		// Don't allow guests
		$this->Session->NoGuest();

		// Get post info
		$this->Db->Query("SELECT * FROM c_posts WHERE p_id = {$post_id};");
		$post_info = $this->Db->Fetch();

		// If the author isn't the user currently logged in
		// check if is an administrator
		if($this->Session->session_info['member_id'] != $post_info['author_id'] && !$this->Session->IsAdmin()) {
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
		$room_id = Http::Request("room_id");

		// Format new post array
		$post = array(
			"author_id"     => $this->Session->member_info['m_id'],
			"thread_id"     => Http::Request("id", true),
			"post_date"     => time(),
			"ip_address"    => $_SERVER['REMOTE_ADDR'],
			"post"          => str_replace("'", "&apos;", $_POST['post']),
			"quote_post_id" => (Http::Request("quote_post_id", true)) ? Http::Request("quote_post_id", true) : 0,
			"best_answer"   => 0,
			"first_post"    => 0
		);

		// Send attachments
		$Upload = new Upload($this->Db);
		$post['attach_id'] = $Upload->Attachment(Http::File("attachment"), $post['author_id']);

		// Insert new post into DB
		$this->Db->Insert("c_posts", $post);

		// Update: thread stats
		$this->Db->Update("c_threads", array(
			"replies = replies + 1",
			"last_post_date = '{$post['post_date']}'",
			"last_post_member_id = '{$post['author_id']}'"
		), "t_id = '{$post['thread_id']}'");

		// Update: room stats
		$this->Db->Update("c_rooms", array(
			"last_post_date = '{$post['post_date']}'",
			"last_post_thread = '{$post['thread_id']}'",
			"last_post_member = '{$post['author_id']}'"
		), "r_id = '{$room_id}'");

		// Update: member stats
		$this->Db->Update("c_members", array(
			"posts = posts + 1",
			"last_post_date = '{$post['post_date']}'"
		), "m_id = '{$post['author_id']}'");

		// Update: community stats
		$this->Db->Update("c_stats", "post_count = post_count + 1");

		// Redirect back to post
		$this->Core->Redirect("thread/" . $id);
	}

	/**
	 * --------------------------------------------------------------------
	 * CREATE A NEW THREAD
	 * --------------------------------------------------------------------
	 */
	public function SaveThread($room_id)
	{
		$this->layout = false;

		// If we're adding a poll, build poll array
		if(Http::Request("poll_question")) {
			// Transform list of choices in an array
			$questions = explode("\r\n", trim(Http::Request("poll_choices")));
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

		// Set the opening date
		if(Http::Request("open_day") != "" || Http::Request("open_month") != "" || Http::Request("open_year") != "") {
			$open_time = mktime(
				Http::Request("open_hours"),
				Http::Request("open_minutes"), 0,
				Http::Request("open_month"),
				Http::Request("open_day"),
				Http::Request("open_year")
			);

			// Convert custom date to UTC (a.k.a. remove timezone offset)
			// All dates in Addictive Community are treated in the back-end as UTC
			$open_time = $open_time - ($this->Core->config['date_default_offset'] * 3600);

			// Check if the opening date is equal or later than the current timestamp
			if($open_time < time()) {
				Html::Error("Open a new thread can't be retroactive.");
			}
		}
		else {
			$open_time = time();
		}

		// Set the lock date
		if(Http::Request("lock_day") != "" || Http::Request("lock_month") != "" || Http::Request("lock_year") != "") {
			$lock_time = mktime(
				Http::Request("lock_hours"),
				Http::Request("lock_minutes"), 0,
				Http::Request("lock_month"),
				Http::Request("lock_day"),
				Http::Request("lock_year")
			);

			// Convert custom date to UTC (a.k.a. remove timezone offset)
			// All dates in Addictive Community are treated in the back-end as UTC
			$lock_time = $lock_time - ($this->Core->config['date_default_offset'] * 3600);
		}
		else {
			$lock_time = 0;
		}

		// Insert new thread item
		$thread = array(
			"title"               => Http::Request("title"),
			"slug"                => Text::Slug(htmlspecialchars_decode(Http::Request("title"), ENT_QUOTES)),
			"author_member_id"    => $this->Session->member_info['m_id'],
			"replies"             => 1,
			"views"               => 0,
			"start_date"          => $open_time,
			"lock_date"           => $lock_time,
			"room_id"             => Http::Request("room_id", true),
			"announcement"        => Http::Request("announcement", true) ? Http::Request("announcement") : 0,
			"last_post_date"       => time(),
			"last_post_member_id"  => $this->Session->member_info['m_id'],
			"locked"              => Http::Request("locked", true) ? Http::Request("announcement") : 0,
			"approved"            => 1,
			"with_best_answer"     => 0,
			"poll_question"       => Http::Request("poll_question"),
			"poll_data"           => $poll_data,
			"poll_allow_multiple" => (isset($_POST['poll_allow_multiple'])) ? 1 : 0
		);
		$this->Db->Insert("c_threads", $thread);

		// Insert first post
		$post = array(
			"author_id"   => $this->Session->member_info['m_id'],
			"thread_id"   => $this->Db->GetLastID(),
			"post_date"   => $thread['last_post_date'],
			"ip_address"  => $_SERVER['REMOTE_ADDR'],
			"post"        => str_replace("'", "&apos;", $_POST['post']),
			"best_answer" => 0,
			"first_post"  => 1
		);

		$Upload = new Upload($this->Db);
		$post['attach_id'] = $Upload->Attachment(Http::File("attachment"), $post['author_id']);

		$this->Db->Insert("c_posts", $post);

		// Update tables

		$this->Db->Update("c_rooms", array(
			"last_post_date = '{$post['post_date']}'",
			"last_post_thread = '{$post['thread_id']}'",
			"last_post_member = '{$post['author_id']}'"
		), "r_id = '{$thread['room_id']}'");

		$this->Db->Update("c_stats", array(
			"post_count = post_count + 1",
			"thread_count = thread_count + 1"
		));

		$this->Db->Update("c_members", array(
			"posts = posts + 1",
			"last_post_date = '{$post['post_date']}'"
		), "m_id = '{$post['author_id']}'");

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

		// Get author ID from database for security reasons
		$this->Db->Query("SELECT author_id FROM c_posts WHERE p_id = {$post_id};");
		$post_info = $this->Db->Fetch();

		// If the author isn't the user currently logged in
		// check if is an administrator
		if($this->Session->session_info['member_id'] != $post_info['author_id'] && !$this->Session->IsAdmin()) {
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
		$this->Core->Redirect("thread/" . Http::Request("thread_id", true) . "#post-" . $post_id);
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
		$author_id = Http::Request("mid", true);
		$thread_id = Http::Request("tid", true);
		$post_id = Http::Request("pid", true);

		// If the author isn't the user currently logged in
		// check if is an administrator
		if($this->Session->session_info['member_id'] != $author_id && !$this->Session->IsAdmin()) {
			Html::Error("You cannot delete a post that you did not publish.");
		}

		// Remove post
		$this->Db->Delete("c_posts", "p_id = {$post_id}");

		// Update thread statistics
		$this->Db->Update("c_threads", "replies = replies - 1", "t_id = {$thread_id}");

		// Update member statistics
		$this->Db->Update("c_members", "posts = posts - 1", "m_id = {$author_id}");

		// Update community statistics
		$this->Db->Update("c_stats", "post_count = post_count - 1");

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

		// Do not allow unauthorized members
		if(!$this->_IsModeratorFromThreadId($thread_id)) {
			Html::Error("You're not allowed to perform this action.");
		}

		// Lock thread
		$this->Db->Update("c_threads", "locked = 1", "t_id = {$thread_id}");

		// Register Moderation log in DB
		$log = array(
			"member_id"  => $this->Session->session_info['member_id'],
			"time"       => time(),
			"act"        => "Locked thread: ID #" . $thread_id,
			"ip_address" => $_SERVER['REMOTE_ADDR']
		);
		$this->Db->Insert("c_logs", $log);

		// Redirect
		header("Location: " . preg_replace("/(\?m=[0-9])/", "", $_SERVER['HTTP_REFERER']) . "?m=4");
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

		// Do not allow unauthorized members
		if(!$this->_IsModeratorFromThreadId($thread_id)) {
			Html::Error("You're not allowed to perform this action.");
		}

		// Lock thread
		$this->Db->Update("c_threads", "locked = 0", "t_id = {$thread_id}");

		// Register Moderation log in DB
		$log = array(
			"member_id"  => $this->Session->session_info['member_id'],
			"time"       => time(),
			"act"        => "Unlocked thread: ID #" . $thread_id,
			"ip_address" => $_SERVER['REMOTE_ADDR']
		);
		$this->Db->Insert("c_logs", $log);

		// Redirect
		header("Location: " . preg_replace("/(\?m=[0-9])/", "", $_SERVER['HTTP_REFERER']) . "?m=5");
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

		// Do not allow unauthorized members
		if(!$this->_IsModeratorFromThreadId($thread_id)) {
			Html::Error("You're not allowed to perform this action.");
		}

		// Lock thread
		$this->Db->Update("c_threads", "announcement = 1", "t_id = {$thread_id}");

		// Register Moderation log in DB
		$log = array(
			"member_id"  => $this->Session->session_info['member_id'],
			"time"       => time(),
			"act"        => "Set thread ID #" . $thread_id . " as announcement",
			"ip_address" => $_SERVER['REMOTE_ADDR']
		);
		$this->Db->Insert("c_logs", $log);

		// Redirect
		header("Location: " . preg_replace("/(\?m=[0-9])/", "", $_SERVER['HTTP_REFERER']) . "?m=6");
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

		// Do not allow unauthorized members
		if(!$this->_IsModeratorFromThreadId($thread_id)) {
			Html::Error("You're not allowed to perform this action.");
		}

		// Lock thread
		$this->Db->Update("c_threads", "announcement = 0", "t_id = {$thread_id}");

		// Register Moderation log in DB
		$log = array(
			"member_id"  => $this->Session->session_info['member_id'],
			"time"       => time(),
			"act"        => "Removed thread ID #" . $thread_id . " as announcement",
			"ip_address" => $_SERVER['REMOTE_ADDR']
		);
		$this->Db->Insert("c_logs", $log);

		// Redirect
		header("Location: " . preg_replace("/(\?m=[0-9])/", "", $_SERVER['HTTP_REFERER']) . "?m=7");
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

		// Do not allow unauthorized members
		if(!$this->_IsModeratorFromThreadId($thread_id)) {
			Html::Error("You're not allowed to perform this action.");
		}

		// Get room ID
		$this->Db->Query("SELECT room_id FROM c_threads WHERE t_id = {$thread_id};");
		$thread_info = $this->Db->Fetch(); // $thread_info['room_id']
		$room_id = $thread_info['room_id'];

		// Delete all posts in this thread
		$this->Db->Delete("c_posts", "thread_id = {$thread_id}");
		$deleted_posts = $this->Db->AffectedRows();

		// Delete thread itself
		$this->Db->Delete("c_threads", "t_id = {$thread_id}");

		// Update community/room statistics
		$this->Db->Update("c_stats", array(
			"thread_count = thread_count - 1",
			"post_count = post_count - {$deleted_posts}"
		));

		// Register Moderation log in DB
		$log = array(
			"member_id"  => $this->Session->session_info['member_id'],
			"time"       => time(),
			"act"        => "Deleted thread: ID #" . $thread_id,
			"ip_address" => $_SERVER['REMOTE_ADDR']
		);
		$this->Db->Insert("c_logs", $log);

		// Redirect
		$this->Core->Redirect("room/" . $room_id . "?m=7");
	}

	/**
	 * --------------------------------------------------------------------
	 * Set an answer as best answer
	 * --------------------------------------------------------------------
	 */
	public function SetBestAnswer($reply_id)
	{
		$this->layout = false;

		// Check if the member logged in is the author of the thread
		// This will protect the script from ill-intentioned people
		$this->Db->Query("SELECT thread_id,
				(SELECT author_member_id
					FROM c_threads
					WHERE c_threads.t_id = c_posts.thread_id)
				AS thread_author
				FROM c_posts WHERE p_id = 9;");

		$thread = $this->Db->Fetch();

		if($this->Session->member_info['m_id'] == $thread['thread_author']) {
			$this->Db->Update("c_posts", "best_answer = 1", "p_id = {$reply_id}");
			$this->Db->Update("c_threads", "with_best_answer = 1", "t_id = {$thread['thread_id']}");
			$this->Core->Redirect("HTTP_REFERER");
		}
		else {
			$this->Core->Redirect("HTTP_REFERER");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * Set an answer as best answer
	 * --------------------------------------------------------------------
	 */
	public function UnsetBestAnswer($reply_id)
	{
		$this->layout = false;

		// Check if the member logged in is the author of the thread
		// This will protect the script from ill-intentioned people
		$this->Db->Query("SELECT thread_id,
				(SELECT author_member_id
					FROM c_threads
					WHERE c_threads.t_id = c_posts.thread_id)
				AS thread_author
				FROM c_posts WHERE p_id = 9;");

		$thread = $this->Db->Fetch();

		if($this->Session->member_info['m_id'] == $thread['thread_author']) {
			$this->Db->Update("c_posts", "best_answer = 0", "p_id = {$reply_id}");
			$this->Db->Update("c_threads", "with_best_answer = 0", "t_id = {$thread['thread_id']}");
			$this->Core->Redirect("HTTP_REFERER");
		}
		else {
			$this->Core->Redirect("HTTP_REFERER");
		}
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
			$poll_data['replies'][Http::Request("chosen_option")] += 1;

			// Add member ID to voters list
			array_push($poll_data['voters'], $this->Session->member_info['m_id']);
		}
		else {
			// If poll allows multiple choice
			foreach(Http::Request("chosen_option") as $chosen_option) {
				$poll_data['replies'][$chosen_option] += 1;
			}

			// Add member ID to voters list
			array_push($poll_data['voters'], $this->Session->member_info['m_id']);
		}

		// Update thread information with encoded data
		$encoded = json_encode($poll_data);
		$this->Db->Update("c_threads", "poll_data = '{$encoded}'", "t_id = {$thread_id}");

		// Redirect
		$this->Core->Redirect("HTTP_REFERER");
	}

	/**
	 * --------------------------------------------------------------------
	 * CHECK IF THREAD IS UNREAD. IF TRUE, ADD TO COOKIE ARRAY
	 * --------------------------------------------------------------------
	 */
	private function _CheckUnread()
	{
		$read_threads_cookie = $this->Session->GetCookie("addictive_community_read_threads");
		if($read_threads_cookie) {
			$login_time_cookie = $this->Session->GetCookie("addictive_community_login_time");
			$read_threads = json_decode(html_entity_decode($read_threads_cookie), true);
			if(!in_array($this->thread_info['t_id'], $read_threads) && $login_time_cookie < $this->thread_info['last_post_date']) {
				array_push($read_threads, $this->thread_info['t_id']);
			}

			$read_threads_cookie = json_encode($read_threads);
			$this->Session->CreateCookie("addictive_community_read_threads", $read_threads_cookie);
		}
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
		$thread = $this->Db->Query("SELECT t.t_id, t.title, t.start_date, t.lock_date, t.room_id, t.author_member_id,
				t.locked, t.announcement, t.last_post_date, t.poll_question, t.poll_data, t.poll_allow_multiple,
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
			$this->Core->Redirect("HTTP_REFERER");
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
		$obsolete_seconds = $this->Core->config['thread_obsolete_value'] * DAY;
		if($this->Core->config['thread_obsolete'] != 0 && ($thread_info['last_post_date'] + $obsolete_seconds) < time()) {
			$thread_info['obsolete'] = true;
			$obsolete_notification = Html::Notification(
				i18n::Translate("T_OBSOLETE", array($this->Core->config['thread_obsolete_value'])), "warning", true
			);
		}
		else {
			$thread_info['obsolete'] = false;
		}

		// Lock thread if it has an scheduled date
		if($thread_info['lock_date'] != 0 && $thread_info['lock_date'] < time()) {
			$thread_info['locked'] = 1;
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
			if(!empty($poll_data['replies'])) {
				$total = array_sum($poll_data['replies']);
				$ratio = ($total != 0) ? 100 / $total : 0; // Avoid "division by zero" exception

				// Get percentage of votes for each option
				foreach($poll_data['replies'] as $k => $v) {
					$poll_data['percentage'][$k] = $v * $ratio;
				}
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
	private function _UpdateSessionTable()
	{
		// Update session table with room ID
		$session = $this->Session->session_id;
		$this->Db->Update("c_sessions", "location_room_id = {$this->thread_info['room_id']}", "s_id = '{$session}'");
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURNS AN ARRAY OF BADWORDS
	 * --------------------------------------------------------------------
	 */
	private function _FilterBadWords($text)
	{
		if($this->Core->config['language_bad_words'] != "") {
			$bad_words = explode("\n", $this->Core->config['language_bad_words']);
			$bad_words_list = preg_replace("/(\r|\n)/i", "", "/(" . implode("|", $bad_words) . ")/i");

			return preg_replace($bad_words_list, $this->Core->config['language_bad_words_replacement'], $text);
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
				c_attachments.*, c_threads.title, c_threads.locked, c_members.*, edit.username AS edit_username FROM c_posts
				INNER JOIN c_threads ON (c_posts.thread_id = c_threads.t_id)
				INNER JOIN c_members ON (c_posts.author_id = c_members.m_id)
				LEFT JOIN c_members AS edit ON (c_posts.edit_author = edit.m_id)
				LEFT JOIN c_attachments ON (c_posts.attach_id = c_attachments.a_id)
				WHERE thread_id = '{$id}' AND first_post = '1' LIMIT 1;");

		$first_post_info = $this->Db->Fetch($first_post);

		// Format first thread
		$first_post_info['avatar'] = $this->Core->GetAvatar($first_post_info, 96);
		$first_post_info['post_date'] = $this->Core->DateFormat($first_post_info['post_date']);

		// Check if the currently logged in member is the thread author
		$first_post_info['is_author'] = ($first_post_info['author_id'] == $this->Session->session_info['member_id']);

		// Show label if post was edited
		if(isset($first_post_info['edit_time'])) {
			$first_post_info['edit_time'] = $this->Core->DateFormat($first_post_info['edit_time']);
			$first_post_info['edited']    = "(" . i18n::Translate("T_EDITED", array($first_post_info['edit_time'], $first_post_info['edit_username'])) . ")";
		}
		else {
			$first_post_info['edited'] = "";
		}

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
	private function _GetReplies($id, $emoticons, $pages)
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
			$result['avatar'] = $this->Core->GetAvatar($result, 192);
			$result['joined'] = $this->Core->DateFormat($result['joined'], "short");
			$result['post_date'] = $this->Core->DateFormat($result['post_date']);

			// Member ranks
			if($this->Core->config['general_member_enable_ranks']) {
				$result['rank'] = $this->_MemberRank($result['posts']);
				if($result['rank']) {
					$result['rank_name'] = $result['rank']['title'];
					if($result['rank']['image'] == "") {
						$result['rank_pips'] = "";
						for($i = 1; $i <= $result['rank']['pips']; $i++) {
							$result['rank_pips'] .= "<i class='fa fa-star'></i>";
						}
					}
					else {
						$result['rank_pips'] = "<img src='" . $result['rank']['image'] . "'>";
					}
				}
			}
			else {
				$result['rank'] = array();
			}

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

			// Get quoted post
			if($result['quote_post_id'] != 0) {
				$result['has_quote'] = true;
				$quoted_post = $this->Db->Query("SELECT c_posts.post_date, c_posts.post, c_members.username AS author FROM c_posts
						INNER JOIN c_members ON (c_posts.author_id = c_members.m_id)
						WHERE p_id = {$result['quote_post_id']};");
				$quoted_post_result = $this->Db->Fetch();

				// Return results
				$result['quote_author'] = $quoted_post_result['author'];
				$result['quote_time'] = $this->Core->DateFormat($quoted_post_result['post_date']);
				$result['quote_post'] = $quoted_post_result['post'];
			}
			else {
				$result['has_quote'] = false;
			}

			// Build post/thread action controls
			$result['post_controls'] = "";
			$result['thread_controls'] = "";

			// Post controls
			if($result['author_id'] == $this->Session->member_info['m_id'] || $this->Session->IsAdmin()) {
				$result['post_controls'] = "<a href='thread/edit/{$result['p_id']}' class='small-button grey'>" . i18n::Translate("T_EDIT") . "</a> "
					. "<a href='#deleteThreadConfirm' data-post='{$result['p_id']}' data-thread='{$id}' data-member='{$result['author_id']}' class='fancybox delete-post-button small-button grey'>" . i18n::Translate("T_DELETE") . "</a>";
			}

			// Thread controls
			if($this->thread_info['author_member_id'] == $this->Session->member_info['m_id']
				&& $result['author_id'] != $this->Session->member_info['m_id']) {
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
	private function _GetPages()
	{
		$pages['items_per_page'] = $this->Core->config['thread_posts_per_page'];
		$total_posts = $this->thread_info['post_count'] - 1;

		// page number for SQL sentences
		$pages['for_sql'] = (Http::Request("p")) ? Http::Request("p") * $pages['items_per_page'] - $pages['items_per_page'] : 0;

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
	private function _RelatedThreads($id)
	{
		$thread_list = "";
		$thread_search = explode(" ", strtolower($this->thread_info['title']));
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
			$thread['thread_date'] = $this->Core->DateFormat($thread['last_post_date'], "short");
			$related_thread_list[] = $thread;
		}

		return $related_thread_list;
	}

	/**
	 * --------------------------------------------------------------------
	 * WHEN POSTING, CHECK IF MEMBER IS A MODERATOR
	 * --------------------------------------------------------------------
	 */
	private function _IsModerator($moderators_serialized = "")
	{
		// Get array of moderators
		$moderators_array = unserialize($moderators_serialized);

		// Check if room has moderators and if
		// the current member is a moderator
		if(!empty($moderators_array) && in_array($this->Session->member_info['m_id'], $moderators_array)) {
			return true;
		}
		else {
			// If member is not a moderator, check if is an Administrator
			return $this->Session->IsAdmin();
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * WHEN POSTING, CHECK IF MEMBER IS A MODERATOR (GET THREAD ID VALUE)
	 * --------------------------------------------------------------------
	 */
	private function _IsModeratorFromThreadId($thread_id = 0)
	{
		// Get thread moderators
		$this->Db->Query("SELECT r.moderators FROM c_threads t
				INNER JOIN c_rooms r ON (r.r_id = t.room_id)
				WHERE t_id = {$thread_id};");

		$moderators = $this->Db->Fetch();

		return $this->_IsModerator($moderators['moderators']);
	}

	/**
	 * --------------------------------------------------------------------
	 * GET MEMBER RANK
	 * --------------------------------------------------------------------
	 */
	private function _MemberRank($posts = 0)
	{
		$this->Db->Query("SELECT * FROM c_ranks;");
		$_ranks = $this->Db->FetchToArray();
		$_ranks = array_reverse($_ranks);

		foreach($_ranks as $rank) {
			if($posts >= $rank['min_posts']) {
				return $rank;
			}
		}

		return array();
	}
}
