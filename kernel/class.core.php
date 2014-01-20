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
			$this->Db->Query("SELECT * FROM c_config;");
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

		public function DateFormat($timestamp, $format = "longdate")
		{
			if($format == "shortdate") {
				$format = $this->config['date_short_format'];	// Get short format date from $_config
			}
			elseif($format == "longdate") {
				$format = $this->config['date_long_format'];	// Get long format date from $_config
			}
			
			// Get timezones and daylight saving time

			$offset = $this->config['date_default_offset'] * MINUTE * MINUTE;

			// format and return it

			$date = date($format, $timestamp + $offset);
			return $date;
		}
	}

?>