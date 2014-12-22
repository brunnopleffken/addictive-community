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
	// Get step number
	// ---------------------------------------------------

	$notification = "";
	$error = Html::Request("error");

	switch($error) {
		case 1:
			$notification = Html::Notification(i18n::Translate("R_ERROR_1"), "failure");
			break;
		case 2:
			$notification = Html::Notification(i18n::Translate("R_ERROR_2"), "failure");
			break;
		case 3:
			$notification = Html::Notification(i18n::Translate("R_ERROR_3"), "failure");
			break;
	}

	// ---------------------------------------------------
	// Let's do it!
	// ---------------------------------------------------

	switch($act) {
		case 'signup':

			// User has entered with username and e-mail address
			if(!Html::Request("username") && !Html::Request("email") && !Html::Request("password")) {
				header("Location: index.php?module=register&step=2&error=3");
				exit;
			}

			// Check if password/confirmation are equal
			if(Html::Request("password") != Html::Request("password_conf")) {
				header("Location: index.php?module=register&step=2&error=2");
				exit;
			}

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
				"show_gender"   => 1,
				"token"         => md5(microtime())
			);

			// Find for already registered email address
			$this->Db->Query("SELECT email FROM c_members "
							 . "WHERE email = '{$registerInfo['email']}' OR "
							 . "username = '{$registerInfo['username']}';");
			$emailExistsCount = $this->Db->Rows();

			if($emailExistsCount > 0) {
				header("Location: index.php?module=register&step=2&error=1");
				exit;
			}

			// ---------------------------------------------------
			// Save new member in the database
			// ---------------------------------------------------

			if($registerInfo['usergroup'] == 6) {

				// ---------------------------------------------------
				// Require validation
				// ---------------------------------------------------

				// Set Email() class with community config info
				$Email = new Email($this->Core->config);

				// Insert into database and update stats
				$this->Db->Insert("c_members", $registerInfo);
				$memberId = $this->Db->GetLastID();
				$this->Db->Query("UPDATE c_stats SET member_count = member_count + 1;");

				// Buid e-mail body

				$validationUrl = $this->Core->config['general_communityurl']
					. "?module=register&act=validate&m={$memberId}&token={$registerInfo['token']}";

				$this->Db->Query("SELECT c_emails.content FROM c_emails WHERE type = 'validate';");
				$emailRawContent = $this->Db->FetchArray();

				$formattedBody = sprintf($emailRawContent[0]['content'],
					$registerInfo['username'],
					$this->Core->config['general_communityname'],
					$validationUrl
				);

				// Send e-mail
				$Email->Send(
					$registerInfo['email'],
					"[" . $this->Core->config['general_communityname'] . "] New Member Validation",
					$formattedBody,
					"index.php?module=register&step=3"
				);
			}
			else {

				// ---------------------------------------------------
				// Does not require validation
				// ---------------------------------------------------

				$this->Db->Insert("c_members", $registerInfo);
				$this->Db->Query("UPDATE c_stats SET member_count = member_count + 1;");
				header("Location: index.php?module=register&step=3");
			}

			exit;
			break;

		case 'validate':

			// Get member ID
			$member = Html::Request("m");
			$token  = Html::Request("token");

			// Check if user has already validated
			$this->Db->Query("SELECT m_id, usergroup, token FROM c_members WHERE m_id = {$member};");
			$validationCount = $this->Db->Rows();
			$validationResults = $this->Db->FetchArray();

			if($validationCount > 0) {
				// Validate usergroup
				if($validationResults[0]['usergroup'] != 6) {
					Html::Error(i18n::Translate("R_VALIDATE_ALREADY"));
				}

				// Validate member's security token
				if($validationResults[0]['token'] != $token) {
					Html::Error(i18n::Translate("R_VALIDATE_NOT_MATCH"));
				}

				// Validate and redirect
				$this->Db->Query("UPDATE c_members SET usergroup = '3' WHERE m_id = '{$member}';");
				header("Location: index.php?module=exception&message=1");
				exit;
			}
			else {
				Html::Error(i18n::Translate("R_VALIDATE_NOT_FOUND"));
			}

			exit;
			break;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = i18n::Translate("R_TITLE");
	$pageinfo['bc'] = array(i18n::Translate("R_TITLE"));

?>
