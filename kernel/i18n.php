<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: i18n.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

class i18n
{
	// Dictionary array
	public static $dictionary = array();

	/**
	 * --------------------------------------------------------------------
	 * GET STRING OF A GIVEN INDEX
	 * --------------------------------------------------------------------
	 */
	public static function Translate($keyword, $variables = array())
	{
		if(array_key_exists($keyword, self::$dictionary)) {
			if(!empty($variables)) {
				return vsprintf(self::$dictionary[$keyword], $variables);
			}
			else {
				return self::$dictionary[$keyword];
			}
		}
		else {
			return $keyword;
		}
	}
}
