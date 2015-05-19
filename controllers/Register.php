<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Register.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
## -------------------------------------------------------

class Register extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * VIEW: REGISTER NEW MEMBER
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		// Get step
		$step = (!Html::Request("step")) ? 1 : Html::Request("step");

		// Notifications
		$message_id = Html::Request("m");
		$notification = array("",
			Html::Notification(i18n::Translate("R_ERROR_1"), "failure", true),
			Html::Notification(i18n::Translate("R_ERROR_2"), "failure", true),
			Html::Notification(i18n::Translate("R_ERROR_3"), "failure", true),
			Html::Notification(i18n::Translate("R_ERROR_4"), "failure", true)
		);

		// Page info
		$page_info['title'] = i18n::Translate("R_TITLE");
		$page_info['bc'] = array(i18n::Translate("R_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("step", $step);
		$this->Set("notification", $notification[$message_id]);
		$this->Set("general_security_validation", $this->config['general_security_validation']);
	}

	/**
	 * --------------------------------------------------------------------
	 * PROCESS REGISTER
	 * --------------------------------------------------------------------
	 */
	public function SignUp()
	{
		$this->layout = false;

		// Check if user has entered with username, password and e-mail address
		if(!Html::Request("username") || !Html::Request("email") || !Html::Request("password")) {
			$this->Core->Request("register?step=2&m=1");
		}

		// Check if passwords are equal
		if(Html::Request("password") != Html::Request("password_conf")) {
			$this->Core->Request("register?step=2&m=2");
		}

		// Check username length
		if(strlen(Html::Request("username")) < 3 || strlen(Html::Request("username")) > 20) {
			$this->Core->Request("register?step=2&m=3");
		}

		// Check if Require Validation is TRUE in community settings
		$usergroup = ($this->config['general_security_validation'] == "true") ? 6 : 3;

		// Hash
		$salt = array(
			"hash" => $this->config['security_salt_hash'],
			"key"  => $this->config['security_salt_key']
		);

		// Build new member info array
		$register_info = array(
			"username"      => Html::Request("username"),
			"password"      => String::PasswordEncrypt(Html::Request("password"), $salt),
			"email"         => Html::Request("email"),
			"hide_email"    => 1,
			"ip_address"    => $_SERVER['REMOTE_ADDR'],
			"joined"        => time(),
			"usergroup"     => $usergroup,
			"photo_type"    => "gravatar",
			"posts"         => 0,
			"template"      => $this->config['template'],
			"theme"         => $this->config['theme'],
			"language"      => $this->config['language'],
			"time_offset"   => $this->config['date_default_offset'],
			"dst"           => 0,
			"show_birthday" => 1,
			"show_gender"   => 1,
			"token"         => md5(microtime())
		);

		// Check if username or e-mail address already exists
		$this->Db->Query("SELECT username, email FROM c_members
				WHERE username = '{$register_info['username']}' OR email = '{$register_info['email']}';");

		if($this->Db->Rows() > 0) {
			$this->Core->Redirect("register?step=2&m=4");
		}

		// Save new member in the Database

		if($register_info['usergroup'] == 6) {
			// REQURE VALIDATION

			// Instance of Email() class
			$Email = new Email($this->config);

			// Insert into database and update stats
			$this->Db->Insert("c_members", $register_info);
			$new_member_id = $this->Db->GetLastID();
			$this->Db->Query("UPDATE c_stats SET member_count = member_count + 1;");

			// Buid e-mail body
			$validation_url = $this->config['general_community_url']
					. "register/validate?m={$new_member_id}&token={$register_info['token']}";

			$this->Db->Query("SELECT content FROM c_emails WHERE type = 'validate';");
			$email_raw_content = $this->Db->Fetch();

			$email_formatted_content = sprintf($email_raw_content['content'],
				$register_info['username'],
				$this->config['general_community_name'],
				$validation_url
			);

			// Send e-mail
			$Email->Send(
				$register_info['email'],
				"[" . $this->config['general_community_name'] . "] New Member Validation",
				$email_formatted_content
			);

			$this->Core->Redirect("register?step=3");
		}
		else {
			// DO NOT REQUIRE VALIDATION
			$this->Db->Insert("c_members", $register_info);
			$this->Db->Query("UPDATE c_stats SET member_count = member_count + 1;");
			$this->Core->Redirect("register?step=3");
		}

		exit;
	}

	/**
	 * --------------------------------------------------------------------
	 * VALIDATE NEW MEMBER ACCOUNT
	 * --------------------------------------------------------------------
	 */
	public function Validate()
	{
		$this->layout = false;

		// Get member ID
		$member = Html::Request("m");
		$token  = Html::Request("token");

		// Check if user has already validated
		$this->Db->Query("SELECT m_id, usergroup, token FROM c_members WHERE m_id = {$member};");
		$validation_count = $this->Db->Rows();
		$validation_results = $this->Db->Fetch();

		if($validation_count > 0) {
			// Validate usergroup
			if($validation_results['usergroup'] != 6) {
				Html::Error(i18n::Translate("R_VALIDATE_ALREADY"));
			}

			// Validate member's security token
			if($validation_results['token'] != $token) {
				Html::Error(i18n::Translate("R_VALIDATE_NOT_MATCH"));
			}

			// Validate and redirect
			$this->Db->Query("UPDATE c_members SET usergroup = '3' WHERE m_id = '{$member}';");
			$this->Core->Redirect("register?step=4");
		}
		else {
			Html::Error(i18n::Translate("R_VALIDATE_NOT_FOUND"));
		}
	}
}
