<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  https://github.com/addictivehub/addictive-community
#
#  File: Failure.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
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
	public function index()
	{
		// Get type of error
		$type = Http::request("t");

		// Show "Error" as master template in these statuses...
		$show_error_when = ['offline', 'update', '403', '404', '500'];

		// Error list
		$errors = [
			'not_allowed' => [
				Html::notification(i18n::translate("failure.message.not_allowed"), "failure", true), "login"
            ],
			'validated' => [
				Html::notification(i18n::translate("failure.message.validated"), "success", true), "login"
            ],
			'protected_room' => [
				Html::notification(i18n::translate("failure.message.protected"), "warning", true), "protected"
            ],
			'thread_locked' => [
				Html::notification(i18n::translate("failure.message.locked_thread"), "failure", true), false
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
			'banned' => [
				"", "banned"
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
        $title = strpos($errors[$type][0], "success") ? i18n::translate("failure.success_title") : i18n::translate("failure.error_title");

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
