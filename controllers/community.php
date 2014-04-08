<?php
	
	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: community.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Get room list
	// ---------------------------------------------------
	
	// If admin, show invisible rooms too
	
	if(isset($this->user['usergroup'])) {
		if($this->user['usergroup'] != 1) {
			$visibility = "WHERE invisible = '0'";
		}
		else {
			$visibility = "";
		}
	}
	else {
		$visibility = "WHERE invisible = '0'";
	}

	// Do query

	$rooms_result = $this->Db->Query("SELECT c_rooms.*, c_members.m_id, c_members.username, c_threads.title, c_threads.t_id, "
			. "(SELECT COUNT(*) FROM c_threads WHERE room_id = c_rooms.r_id) AS thread_count "
			. "FROM c_rooms LEFT JOIN c_members ON (c_members.m_id = c_rooms.lastpost_member) "
			. "LEFT JOIN c_threads ON (c_threads.t_id = c_rooms.lastpost_thread) "
			. "{$visibility} ORDER BY r_id ASC;");

	// Process data

	while($result = $this->Db->Fetch($rooms_result)) {

		// If last post timestamp is diff. from zero

		if($result['lastpost_date'] > 0) {
			$result['lastpost_date'] = $this->Core->DateFormat($result['lastpost_date']);
		}
		else {
			$result['lastpost_date'] = "---";
		}

		// Is this room a protected room?

		if($result['password'] != "") {
			$result['title'] = "<em>Protected room</em>";
		}
		else {
			$result['title'] = "<a href=\"index.php?module=thread&id={$result['t_id']}\">{$result['title']}</a>";
		}

		// Store result in array

		$_rooms[] = $result;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = "";
	$pageinfo['bc'] = array();

?>