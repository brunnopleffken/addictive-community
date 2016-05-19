<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: updater.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

/**
 * --------------------------------------------------------------------
 * INITIALIZE
 * --------------------------------------------------------------------
 */

// Load kernel modules
require_once("../kernel/Core.php");
require_once("../kernel/Html.php");
require_once("../kernel/Text.php");
require_once("../kernel/Database.php");

// Load configuration file
require_once("../config.php");

// Get step number
$step = Html::Request("step");

// Get user data
$migration_array = unserialize(str_replace('\"', '"', $_POST['migration_array']));


/**
 * --------------------------------------------------------------------
 * OK, LET'S DO IT
 * --------------------------------------------------------------------
 */

switch($step) {

	/**
	 * --------------------------------------------------------------------
	 * TEST CONNECTION TO DATABASE
	 * --------------------------------------------------------------------
	 */

	case 1:
		if(is_array($config)) {
			$link = mysqli_connect($config['db_server'], $config['db_username'], $config['db_password']);

			if($link) {
				$status = 1;
				$description = "Database connection successfully tested.";
			}
			else {
				$status = 0;
				$description = "Unable to connect to database.";
			}
		}
		else {
			$status = 0;
			$description = "File 'config.php' doesn't contain an array or is empty.";
		}

		break;


	/**
	 * --------------------------------------------------------------------
	 * SET COMMUNITY TO OFFLINE MODE
	 * --------------------------------------------------------------------
	 */

	case 2:
		$Db = new Database($config);

		// Turn community to Update Mode
		$to_offline = $Db->Query("UPDATE c_config SET value = 'true' WHERE `index` = 'general_updating';");

		if($to_offline) {
			$status = 1;
			$description = "Set community to Update Mode.";
		}
		else {
			$status = 0;
			$description = "Unable to set community to Update Mode.";
		}

		break;


	/**
	 * --------------------------------------------------------------------
	 * UPDATE TABLE STRUCTURE AND DATA
	 * --------------------------------------------------------------------
	 */

	case 3:


		$description = "Extract table structure";
		break;


	/**
	 * --------------------------------------------------------------------
	 * LOCK INSTALLER
	 * --------------------------------------------------------------------
	 */

	case 4:


		$description = "Insert initial data and settings";
		break;


	/**
	 * --------------------------------------------------------------------
	 * SET COMMUNITY BACK ONLINE
	 * --------------------------------------------------------------------
	 */

	case 5:


		$description = "Save user information";
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
