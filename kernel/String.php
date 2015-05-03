<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: String.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

class String
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
	 * ONE-WAY PASSWORD ENCRYPT
	 * --------------------------------------------------------------------
	 */
	public static function PasswordEncrypt($password)
	{
		return hash("sha512", base64_decode("Ly9hZGRpY3RpdmU=") . $password);
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
	
	public static function FileSizeFormat($bytes)
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
}
