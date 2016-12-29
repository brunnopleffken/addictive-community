<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Session.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Kernel;

class Session
{
	// Database class and Main->info array
	private $Db;
	private $Member;
	private $controller;

	// Session expiration time (defined in constructor)
	private $session_expires = 0;

	// Session activity time cut-off (defined in constructor)
	private $session_activity_cut = 0;

	// Session ID (MD5 format)
	public $session_id = "";

	// Session information
	public $session_info = array();

	// Member information
	public $member_info = array(
		'm_id'      => 0,
		'usergroup' => 5
	);

	/**
	 * --------------------------------------------------------------------
	 * CONSTRUCTOR: SET SESSION CONFIGURATIONS
	 * --------------------------------------------------------------------
	 */
	public function __construct($database, $controller)
	{
		// Store database class in $this->Db
		$this->Db = $database;
		$this->controller = $controller;

		// Session will expires in 30 days' time
		$this->session_expires = time() + DAY * 30;

		// Activity cut-off set to past 20 minutes (by default)
		$this->session_activity_cut = time() - MINUTE * 20;
	}

	/**
	 * --------------------------------------------------------------------
	 * HELPER: SET A NEW COOKIE
	 * --------------------------------------------------------------------
	 */
	public function CreateCookie($name, $value, $expire = 1)
	{
		if($expire == 1) {
			$expire = $this->session_expires;
		}
		setcookie($name, Text::Sanitize($value), $expire, "/");
	}

	/**
	 * --------------------------------------------------------------------
	 * HELPER: GET COOKIE VALUE
	 * --------------------------------------------------------------------
	 */
	public function GetCookie($name)
	{
		if(isset($_COOKIE[$name])) {
			return $_COOKIE[$name];
		}
		else {
			return false;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * HELPER: UNLOAD COOKIE FROM CLIENT
	 * --------------------------------------------------------------------
	 */
	public function UnloadCookie($name)
	{
		if(isset($_COOKIE[$name])) {
			unset($_COOKIE[$name]);
			setcookie($name, "", 1, "/");
		}
		else {
			throw new Exception("Could not unload cookie '" . $name . "'.");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * DO NOT ALLOW GUEST TO VIEW PAGE
	 * --------------------------------------------------------------------
	 */
	public function NoGuest()
	{
		if($this->session_info['member_id'] == 0) {
			header("Location: error?t=not_allowed");
			exit;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURN TRUE IF USER IS A REGISTERED MEMBER
	 * --------------------------------------------------------------------
	 */
	public function IsMember()
	{
		if($this->session_info['member_id'] != 0) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURN TRUE IF USER IS AN ADMINISTRATOR
	 * --------------------------------------------------------------------
	 */
	public function IsAdmin()
	{
		if($this->member_info['usergroup'] == 1) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * UPDATE SESSION (OR CREATE A NEW ONE)
	 * THIS IS THE MAIN METHOD ALL OVER THE CLASS. FROM HERE, YOU'LL DEFINE
	 * IF USER IS A GUEST OR A MEMBER, TO CREATE A NEW SESSION OR UPDATE
	 * AN EXISTING ONE.
	 * --------------------------------------------------------------------
	 */
	public function UpdateSession()
	{
		// Initialize session
		session_start();

		// Set flag if 'session_id' index is defined
		$has_session = (isset($_SESSION['session_id'])) ? true : false;

		// Create a new session ID if it does not exists in browser session
		// If it exists, save in class property for further usage
		if(!$has_session) {
			// Create new session ID and store it in browser session
			$this->session_id = md5(uniqid(mt_rand(), true));
			$_SESSION['session_id'] = $this->session_id;
		}
		else {
			// If session exists in browser session, store it on $this->session_id
			$this->session_id = $_SESSION['session_id'];
		}

		// Check cookies for Member ID (if user has already logged in)
		if($this->GetCookie("member_id") && $this->GetCookie("member_id") >= 1) {
			// Member has already logged in
			$this->session_info['member_id'] = $this->GetCookie("member_id");

			// Delete all old session data, except current logged in member
			$this->Db->Delete("c_sessions",
				"activity_time < '{$this->session_activity_cut}' AND member_id <> {$this->session_info['member_id']}"
			);

			// Update member session
			$this->UpdateMemberSession();
		}
		else {
			// Ok, is a guest, so... member ID is zero!
			$this->session_info['member_id'] = 0;

			// Delete old session data from other users
			$this->Db->Delete("c_sessions", "activity_time < '{$this->session_activity_cut}'");

			if($has_session) {
				// Just a guest navigating...
				$this->session_id = $_SESSION['session_id'];
				$this->UpdateGuestSession($this->session_id);
			}
			else {
				// Viewing page for the first time!
				$this->CreateGuestSession();
			}
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * CREATE A NEW MEMBER SESSION (WITH ALL COOKIES AND SESSIONS IN THE
	 * PACKAGE) WHEN THE USER LOGS IN
	 * --------------------------------------------------------------------
	 */
	public function CreateMemberSession($user_info)
	{
		if(is_array($user_info)) {
			// Delete all existing session register in DB with the same session ID
			$this->Db->Query("DELETE FROM c_sessions
					WHERE activity_time < '{$this->session_activity_cut}'
						OR s_id = '{$user_info['session_id']}'
						OR member_id = {$user_info['m_id']};");

			// Remember user session?
			$persistent = $user_info['remember'];

			// Get session information
			$this->session_info = array(
				's_id'          => $this->session_id,
				'member_id'     => $user_info['m_id'],
				'ip_address'    => getenv("REMOTE_ADDR"),
				'browser'       => getenv("HTTP_USER_AGENT"),
				'activity_time' => time(),
				'usergroup'     => $user_info['usergroup'],
				'anonymous'     => $user_info['anonymous']
			);

			// Create new cookie with member ID
			$this->CreateCookie("member_id", $this->session_info['member_id'], $persistent);

			// Create new sessions for read/unread threads
			$this->CreateCookie("addictive_community_login_time", time(), 1);
			$this->CreateCookie("addictive_community_read_threads", json_encode(array()), 1);

			// Insert new information
			$this->Db->Insert("c_sessions", $this->session_info);
		}
		else {
			Html::Error("Unable to run Session::CreateMemberSession(). '$user_info' must be an array.");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * DESTROY ALL SESSIONS AND REMOVE ALL REGISTER FROM DATABASE
	 * --------------------------------------------------------------------
	 */
	public function DestroySession($member_id)
	{
		// Delete session register in database
		$this->Db->Delete("c_sessions", "member_id = {$member_id}");

		// Delete cookie
		$this->UnloadCookie("member_id");
		$this->UnloadCookie("addictive_community_login_time");
		$this->UnloadCookie("addictive_community_read_threads");

		// Delete browser session
		session_destroy();
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEWING PAGE FOR THE FIRST TIME? CREATE A NEW SESSION FOR HIM! :)
	 * --------------------------------------------------------------------
	 */
	private function CreateGuestSession()
	{
		// Sets new guest session information
		$this->session_info = array(
			's_id'          => $this->session_id,
			'member_id'     => 0,
			'ip_address'    => getenv("REMOTE_ADDR"),
			'browser'       => getenv("HTTP_USER_AGENT"),
			'activity_time' => time(),
			'usergroup'     => 5,
			'anonymous'     => 0
		);

		// Insert new session on database
		$this->Db->Insert("c_sessions", $this->session_info);
	}

	/**
	 * --------------------------------------------------------------------
	 * UPDATE AN EXISTING GUEST SESSION
	 * --------------------------------------------------------------------
	 */
	private function UpdateGuestSession($session_id)
	{
		// Get activity time
		$this->session_info['activity_time'] = time();

		// Update session in database
		$this->Db->Update("c_sessions", array(
			"activity_time = '{$this->session_info['activity_time']}'",
			"location_type = '{$this->controller}'"
		), "s_id = '{$session_id}'");
	}

	/**
	 * --------------------------------------------------------------------
	 * UPDATE AN EXISTING MEMBER SESSION
	 * --------------------------------------------------------------------
	 */
	private function UpdateMemberSession()
	{
		// Set activity time
		$this->session_info['activity_time'] = time();

		// Running the method UpdateExistingMember() for the first time?
		// Check if session register exists in database, if not, create it
		if(!isset($_SESSION['logged_in'])) {
			$this->Db->Query("SELECT EXISTS(SELECT 1 FROM c_sessions WHERE member_id = {$this->session_info['member_id']});");
			$result = $this->Db->Fetch();

			// Ok, register does not exists. Let's create it!
			if($result[key($result)] == 0) {
				// Sets new member session information
				$this->session_info = array(
					's_id'          => $this->session_id,
					'member_id'     => $this->session_info['member_id'],
					'ip_address'    => getenv("REMOTE_ADDR"),
					'browser'       => getenv("HTTP_USER_AGENT"),
					'activity_time' => time(),
					'usergroup'     => 3,
					'anonymous'     => 0
				);

				// Insert new session on database
				$this->Db->Insert("c_sessions", $this->session_info);
			}
		}

		// Get logged in member information
		$this->Db->Query("SELECT * FROM c_members WHERE m_id = '{$this->session_info['member_id']}';");
		$this->member_info = $this->Db->Fetch();

		// Update session in database
		$this->Db->Update("c_sessions", array(
			"activity_time = '{$this->session_info['activity_time']}'",
			"location_type = '{$this->controller}'"
		), "member_id = '{$this->session_info['member_id']}'");
	}
}
