<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.i18n.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	class i18n
	{
		// Dictionary array
		public static $dictionary = array();

		// ---------------------------------------------------
		// Get string of a given index
		// ---------------------------------------------------

		public static function Translate($keyword, $variables = array())
		{
			if(array_key_exists($keyword, self::$dictionary)) {
				if(!empty($variables)) {
					return vprintf(self::$dictionary[$keyword], $variables);
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

?>
