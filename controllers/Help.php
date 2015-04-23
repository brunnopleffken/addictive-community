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

		// Return variables
		$this->Set("help", $help);
	}
}
