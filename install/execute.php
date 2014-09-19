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
			$description = "Check information and connect to database";
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
		// Insert initial data and settings
		// --------------------------------------------
		case 4:
			$communityInfo = array(
				'community_name' => String::Sanitize($data['community_name'])
			);

			$status      = 1;
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
				'username' => String::Sanitize($data['admin_username']),
				'password' => String::PasswordEncrypt($data['admin_password']),
				'email'    => String::Sanitize($data['admin_email']),
				'joined'   => time()
			);

			// Build SQL

			$sqlInsertAdmin = "INSERT INTO `c_members` (`username`, `password`, `email`, `hide_email`, `ip_address`, `joined`, `usergroup`, `member_title`, `location`, `profile`, `gender`, `b_day`, `b_month`, `b_year`, `photo`, `photo_type`, `website`, `im_windowslive`, `im_skype`, `im_facebook`, `im_twitter`, `im_yim`, `im_aol`, `posts`, `lastpost_date`, `signature`, `template`, `language`, `warn_level`, `warn_date`, `last_activity`, `time_offset`, `dst`, `show_email`, `show_birthday`, `show_gender`) VALUES ('{$adminInfo['username']}', '{$adminInfo['password']}', '{$adminInfo['email']}', 0, '', {$adminInfo['joined']}, 1, '', '', '', '', 0, 0, 0, '', 'gravatar', '0', '', '', '', '', '', '', 1, 1367848084, '', 'default', 'en_US', NULL, NULL, 0, '0', 0, 1, 1, 1)";

			$insertAdmin = $database->Query($sqlInsertAdmin);

			$status      = ($insertAdmin) ? 1 : 0;
			$description = "Save user information";
			break;

		// --------------------------------------------
		// Lock installer
		// --------------------------------------------
		case 6:
			$status = (fopen(".lock", "w")) ? 1 : 0;
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
