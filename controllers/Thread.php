<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Thread.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
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
		// Get thread information
		$thread_info = $this->_GetThreadInfo($id);

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
		$this->Set("first_post_info", $first_post_info);
		$this->Set("reply", $replies);
		$this->Set("pagination", $pagination);
		$this->Set("related_thread_list", $related_thread_list);
	}

	/**
	 * --------------------------------------------------------------------
	 * REPLY THREAD
	 * --------------------------------------------------------------------
	 */
	public function Reply($id)
	{
		$this->Db->Query("SELECT t.t_id, t.title, r.r_id, r.name FROM c_threads t
				INNER JOIN c_rooms r ON (t.room_id = r.r_id) WHERE t_id = {$id};");
		$thread_info = $this->Db->Fetch();

		$this->Set("community_name", $this->config['general_community_name']);
		$this->Set("thread_id", $id);
		$this->Set("thread_info", $thread_info);
	}

	/**
	 * --------------------------------------------------------------------
	 * ADD NEW THREAD
	 * --------------------------------------------------------------------
	 */
	public function Add($room_id)
	{
		$this->Db->Query("SELECT r_id, name FROM c_rooms WHERE r_id = {$room_id};");
		$room_info = $this->Db->Fetch();

		// Return variables
		$this->Set("community_name", $this->config['general_community_name']);
		$this->Set("room_info", $room_info);
		$this->Set("member_info", $this->Session->member_info);
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

		// Insert new thread item
		$thread = array(
			"title"              => Html::Request("title"),
			"author_member_id"   => $this->Session->member_info['m_id'],
			"replies"            => 1,
			"views"              => 0,
			"start_date"         => time(),
			"room_id"            => Html::Request("room_id", true),
			"announcement"       => Html::Request("announcement", true),
			"lastpost_date"      => time(),
			"lastpost_member_id" => $this->Session->member_info['m_id'],
			"locked"             => Html::Request("locked", true),
			"approved"           => 1,
			"with_bestanswer"    => 0
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
		$this->Core->Redirect("thread/" . $post['thread_id']);
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
		$thread = $this->Db->Query("SELECT t.title, t.author_member_id, t.locked, t.lastpost_date, r.r_id, r.name, r.perm_view, r.perm_reply,
				(SELECT COUNT(*) FROM c_posts p WHERE p.thread_id = t.t_id) AS post_count FROM c_threads t
				INNER JOIN c_rooms r ON (r.r_id = t.room_id) WHERE t.t_id = '{$id}';");
		$thread_info = $this->Db->Fetch($thread);

		// Calculate the number of actual replies (first post is not a reply)
		$thread_info['post_count_display'] = $thread_info['post_count'] - 1;

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
	 * RETURNS AN ARRAY OF BADWORDS
	 * --------------------------------------------------------------------
	 */
	public function _FilterBadWords($text)
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

		$replies = $this->Db->Query("SELECT c_posts.*, c_attachments.*, c_members.* FROM c_posts
				INNER JOIN c_members ON (c_posts.author_id = c_members.m_id)
				LEFT JOIN c_attachments ON (c_posts.attach_id = c_attachments.a_id)
				WHERE thread_id = '{$id}' AND first_post = '0'
				ORDER BY best_answer DESC, post_date ASC LIMIT {$pages['for_sql']},{$pages['items_per_page']};");

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

			// Show label if post was edited
			if(isset($result['edited'])) {
				$result['edit_time'] = $this->Core->DateFormat($result['edit_time']);
				$result['edited']    = "<em>(" . i18n::Translate("T_EDITED", array($result['edit_time'], $result['edit_author'])) . ")</em>";
			}
			else {
				$result['edited'] = "";
			}

			// Build post/thread action controls
			$result['post_controls'] = "";
			$result['thread_controls'] = "";

			// Post controls
			if($result['author_id'] == $this->Session->member_info['m_id']) {
				$result['post_controls'] = "<a href='' class='small-button grey'>" . i18n::Translate("T_EDIT") . "</a> "
					. "<a href='#deleteThreadConfirm' data-post='{$result['p_id']}' data-thread='{$id}' data-member='{$result['author_id']}' class='fancybox deleteButton small-button grey'>" . i18n::Translate("T_DELETE") . "</a>";
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
}
