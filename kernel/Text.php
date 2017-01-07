<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Text.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Kernel;

class Text
{
	/**
	 * --------------------------------------------------------------------
	 * FORMATTED print_r() FUNCTION
	 * --------------------------------------------------------------------
	 */
	public static function PR($var)
	{
		echo "<pre style='font-size:11px;'>";
		print_r($var);
		echo "</pre>";
	}

	/**
	 * --------------------------------------------------------------------
	 * SANITIZE STRING (REPLACE SPECIAL CHARS TO HTML ENTITIES)
	 * --------------------------------------------------------------------
	 */
	public static function Sanitize($text)
	{
		$text = str_replace("&", "&amp;", $text);
		$text = str_replace("<", "&lt;", $text);
		$text = str_replace(">", "&gt;", $text);
		$text = str_replace("'", "&apos;", $text);
		$text = str_replace('"', "&quot;", $text);

		// An extra for double slashes (escape char in PHP)
		$text = str_replace("\\", "\\\\", $text);

		return $text;
	}

	/**
	 * --------------------------------------------------------------------
	 * REMOVE SPECIFIC TAGS TO AVOID CROSS-SIDE SCRIPTING
	 * --------------------------------------------------------------------
	 */
	public static function RemoveHTMLElements($text)
	{
		// Dangerous HTML elements
		$text = str_replace("<!--", "&lt;!--", $text);
		$text = str_replace("-->", "--&gt;", $text);
		$text = preg_replace("/(<\?php|<\?=|\?>)/", "", $text); // No PHP open/close tags
		$text = preg_replace("/(<script>|<\/script>)/", "", $text); // No JS
		$text = preg_replace("/(<applet>|<\/applet>|<object>|<\/object>|<embed>|<\/embed>)/", "", $text); // No embedded elements
		$text = preg_replace("/(<iframe>|<\/iframe>)/", "", $text); // No IFRAMES

		return $text;
	}

	/**
	 * --------------------------------------------------------------------
	 * ONE-WAY PASSWORD ENCRYPT
	 * $salt must be an array containing [ "hash", "key" ]
	 * --------------------------------------------------------------------
	 */
	public static function Encrypt($password, $salt = array())
	{
		$hash = $password . $salt['hash'];
		for($i = 0; $i < $salt['key']; $i++) {
			$hash = hash("sha512", $password . $hash . $salt['hash']);
		}

		return $hash;
	}

	/**
	 * --------------------------------------------------------------------
	 * CREATE A RANDOM 8 CHAR PASSWORD
	 * --------------------------------------------------------------------
	 */
	public static function MakePassword()
	{
		$pass = "";
		$chars = array(
			"1","2","3","4","5","6","7","8","9","0",
			"a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J",
			"k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T",
			"u","U","v","V","w","W","x","X","y","Y","z","Z"
		);

		$count = count($chars) - 1;

		for($i = 0; $i < 8; $i++) {
			$pass .= $chars[rand(0, $count)];
		}

		return $pass;
	}

	/**
	 * --------------------------------------------------------------------
	 * CALCULATE MEMBER AGE FROM BIRTHDATE TIMESTAMP
	 * --------------------------------------------------------------------
	 */
	public static function MemberAge($timestamp)
	{
		$birth = date("md Y", $timestamp);
		$birth = explode(" ", $birth);

		$now = date("md Y", time());
		$now = explode(" ", $now);

		if($now[0] < $birth[0]) {
			$age = $now[1] - $birth[1] - 1;
		}
		else {
			$age = $now[1] - $birth[1];
		}

		return $age;
	}

	/**
	 * --------------------------------------------------------------------
	 * CREATES A COMMA-SEPARATED LIST WHERE THE LAST TWO ITEMS
	 * ARE JOINED WITH 'AND'.
	 * --------------------------------------------------------------------
	 */
	public static function ToList($list, $and = "and", $separator = ", ")
	{
		if(count($list) > 1) {
			return implode($separator, array_slice($list, null, -1)) . " " . $and . " " . array_pop($list);
		}
		return array_pop($list);
	}

	/**
	 * --------------------------------------------------------------------
	 * CONVERT BYTE SIZE INTO HUMAN READABLE FORMAT
	 * --------------------------------------------------------------------
	 */
	public static function FileSizeFormat($bytes = 0)
	{
		if($bytes >= 1048576) {
			$retval = round($bytes / 1048576 * 100) / 100 . " MB";
		}
		elseif($bytes >= 1024) {
			$retval = round($bytes / 1024 * 100) / 100 . " kB";
		}
		else {
			$retval = $bytes . " bytes";
		}

		return $retval;
	}

	/**
	 * --------------------------------------------------------------------
	 * CREATES A COMMA-SEPARATED LIST WHERE THE LAST TWO ITEMS
	 * ARE JOINED WITH 'AND'.
	 * --------------------------------------------------------------------
	 */
	public static function Slug($string, $replacement = "-")
	{
		$transliteration = Transliterator::create("Any-Latin; Latin-ASCII; Lower()");
		$string = $transliteration->transliterate($string);
		$string = preg_replace("/[^a-zA-Z0-9\s]/", "", $string);
		$string = preg_replace("/[\s]+/", $replacement, $string);

		return trim($string, "-");
	}

	/**
	 * --------------------------------------------------------------------
	 * CONVERT string_with_underscore TO StringWithUnderscore
	 * --------------------------------------------------------------------
	 */
	public static function CamelCase($string = "")
	{
		$string = preg_replace("/(_)/", " ", $string);
		$string = preg_replace("/([\s])/", "", ucwords($string));
		return $string;
	}

	/**
	 * --------------------------------------------------------------------
	 * CONVERT string_with_underscore TO stringWithUnderscore
	 * --------------------------------------------------------------------
	 */
	public static function LowerCamelCase($string = "")
	{
		$string = self::CamelCase($string);
		$replace = strtolower(substr($string, 0, 1));
		$result = $replace . substr($string, 1);
		return $result;
	}
}
