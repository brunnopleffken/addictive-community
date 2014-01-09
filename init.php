<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: init.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	class Init
	{
		// ---------------------------------------------------
		// Define 'display_errors' and global timezone to UTC
		// ---------------------------------------------------
		
		public function __construct()
		{
			ini_set('display_errors', 'On');
			error_reporting(E_ALL);
			date_default_timezone_set("UTC");
		}
		
		// ---------------------------------------------------
		// Load class helper
		// ---------------------------------------------------
		
		public function Load()
		{
			$dir = "kernel";
			$files = scandir($dir);

			foreach($files as $filename)
			{
				if($filename == "." or $filename == "..") {
					continue;
				}
				else {
					include("kernel/" . $filename);
				}
			}
		}
	}

?>