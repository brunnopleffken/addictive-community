<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Login.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\Http;
use \AC\Kernel\Session\SessionState;
use \AC\Kernel\Text;

class Login extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * LOGIN FORM
	 * --------------------------------------------------------------------
	 */
	public function Index()
	{
		// Load "/templates/.../Ajax.php" as Master Page
		$this->master = "Ajax";
	}

	/**
	 * --------------------------------------------------------------------
	 * DO LOGIN: CREATE A NEW MEMBER SESSION
	 * --------------------------------------------------------------------
	 */
	public function Authenticate()
	{
		$this->layout = false;

		// Hash
		$salt = array(
			"hash" => $this->Core->config['security_salt_hash'],
			"key" => $this->Core->config['security_salt_key']
		);

		if(Http::Request("username") && Http::Request("password")) {
			$username = Http::Request("username");
			$password = Text::Encrypt(Http::Request("password"), $salt);

			Database::Query("SELECT m_id, usergroup FROM c_members
					WHERE username = '{$username}' AND password = '{$password}';");

			if(Database::Rows()) {
				$member_info = Database::Fetch();
				$member_info['anonymous'] = (Http::Request("anonymous", true)) ? 1 : 0;
				$member_info['remember'] = (Http::Request("remember", true)) ? 1 : 0;
				$member_info['session_token'] = SessionState::Retrieve("session_token");

				// Check if member session was created successfully
				SessionState::CreateMemberSession($member_info);

				// Redirect to Home
				$this->Core->Redirect("/");
			}
			else {
				// No lines returned: show error
				// "Username or password is wrong."
				$this->Core->Redirect("failure?t=wrong_username_password");
			}
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * VALIDATE USERNAME AND PASSWORD
	 * --------------------------------------------------------------------
	 */
	public function Validate()
	{
		$this->layout = false;

		// Hash
		$salt = array(
			"hash" => $this->Core->config['security_salt_hash'],
			"key"  => $this->Core->config['security_salt_key']
		);

		if(Http::Request("username") && Http::Request("password")) {
			$username = Http::Request("username");
			$password = Text::Encrypt(Http::Request("password"), $salt);

			Database::Query("SELECT 1 FROM c_members
					WHERE username = '{$username}' AND password = '{$password}';");

			// Check if username and password match
			if(Database::Rows()) {
				$user_info = Database::Fetch();

				// Check if user has been banned (user group #4)
				if($user_info['usergroup'] == 4) {
					$data = array("authenticated" => false, "message" => "You've been banned");
				}
				else {
					$data = array("authenticated" => true);
				}
			}
			else {
				$data = array("authenticated" => false, "message" => "Wrong username or password");
			}
		}
		else {
			$data = array("authenticated" => false, "message" => "Username or password is required");
		}

		echo json_encode($data);
	}

	/**
	 * --------------------------------------------------------------------
	 * REMOVE ALL MEMBER SESSIONS/COOKIES AND LOG OUT
	 * --------------------------------------------------------------------
	 */
	public function Logout()
	{
		$this->layout = false;

		// Delete session from the database
		$member_id = SessionState::$user_data['m_id'];
		Database::Delete("c_sessions", "member_id = {$member_id}");

		// Destroy cookies
		SessionState::UnloadCookie("member_id");
		SessionState::UnloadCookie("login_time");
		SessionState::UnloadCookie("read_threads");

		// Redirect
		$this->Core->Redirect("/");
	}
}
