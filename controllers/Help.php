<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Help.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

class Help extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * VIEW HELP TOPICS
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		$_topics = array();

		// Get help topics list
		$this->Db->Query("SELECT h_id, title, short_desc FROM c_help ORDER BY title ASC;");

		while($help = $this->Db->Fetch()) {
			$_topics[] = $help;
		}

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
		// Get the topic
		$this->Db->Query("SELECT * FROM c_help WHERE h_id = '{$id}';");
		$help = $this->Db->Fetch();

		// Page info
		$page_info['title'] = i18n::Translate("H_TITLE");
		$page_info['bc'] = array(i18n::Translate("H_TITLE"), $help['title']);
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("help", $help);
	}
}
