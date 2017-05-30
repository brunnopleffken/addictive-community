<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  https://github.com/addictivehub/addictive-community
#
#  File: Session.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## -------------------------------------------------------

namespace AC\Kernel;

class Session
{
	/**
	 * --------------------------------------------------------------------
	 * Initialize a new session
	 * --------------------------------------------------------------------
	 */
	public static function init()
	{
		session_start();
	}

	/**
	 * --------------------------------------------------------------------
	 * Create a new session
	 * --------------------------------------------------------------------
	 */
	public static function write($name, $value)
	{
		if(!self::isDefined($name)) {
			$_SESSION[$name] = $value;
		}
		else {
			Html::throwError("Session '{$name}' is already defined.");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * Get session value
	 * --------------------------------------------------------------------
	 */
	public static function retrieve($name)
	{
		if(self::isDefined($name)) {
			return $_SESSION[$name];
		}
		else {
			return false;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * Permanently deletes a session; or all sessions if there's no param
	 * --------------------------------------------------------------------
	 */
	public static function destroy($name = "")
	{
		if($name != "") {
			unset($_SESSION[$name]);
			return true;
		}
		else {
			return session_destroy();
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * Return all current sessions
	 * --------------------------------------------------------------------
	 */
	public static function show()
	{
		Text::PR($_SESSION);
	}

	/**
	 * --------------------------------------------------------------------
	 * Check if the session exists
	 * --------------------------------------------------------------------
	 */
	public static function isDefined($session_name)
	{
		if(isset($_SESSION[$session_name])) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * Set a new cookie (cookie expires in 30 days)
	 * --------------------------------------------------------------------
	 */
	public static function createCookie($name, $value, $expire = 1)
	{
		if($expire == 1) {
			$expire = time() + DAY * 30;
		}
		setcookie($name, Text::sanitize($value), $expire, "/");
	}

	/**
	 * --------------------------------------------------------------------
	 * Return defined cookies
	 * --------------------------------------------------------------------
	 */
	public static function getCookie($name)
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
	 * Unload cookie from client environment
	 * --------------------------------------------------------------------
	 */
	public static function unloadCookie($name)
	{
		if(isset($_COOKIE[$name])) {
			unset($_COOKIE[$name]);
			setcookie($name, "", 1, "/");
		}
	}
}
