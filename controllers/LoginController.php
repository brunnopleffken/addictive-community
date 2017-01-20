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
	public function index()
	{
		// Load "/templates/.../Ajax.php" as Master Page
		$this->master = "Ajax";
	}

	/**
	 * --------------------------------------------------------------------
	 * DO LOGIN: CREATE A NEW MEMBER SESSION
	 * --------------------------------------------------------------------
	 */
	public function authenticate()
	{
		$this->layout = false;

		// Hash
		$salt = array(
			"hash" => $this->Core->config['security_salt_hash'],
			"key" => $this->Core->config['security_salt_key']
		);

		if(Http::request("username") && Http::request("password")) {
			$username = Http::request("username");
			$password = Text::encrypt(Http::request("password"), $salt);

			Database::query("SELECT m_id, usergroup FROM c_members
					WHERE username = '{$username}' AND password = '{$password}';");

			if(Database::rows()) {
				$member_info = Database::fetch();

				// Check if member has been banned
				if($member_info['usergroup'] == 4) {
					$this->Core->redirect("/failure?t=banned");
				}

				$member_info['anonymous'] = (Http::request("anonymous", true)) ? 1 : 0;
				$member_info['remember'] = (Http::request("remember", true)) ? 1 : 0;
				$member_info['session_token'] = SessionState::retrieve("session_token");

				// Check if member session was created successfully
				SessionState::createMemberSession($member_info);

				// Redirect to Home
				$this->Core->redirect("/");
			}
			else {
				// No lines returned: show error
				// "Username or password is wrong."
				$this->Core->redirect("failure?t=wrong_username_password");
			}
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * VALIDATE USERNAME AND PASSWORD
	 * --------------------------------------------------------------------
	 */
	public function validate()
	{
		$this->layout = false;

		// Hash
		$salt = array(
			"hash" => $this->Core->config['security_salt_hash'],
			"key"  => $this->Core->config['security_salt_key']
		);

		if(Http::request("username") && Http::request("password")) {
			$username = Http::request("username");
			$password = Text::encrypt(Http::request("password"), $salt);

			Database::query("SELECT 1 FROM c_members
					WHERE username = '{$username}' AND password = '{$password}';");

			// Check if username and password match
			if(Database::rows()) {
				$user_info = Database::fetch();

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
	public function logout()
	{
		$this->layout = false;

		// Delete session from the database
		$member_id = SessionState::$user_data['m_id'];
		Database::delete("c_sessions", "member_id = {$member_id}");

		// Destroy cookies
		SessionState::unloadCookie("member_id");
		SessionState::unloadCookie("login_time");
		SessionState::unloadCookie("read_threads");

		// Redirect
		$this->Core->redirect("/");
	}
}
