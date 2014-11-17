<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: thread.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	// ---------------------------------------------------
	// Get thread ID
	// ---------------------------------------------------

	$threadId = Html::Request("id", true);

	// ---------------------------------------------------
	// What are we doing?
	// ---------------------------------------------------

	$act = Html::Request("act");

	if($act) {
		switch($act) {
			case 'delete':
				# code...
				break;
			
			case 'setbestanswer':
				# code...
				break;

			case 'unsetbestanswer':
				# code...
				break;
		}
	}

	// ---------------------------------------------------
	// Fetch thread general info
	// ---------------------------------------------------

	$this->Db->Query("SELECT t.title, t.author_member_id, t.locked, t.lastpost_date, r.r_id, r.name, r.perm_view, r.perm_reply, "
			. "(SELECT COUNT(*) FROM c_posts p WHERE p.thread_id = t.t_id) AS post_count FROM c_threads t "
			. "INNER JOIN c_rooms r ON (r.r_id = t.room_id) "
			. "WHERE t.t_id = '{$threadId}';");

	$threadInfo = $this->Db->Fetch();

	// Get number of replies
	$threadInfo['post_count_display'] = $threadInfo['post_count'] - 1;

	// ---------------------------------------------------
	// Check room permissions (view and reply)
	// ---------------------------------------------------

	// Permission to view
	
	$threadInfo['perm_view'] = unserialize($threadInfo['perm_view']);
	$permissionValue = "V_" . $this->member['usergroup'];

	if(!in_array($permissionValue, $threadInfo['perm_view'])) {
		header("Location: " . $_SERVER['HTTP_REFERER']);
	}

	// Check if it's an obsolete thread
	
	$obsoleteSeconds = $this->Core->config['thread_obsolete_value'] * DAY;
	if(($threadInfo['lastpost_date'] + $obsoleteSeconds) < time()) {
		$threadInfo['obsolete'] = true;
	}
	else {
		$threadInfo['obsolete'] = false;
	}

	// Permission to reply

	$threadInfo['perm_reply'] = unserialize($threadInfo['perm_reply']);
	$permissionValue = "V_" . $this->member['usergroup'];

	if(in_array($permissionValue, $threadInfo['perm_reply'])) {
		$allowToReply = true;
	}
	else {
		$allowToReply = false;
	}

	// ---------------------------------------------------
	// Get thread number of pages
	// ---------------------------------------------------

	$itemsPerPage = $this->Core->config['thread_posts_per_page'];
	$totalPosts   = $threadInfo['post_count'];
	
	// page number for SQL sentences
	$pSql = (Html::Request("p")) ? Html::Request("p") * $itemsPerPage - $itemsPerPage : 0;

	// page number for HTML page numbers
	$pDisp = (isset($_REQUEST['p'])) ? $_REQUEST['p'] : 1;
	$pages = ceil($totalPosts / $itemsPerPage);

	// ---------------------------------------------------
	// Avoid incrementing visit counter in navigation
	// ---------------------------------------------------

	$_SERVER['HTTP_REFERER'] = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : false;

	if(!strstr($_SERVER['HTTP_REFERER'], "module=thread")) {
		$this->Db->Query("UPDATE c_threads SET views = views + 1 WHERE t_id = '{$threadId}';");
	}

	// ---------------------------------------------------
	// Get emoticons, if exists
	// ---------------------------------------------------

	$this->Db->Query("SELECT * FROM c_emoticons WHERE
		emoticon_set = '" . $this->Core->config['emoticon_default_set'] . "' AND display = '1';");
	$emoticons = $this->Db->FetchArray();

	// ---------------------------------------------------
	// Get first post
	// ---------------------------------------------------

	$this->Db->Query("SELECT c_posts.*, c_threads.t_id, c_threads.tags, c_threads.room_id, "
			. "c_attachments.*, c_threads.title, c_threads.locked, c_members.* FROM c_posts "
			. "INNER JOIN c_threads ON (c_posts.thread_id = c_threads.t_id) "
			. "INNER JOIN c_members ON (c_posts.author_id = c_members.m_id) "
			. "LEFT JOIN c_attachments ON (c_posts.attach_id = c_attachments.a_id) "
			. "WHERE thread_id = '{$threadId}' AND first_post = '1' LIMIT 1;");

	$firstPostInfo = $this->Db->Fetch();

	// Format first thread
	$firstPostInfo['avatar'] = $this->Core->GetGravatar($firstPostInfo['email'], $firstPostInfo['photo'], 96, $firstPostInfo['photo_type']);
	$firstPostInfo['post_date'] = $this->Core->DateFormat($firstPostInfo['post_date']);

	// Get emoticons
	$firstPostInfo['post'] = $this->Core->ParseEmoticons($firstPostInfo['post'], $emoticons);

	// ---------------------------------------------------
	// Get replies
	// ---------------------------------------------------

	$this->Db->Query("SELECT c_posts.*, c_attachments.*, c_members.* FROM c_posts "
			. "INNER JOIN c_members ON (c_posts.author_id = c_members.m_id) "
			. "LEFT JOIN c_attachments ON (c_posts.attach_id = c_attachments.a_id) "
			. "WHERE thread_id = '{$threadId}' AND first_post = '0' "
			. "ORDER BY best_answer DESC, post_date ASC LIMIT {$pSql},{$itemsPerPage};");

	while($result = $this->Db->Fetch()) {

		// Is this a best answer or a regular reply?
		$result['bestanswer_class'] = ($result['best_answer'] == 1) ? "bestAnswer" : "";

		$result['avatar'] = $this->Core->GetGravatar($result['email'], $result['photo'], 192, $result['photo_type']);
		$result['joined'] = $this->Core->DateFormat($result['joined'], "short");
		$result['post_date'] = $this->Core->DateFormat($result['post_date']);
		
		// Get emoticons
		$result['post'] = $this->Core->ParseEmoticons($result['post'], $emoticons);

		if(isset($result['edited'])) {
			$result['edit_time'] = $this->Core->DateFormat($result['edit_time']);
			$result['edited']    = "<em>(Edited in " . $result['edit_time'] . " by " . $result['edit_author'] . ")</em>";
		}
		else {
			$result['edited'] = "";
		}

		$_replyResult[] = $result;
	}

	// ---------------------------------------------------
	// Pagination links
	// ---------------------------------------------------
	
	$paginationNav = "";
	
	if($pages != 0) {
		$paginationNav .= "<div class=\"pages\">Pages: ";
		
		// If it is not the first page, show link "Back"
		if($pDisp != 1) {
			$prev = $pDisp - 1;
			$paginationNav .= "<a href=\"index.php?module=thread&id={$threadId}&p={$prev}\">&laquo;</a>\n";
		}
		
		// Page numbers
		for($i = 1; $i <= $pages; $i++) {
			if($i == $pDisp) {
				$paginationNav .= "<a href=\"index.php?module=thread&id={$threadId}&p={$i}\" class=\"page-selected\">{$i}</a>\n";
			}
			else {
				$paginationNav .= "<a href=\"index.php?module=thread&id={$threadId}&p={$i}\">{$i}</a>\n";
			}
		}
		
		// If it is not the last page, show link "Next"
		if($pDisp != $i - 1) {
			$next = $pDisp + 1;
			$paginationNav .= "<a href=\"index.php?module=thread&id={$threadId}&p={$next}\">&raquo;</a>\n";
		}
		
		$paginationNav .= "</div>";
	}
	
	Template::Add($paginationNav);

	$pagination = Template::Get();
	Template::Clean();

	// ---------------------------------------------------
	// Do Related Threads list
	// ---------------------------------------------------

	$threadList = "";
	$threadSearch = explode(" ", String::Sanitize($firstPostInfo['title']));
	$_relatedThreadList = array();

	foreach($threadSearch as $key => $value) {
		if(strlen($value) < 4) {
			unset($threadSearch[$key]);
		}
	}

	$threadSearch = implode(" ", $threadSearch);

	$this->Db->Query("SELECT *, MATCH(title) AGAINST ('{$threadSearch}') AS relevance FROM c_threads "
		. "WHERE t_id <> {$threadId} AND MATCH(title) AGAINST ('{$threadSearch}');");

	while($relatedThread = $this->Db->Fetch()) {
		$relatedThread['thread_date'] = $this->Core->DateFormat($relatedThread['lastpost_date'], "short");
		$_relatedThreadList[] = $relatedThread;

		//Template::Add($this->Core->DateFormat($relatedThread['start_date'], "short") . " - " . $relatedThread['title'] . " - " . $relatedThread['t_id']);
	}
	
	$threadList = Template::Get();
	Template::Clean();

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------
	
	// Page information
	$pageinfo['title'] = $threadInfo['title'];
	$pageinfo['bc'] = array($threadInfo['name'], $threadInfo['title']);

?>