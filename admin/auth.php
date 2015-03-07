<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: auth.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// Include files...

	require_once("../config.php");
	require_once("../kernel/class.string.php");
	require_once("../kernel/class.html.php");
	require_once("../kernel/class.database.php");

	// Load MySQL driver and connect

	$Db = new Database($config);

	// Get form information

	if(Html::Request("username") && Html::Request("password")) {
		$username = Html::Request("username");
		$password = String::PasswordEncrypt(Html::Request("password"));
	}

	$now = time();

	// Check if user exists

	$Db->Query("SELECT m_id, username, usergroup FROM c_members WHERE username = '{$username}' AND password = '{$password}';");
	$rows = $Db->Rows();

	if($rows != 1) {
		header("Location: index.php?error=1");
		exit;
	}
	else {
		$info = $Db->Fetch();

		if($info['usergroup'] != 1) {
			header("Location: index.php?error=4");
			exit;
		}

		session_start();
		$_SESSION['admin_m_id']     = $info['m_id'];
		$_SESSION['admin_username'] = $info['username'];
		$_SESSION['admin_time']     = $now;

		header("Location: main.php");
	}

?>
