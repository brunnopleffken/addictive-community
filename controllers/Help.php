<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Help.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\Http;
use \AC\Kernel\i18n;

class Help extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * VIEW HELP TOPICS
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		$this->Core->redirect("404");
		exit;

		// Get help topics list
		Database::Query("SELECT h_id, title, short_desc FROM c_help ORDER BY title ASC;");
		$_topics = Database::FetchToArray();

		// Page info
		$page_info['title'] = i18n::Translate("H_TITLE");
		$page_info['bc'] = array(i18n::Translate("H_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("topics", $_topics);
	}

	/**
	 * --------------------------------------------------------------------
	 * READ AN SPECIFIC TOPIC
	 * --------------------------------------------------------------------
	 */
	public function View($id)
	{
		$this->Core->redirect("404");
		exit;

		// Get the topic
		Database::Query("SELECT * FROM c_help WHERE h_id = '{$id}';");
		$help = Database::Fetch();

		// Page info
		$page_info['title'] = i18n::Translate("H_TITLE");
		$page_info['bc'] = array(i18n::Translate("H_TITLE"), $help['title']);
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("help", $help);
	}
}
