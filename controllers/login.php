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

				$this->Db->Query("SELECT m_id, username, password, usergroup FROM c_members "
						. "WHERE username = '{$username}' AND password = '{$password}';");

				if($this->Db->Rows()) {
					$userInfo = $this->Db->Fetch();

					$userInfo['remember'] = (Html::Request("remember")) ? 1 : 0;
					$userInfo['anonymous'] = (Html::Request("anonymous")) ? 1 : 0;
					
					// Check if member session was created successfully
					try {
						$this->Session->SetMemberSession($userInfo);
					} catch (Exception $ex) {
						Html::Error($ex);
					}

					// Are we attempting to login from an exception page?
					// HTML: <input type="hidden" name="exception_referrer" value="true">
					
					if(!Html::Request("exception_referrer")) {
						header("Location: " . getenv("HTTP_REFERER"));
					}
					else {
						header("Location: index.php");
					}
				}
				else {
					// No lines returned: show error
					// "Username or password is wrong."
					header("Location: index.php?module=exception&errno=7");
					exit;
				}
			}
			
			exit;
			break;
		
		case "logout":
			
			// Get referrer URL
			$from = getenv('HTTP_REFERER');
			
			// Destroy everything
			try {
				$this->Session->UnloadCookie("session_id");
				$this->Session->UnloadCookie("member_id");
			} catch (Exception $ex) {
				Html::Error($ex);
			}
			
			// Redirect
			header("Location: " . $from);
			
			exit;
			break;
	}

	// ---------------------------------------------------
	// This is an Ajax window, so...
	// ---------------------------------------------------

	$define['layout'] = "ajax";

?>