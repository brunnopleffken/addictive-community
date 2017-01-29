<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Template.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Kernel;

class Template
{
	// HTML template
	public static $html = "";

	/**
	 * --------------------------------------------------------------------
	 * INSERT HTML INTO $this->html
	 * --------------------------------------------------------------------
	 */
	public static function add($html = "")
	{
		self::$html .= $html;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET HTML TEMPLATE STORED IN $this->html
	 * --------------------------------------------------------------------
	 */
	public static function get()
	{
		return self::$html;
	}

	/**
	 * --------------------------------------------------------------------
	 * CLEAN ALL
	 * --------------------------------------------------------------------
	 */
	public static function clean()
	{
		self::$html = "";
	}
}
