<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: login.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Get action
	// ---------------------------------------------------

	$act = $this->Core->QueryString("act", null);

	switch($act) {
		case "do":
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
						header("Location: " . $this->Core->config['general_communityurl']);
					}
				}
				else {
					// No lines returned: show error
					// "Username or password is wrong."
					header("Location: index.php?module=exception&errno=1");
					exit;
				}
			}

			exit;
			break;

		case "logout":

			// Get referrer URL and member ID
			$from = getenv('HTTP_REFERER');
			$m_id = $this->member['m_id'];

			// Destroy everything
			$this->Session->DestroySession($m_id);

			// Redirect
			header("Location: " . $this->Core->config['general_communityurl']);

			exit;
			break;

		case "validate":

			if(Html::Request("username") && Html::Request("password")) {
				$username = Html::Request("username");
				$password = String::PasswordEncrypt(Html::Request("password"));

				$this->Db->Query("SELECT m_id, username, password, usergroup FROM c_members
						WHERE username = '{$username}' AND password = '{$password}';");

				if($this->Db->Rows()) {

					$user_info = $this->Db->FetchArray();

					if($user_info[0]['usergroup'] == 4) {
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

			exit;
			break;
	}

	// ---------------------------------------------------
	// This is an Ajax window, so...
	// ---------------------------------------------------

	$define['layout'] = "ajax";

?>
