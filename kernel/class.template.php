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
		public $html = "";

		// ---------------------------------------------------
		// Insert HTML to $this->html
		// ---------------------------------------------------

		public function Add($html)
		{
			$this->html .= $html;
		}

		// ---------------------------------------------------
		// Get HTML template stored in $this->html
		// ---------------------------------------------------

		public function Get()
		{
			return $this->html;
		}

		// ---------------------------------------------------
		// Clear all
		// ---------------------------------------------------

		public function Clear()
		{
			$this->html = "";
		}

		// ---------------------------------------------------
		// Force template including inside controller
		// ---------------------------------------------------

		public function Element($filename, $fullpath = false)
		{
			if($fullpath)
			{
				require_once("templates/1/" . $filename . ".php");
			}
			else
			{
				require_once($filename . ".php");
			}
		}
	}

?>