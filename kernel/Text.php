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
		// Remove single quotes to join contracted English words
		// So, "don't like" becomes "dont-like", and not "don-t-like"
		$string = str_replace("&apos;", "", $string);

		$quoted_replacement = preg_quote($replacement, '/');

		$_transliteration = array(
			'ä' => 'ae', 'æ' => 'ae', 'ǽ' => 'ae', 'ö' => 'oe', 'œ' => 'oe', 'ü' => 'ue', 'Ä' => 'Ae', 'Ü' => 'Ue', 'Ö' => 'Oe', 'À' => 'A',
			'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Å' => 'A', 'Ǻ' => 'A', 'Ā' => 'A', 'Å' => 'A', 'Ă' => 'A', 'Ą' => 'A', 'Ǎ' => 'A',
			'Ä' => 'Ae', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'å' => 'a', 'ǻ' => 'a', 'ā' => 'a', 'ă' => 'a', 'ą' => 'a',
			'ǎ' => 'a', 'ª' => 'a', 'Ç' => 'C', 'Ć' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Č' => 'C', 'ç' => 'c', 'ć' => 'c', 'ĉ' => 'c',
			'ċ' => 'c', 'č' => 'c', 'Ð' => 'D', 'Ď' => 'D', 'Đ' => 'D', 'ð' => 'd', 'ď' => 'd', 'đ' => 'd', 'È' => 'E', 'É' => 'E',
			'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ę' => 'E', 'Ě' => 'E', 'Ë' => 'E', 'è' => 'e', 'é' => 'e',
			'ê' => 'e', 'ë' => 'e', 'ē' => 'e', 'ĕ' => 'e', 'ė' => 'e', 'ę' => 'e', 'ě' => 'e', 'Ĝ' => 'G', 'Ğ' => 'G', 'Ġ' => 'G',
			'Ģ' => 'G', 'Ґ' => 'G', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ґ' => 'g', 'Ĥ' => 'H', 'Ħ' => 'H', 'ĥ' => 'h',
			'ħ' => 'h', 'І' => 'I', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ї' => 'Yi', 'Ï' => 'I', 'Ĩ' => 'I', 'Ī' => 'I', 'Ĭ' => 'I',
			'Ǐ' => 'I', 'Į' => 'I', 'İ' => 'I', 'і' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ї' => 'yi', 'ĩ' => 'i',
			'ī' => 'i', 'ĭ' => 'i', 'ǐ' => 'i', 'į' => 'i', 'ı' => 'i', 'Ĵ' => 'J', 'ĵ' => 'j', 'Ķ' => 'K', 'ķ' => 'k', 'Ĺ' => 'L',
			'Ļ' => 'L', 'Ľ' => 'L', 'Ŀ' => 'L', 'Ł' => 'L', 'ĺ' => 'l', 'ļ' => 'l', 'ľ' => 'l', 'ŀ' => 'l', 'ł' => 'l', 'Ñ' => 'N',
			'Ń' => 'N', 'Ņ' => 'N', 'Ň' => 'N', 'ñ' => 'n', 'ń' => 'n', 'ņ' => 'n', 'ň' => 'n', 'ŉ' => 'n', 'Ò' => 'O', 'Ó' => 'O',
			'Ô' => 'O', 'Õ' => 'O', 'Ō' => 'O', 'Ŏ' => 'O', 'Ǒ' => 'O', 'Ő' => 'O', 'Ơ' => 'O', 'Ø' => 'O', 'Ǿ' => 'O', 'Ö' => 'Oe',
			'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ō' => 'o', 'ŏ' => 'o', 'ǒ' => 'o', 'ő' => 'o', 'ơ' => 'o', 'ø' => 'o',
			'ǿ' => 'o', 'º' => 'o', 'Ŕ' => 'R', 'Ŗ' => 'R', 'Ř' => 'R', 'ŕ' => 'r', 'ŗ' => 'r', 'ř' => 'r', 'Ś' => 'S', 'Ŝ' => 'S',
			'Ş' => 'S', 'Ș' => 'S', 'Š' => 'S', 'ẞ' => 'SS', 'ś' => 's', 'ŝ' => 's', 'ş' => 's', 'ș' => 's', 'š' => 's', 'ſ' => 's',
			'Ţ' => 'T', 'Ț' => 'T', 'Ť' => 'T', 'Ŧ' => 'T', 'ţ' => 't', 'ț' => 't', 'ť' => 't', 'ŧ' => 't', 'Ù' => 'U', 'Ú' => 'U',
			'Û' => 'U', 'Ũ' => 'U', 'Ū' => 'U', 'Ŭ' => 'U', 'Ů' => 'U', 'Ű' => 'U', 'Ų' => 'U', 'Ư' => 'U', 'Ǔ' => 'U', 'Ǖ' => 'U',
			'Ǘ' => 'U', 'Ǚ' => 'U', 'Ǜ' => 'U', 'Ü' => 'Ue', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ũ' => 'u', 'ū' => 'u', 'ŭ' => 'u',
			'ů' => 'u', 'ű' => 'u', 'ų' => 'u', 'ư' => 'u', 'ǔ' => 'u', 'ǖ' => 'u', 'ǘ' => 'u', 'ǚ' => 'u', 'ǜ' => 'u', 'Ý' => 'Y',
			'Ÿ' => 'Y', 'Ŷ' => 'Y', 'ý' => 'y', 'ÿ' => 'y', 'ŷ' => 'y', 'Ŵ' => 'W', 'ŵ' => 'w', 'Ź' => 'Z', 'Ż' => 'Z', 'Ž' => 'Z',
			'ź' => 'z', 'ż' => 'z', 'ž' => 'z', 'Æ' => 'AE', 'Ǽ' => 'AE', 'ß' => 'ss', 'Ĳ' => 'IJ', 'ĳ' => 'ij', 'Œ' => 'OE', 'ƒ' => 'f',
			'Þ' => 'TH', 'þ' => 'th', 'Є' => 'Ye', 'є' => 'ye'
		);

		$map = array(
			'/[^\s\p{Zs}\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
			'/[\s\p{Zs}]+/mu' => $replacement,
			sprintf('/^[%s]+|[%s]+$/', $quoted_replacement, $quoted_replacement) => '',
		);

		$string = str_replace(
			array_keys($_transliteration),
			array_values($_transliteration),
			strtolower($string)
		);

		return preg_replace(array_keys($map), array_values($map), $string);
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
