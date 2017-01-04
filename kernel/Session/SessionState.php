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

namespace AC\Kernel\Session;

use AC\Kernel\Database;
use AC\Kernel\Session;

class SessionState extends Session
{
	// Store session state variables
	public static $user_data = null;

	// Current session ID
	public static $session_token = "";

	// Expiration time and activity time cut-off
	private static $session_expires = 0;
	private static $session_activity_cut = 0;

	// Currently loaded controller
	private static $controller_name = "";

	/**
	 * --------------------------------------------------------------------
	 * Initialize a new session state
	 * --------------------------------------------------------------------
	 */
	public static function Initialize($controller_name)
	{
		// Start new or resume existing session
		session_start();

		// Get expiration and activity cut times
		self::$session_expires = time() + DAY * 30;
		self::$session_activity_cut = time() - MINUTE * 20;

		// Assign controller name to private property
		self::$controller_name = $controller_name;
	}

	/**
	 * --------------------------------------------------------------------
	 * Update session; or create a new one
	 * --------------------------------------------------------------------
	 */
	public static function UpdateSession()
	{
		$has_session = Session::Retrieve("session_token") ? true : false;

		if(!$has_session) {
			// Create new session ID
			self::$session_token = md5(uniqid(mt_rand(), true));
			Session::Write("session_token", self::$session_token);
		}
		else {
			// Get existing session ID
			self::$session_token = Session::Retrieve("session_token");
		}

		if(self::GetCookie("member_id")) {
			// User is logged in, manage member session
			self::UpdateMemberSession();
		}
		else {
			if($has_session) {
				// Is not a member; has session
				self::GuestSession('update', self::$session_token);
			}
			else {
				// Is not a member; and has no session
				self::GuestSession('create');
			}
		}

		// After everything, created new sessions, updated the existing ones, delete all expired sessions
		Database::Delete("c_sessions",
			"session_token <> '" . self::$session_token . "' AND activity_time < '" . self::$session_activity_cut . "'"
		);
	}

	/**
	 * --------------------------------------------------------------------
	 * Create a brand new member session
	 * --------------------------------------------------------------------
	 */
	public static function CreateMemberSession($member_info)
	{
		// Delete all existing rows in DB with the same member or session ID
		Database::Delete("c_sessions",
			"session_token = '{$member_info['m_id']}' OR member_id = '{$member_info['session_token']}'"
		);

		// Remember user session?
		$persistent = $member_info['remember'];

		// Build session information
		$session = array(
			"session_token" => $member_info['session_token'],
			"member_id"     => $member_info['m_id'],
			"ip_address"    => getenv("REMOTE_ADDR"),
			"activity_time" => time(),
			"usergroup"     => $member_info['usergroup'],
			"anonymous"     => $member_info['anonymous']
		);

		// Create new cookie with member ID
		self::CreateCookie("member_id", $member_info['m_id'], $persistent);

		// Create new sessions for read/unread threads
		self::CreateCookie("login_time", time(), 1);
		self::CreateCookie("read_threads", json_encode(array()), 1);

		// Insert new information in DB
		Database::Insert("c_sessions", $session);
	}

	/**
	 * --------------------------------------------------------------------
	 * Update a guest session (create it if it doesn't exist)
	 * --------------------------------------------------------------------
	 */
	private static function GuestSession($action, $session_token = "")
	{
		Database::Query("INSERT INTO c_sessions
			(session_token, member_id, ip_address, activity_time, usergroup, anonymous) VALUES
			('" . self::$session_token . "', 0, '" . getenv("REMOTE_ADDR") . "', '" . time() . "', 5, 0)
			ON DUPLICATE KEY UPDATE activity_time = '" . time() . "', location_controller = '" . self::$controller_name . "';");

		self::$user_data = array(
			"m_id" => 0,
			"usergroup" => 5
		);
	}

	/**
	 * --------------------------------------------------------------------
	 * Update an already logged in member
	 * Cookie 'member_id' must match the 'session_token'
	 * --------------------------------------------------------------------
	 */
	private static function UpdateMemberSession()
	{
		$session_token = Session::Retrieve("session_token");
		$member_id = Session::GetCookie("member_id");

		// Validate token and member ID association
		$find_member = Database::Query("SELECT * FROM c_sessions
			WHERE session_token = '{$session_token}' AND member_id = '{$member_id}';");

		if(Database::Rows($find_member)) {
			$member = Database::Query("SELECT m_id, username, usergroup, email, theme, template, language, photo_type
				FROM c_members WHERE m_id = {$member_id};");

			$user_data = Database::Fetch();

			// Store member ID and user group for later use
			self::$user_data = $user_data;
		}
		else {
			$member_id = SessionState::GetCookie("member_id");

			// Delete session from the database (if any)
			Database::Delete("c_sessions", "member_id = {$member_id}");

			// Destroy cookies
			SessionState::UnloadCookie("member_id");
			SessionState::UnloadCookie("login_time");
			SessionState::UnloadCookie("read_threads");

			// Redirect
			header("Location: /");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * Return true if session belongs to a logged in member
	 * --------------------------------------------------------------------
	 */
	public static function IsMember()
	{
		if(self::$user_data['m_id'] != 0) {
			return true;
		}
		return false;
	}

	/**
	 * --------------------------------------------------------------------
	 * Return true if logged in member is an Administrator
	 * --------------------------------------------------------------------
	 */
	public static function IsAdmin()
	{
		if(self::$user_data['m_id'] != 0 && self::$user_data['usergroup'] == 1) {
			return true;
		}
		return false;
	}
}
