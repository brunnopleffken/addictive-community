<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: process.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// First... check if the login sessions exists!

	session_start();

	if(!isset($_SESSION['admin_m_id'])) {
		header("Location: index.php?error=2");
	}

	// If we have a validate session, check the running time.
	// If it's older than 30 minutes, ask for a log in

	if($_SESSION['admin_time'] < (time() - 60 * 30)) {
		session_destroy();
		header("Location: index.php?error=3");
	}

	// Call files, classes, functions, etc

	require_once("../init.php");
	require_once("../config.php");
	require_once("../kernel/Admin.php");
	require_once("../kernel/Core.php");
	require_once("../kernel/Html.php");
	require_once("../kernel/String.php");
	require_once("../kernel/Database.php");

	$Db = new Database($config);
	$Core = new Core($Db, $config);
	$Admin = new Admin($Db);

	// Do we have an action?

	$do = Html::Request("do");

	if(!$do) {
		echo "Variable 'do' is undefined.";
		exit;
	}

	// ...so, do it!

	switch($do) {
		case "save":

			$Admin->SaveConfig($_POST);

			header("Location: " . $_SERVER['HTTP_REFERER'] . "&msg=1");
			exit;

			break;

		case "newroom":

			$room = array(
				"name"          => String::Sanitize($_POST['name']),
				"description"   => String::Sanitize($_POST['description']),
				"password"      => ($_POST['password'] != "") ? $_POST['password'] : "",
				"read_only"     => (isset($_POST['read_only'])) ? "1" : "0",
				"invisible"     => (isset($_POST['invisible'])) ? "1" : "0",
				"rules_visible" => (isset($_POST['rules_visible'])) ? "1" : "0",
				"rules_title"   => (isset($_POST['rules_title'])) ? String::Sanitize($_POST['rules_title']) : "",
				"rules_text"    => (isset($_POST['rules_text'])) ? String::Sanitize($_POST['rules_text']) : "",
				"upload"        => 1,
				"perm_view"     => serialize($_POST['view']),
				"perm_post"     => serialize($_POST['post']),
				"perm_reply"    => serialize($_POST['reply'])
			);

			$Db->Insert("c_rooms", $room);
			$Admin->RegisterLog("Created new room: " . $room['name']);

			header("Location: main.php?act=rooms&p=manage&msg=1");
			exit;

			break;

		case "editroom":

			$room = array(
				"name"          => String::Sanitize($_POST['room_name']),
				"description"   => String::Sanitize($_POST['room_description']),
				"invisible"     => ($_POST['invisible'] == "true") ? "1" : "0",
				"rules_title"   => (isset($_POST['rules_title'])) ? String::Sanitize($_POST['rules_title']) : "",
				"rules_text"    => (isset($_POST['rules_text'])) ? String::Sanitize($_POST['rules_text']) : "",
				"rules_visible" => ($_POST['rules_visible'] == "true") ? "1" : "0",
				"read_only"     => ($_POST['read_only'] == "true") ? "1" : "0",
				"password"      => ($_POST['password'] != "") ? $_POST['password'] : "",
				"upload"        => ($_POST['upload'] == "true") ? "1" : "0"
			);

			$Db->Update("c_rooms", $room, "r_id = '{$_REQUEST['room_id']}'");
			$Admin->RegisterLog("Edited room: " . $room['name']);

			header("Location: main.php?act=rooms&p=manage&msg=2");
			exit;

			break;

		case "deleteroom":

			$r_id = Html::Request("r_id");
			$Db2 = clone($Db);

			// Register room exclusion in Admin log

			$Db->Query("SELECT name FROM c_rooms WHERE r_id = {$r_id}");
			$room = $Db->Fetch();
			$Admin->RegisterLog("Deleted room: " . $room['name']);

			// Delete all related posts

			$Db->Query("SELECT t_id FROM c_threads WHERE room_id = '{$r_id}';");

			while($threads = $Db->Fetch()) {
				$Db2->Query("DELETE FROM c_posts WHERE thread_id = '{$threads['t_id']}';");
			}

			// Delete threads and room itself

			$Db->Query("DELETE FROM c_threads WHERE room_id = '{$r_id}';");
			$Db->Query("DELETE FROM c_rooms WHERE r_id = '{$r_id}';");

			header("Location: main.php?act=rooms&p=manage&msg=3");
			exit;

			break;

		case "resyncroom":

			$r_id = Html::Request("r_id");

			echo $r_id . "<br><br>";

			$sql->Query("SELECT * FROM c_threads WHERE room_id = '{$r_id}';");
			$num_threads = $Db->NumRows();

			print_r($num_threads);
			/*
			while($threads = $sql->Fetch())
			{
				$sql->
			}
			*/
			echo "<br><br>";



			//header("Location: main.php?act=rooms&p=manage&msg=3");
			//exit;

			break;

		case "savehelp":

			$topic = array(
				"title"      => String::Sanitize(Html::Request("title")),
				"short_desc" => String::Sanitize(Html::Request("short_desc")),
				"content"    => nl2br(String::Sanitize(Html::Request("content")))
			);

			$Admin->RegisterLog("Created help topic: " . $topic['title']);

			$Db->Query("INSERT INTO c_help
				(title, short_desc, content) VALUES
				('{$topic['title']}', '{$topic['short_desc']}', '{$topic['content']}');");

			header("Location: main.php?act=templates&p=help&msg=1");
			exit;

			break;

		case "deletereport":

			$Db->Query("DELETE FROM c_reports WHERE rp_id = '{$_REQUEST['report']}';");
			$Admin->RegisterLog("Deleted abuse report ID #" . $_REQUEST['report'] . " for the thread ID #" . $_REQUEST['thread']);

			header("Location: main.php");

			break;

		case "savelang":

			// File info

			$file = Html::Request("file");
			$dir  = Html::Request("dir");

			$file_path = "../languages/" . $dir . "/" . $file . ".php";

			// Language file content

			$file_content = "<?php\n";
			foreach(Html::Request("index") as $key) {
				$file_content .= "\t\$t[\"" . $key . "\"] = \"" . $_REQUEST[$key] . "\";\n";
			}
			$file_content .= "?>\n";

			// Open file and write
			$handle = fopen($file_path, "w");
			if(fwrite($handle, $file_content)) {
				fclose($handle);
			}

			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit;

			break;

		case "editcss":
			String::PR($_REQUEST);
			break;

		case "install_language":

			// Get locale code
			$code = $_REQUEST['id'];

			// Get array from language JSON manifest
			$language_info = json_decode(file_get_contents("../languages/" . $code . "/_language.json"), true);

			// Insert new language into DB
			$language = array(
				"name"         => $language_info['name'],
				"directory"    => $language_info['directory'],
				"author_name"  => $language_info['author_name'],
				"author_email" => $language_info['author_email'],
				"is_active"    => 1,
				"is_default"   => 0
			);

			$Db->Insert("c_languages", $language);

			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit;

			break;
	}
