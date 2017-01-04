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
	/**
	 * --------------------------------------------------------------------
	 * Create a new session
	 * --------------------------------------------------------------------
	 */
	public static function Write($name, $value)
	{
		if(!self::IsDefined($name)) {
			$_SESSION[$name] = $value;
		}
		else {
			Html::Error("Session '{$name}' is already defined.");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * Get session value
	 * --------------------------------------------------------------------
	 */
	public static function Retrieve($name)
	{
		if(self::IsDefined($name)) {
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
	public static function Destroy($name = "")
	{
		if($name != "") {
			unset($_SESSION[$name]);
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
	public static function List()
	{
		Text::PR($_SESSION);
	}

	/**
	 * --------------------------------------------------------------------
	 * Check if the session exists
	 * --------------------------------------------------------------------
	 */
	public static function IsDefined($session_name)
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
	public static function CreateCookie($name, $value, $expire = 1)
	{
		if($expire == 1) {
			$expire = time() + DAY * 30;
		}
		setcookie($name, Text::Sanitize($value), $expire, "/");
	}

	/**
	 * --------------------------------------------------------------------
	 * Return defined cookies
	 * --------------------------------------------------------------------
	 */
	public static function GetCookie($name)
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
	public static function UnloadCookie($name)
	{
		if(isset($_COOKIE[$name])) {
			unset($_COOKIE[$name]);
			setcookie($name, "", 1, "/");
		}
	}
}
