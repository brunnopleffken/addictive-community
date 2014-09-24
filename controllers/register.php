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

	switch($act) {
		case 'signup':

			// Check if Require Validation is TRUE in community settings
			$usergroup = ($this->Core->config['general_security_validation'] == "true") ? 6 : 3;

			// Build new member info array
			$registerInfo = array(
				"username"      => Html::Request("username"),
				"password"      => String::PasswordEncrypt(Html::Request("password")),
				"email"         => Html::Request("email"),
				"hide_email"    => 0,
				"ip_address"    => $_SERVER['REMOTE_ADDR'],
				"joined"        => time(),
				"usergroup"     => $usergroup,
				"photo_type"    => "gravatar",
				"posts"         => 0,
				"template"      => "default",
				"language"      => $this->info['language'],
				"time_offset"   => $this->Core->config['date_default_offset'],
				"dst"           => 0,
				"show_email"    => 1,
				"show_birthday" => 1,
				"show_gender"   => 1
			);

			// Find for already registered email address
			$this->Db->Query("SELECT email FROM c_members WHERE email = '{$registerInfo['email']}';");
			$emailExistsCount = $this->Db->Rows();

			if($emailExistsCount > 0) {
				header("Location: index.php?module=register?step=2?error=1");
				exit;
			}

			// Insert new member on database

			$this->Db->Insert($registerInfo, "c_members");
			$this->Db->Query("UPDATE c_stats SET member_count = member_count + 1;");
			header("Location: index.php?module=register&step=3");

			// TO DO: [ GENERATE RANDOM MD5 / SEND VALIDATION E-MAIL TO THE USER ]
			
			if($registerInfo['usergroup'] == 6) {
				// ...
			}

			exit;
			break;

		case 'validate':

			// Get member ID
			$member = Html::Request("member");
			$token  = Html::Request("token");

			// Check if user has already validated
			$this->Db->Query("SELECT m_id FROM c_members WHERE m_id = {$member} AND usergroup = 6;");
			$validationCount = $this->Db->Rows();

			if($validationCount > 0) {
				// TO DO: [ VALIDATE RANDOM MD5 AND PROCESS VALIDATION ]
			}

			exit;
			break;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------
	
	// Page information
	$pageinfo['title'] = "Create Account";
	$pageinfo['bc'] = array("Create Account");

?>