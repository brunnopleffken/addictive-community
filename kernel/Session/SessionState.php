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
	public static function initialize($controller_name)
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
	public static function updateSession()
	{
		$has_session = Session::retrieve("session_token") ? true : false;

		if(!$has_session) {
			// Create new session ID
			self::$session_token = md5(uniqid(mt_rand(), true));
			Session::write("session_token", self::$session_token);
		}
		else {
			// Get existing session ID
			self::$session_token = Session::retrieve("session_token");
		}

		if(self::getCookie("member_id")) {
			// User is logged in, manage member session
			self::updateMemberSession();
		}
		else {
			if($has_session) {
				// Is not a member; has session
				self::guestSession();
			}
			else {
				// Is not a member; and has no session
				self::guestSession();
			}
		}

		// After everything, created new sessions, updated the existing ones, delete all expired sessions
		Database::delete("c_sessions",
			"session_token <> '" . self::$session_token . "' AND activity_time < '" . self::$session_activity_cut . "'"
		);
	}

	/**
	 * --------------------------------------------------------------------
	 * Create a brand new member session
	 * --------------------------------------------------------------------
	 */
	public static function createMemberSession($member_info)
	{
		// Delete all existing rows in DB with the same member or session ID
		Database::delete("c_sessions",
			"session_token = '{$member_info['session_token']}' OR member_id = '{$member_info['m_id']}'"
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
		self::createCookie("member_id", $member_info['m_id'], $persistent);

		// Create new sessions for read/unread threads
		self::createCookie("login_time", time(), 1);
		self::createCookie("read_threads", json_encode(array()), 1);

		// Insert new information in DB
		Database::insert("c_sessions", $session);
	}

	/**
	 * --------------------------------------------------------------------
	 * Update a guest session (create it if it doesn't exist)
	 * --------------------------------------------------------------------
	 */
	private static function guestSession()
	{
		Database::query("INSERT INTO c_sessions
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
	private static function updateMemberSession()
	{
		$session_token = Session::retrieve("session_token");
		$member_id = Session::getCookie("member_id");

		// Fields to select from c_members
		// These fields are required globally in Addictive Community
		$fields = implode(", ", [
			"m_id", "username", "usergroup", "email", "theme",
			"template","time_offset", "language", "photo", "photo_type"
		]);

		$find_member = Database::query("SELECT * FROM c_sessions
			WHERE session_token = '{$session_token}' AND member_id = '{$member_id}';");

		if(Database::rows($find_member)) {
			// Get and save user information for later use
			Database::query("SELECT {$fields} FROM c_members WHERE m_id = {$member_id};");
			self::$user_data = Database::fetch();

			// Update activity time and controller
			$now = time();
			Database::query("UPDATE c_sessions
				SET activity_time = '{$now}', location_controller = '" . self::$controller_name . "'
				WHERE session_token = '{$session_token}';");
		}
		else {
			$member_id = SessionState::getCookie("member_id");

			// Delete session from the database (if any)
			Database::delete("c_sessions", "member_id = {$member_id}");

			// Destroy cookies
			SessionState::unloadCookie("member_id");
			SessionState::unloadCookie("login_time");
			SessionState::unloadCookie("read_threads");

			// Redirect
			header("Location: /");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * Return true if session belongs to a logged in member
	 * --------------------------------------------------------------------
	 */
	public static function isMember()
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
	public static function isAdmin()
	{
		if(self::$user_data['m_id'] != 0 && self::$user_data['usergroup'] == 1) {
			return true;
		}
		return false;
	}

	/**
	 * --------------------------------------------------------------------
	 * Redirect to HTTP 403 if user is a guest
	 * --------------------------------------------------------------------
	 */
	public static function noGuest()
	{
		if(self::$user_data['usergroup'] == 5) {
			header("Location: /403");
		}
	}
}
