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

namespace AC\Kernel;

class i18n
{
	// Dictionary array
	public static $dictionary = array();

	/**
	 * --------------------------------------------------------------------
	 * GET STRING OF A GIVEN INDEX
	 * --------------------------------------------------------------------
	 */
	public static function translate($keyword, $variables = array())
	{
		$keys = explode(".", $keyword);
		$word = self::$dictionary;

		foreach($keys as $key) {
			$word = &$word[$key];
		}

		if(!$word == null) {
			if(!empty($variables)) {
				return vsprintf($word, $variables);
			}
			else {
				return $word;
			}
		}
		else {
			return $keyword;
		}
	}
}
