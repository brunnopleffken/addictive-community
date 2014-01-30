<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.session.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	class Session
	{
		// Database class
		private $Db;

		// Session expiration time (in 30 days' time)
		private $sExpires = 0;

		// Session activity time cut-off
		private $sActivityCut = 0;

		// Session ID (MD5)
		public $sId = "";

		// Session information
		public $sInfo = array();

		// ----------------------------------------
		// Constructor: get session config
		// ----------------------------------------
		
		public function __construct($database)
		{
			// Store database class in $this->Db
			$this->Db = $database;

			// Session will expires in 30 days' time
			$this->sExpires = time() + 2592000;

			// Activity cut-off set to past 20 minutes (by default)
			$this->sActivityCut = time() - 1200;
		}

		// ----------------------------------------
		// Sets a new cookie
		// ----------------------------------------
		
		public function CreateCookie($name, $value, $expire = 1)
		{
			if($expire == 1) {
				$expire = $this->sExpires;
			}
			
			setcookie($name, String::Sanitize($value), $expire);
		}

		// ----------------------------------------
		// Get cookie information
		// ----------------------------------------
		
		public function GetCookie($name)
		{
			if(isset($_COOKIE[$name])) {
				return $_COOKIE[$name];
			}
			else {
				throw new Exception("Could not get cookie '" . $name . "'.");
			}
		}

		// ----------------------------------------
		// Unload cookie from client
		// ----------------------------------------
		
		public function UnloadCookie($name)
		{
			if(isset($_COOKIE[$name])) {
				setcookie($name, "0", -1);
			}
			else {
				throw new Exception("Could not unload cookie '" . $name . "'.");
			}
		}

		// ---------------------------------------------------
		// Do not allow guests to view page
		// ---------------------------------------------------

		public function NoGuest()
		{
			if($this->sInfo['member_id'] == 0) {
				header("Location: index.php?module=exception&errno=1");
				exit;
			}
		}

		// ----------------------------------------
		// Creates a new guest session
		// ----------------------------------------
		
		public function SetGuestSession()
		{
			$this->sId = md5(uniqid(microtime()));
			
			$this->sInfo = array(
				's_id'			=> $this->sId,
				'member_id'		=> 0,
				'username'		=> "Guest",
				'usergroup'		=> 5,
				'anonymous'		=> 0,
				'ip_address'	=> getenv("REMOTE_ADDR"),
				'browser'		=> getenv("HTTP_USER_AGENT"),
				'activity_time'	=> time()
			);
			
			// Delete old sessions
			
			$this->Db->Query("DELETE FROM c_sessions
				WHERE ip_address = '{$this->sInfo['ip_address']}'
					OR ip_address = '{$this->sInfo['ip_address']}'
					OR activity_time < '{$this->sActivityCut}';");

			// Create new session
			
			$this->Db->Insert("c_sessions", $this->sInfo);
			
			try {
				$this->CreateCookie("session_id", $this->sInfo['s_id'], 1);
			} catch (Exception $ex) {
				Html::Error($ex);
			}

		}

		// ----------------------------------------
		// Creates a new member session
		// ----------------------------------------
		
		public function SetMemberSession($userInfo)
		{
			if($userInfo['m_id']) {
				// Get user information
				
				$this->sId = md5(uniqid(microtime()));
				$persistent = $userInfo['remember'];
				
				$this->sInfo = array(
					'member_id'		=> $userInfo['m_id'],
					'username'		=> $userInfo['username'],
					'usergroup'		=> $userInfo['usergroup'],
					'anonymous'		=> $userInfo['anonymous'],
					's_id'			=> $this->sId,
					'ip_address'	=> getenv("REMOTE_ADDR"),
					'browser'		=> getenv("HTTP_USER_AGENT"),
					'activity_time'	=> time()
				);
				
				// Delete old sessions
				
				$this->Db->Query("DELETE FROM c_sessions
					WHERE member_id = '{$this->sInfo['member_id']}'
						OR ip_address = '{$this->sInfo['ip_address']}'
						OR activity_time < '{$this->sActivityCut}';");
				
				// Create new session
				
				$this->Db->Insert("c_sessions", $this->sInfo);
				
				try {
					$this->CreateCookie("session_id", $this->sInfo['s_id'], $persistent);
					$this->CreateCookie("member_id", $this->sInfo['member_id'], $persistent);
				} catch (Exception $ex) {
					Html::Error($ex);
				}
				
			}
			else {
				throw new Exception("SetMemberSession() could not receive member ID.");
			}

		}

		// ----------------------------------------
		// Update session (or create a new one)
		// ----------------------------------------
		
		public function UpdateSession($community_info)
		{
			try {
				$s_id = $this->GetCookie("session_id");
				$m_id = $this->GetCookie("member_id");
			} catch (Exception $ex) {
				Html::Error($ex);
			}
			
			// Check if the session is registered
			
			if($s_id)
			{
				// Member or guest?
				
				if($m_id)
				{
					// If member
					$this->sInfo['activity_time'] = time();
					$this->sInfo['member_id'] = $m_id;
					
					$this->Db->Query("UPDATE c_members "
							. "SET last_activity = '{$this->sInfo['activity_time']}' "
							. "WHERE m_id = '{$m_id}';");
						
					$this->Db->Query("UPDATE c_sessions SET "
							. "activity_time = '{$this->sInfo['activity_time']}', "
							. "location_type = '{$community_info['module']}' "
							. "WHERE s_id = '{$s_id}';");
				}
				else
				{
					// If guest
					$this->sInfo['activity_time'] = time();
					$this->sInfo['member_id'] = 0;
					
					$this->Db->Query("UPDATE c_sessions SET "
							. "activity_time = '{$this->sInfo['activity_time']}', "
							. "location_type = '{$community_info['module']}' "
							. "WHERE s_id = '{$s_id}';");
				}
			}
			else
			{
				try {
					$this->SetGuestSession();
				} catch (Exception $ex) {
					Html::Error($ex);
				}
			}
		}
	}

?>