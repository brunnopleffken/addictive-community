<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Html.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
## -------------------------------------------------------

class Html
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
			$text = $_REQUEST[$name];
			$text = str_replace("&", "&amp;", $text);
			$text = str_replace("<", "&lt;", $text);
			$text = str_replace(">", "&gt;", $text);
			$text = str_replace('"', "&quot;", $text);
			$text = str_replace("'", "&#39;", $text);
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
	 * REMOVE ALL NON-ALPHANUMERIC CHARACTER
	 * --------------------------------------------------------------------
	 */
	public static function Sanitize($string, $allowed = array())
	{
		$allow = null;
		if(!empty($allowed)) {
			foreach($allowed as $value) {
				$allow .= "\\$value";
			}
		}
		if(!is_array($string)) {
			return preg_replace("/[^{$allow}a-zA-Z0-9]/", "", $string);
		}
		$cleaned = array();
		foreach($string as $key => $clean) {
			$cleaned[$key] = preg_replace("/[^{$allow}a-zA-Z0-9]/", "", $clean);
		}
		return $cleaned;
	}

	/**
	 * --------------------------------------------------------------------
	 * LIST OF DAYS OF THE MONTH (1 TO 31)
	 * --------------------------------------------------------------------
	 */
	public static function Days($name, $current = 1, $show_placeholder = false)
	{
		$retval = "<select name='{$name}' id='{$name}' class='select2-no-search' style='width: 60px'>";

		if($show_placeholder) {
			$retval .= "<option value=''>-</option>";
		}

		for($i = 1; $i <= 31; $i++) {
			$selected = ($i == $current) ? "selected" : "";
			$retval .= "<option value='{$i}' {$selected}>{$i}</option>";
		}
		$retval .= "</select>";
		return $retval;
	}

	/**
	 * --------------------------------------------------------------------
	 * LIST OF MONTHS OF THE YEAR (JAN TO DEC)
	 * $lang IS REQUIRED IF $numeric = false
	 * --------------------------------------------------------------------
	 */
	public static function Months($name, $numeric = true, $current = 1, $show_placeholder = false)
	{
		$retval = "<select name='{$name}' id='{$name}' class='select2-no-search' style='width: 110px'>";

		if($show_placeholder) {
			$retval .= "<option value=''>-</option>";
		}

		for($i = 1; $i <= 12; $i++) {
			$selected = ($i == $current) ? "selected" : "";
			if(!$numeric) {
				$month_name = i18n::Translate("M_" . $i);
				$retval .= "<option value='{$i}' {$selected}>{$month_name}</option>";
			}
			else {
				$retval .= "<option value='{$i}' {$selected}>{$i}</option>";
			}
		}
		$retval .= "</select>";
		return $retval;
	}

	/**
	 * --------------------------------------------------------------------
	 * REALLY NICE YEAR DROP-DOWN GENERATOR
	 * --------------------------------------------------------------------
	 */
	public static function Years($name, $before, $after, $current = 0, $show_placeholder = false)
	{
		$now = date("Y", time());
		$retval = "<select name='{$name}' id='{$name}' class='select2-no-search' style='width: 75px'>";

		if($show_placeholder) {
			$retval .= "<option value=''>-</option>";
		}
		else {
			$current = ($current == 0) ? $now : $current;
		}

		for($i = $now + $after; $i >= $now - $before; $i--) {
			$selected = ($i == $current) ? "selected" : "";
			$retval .= "<option value='{$i}' {$selected}>{$i}</option>";
		}
		$retval .= "</select>";
		return $retval;
	}

	/**
	 * --------------------------------------------------------------------
	 * LIST OF HOURS (0 TO 23)
	 * --------------------------------------------------------------------
	 */
	public static function Hours($name, $current = 0)
	{
		$retval = "<select name='{$name}' id='{$name}' class='select2-no-search' style='width: 55px'>";
		for($i = 0; $i <= 23; $i++) {
			$selected = ($i == $current) ? "selected" : "";
			if($i < 10) {
				$i = "0" . $i;
			}
			$retval .= "<option value='{$i}' {$selected}>{$i}</option>";
		}
		$retval .= "</select>";
		return $retval;
	}

	/**
	 * --------------------------------------------------------------------
	 * LIST OF MINUTES (0, 15, 30 AND 45)
	 * --------------------------------------------------------------------
	 */
	public static function Minutes($name, $current = 0)
	{
		$retval = "<select name='{$name}' id='{$name}' class='select2-no-search' style='width: 55px'>";
		for($i = 0; $i <= 45; $i += 15) {
			$selected = ($i == $current) ? "selected" : "";
			if($i < 10) {
				$i = "0" . $i;
			}
			$retval .= "<option value='{$i}' {$selected}>{$i}</option>";
		}
		$retval .= "</select>";
		return $retval;
	}

	/**
	 * --------------------------------------------------------------------
	 * SHOW NOTIFICATION MESSAGE
	 * --------------------------------------------------------------------
	 */
	public static function Notification($message, $code, $persistent = false, $custom_title = "")
	{
		switch($code) {
			case "warning":
				$title = "WARNING!";
				break;
			case "success":
				$title = "SUCCESS!";
				break;
			case "failure":
				$title = "ERROR!";
				break;
			case "info":
				$title = "INFORMATION:";
				break;
		}
		if($persistent) {
			$persistent = "persistent";
		}
		if($custom_title != "") {
			$title = $custom_title;
		}
		$html = "<div class='notification " . $code . " " . $persistent . "'><p><strong>" . $title . "</strong> " . $message . "</p></div>";
		return $html;
	}

	/**
	 * --------------------------------------------------------------------
	 * FORUM RULES TEMPLATE
	 * --------------------------------------------------------------------
	 */
	public static function ForumRules($title, $text)
	{
		$html = "<div class='notification warning'><p><strong>" . $title . "</strong> " . $text . "</p></div>";
		return $html;
	}

	/**
	 * --------------------------------------------------------------------
	 * CROP IMAGE TO FILL AREA
	 * --------------------------------------------------------------------
	 */
	public static function Crop($image, $w, $h, $class = "")
	{
		$html = "<div style=\"display:inline-block; width:{$w}px; height:{$h}px; background: url('{$image}') no-repeat center top; background-size:cover; image-rendering: optimizeQuality;\" class='{$class}'></div>";
		return $html;
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

	/**
	 * --------------------------------------------------------------------
	 * SHOW ERROR MESSAGE
	 * --------------------------------------------------------------------
	 */
	public static function Error($message)
	{
		echo "<h1>Error!</h1><p>" . $message . "</p><hr><em>Addictive Community - (c) " . date("Y") . " All rights reserved.</em>";
		exit;
	}
}
