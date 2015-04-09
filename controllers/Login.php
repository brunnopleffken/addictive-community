<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Login.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

class Login extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * LOGIN FORM
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		// Load "/templates/.../Ajax.php" as Master Page
		$this->master = "Ajax";
	}

	/**
	 * --------------------------------------------------------------------
	 * DO LOGIN: CREATE A NEW MEMBER SESSION
	 * --------------------------------------------------------------------
	 */
	public function Do()
	{
		if(Html::Request("username") && Html::Request("password")) {
			$username = Html::Request("username");
			$password = String::PasswordEncrypt(Html::Request("password"));

			$this->Db->Query("SELECT m_id, username, password, usergroup FROM c_members
					WHERE username = '{$username}' AND password = '{$password}';");

			if($this->Db->Rows()) {
				$userInfo = $this->Db->Fetch();
				$userInfo['anonymous']  = (Html::Request("anonymous")) ? 1 : 0;
				$userInfo['remember']   = (Html::Request("remember")) ? 1 : 0;
				$userInfo['session_id'] = $_SESSION['session_id'];

				// Check if member session was created successfully
				$this->Session->LoginMemberSession($userInfo);

				// Are we attempting to login from an exception page?
				// HTML: <input type="hidden" name="exception_referrer" value="true">
				if(!Html::Request("exception_referrer")) {
					header("Location: " . getenv("HTTP_REFERER"));
				}
				else {
					$this->Core->Redirect("");
				}
			}
			else {
				// No lines returned: show error
				// "Username or password is wrong."
				header("Location: index.php?module=exception&errno=1");
				exit;
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

		if(Html::Request("username") && Html::Request("password")) {
			$username = Html::Request("username");
			$password = String::PasswordEncrypt(Html::Request("password"));

			$this->Db->Query("SELECT m_id, username, password, usergroup FROM c_members
					WHERE username = '{$username}' AND password = '{$password}';");

			if($this->Db->Rows()) {
				$user_info = $this->Db->Fetch();

				if($user_info['usergroup'] == 4) {
					$data = array("authenticated" => "false", "message" => "You've been banned");
				}
				else {
					$data = array("authenticated" => "true");
				}
			}
			else {
				$data = array("authenticated" => "false", "message" => "Wrong username or password");
			}
		}

		echo json_encode($data);
	}
}
