<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  https://github.com/addictivehub/addictive-community
#
#  File: i18n.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## -------------------------------------------------------

namespace AC\Kernel;

class i18n
{
	// Dictionary array
	public static $dictionary = array();

	// Missing keys
	public static $missing_keys = array();

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
			self::$missing_keys[] = $keyword;
			return $keyword;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURN MISSING KEYS
	 * --------------------------------------------------------------------
	 */
	public static function getMissingKeys()
	{
		return self::$missing_keys;
	}
}
