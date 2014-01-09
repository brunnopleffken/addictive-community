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
		private $config = array();

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
				$this->_config[$data['index']] = $data['value'];
			}
		}

		// ---------------------------------------------------
		// Use custom date formatting
		// ---------------------------------------------------

		public function DateFormat($timestamp, $format = "longdate")
		{
			if($format == "shortdate") {
				$format = $this->_config['date_short_format']; // Get short format date from $_config
			}
			elseif($format == "longdate") {
				$format = $this->_config['date_long_format']; // Get long format date from $_config
			}
			
			// Get timezones and daylight saving time

			$offset = $this->_config['date_default_offset'] * 60 * 60;

			// format and return it

			$date = date($format, $timestamp + $offset);
			return $date;
		}
	}

?>