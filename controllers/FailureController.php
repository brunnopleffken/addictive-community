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
		$show_error_when = ['offline', 'update', '403', '404', '500'];

		// Error list
		$errors = [
			'not_allowed' => [
				Html::Notification(i18n::Translate("E_MESSAGE_NOT_ALLOWED"), "failure", true), "login"
            ],
			'validated' => [
				Html::Notification(i18n::Translate("E_MESSAGE_VALIDATED"), "success", true), "login"
            ],
			'protected_room' => [
				Html::Notification(i18n::Translate("E_MESSAGE_PROTECTED"), "warning", true), "protected"
            ],
			'thread_locked' => [
				Html::Notification(i18n::Translate("E_MESSAGE_LOCKED_THREAD"), "failure", true), false
            ],
			'offline' => [
				"", "offline"
            ],
			'update' => [
				"", "update"
            ],
			'deleted_member' => [
				"", "deleted"
            ],
			'403' => [
				"", "403", "Error 403"
            ],
			'404' => [
				"", "404", "Error 404"
            ],
			'500' => [
				"", "500", "Error 500"
            ]
        ];

		// Is this an error, or just a notification message? Change title!
        $title = strpos($errors[$type][0], "success") ? i18n::Translate("E_SUCCESS_TITLE") : i18n::Translate("E_ERROR_TITLE");

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
