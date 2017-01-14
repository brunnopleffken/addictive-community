<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Html.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Kernel;

class Html
{
	/**
	 * --------------------------------------------------------------------
	 * REMOVE ALL NON-ALPHANUMERIC CHARACTER
	 * --------------------------------------------------------------------
	 */
	public static function sanitize($string, $allowed = array())
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
	public static function days($name, $current = 1, $show_placeholder = false)
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
	public static function months($name, $numeric = true, $current = 1, $show_placeholder = false)
	{
		$retval = "<select name='{$name}' id='{$name}' class='select2-no-search' style='width: 110px'>";

		if($show_placeholder) {
			$retval .= "<option value=''>-</option>";
		}

		for($i = 1; $i <= 12; $i++) {
			$selected = ($i == $current) ? "selected" : "";
			if(!$numeric) {
				$month_name = i18n::translate("M_" . $i);
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
	public static function years($name, $before = 0, $after = 0, $current = 0, $show_placeholder = false)
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
	public static function hours($name, $current = 0)
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
	public static function minutes($name, $current = 0)
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
	public static function notification($message, $code = "", $persistent = false, $custom_title = "")
	{
		switch($code) {
			case "warning":
				$class = "alert-warning";
				$title = "WARNING!";
				break;
			case "success":
				$class = "alert-success";
				$title = "SUCCESS!";
				break;
			case "failure":
				$class = "alert-danger";
				$title = "ERROR!";
				break;
			case "info":
				$class = "alert-info";
				$title = "INFORMATION:";
				break;
		}
		if($persistent) {
			$persistent = "persistent";
		}
		if($custom_title != "") {
			$title = $custom_title;
		}

		return "<div class='alert {$class} {$persistent}'><strong>{$title}</strong> {$message}</div>";
	}

	/**
	 * --------------------------------------------------------------------
	 * FORUM RULES TEMPLATE
	 * --------------------------------------------------------------------
	 */
	public static function forumRules($title, $text)
	{
		return "<div class='notification warning'><p><strong>" . $title . "</strong> " . $text . "</p></div>";
	}

	/**
	 * --------------------------------------------------------------------
	 * CROP IMAGE TO FILL AREA
	 * --------------------------------------------------------------------
	 */
	public static function crop($image, $size = 0, $class = "")
	{
		if($image == "public/avatar/") {
			$image = "static/images/no-photo.png";
		}

		return "<div style=\"display:block; width:{$size}px; height:{$size}px; background: url('{$image}') no-repeat center top; background-size:cover\" class='crop {$class}'></div>";
	}

	/**
	 * --------------------------------------------------------------------
	 * SHOW ERROR MESSAGE
	 * --------------------------------------------------------------------
	 */
	public static function throwError($message)
	{
		echo "<h1>Error!</h1><p>" . $message . "</p><hr><em>Addictive Community - (c) " . date("Y") . " All rights reserved.</em>";
		exit;
	}

	/**
	 * --------------------------------------------------------------------
	 * BUILD PAGINATION
	 * --------------------------------------------------------------------
	 */
	public static function paginate($number_of_pages, $current_page, $url, $label = true)
	{
		$template = "<ul class=\"pagination\">";

		if($label) {
			$template .= "<li class=\"label\">Pages:</li>";
		}

		for($i = 1; $i <= $number_of_pages; $i++) {
			$class = $current_page == $i ? "class=\"active\"" : "";
			$formatted_url = sprintf($url, $i);

			$template .= "<li {$class}><a href=\"{$formatted_url}\">{$i}</a></li>";
		}

		$template .= "</ul>";

		return $template;
	}
}
