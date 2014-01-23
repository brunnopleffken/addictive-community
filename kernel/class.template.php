<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.template.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	class Template
	{
		public static $html = "";

		// ---------------------------------------------------
		// Insert HTML to $this->html
		// ---------------------------------------------------

		public static function Add($html)
		{
			self::$html .= $html;
		}

		// ---------------------------------------------------
		// Get HTML template stored in $this->html
		// ---------------------------------------------------

		public static function Get()
		{
			return self::$html;
		}

		// ---------------------------------------------------
		// Clear all
		// ---------------------------------------------------

		public static function Clear()
		{
			self::$html = "";
		}

		// ---------------------------------------------------
		// Force template including inside controller
		// ---------------------------------------------------

		// public static function Element($filename, $fullpath = false)
		// {
		// 	if($fullpath)
		// 	{
		// 		require_once("templates/1/" . $filename . ".php");
		// 	}
		// 	else
		// 	{
		// 		require_once($filename . ".php");
		// 	}
		// }
	}

?>