<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: run.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
## ---------------------------------------------------

// Get task
$task = $_REQUEST['task'];

// In case of error, $result is FALSE by default
$result = false;

// Run tasks
switch($task) {
	case 'config-exists':
		if(file_exists("../../config.php")) {
			$result = true;
		}
		$status = array("status" => $result);
		break;

	case 'config-has-data':
		require("../../config.php");
		if(filesize("../../config.php") > 0 && !empty($config)) {
			$result = true;
		}
		$status = array("status" => $result);
		break;

	case 'db-connect':
		require("../../config.php");
		$link = mysqli_connect($config['db_server'], $config['db_username'], $config['db_password']);
		if($link) {
			$result = true;
		}
		$status = array("status" => $result);
		break;

	case 'db-database':
		require("../../config.php");
		$link = mysqli_connect($config['db_server'], $config['db_username'], $config['db_password']);
		$query = mysqli_query($link, "SHOW DATABASES LIKE '{$config['db_database']}';");
		if(mysqli_num_rows($query) > 0) {
			$result = true;
		}
		$status = array("status" => $result);
		break;

	case 'db-tables':
		require("../../config.php");

		// List of tables that should exist
		$tables = array(
			"c_attachments", "c_config", "c_emails", "c_emoticons",
			"c_events", "c_follow", "c_help", "c_languages",
			"c_logs", "c_members", "c_messages", "c_posts",
			"c_ranks", "c_reports", "c_rooms", "c_sessions",
			"c_stats", "c_templates", "c_themes", "c_threads",
			"c_usergroups"
		);

		$link = mysqli_connect($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
		$query = mysqli_query($link, "SHOW TABLES;");
		while($result = mysqli_fetch_assoc($query)) {
			$existing_tables[] = $result['Tables_in_community'];
		}
		$diff = array_diff($existing_tables, $tables);

		if(empty($diff)) {
			$result = true;
		}
		$status = array("status" => $result);
		break;

	case 'env-apache':
		if(substr_count(strtolower($_SERVER['SERVER_SOFTWARE']), "apache")) {
			$result = true;
		}
		$status = array("status" => $result);
		break;

	case 'env-php':
		if(version_compare(PHP_VERSION, 5.3) >= 0) {
			$result = true;
		}
		$status = array("status" => $result);
		break;

	case 'env-mysql':
		require("../../config.php");
		$link = mysqli_connect($config['db_server'], $config['db_username'], $config['db_password']);
		if(version_compare($link->server_info, 5.5) >= 0) {
			$result = true;
		}
		$status = array("status" => $result);
		break;

	case 'env-mod-rewrite':
		if(in_array("mod_rewrite", apache_get_modules())) {
			$result = true;
		}
		$status = array("status" => $result);
		break;

	default:
		$status = array("status" => "end");
		break;
}

echo json_encode($status);
