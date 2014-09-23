<?php

	## ---------------------------------------------------
	#  HOMEFRONT ONLINE
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: report.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Homefront Interactive
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Set layout as Ajax
	// ---------------------------------------------------

	$define['layout'] = "ajax";

	// ---------------------------------------------------
	// Get thread/post information
	// ---------------------------------------------------

	$threadId = Html::Request("t_id", true);
	$postId   = Html::Request("p_id", true);

	// ---------------------------------------------------
	// Execute actions
	// ---------------------------------------------------
	
	$act = Html::Request("act");

	switch($act) {
		case 'send':
			$reportInfo = array(
				"description" => Html::Request("description"),
				"date"        => time(),
				"sender_id"   => Html::Request("m_id"),
				"ip_address"  => $_SERVER['REMOTE_ADDR'],
				"post_id"     => Html::Request("p_id"),
				"thread_id"   => Html::Request("t_id"),
				"referer"     => $_SERVER['HTTP_REFERER']
			);

			$this->Db->Insert("c_reports", $reportInfo);

			header("Location: " . $reportInfo['referer']);
			exit;

			break;
	}

?>