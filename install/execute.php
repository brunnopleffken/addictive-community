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

	$step = $_REQUEST['step'];
	$data = $_POST;

	switch($step) {
		// --------------------------------------------
		// Save configuration file
		// --------------------------------------------
		case 1:
			if(is_writable("../config.php")) {
				// ...
				$status = 1;
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
			$status      = 1;
			$description = "Check information and connect to database";
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
