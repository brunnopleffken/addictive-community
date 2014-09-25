<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.core.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	class Core
	{
		private $Db;
		public $config = array();

		// ---------------------------------------------------
		// Constructor
		// ---------------------------------------------------

		public function __construct($database_class)
		{
			// Store database class inside Core()
			$this->Db = $database_class;

			// Get configurations from database
			$this->Db->Query("SELECT c.index, c.value FROM c_config c;");
			while($data = $this->Db->Fetch()) {
				$this->config[$data['index']] = $data['value'];
			}
		}

		// ---------------------------------------------------
		// Get query string (if not defined, set default)
		// ---------------------------------------------------

		public function QueryString($variable, $default = "", $numeric_only = false)
		{
			if(isset($_REQUEST[$variable])) {
				if(!is_numeric($_REQUEST[$variable]) and $numeric_only == true) {
					Html::Error("Query string '{$variable}' must be a numeric value.");
					return false;
					exit;
				}
				$retval = addslashes($_REQUEST[$variable]);
			}
			else {
				$retval = $default;
			}

			return $retval;
		}

		// ---------------------------------------------------
		// Use custom date formatting
		// ---------------------------------------------------

		public function DateFormat($timestamp, $format = "long")
		{
			if($format == "short") {
				$format = $this->config['date_short_format'];	// Get short format date from $_config
			}
			elseif($format == "long") {
				$format = $this->config['date_long_format'];	// Get long format date from $_config
			}
			
			// Get timezones and daylight saving time

			$offset = $this->config['date_default_offset'] * MINUTE * MINUTE;

			// format and return it

			$date = date($format, $timestamp + $offset);
			return $date;
		}

		// ---------------------------------------------------
		// Get Gravatar or community avatar image path
		// ---------------------------------------------------

		public function GetGravatar($email, $photo, $size = 96, $mode = "gravatar", $d = "mm", $r = "g")
		{
			if($mode == "gravatar")
			{
				$url = "http://www.gravatar.com/avatar/";
				$url .= md5(strtolower(trim($email)));
				$url .= "?s={$size}&amp;d={$d}&amp;r={$r}";
			}
			elseif($mode == "custom")
			{
				$url = "public/avatar/{$photo}";
			}
			
			return $url;
		}

		// ---------------------------------------------------
		// Calculate member age from birthday timestamp
		// ---------------------------------------------------

		public function MemberAge($timestamp)
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
	}

?>