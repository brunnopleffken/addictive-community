<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.string.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	class String
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
		// Generate a random RFC 4122 UUID
		// ---------------------------------------------------

		public static function UUID() {
			$node = getenv('SERVER_ADDR');

			if (strpos($node, ':') !== false) {
				if (substr_count($node, '::')) {
					$node = str_replace(
						'::', str_repeat(':0000', 8 - substr_count($node, ':')) . ':', $node
					);
				}

				$node = explode(':', $node);
				$ipSix = '';

				foreach ($node as $id) {
					$ipSix .= str_pad(base_convert($id, 16, 2), 16, 0, STR_PAD_LEFT);
				}

				$node = base_convert($ipSix, 2, 10);

				if (strlen($node) < 38) {
					$node = null;
				}
				else {
					$node = crc32($node);
				}
			}
			elseif (empty($node)) {
				$host = getenv('HOSTNAME');
				if (empty($host)) {
					$host = getenv('HOST');
				}
				if (!empty($host)) {
					$ip = gethostbyname($host);
					if ($ip === $host) {
						$node = crc32($host);
					}
					else {
						$node = ip2long($ip);
					}
				}
			}
			elseif ($node !== '127.0.0.1') {
				$node = ip2long($node);
			}
			else {
				$node = null;
			}

			if (empty($node)) {
				$node = crc32('loremipsum');
			}

			if (function_exists('hphp_get_thread_id')) {
				$pid = hphp_get_thread_id();
			}
			elseif (function_exists('zend_thread_id')) {
				$pid = zend_thread_id();
			}
			else {
				$pid = getmypid();
			}

			if (!$pid || $pid > 65535) {
				$pid = mt_rand(0, 0xfff) | 0x4000;
			}

			list($timeMid, $timeLow) = explode(' ', microtime());

			return sprintf(
				"%08x-%04x-%04x-%02x%02x-%04x%08x", (int)$timeLow, (int)substr($timeMid, 2) & 0xffff,
				mt_rand(0, 0xfff) | 0x4000, mt_rand(0, 0x3f) | 0x80, mt_rand(0, 0xff), $pid, $node
			);
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

		// ---------------------------------------------------
		// Password encrypt (with salt)
		// ---------------------------------------------------

		public static function PasswordEncrypt($password)
		{
			return md5(base64_decode("Ly9hZGRpY3RpdmU=") . $password);
		}

		// ---------------------------------------------------
		// Creates a comma separated list where the last two
		// items are joined with 'and'.
		// ---------------------------------------------------

		public static function ToList($list, $and = 'and', $separator = ', ') {
			if (count($list) > 1) {
				return implode($separator, array_slice($list, null, -1)) . ' ' . $and . ' ' . array_pop($list);
			}

			return array_pop($list);
		}
	}

?>