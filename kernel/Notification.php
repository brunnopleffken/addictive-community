<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Notification.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Kernel;

class Notification
{
	// HTML and CSS templates - This is predefined to be used with Bootstrap
	private static $class_template = "alert alert-{1} {2}";
	private static $html_template = "<div class='%s'>%s</div>";

	/**
	 * --------------------------------------------------------------------
	 * Magic method for verbose flash methods based on element names
	 * void Notification::$name(string $message [, bool $dismissable = true])
	 * --------------------------------------------------------------------
	 */

	public static function __callStatic($name, $arguments)
	{
		// Check if at least one parameter exists
		if(count($arguments) < 1) {
			Html::throwError("Notification message is missing.");
		}

		// Check if notification type is usable
		if(!in_array(strtolower($name), array("success", "warning", "danger", "info", "debug"))) {
			Html::throwError("This notification type ({$name}) doesn't exist.");
		}

		// Check if notification is persistent, or not
		$persistent_class_name = (isset($arguments[1]) && $arguments[1] == true) ? "" : "persistent";

		self::$class_template = str_replace("{1}", strtolower($name), self::$class_template);
		self::$class_template = str_replace("{2}", $persistent_class_name, self::$class_template);
		$message = vsprintf(self::$html_template, array(self::$class_template, $arguments[0]));

		Session::write("Notification.Flash", $message);
	}

	/**
	 * --------------------------------------------------------------------
	 * Render the notification message stored in browser session
	 * --------------------------------------------------------------------
	 */

	public static function render()
	{
		$notification = Session::retrieve("Notification.Flash");

		if($notification) {
			Session::destroy("Notification.Flash");
		}

		return $notification;
	}
}
