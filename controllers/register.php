<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: register.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	// ---------------------------------------------------
	// Get step number
	// ---------------------------------------------------

	$step = $this->Core->QueryString("step", 1, true);
	$act = Html::Request("act");

	// ---------------------------------------------------
	// Let's do it!
	// ---------------------------------------------------

	if($act == "signup") {

		// Set new member info

		$registerInfo = array(
			"username"		=> Html::Request("username"),
			"password"		=> Html::PasswordEncrypt(Html::Request("password")),
			"email"			=> Html::Request("email"),
			"ip_address"	=> $_SERVER['REMOTE_ADDR'],
			"joined"		=> time(),
			"usergroup"		=> 3,
			"photo_type"	=> "gravatar",
			"posts"			=> 0,
			"template"		=> "default",
			"language"		=> "en_US",
			"time_offset"	=> 0,
			"dst"			=> 0
			);

		// Find for already registered email address

		$this->Db->Query("SELECT email FROM c_members WHERE
			email = '{$registerInfo['email']}';");

		$emailExistsCount = $this->Db->Rows();

		if($emailExistsCount > 0) {
			header("Location: index.php?module=register?step=2?error=1");
			exit;
		}

		// Insert new member on database

		$this->Db->Insert($registerInfo, "c_members");
		$this->Db->Query("UPDATE c_stats SET member_count = member_count + 1;");
		header("Location: index.php?module=register&step=3");

		exit;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------
	
	// Page information
	$pageinfo['title'] = "Create Account";
	$pageinfo['bc'] = array("Create Account");

?>