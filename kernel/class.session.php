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
		// Database class and Main->info array
		private $Db;
		private $Member;
		private $Info;

		// Session expiration time (defined in constructor)
		private $sessionExpires = 0;

		// Session activity time cut-off (defined in constructor)
		private $sessionActivityCut = 0;

		// Session ID (MD5 format)
		public $sessionId = "";

		// Session information
		public $sessionInfo = array();

		// ---------------------------------------------------
		// Constructor: get session config
		// ---------------------------------------------------

		public function __construct($database, $communityInfo)
		{
			// Store database class in $this->Db
			$this->Db = $database;
			$this->Info = $communityInfo;

			// Session will expires in 30 days' time
			$this->sessionExpires = time() + DAY * 30;

			// Activity cut-off set to past 20 minutes (by default)
			$this->sessionActivityCut = time() - MINUTE * 20;
		}

		// ---------------------------------------------------
		// HELPER: Sets a new cookie
		// ---------------------------------------------------

		public function CreateCookie($name, $value, $expire = 1)
		{
			if($expire == 1) {
				$expire = $this->sessionExpires;
			}

			setcookie($name, String::Sanitize($value), $expire);
		}

		// ---------------------------------------------------
		// HELPER: Get cookie information
		// ---------------------------------------------------

		public function GetCookie($name)
		{
			if(isset($_COOKIE[$name])) {
				return $_COOKIE[$name];
			}
			else {
				return false;
			}
		}

		// ---------------------------------------------------
		// HELPER: Unload cookie from client
		// ---------------------------------------------------

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
			if($this->Member['m_id'] == 0) {
				header("Location: index.php?module=exception&errno=1");
				exit;
			}
		}

		// ---------------------------------------------------
		// Update session (or create a new one).
		// This is the MAIN method all over the class. From
		// here, you'll define if user is a guest or member,
		// create a new session or update an existing one.
		// ---------------------------------------------------

		public function UpdateSession()
		{
			// Initialize session
			session_start();

			// Set flag if 'session_id' index is defined
			$hasSession = (isset($_SESSION['session_id'])) ? true : false;

			// Create a new session ID if it does not exists in browser session
			// If it exists, save in class property for further usage
			if(!$hasSession) {
				// Create new session ID and store it in browser session
				$this->sessionId = md5(uniqid(microtime()));
				$_SESSION['session_id'] = $this->sessionId;
			}
			else {
				// If session exists in browser session, store it on class property
				$this->sessionId = $_SESSION['session_id'];
			}

			// Check cookies for Member ID (if user has already logged in)
			$this->sessionInfo['member_id'] = $this->GetCookie("member_id");

			if($this->sessionInfo['member_id']) {
				// Update existing member session
				$this->UpdateMemberSession();
			}
			else {
				// Delete old session data
				$this->Db->Query("DELETE FROM c_sessions
						WHERE activity_time < '{$this->sessionActivityCut}';");

				if($hasSession) {
					// Just a guest navigating...
					$this->sessionId = $_SESSION['session_id'];
					$this->UpdateGuestSession($this->sessionId);
				}
				else {
					// Viewing page for the first time!
					$this->CreateGuestSession();
				}
			}
		}

		// ---------------------------------------------------
		// Create a new member session (with all cookies and
		// sessions in the package) when the user log in
		// ---------------------------------------------------

		public function LoginMemberSession($userInfo)
		{
			if(is_array($userInfo)) {
				// Delete all existing session register in DB with the same session ID
				$this->Db->Query("DELETE FROM c_sessions
						WHERE activity_time < '{$this->sessionActivityCut}'
							OR s_id = '{$userInfo['session_id']}'
							OR member_id = {$userInfo['m_id']};");

				// Remember user session?
				$persistent = $userInfo['remember'];

				// Get session information
				$this->sessionInfo = array(
					's_id'			=> $this->sessionId,
					'member_id'		=> $userInfo['m_id'],
					'ip_address'	=> getenv("REMOTE_ADDR"),
					'browser'		=> getenv("HTTP_USER_AGENT"),
					'activity_time'	=> time(),
					'usergroup'		=> $userInfo['usergroup'],
					'anonymous'		=> $userInfo['anonymous']
				);

				// Create new cookie with member ID
				$this->CreateCookie("member_id", $this->sessionInfo['member_id'], $persistent);

				// Insert new information
				$this->Db->Insert("c_sessions", $this->sessionInfo);
			}
			else {
				Html::Error("Unable to run Session::CreateMemberSession(). '$userInfo' must be an array.");
			}
		}

		// ---------------------------------------------------
		// Destroy all sessions and remove all registers in DB
		// ---------------------------------------------------

		public function DestroySession($memberId)
		{
			// Delete session register in database
			$this->Db->Query("DELETE FROM c_sessions WHERE member_id = {$memberId}");

			// Delete cookie
			$this->UnloadCookie("member_id");

			// Delete browser session
			session_destroy();
		}

		// ---------------------------------------------------
		// Viewing page for the first time? Create a brand
		// new session for him! :)
		// ---------------------------------------------------

		private function CreateGuestSession()
		{
			// Sets new guest session information
			$this->sessionInfo = array(
				's_id'          => $this->sessionId,
				'member_id'     => 0,
				'ip_address'    => getenv("REMOTE_ADDR"),
				'browser'       => getenv("HTTP_USER_AGENT"),
				'activity_time' => time(),
				'usergroup'     => 5,
				'anonymous'     => 0
			);

			// Insert new session on database
			$this->Db->Insert("c_sessions", $this->sessionInfo);
		}

		// ---------------------------------------------------
		// Update an existing guest session
		// ---------------------------------------------------

		private function UpdateGuestSession($sessionId)
		{
			// Get activity time
			$this->sessionInfo['activity_time'] = time();

			// Update session in database
			$this->Db->Query("UPDATE c_sessions SET
				activity_time = '{$this->sessionInfo['activity_time']}',
				location_type = '{$this->Info['module']}'
				WHERE s_id = '{$sessionId}';");
		}

		// ---------------------------------------------------
		// Update an existing member session
		// ---------------------------------------------------

		private function UpdateMemberSession()
		{
			// Get activity time
			$this->sessionInfo['activity_time'] = time();

			// Delete old session data, except current logged in member
			$this->Db->Query("DELETE FROM c_sessions WHERE activity_time < '{$this->sessionActivityCut}'
					AND member_id <> {$this->sessionInfo['member_id']};");

			// Running the method UpdateExistingMember() for the first time?
			// Check if session register exists in database, if not, create it
			// and create a session 'logged_in'.
			if(!isset($_SESSION['logged_in'])) {
				$this->Db->Query("SELECT EXISTS(SELECT 1 FROM c_sessions WHERE member_id = {$this->sessionInfo['member_id']});");
				$result = $this->Db->Fetch();

				// Ok, register does not exists. Let's create it!
				if($result[key($result)] == 0) {
					// Sets new member session information
					$this->sessionInfo = array(
						's_id'          => $this->sessionId,
						'member_id'     => $this->sessionInfo['member_id'],
						'ip_address'    => getenv("REMOTE_ADDR"),
						'browser'       => getenv("HTTP_USER_AGENT"),
						'activity_time' => time(),
						'usergroup'     => 3,
						'anonymous'     => 0
					);

					// Insert new session on database
					$this->Db->Insert("c_sessions", $this->sessionInfo);
				}

				$_SESSION['logged_in'] = $this->sessionInfo['member_id'];
			}

			// Update session in database
			$this->Db->Query("UPDATE c_sessions SET
				activity_time = '{$this->sessionInfo['activity_time']}',
				location_type = '{$this->Info['module']}'
				WHERE member_id = '{$this->sessionInfo['member_id']}';");
		}
	}

?>
