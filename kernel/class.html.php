<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.html.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	class Html
	{
		// ---------------------------------------------------
		// Display formatted error message
		// ---------------------------------------------------

		public static function Error($message)
		{
			echo "<h1>Error!</h1><p>" . $message . "</p><hr><em>Addictive Community - (c) 2014 All rights reserved.</em>";
			exit;
		}

		// ---------------------------------------------------
		// List of days of the month (1 to 31)
		// ---------------------------------------------------

		public static function Days($name, $current = 1)
		{
			$retval = "<select name=\"{$name}\" id=\"{$name}\">";

			for($i = 1; $i <= 31; $i++) {
				$selected = ($i == $current) ? "selected" : "";
				$retval .= "<option value=\"{$i}\" {$selected}>{$i}</option>";
			}

			$retval .= "</select>";

			return $retval;
		}

		// ---------------------------------------------------
		// List of months of the year (Jan to Dec)
		// ---------------------------------------------------

		public static function Months($name, $current = 1)
		{
			$retval = "<select name=\"{$name}\" id=\"{$name}\">";

			for($i = 1; $i <= 12; $i++) {
				$selected = ($i == $current) ? "selected" : "";
				$retval .= "<option value=\"{$i}\" {$selected}>{$i}</option>";
			}

			$retval .= "</select>";

			return $retval;
		}

		// ---------------------------------------------------
		// Really nice years drop-down generator
		// ---------------------------------------------------

		public static function Years($name, $before, $after)
		{
			$current = date("Y", time());

			$retval = "<select name=\"{$name}\" id=\"{$name}\">";

			for($i = $current - $before; $i <= $current + $after; $i++) {
				$selected = ($i == $current) ? "selected" : "";
				$retval .= "<option value=\"{$i}\" {$selected}>{$i}</option>";
			}

			$retval .= "</select>";

			return $retval;
		}

		// ---------------------------------------------------
		// Show notification message
		// ---------------------------------------------------

		public static function Notification($message, $code)
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
			
			$html = "<div class=\"notification " . $code . "\"><p><strong>" . $title . "</strong> " . $message . "</p></div>";
			
			return $html;
		}

		// ---------------------------------------------------
		// "Forum Rules" template
		// ---------------------------------------------------
		
		public static function ForumRules($title, $text)
		{
			$html = "<div class=\"notification warning\"><p><strong>" . $title . "</strong> " . $text . "</p></div>";
			return $html;
		}
	}

?>