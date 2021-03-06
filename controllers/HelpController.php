<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  https://github.com/addictivehub/addictive-community
#
#  File: Help.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\i18n;

class Help extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * VIEW HELP TOPICS
	 * --------------------------------------------------------------------
	 */
	public function index()
	{
		$this->Core->redirect("404");
		exit;

		// Get help topics list
		Database::query("SELECT h_id, title, short_desc FROM c_help ORDER BY title ASC;");
		$_topics = Database::fetchToArray();

		// Page info
		$page_info['title'] = i18n::translate("help.title");
		$page_info['bc'] = array(i18n::translate("help.title"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("topics", $_topics);
	}

	/**
	 * --------------------------------------------------------------------
	 * READ AN SPECIFIC TOPIC
	 * --------------------------------------------------------------------
	 */
	public function view($id)
	{
		$this->Core->redirect("404");
		exit;

		// Get the topic
		Database::query("SELECT * FROM c_help WHERE h_id = '{$id}';");
		$help = Database::fetch();

		// Page info
		$page_info['title'] = i18n::translate("help.title");
		$page_info['bc'] = array(i18n::translate("help.title"), $help['title']);
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("help", $help);
	}
}
