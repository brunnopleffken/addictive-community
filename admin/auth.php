<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: auth.php
	#  License: GPLv2
	#  Copyright: (c) 2015 - Addictive Community
	## ---------------------------------------------------

	// Include files...

	require_once("../config.php");
	require_once("../kernel/String.php");
	require_once("../kernel/Html.php");
	require_once("../kernel/Database.php");

	// Load MySQL driver and connect

	$Db = new Database($config);

	// Get security hash key
	$Db->Query("SELECT * FROM c_config c WHERE field = 'security_salt_hash' OR field = 'security_salt_key';");
	$_salt = $Db->FetchToArray();

	$salt = array(
		"hash" => $_salt[0]['value'],
		"key"  => $_salt[1]['value']
	);

	// Get form information

	if(Html::Request("username") && Html::Request("password")) {
		$username = Html::Request("username");
		$password = String::Encrypt(Html::Request("password"), $salt);
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
