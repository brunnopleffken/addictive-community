<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.text.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	class Text
	{
		// --------------------------------------------
		// Clean reserved characters in HTML
		// --------------------------------------------

		public static function ClearString($text)
		{
			$text = str_replace("&", "&amp;", $text);
			$text = str_replace("<", "&lt;", $text);
			$text = str_replace(">", "&gt;", $text);
			$text = str_replace('"', "&quot;", $text);
			$text = str_replace("'", "&#39;", $text);

			// An extra for double slashes (escape char in PHP)
			$text = str_replace("\\", "\\\\", $text);

			return $text;
		}
	}

?>