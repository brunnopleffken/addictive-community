<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: default.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	// ---------------------------------------------------
	// Format page title
	// ---------------------------------------------------
	
	if($pageinfo['title'] != "") {
		$html['title'] = $pageinfo['title'] . " - ";
	}
	else {
		$html['title'] = "";
	}

	// ---------------------------------------------------
	// Format breadcrumbs
	// ---------------------------------------------------
	
	$html['breadcrumb'] = "";

	foreach($pageinfo['bc'] as $item) {
		$html['breadcrumb'] .= " &raquo; " . $item;
	}
	
	// ---------------------------------------------------
	// SIDEBAR: get member information (when logged in)
	// ---------------------------------------------------
	
	if(isset($this->Session->sInfo['member_id'])) {
		$m_id = $this->Session->sInfo['member_id'];
		// Get user avatar
		$this->member['avatar'] = $this->Core->GetGravatar($this->member['email'], $this->member['photo'], 30, $this->member['photo_type']);

		// Number of new messages
		$this->Db->Query("SELECT COUNT(*) AS total FROM c_messages WHERE to_id = '{$m_id}' AND status = 0;");
		$unreadMessages = $this->Db->Fetch();
	}

	// ---------------------------------------------------
	// SIDEBAR: get list of rooms
	// ---------------------------------------------------

	$this->Db->Query("SELECT c_rooms.r_id, c_rooms.name, "
			. "(SELECT COUNT(*) FROM c_threads WHERE c_threads.room_id = c_rooms.r_id) AS threads "
			. "FROM c_rooms WHERE invisible = 0;");

	while($result = $this->Db->Fetch()) {
		$_siderooms[] = $result;
	}

	// ---------------------------------------------------
	// SIDEBAR: get members online
	// ---------------------------------------------------

	// Members online

	$online = array();
	$sessionExpiration = $this->Core->config['general_session_expiration'];

	$this->Db->Query("SELECT * FROM c_sessions WHERE "
			. "member_id <> 0 AND activity_time > '{$sessionExpiration}' AND anonymous = 0 "
			. "ORDER BY activity_time DESC;");

	while($members = $this->Db->Fetch()) {
		$online[] = "<a href=\"index.php?module=profile&amp;id={$members['member_id']}\">{$members['username']}</a>";
	}

	$memberCount = count($online);
	$memberList = implode(", ", $online);

	// Number of guests

	$this->Db->Query("SELECT COUNT(s_id) AS count FROM c_sessions
		WHERE member_id = 0;");

	$guestsCount = $this->Db->Fetch();
	$guestsCount = $guestsCount['count'];

	// ---------------------------------------------------
	// SIDEBAR: get community statistics
	// ---------------------------------------------------

	$this->Db->Query("SELECT * FROM c_stats;");
	$statsResultTmp = $this->Db->Fetch();

	$_stats['threads'] = $statsResultTmp['total_threads'];
	$_stats['replies'] = $statsResultTmp['total_posts'];
	$_stats['members'] = $statsResultTmp['member_count'];

	$this->Db->Query("SELECT m_id, username FROM c_members "
			. "ORDER BY m_id DESC LIMIT 1;");
	$statsResultTmp = $this->Db->Fetch();

	$_stats['lastmemberid'] = $statsResultTmp['m_id'];
	$_stats['lastmembername'] = $statsResultTmp['username'];

?>