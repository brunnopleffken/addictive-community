<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Failure.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\i18n;

class Failure extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * VIEW: ERROR PAGE
	 * --------------------------------------------------------------------
	 */
	public function Index()
	{
		// Get type of error
		$type = Http::Request("t");

		// Show "Error" as master template in these statuses...
		$show_error_when = array('offline', 'update', '403', '404', '500');

		// Error list
		$errors = array(
			'not_allowed' => array(
				Html::Notification(i18n::Translate("E_MESSAGE_NOT_ALLOWED"), "failure", true), "login"
			),
			'validated' => array(
				Html::Notification(i18n::Translate("E_MESSAGE_VALIDATED"), "success", true), "login"
			),
			'protected_room' => array(
				Html::Notification(i18n::Translate("E_MESSAGE_PROTECTED"), "warning", true), "protected"
			),
			'thread_locked' => array(
				Html::Notification(i18n::Translate("E_MESSAGE_LOCKED_THREAD"), "failure", true), false
			),
			'offline' => array(
				"", "offline"
			),
			'update' => array(
				"", "update"
			),
			'deleted_member' => array(
				"", "deleted"
			),
			'403' => array(
				"", "403", "Error 403"
			),
			'404' => array(
				"", "404", "Error 404"
			),
			'500' => array(
				"", "500", "Error 500"
			)
		);

		// Is this an error, or just a notification message? Change title!
		if(strpos($errors[$type][0], "success")) {
			$title = i18n::Translate("E_SUCCESS_TITLE");
		}
		else {
			$title = i18n::Translate("E_ERROR_TITLE");
		}

		// Show custom title
		if(isset($errors[$type][2])) {
			$title = $errors[$type][2];
		}

		if(in_array($type, $show_error_when)) {
			$this->master = "Error";
		}

		// Return variables
		$this->Set("title", $title);
		$this->Set("error", $errors[$type][0]);
		$this->Set("action", $errors[$type][1]);
	}
}
