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
		// Formatted print_r() function
		// --------------------------------------------

		public static function PR($var)
		{
			echo "<pre>";
			print_r($var);
			echo "</pre>";
		}

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

		// --------------------------------------------
		// Format file sizes (enter value in bytes)
		// --------------------------------------------

		public static function FileSizeFormat($bytes)
		{
			if($bytes >= 1048576) {
				$retval = round($bytes / 1048576 * 100) / 100 . "MB";
			}
			elseif($bytes >= 1024) {
				$retval = round($bytes / 1024 * 100) / 100 . "kB";
			}
			else {
				$retval = $bytes . " bytes";
			}
			
			return $retval;
		}

		// --------------------------------------------
		// Create a random 8 character password
		// --------------------------------------------

		public static function MakePassword()
		{
			$pass = "";
			$chars = array(
				"1","2","3","4","5","6","7","8","9","0",
				"a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J",
				"k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T",
				"u","U","v","V","w","W","x","X","y","Y","z","Z");

			$count = count($chars) - 1;

			for($i = 0; $i < 8; $i++) {
				$pass .= $chars[rand(0, $count)];
			}
		
			return $pass;
		}

		// ---------------------------------------------------
		// Calculate member age from birthday timestamp
		// ---------------------------------------------------

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

		// ---------------------------------------------------
		// Turn "THIS STRING" into "This String"
		// ---------------------------------------------------

		public static function RemoveUppercase($string)
		{
			return ucwords(strtolower($string));
		}

		// ----------------------------------------
		// Remove BBcodes, show pure text!
		// ----------------------------------------
		
		public static function RemoveBBcode($string)
		{
			return preg_replace("#\[(.+)\](.+)\[\/(.+)\]#iUs", "$2", $string);
		}

		// ---------------------------------------------------
		// Password encrypt (with salt)
		// ---------------------------------------------------

		public static function PasswordEncrypt($password)
		{
			return md5(base64_decode("Ly9hZGRpY3RpdmU=") . $password);
		}
	}

?>