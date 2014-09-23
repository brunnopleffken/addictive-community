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
		
		public function Load($file = "", $level = false)
		{
			if($file == "") {
				$dir = "kernel";
				$files = scandir($dir);

				foreach($files as $filename)
				{
					if($filename == "." || $filename == ".." || $filename == "index.html") {
						continue;
					}
					else {
						require("kernel/" . $filename);
					}
				}
			}
			else {
				if($level) {
					require("../kernel/class." . $file . ".php");
				}
				else {
					require("kernel/class." . $file . ".php");
				}
			}
		}
	}

?>