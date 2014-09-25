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
			String::PR($_POST);
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