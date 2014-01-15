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
	// SIDEBAR: get list of rooms
	// ---------------------------------------------------

	$this->Db->Query("SELECT c_rooms.r_id, c_rooms.name,
		(SELECT COUNT(*) FROM c_threads WHERE c_threads.room_id = c_rooms.r_id) AS threads
		FROM c_rooms WHERE invisible = 0;");

	while($result = $this->Db->Fetch()) {
		$_siderooms[] = $result;
	}

	// ---------------------------------------------------
	// SIDEBAR: get community statistics
	// ---------------------------------------------------

	$this->Db->Query("SELECT * FROM c_stats;");
	$statsResultTmp = $this->Db->Fetch();

	$_stats['threads'] = $statsResultTmp['total_threads'];
	$_stats['replies'] = $statsResultTmp['total_posts'];
	$_stats['members'] = $statsResultTmp['member_count'];

	$this->Db->Query("SELECT m_id, username FROM c_members
		ORDER BY m_id DESC LIMIT 1;");
	$statsResultTmp = $this->Db->Fetch();

	$_stats['lastmemberid'] = $statsResultTmp['m_id'];
	$_stats['lastmembername'] = $statsResultTmp['username'];

?>