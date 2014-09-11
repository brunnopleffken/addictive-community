<?php

	// ---------------------------------------------------
	//  ADDICTIVE COMMUNITY
	// ---------------------------------------------------
	// Created by Brunno Pleffken Hosti
	//
	// Website: www.addictive.com.br
	// E-mail: brunno.pleffken@addictive.com.br
	// Release: December/2012
	// ---------------------------------------------------

	// Load kernel modules
	require_once("../kernel/class.core.php");
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
				
				$file_content = "<?php
					\$config['db_server'] = \"{$data['db_server']}\";
					\$config['db_username'] = \"{$data['db_username']}\";
					\$config['db_password'] = \"{$data['db_password']}\";
					\$config['db_database'] = \"{$data['db_database']}\";
					\$config['db_prefix'] = \"c_\";
				?>";
				
				if(fwrite) {
					$status = 1;
				}
				else {
					$status = 0;
				}
				
				fclose($handle);
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
		
			String::PR($database;)
		
			$status      = ($database) ? 1 : 0;
			$description = "Check information and connect to database" ;
			break;

		// --------------------------------------------
		// Extract table structure
		// --------------------------------------------
		case 3:
			$status      = 1;
			$description = "Extract table structure";
			break;

		// --------------------------------------------
		// Insert initial data
		// --------------------------------------------
		case 4:
			$status      = 1;
			$description = "Insert initial data";
			break;

		// --------------------------------------------
		// Save user information
		// --------------------------------------------
		case 5:
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
