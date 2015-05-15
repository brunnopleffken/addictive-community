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

		case "resync_room":

			$id = Html::Request("r_id");

			// Clone Database class for secondary tasks
			$Db2 = clone($Db);

			// Count and update number of threads
			$Db->Query("SELECT t_id FROM c_threads WHERE room_id = '{$id}';");
			$num_threads = $Db->Rows();
			$Db2->Query("UPDATE c_rooms SET threads = {$num_threads} WHERE r_id = {$id};");

			// Iterate between threads
			while($thread = $Db->Fetch()) {
				// Count and update number of replies
				$Db2->Query("SELECT COUNT(*) AS total FROM c_posts WHERE thread_id = {$thread['t_id']}; ");
				$posts = $Db2->Fetch();
				$Db2->Query("UPDATE c_threads SET replies = {$posts['total']} WHERE t_id = {$thread['t_id']};");

				// Get and update last post info
				$Db2->Query("SELECT p.author_id, p.post_date FROM c_posts p
						LEFT JOIN c_members m ON (p.author_id = m.m_id)
						WHERE p.thread_id = {$thread['t_id']}
						ORDER BY p.post_date DESC LIMIT 1");

				$last_post = $Db2->Fetch();
				$Db2->Query("UPDATE c_threads
						SET lastpost_date = {$last_post['post_date']}, lastpost_member_id = {$last_post['author_id']}
						WHERE t_id = {$thread['t_id']};");
			}

			header("Location: main.php?act=rooms&p=manage&msg=4");
			exit;

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

		case "edit_css":
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

		case "uninstall_language":

			// Get locale code
			$id = $_REQUEST['id'];

			// Transfer all members using this language to default
			$default_language = $Admin->SelectConfig("language_default_set");

			$Db->Query("SELECT directory FROM c_languages WHERE l_id = {$id};");
			$language_directory = $Db->Fetch();

			$Db->Query("UPDATE c_members SET language = '{$default_language}' WHERE language = '{$language_directory['directory']}';");

			// Delete from database
			$Db->Query("DELETE FROM c_languages WHERE l_id = {$id};");


			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit;

			break;

		case "disable_emoticon":

			// Get emoticon ID
			$id = $_REQUEST['id'];

			// Disable emoticon
			$Db->Query("UPDATE c_emoticons SET display = 0 WHERE id = {$id};");

			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit;

			break;

		case "enable_emoticon":

			// Get emoticon ID
			$id = $_REQUEST['id'];

			// Disable emoticon
			$Db->Query("UPDATE c_emoticons SET display = 1 WHERE id = {$id};");

			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit;

			break;
	}
