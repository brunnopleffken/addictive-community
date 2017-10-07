<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  https://github.com/addictivehub/addictive-community
#
#  File: Articles.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\i18n;
use \AC\Kernel\Session\SessionState;
use \AC\Kernel\Text;

class Articles extends Application
{
	public $master = "Articles";

	/**
	 * --------------------------------------------------------------------
	 * ARTICLES HOME
	 * --------------------------------------------------------------------
	 */
	public function index()
	{
		// ...
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW AN ARTICLE
	 * --------------------------------------------------------------------
	 */
	public function read($id)
	{
		$this->Set('teste', $id);
	}
}
