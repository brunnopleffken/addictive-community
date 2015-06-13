<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Http.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
## -------------------------------------------------------

class Http
{
	/**
	 * --------------------------------------------------------------------
	 * SAME AS $_REQUEST['var'], BUT SANITIZED
	 * --------------------------------------------------------------------
	 */
	public static function Request($name, $numeric = false)
	{
		if(isset($_REQUEST[$name]) && $numeric == true && !is_numeric($_REQUEST[$name])) {
			Html::Error("Variable '{$name}' must be an integer.");
		}

		if(isset($_REQUEST[$name])) {
			$text = stripslashes($_REQUEST[$name]);
			$text = str_replace("&", "&amp;", $text);
			$text = str_replace("<", "&lt;", $text);
			$text = str_replace(">", "&gt;", $text);
			$text = str_replace('"', "&quot;", $text);
			$text = str_replace("'", "&apos;", $text);
		}
		else {
			return false;
		}

		return $text;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET UPLOADED FILE
	 * --------------------------------------------------------------------
	 */
	public static function File($name)
	{
		if(isset($_FILES[$name]) && !empty($_FILES[$name])) {
			return $_FILES[$name];
		}
		else {
			return false;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * GET CURRENT URL
	 * --------------------------------------------------------------------
	 */
	public static function CurrentUrl()
	{
		$page_url = (@$_SERVER['HTTPS'] == "on") ? "https" : "http";
		$page_url .= "://";

		if($_SERVER['SERVER_PORT'] != "80") {
			$page_url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		}
		else {
			$page_url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}

		return $page_url;
	}
}
