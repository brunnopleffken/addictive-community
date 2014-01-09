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
	// Where are we?
	// ---------------------------------------------------

	// ... root breadcrumb ...

	// ---------------------------------------------------
	// Get room list
	// ---------------------------------------------------

	$rooms_result = $this->Db->Query("SELECT c_rooms.*, c_members.m_id, c_members.username, c_threads.title, c_threads.t_id,
		(SELECT COUNT(*) FROM c_threads WHERE room_id = c_rooms.r_id) AS thread_count FROM c_rooms
		LEFT JOIN c_members ON (c_members.m_id = c_rooms.lastpost_member)
		LEFT JOIN c_threads ON (c_threads.t_id = c_rooms.lastpost_thread)
		ORDER BY r_id ASC;");

?>