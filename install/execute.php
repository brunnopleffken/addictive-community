<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: execute.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Initialize
	// ---------------------------------------------------

	// Load kernel modules
	require_once("../kernel/class.core.php");
	require_once("../kernel/class.html.php");
	require_once("../kernel/class.string.php");
	require_once("../kernel/class.database.php");

	// Get step number
	$step = $_REQUEST['step'];
	// Get user data
	$data = $_POST;

	// --------------------------------------------
	// OK, let's do it!
	// --------------------------------------------

	switch($step) {
		// --------------------------------------------
		// Save configuration file
		// --------------------------------------------
		case 1:
			// Check if config.php is writable (CHMOD 777)
			if(is_writable("../config.php")) {
				$handle = fopen("../config.php", "w");
				
				$fileContent = "<?php
	// Addictive Community configuration file for MySQL
	\$config['db_server']   = \"{$data['db_server']}\";
	\$config['db_username'] = \"{$data['db_username']}\";
	\$config['db_password'] = \"{$data['db_password']}\";
	\$config['db_database'] = \"{$data['db_database']}\";
	\$config['db_prefix']   = \"c_\";
?>";
				
				if(fwrite($handle, $fileContent)) {
					$status = 1;
					fclose($handle);
				}
				else {
					$status = 0;
				}
			}
			else {
				$status = 0; // Error
			}

			$description = "Save configuration file " . $_POST['community_name'];
			break;

		// --------------------------------------------
		// Check information and connect to database
		// --------------------------------------------
		case 2:
			// Get brand new config.php file
			require("../config.php");
			// Try to connect to database using config.php data
			$database = new Database($config);
		
			$status      = ($database) ? 1 : 0;
			$description = "Check information and connect to database" ;
			break;

		// --------------------------------------------
		// Extract table structure
		// --------------------------------------------
		case 3:
			// Get config file and connect to Database
			require("../config.php");
			$database = new Database($config);

			// Avoid PHP timeout
			set_time_limit(0);

			// Get SQL file and its content
			$file = "sql/tables.sql";
			$handle = fopen($file, "r");

			if($handle) {
				$sqlQuery = fread($handle, filesize($file));
				$queries = explode("\n", $sqlQuery);

				foreach($queries as $value) {
					$query = $database->Query($value);
				}

				$status = ($query) ? 1 : 0;
			}
			else {
				$status = 0;
			}

			$description = "Extract table structure";
			break;

		// --------------------------------------------
		// Insert initial data
		// --------------------------------------------
		case 4:
			$status      = 0;
			$description = "Insert initial data and settings";
			break;

		// --------------------------------------------
		// Save user information
		// --------------------------------------------
		case 5:
			// Get config file and connect to Database
			require("../config.php");
			$database = new Database($config);

			// Get administrator account data
			$adminInfo = array(
				'username' => String::Sanitize($dta['adm_username']),
				'password' => String::PasswordEncrypt($data['adm_password']),
				'email'    => String::Sanitize($data['adm_email'])
			);

			$status      = 1;
			$description = "Save user information";
			break;

		// --------------------------------------------
		// Lock installer
		// --------------------------------------------
		case 6:
			$status      = 1;
			$description = "Lock installer";
			break;

		default:
			$status = 0;
	}

	$data = array(
		'status' => $status,
		'step'   => $step,
		'time'   => microtime(),
		'desc'   => $description
	);

	echo json_encode($data);

?>
