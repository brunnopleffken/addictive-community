<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: process.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Admin;
use \AC\Kernel\Database;
use \AC\Kernel\Core;
use \AC\Kernel\Http;
use \AC\Kernel\Text;

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
require_once("../kernel/Admin.php");
require_once("../kernel/Core.php");
require_once("../kernel/Html.php");
require_once("../kernel/Http.php");
require_once("../kernel/Text.php");
require_once("../kernel/Database.php");

// Load configuration file
$config = parse_ini_file("../config.ini");

// Load classes
$Db = new Database();
$Core = new Core($Db, $config);
$Admin = new Admin($Db);

$Db->connect($config);

// Do we have an action?

$do = Http::request("do");

if(!$do) {
	echo "Variable 'do' is undefined.";
	exit;
}

// ...so, do it!

switch($do) {
	case "save":

		$Admin->saveConfig($_POST);
		header("Location: " . $_SERVER['HTTP_REFERER'] . "&msg=1");
		exit;

		break;

	case "remove_report":

		$id = Http::request("id", true);
		$Db->query("DELETE FROM c_reports WHERE rp_id = {$id}");
		$Admin->registerLog("Removed a report");

		header("Location: main.php");
		exit;

		break;

	case "new_category":

		$category = array(
			"name"    => Http::request("name"),
			"order_n" => 0,
			"visible" => 1
		);

		$Db->insert("c_categories", $category);
		$Admin->registerLog("Created new category: " . $category['name']);

		header("Location: main.php?act=rooms&p=manage&msg=1");
		exit;

		break;

	case "update_categories":

		$categories = $_POST['category'];

		foreach($categories as $id => $value) {
			$category = array(
				"order_n" => $value['order_n'],
				"visible" => $value['visible']
			);

			$Db->update("c_categories", $category, "c_id = {$id}");
		}

		header("Location: main.php?act=rooms&p=categories&msg=1");
		exit;

		break;

	case "remove_category":

		$id = Http::request("id", true);

		// Get the first category
		$category = $Db->query("SELECT c_id FROM c_categories LIMIT 1;");
		$first_category = $Db->fetch($category);

		// Update all rooms th the first selected category
		$Db->update("c_rooms", "category_id = {$first_category['c_id']}", "category_id = {$id}");

		// Remove the selected category
		$Db->delete("c_categories", "c_id = {$id}");

		header("Location: main.php?act=rooms&p=categories&msg=2");
		exit;

		break;

	case "newroom":

		$room = array(
			"category_id"   => $_POST['category_id'],
			"name"          => Text::sanitize($_POST['name']),
			"description"   => Text::sanitize($_POST['description']),
			"url"           => ($_POST['url'] != "") ? $_POST['url'] : NULL,
			"threads"       => 0,
			"password"      => ($_POST['password'] != "") ? $_POST['password'] : NULL,
			"read_only"     => (isset($_POST['read_only'])) ? "1" : "0",
			"invisible"     => (isset($_POST['invisible'])) ? "1" : "0",
			"rules_visible" => (isset($_POST['rules_visible'])) ? "1" : "0",
			"rules_title"   => (isset($_POST['rules_title'])) ? Text::sanitize($_POST['rules_title']) : NULL,
			"rules_text"    => (isset($_POST['rules_text'])) ? Text::sanitize($_POST['rules_text']) : NULL,
			"upload"        => 1,
			"perm_view"     => serialize($_POST['view']),
			"perm_post"     => serialize($_POST['post']),
			"perm_reply"    => serialize($_POST['reply'])
		);

		$Db->insert("c_rooms", $room);
		$Admin->registerLog("Created new room: " . $room['name']);

		header("Location: main.php?act=rooms&p=manage&msg=1");
		exit;

		break;

	case "editroom":

		$room = array(
			"name"          => Text::sanitize($_POST['room_name']),
			"description"   => Text::sanitize($_POST['room_description']),
			"invisible"     => ($_POST['invisible'] == "1") ? "1" : "0",
			"rules_title"   => (isset($_POST['rules_title'])) ? Text::sanitize($_POST['rules_title']) : "",
			"rules_text"    => (isset($_POST['rules_text'])) ? Text::sanitize($_POST['rules_text']) : "",
			"rules_visible" => ($_POST['rules_visible'] == "1") ? "1" : "0",
			"read_only"     => ($_POST['read_only'] == "1") ? "1" : "0",
			"password"      => ($_POST['password'] != "") ? $_POST['password'] : "",
			"upload"        => ($_POST['upload'] == "1") ? "1" : "0"
		);

		$Db->update("c_rooms", $room, "r_id = '{$_REQUEST['room_id']}'");
		$Admin->registerLog("Edited room: " . $room['name']);

		header("Location: main.php?act=rooms&p=manage&msg=2");
		exit;

		break;

	case "deleteroom":

		$r_id = Http::request("r_id", true);

		// Register room exclusion in Admin log
		$Db->query("SELECT name FROM c_rooms WHERE r_id = {$r_id}");
		$room = $Db->fetch();

		// Delete all related posts
		$threads = $Db->query("SELECT t_id FROM c_threads WHERE room_id = '{$r_id}';");

		while($_threads = $Db->fetch($threads)) {
			$Db->query("DELETE FROM c_posts WHERE thread_id = '{$_threads['t_id']}';");
		}

		// Delete threads and room itself
		$Db->query("DELETE FROM c_threads WHERE room_id = '{$r_id}';");
		$Db->query("DELETE FROM c_rooms WHERE r_id = '{$r_id}';");
		$Admin->registerLog("Deleted room: " . $room['name']);

		header("Location: main.php?act=rooms&p=manage&msg=3");
		exit;

		break;

	case "resync_room":

		$id = Http::request("r_id", true);

		// Clone Database class for secondary tasks
		$Db2 = clone($Db);

		// Count and update number of threads
		$Db->query("SELECT t_id FROM c_threads WHERE room_id = '{$id}';");
		$num_threads = $Db->rows();
		$Db2->query("UPDATE c_rooms SET threads = {$num_threads} WHERE r_id = {$id};");

		// Iterate between threads
		while($thread = $Db->fetch()) {
			// Count and update number of replies
			$Db2->query("SELECT COUNT(*) AS total FROM c_posts WHERE thread_id = {$thread['t_id']}; ");
			$posts = $Db2->fetch();
			$Db2->query("UPDATE c_threads SET replies = {$posts['total']} WHERE t_id = {$thread['t_id']};");

			// Get and update last post info
			$Db2->query("SELECT p.author_id, p.post_date FROM c_posts p
					LEFT JOIN c_members m ON (p.author_id = m.m_id)
					WHERE p.thread_id = {$thread['t_id']}
					ORDER BY p.post_date DESC LIMIT 1");

			$last_post = $Db2->fetch();
			$Db2->query("UPDATE c_threads
					SET last_post_date = {$last_post['post_date']}, last_post_member_id = {$last_post['author_id']}
					WHERE t_id = {$thread['t_id']};");
		}

		$Admin->registerLog("Resynchronized room: " . $id);

		header("Location: main.php?act=rooms&p=manage&msg=4");
		exit;

		break;

	case "savehelp":

		$topic = array(
			"title"      => Text::sanitize(Http::request("title")),
			"short_desc" => Text::sanitize(Http::request("short_desc")),
			"content"    => nl2br(Text::sanitize(Http::request("content")))
		);

		$Admin->registerLog("Created help topic: " . $topic['title']);

		$Db->query("INSERT INTO c_help
			(title, short_desc, content) VALUES
			('{$topic['title']}', '{$topic['short_desc']}', '{$topic['content']}');");

		header("Location: main.php?act=templates&p=help&msg=1");
		exit;

		break;

	case "deletereport":

		$Db->query("DELETE FROM c_reports WHERE rp_id = '{$_REQUEST['report']}';");
		$Admin->registerLog("Deleted abuse report ID #" . Http::request("report", true) . " for the thread ID #" . Http::request("thread", true));

		header("Location: main.php");

		break;

	case "savelang":

		// File info
		$file = Http::request("file");
		$dir  = Http::request("dir");

		$file_path = "../languages/" . $dir . "/" . $file . ".php";

		// Language file content
		$file_content = "<?php\n";
		foreach(Http::request("index") as $key) {
			$file_content .= "\t\$t[\"" . $key . "\"] = \"" . Http::request($key) . "\";\n";
		}
		$file_content .= "?>\n";

		// Open file and write
		$handle = fopen($file_path, "w");
		if(fwrite($handle, $file_content)) {
			fclose($handle);
		}

		$Admin->registerLog("Edited language file \'" . $file . "\' from \'" . $dir . "\'");

		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;

		break;

	case "edit_css":
		$handle = fopen(Http::request("css_file"), "w");
		$file_content = $_POST['css'];

		if(fwrite($handle, $file_content)) {
			fclose($handle);
			$Admin->registerLog("Edited CSS file: " . Http::request("css_file"));
			header("Location: main.php?act=templates&p=themes");
			exit;
		}
		else {
			header("Location: main.php?act=templates&p=themes");
			exit;
		}

		break;

	case "install_language":

		// Get locale code
		$code = Http::request("id");

		// Get array from language JSON manifest
		$language_info = json_decode(file_get_contents("../languages/" . $code . "/_language.json"), true);

		// Insert new language into DB
		$language = array(
			"name"         => $language_info['name'],
			"directory"    => $language_info['directory'],
			"author_name"  => $language_info['author_name'],
			"author_email" => $language_info['author_email'],
			"is_active"    => 1
		);

		$Db->insert("c_languages", $language);
		$Admin->registerLog("Installed new language : " . $code);

		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;

		break;

	case "uninstall_language":

		// Get locale code
		$id = Http::request("id");

		// Transfer all members using this language to default
		$default_language = $Admin->selectConfig("language_default_set");

		$Db->query("SELECT directory FROM c_languages WHERE l_id = {$id};");
		$language_directory = $Db->fetch();

		$Db->query("UPDATE c_members SET language = '{$default_language}' WHERE language = '{$language_directory['directory']}';");

		// Delete from database
		$Db->query("DELETE FROM c_languages WHERE l_id = {$id};");
		$Admin->registerLog("Uninstalled language package: " . $language_directory['directory']);

		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;

		break;

	case "install_theme":

		// Get theme name
		$code = Http::request("id");

		// Get array from theme JSON manifest
		$theme_info = json_decode(file_get_contents("../themes/" . $code . "/_theme.json"), true);

		// Insert new language into DB
		$theme = array(
			"name"         => $theme_info['name'],
			"directory"    => $theme_info['directory'],
			"author_name"  => $theme_info['name'],
			"author_email" => $theme_info['email'],
			"is_active"    => 1
		);

		$Db->insert("c_themes", $theme);
		$Admin->registerLog("Installed new theme : " . $code);

		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;

		break;

	case "theme_remove":

		// Get theme name
		$id = Http::request("id");

		// Transfer all members using this theme to default
		$default_theme = $Admin->selectConfig("theme_default_set");

		$Db->query("SELECT directory FROM c_themes WHERE theme_id = {$id};");
		$theme_directory = $Db->fetch();

		$Db->query("UPDATE c_members SET theme = '{$default_theme}' WHERE theme = '{$theme_directory['directory']}';");

		// Delete from database
		$Db->query("DELETE FROM c_themes WHERE theme_id = {$id};");
		$Admin->registerLog("Uninstalled theme: " . $theme_directory['directory']);

		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;

		break;

	case "install_template":

		// Get template name
		$code = Http::request("id");

		// Get array from template JSON manifest
		$template_info = json_decode(file_get_contents("../templates/" . $code . "/_template.json"), true);

		// Insert new language into DB
		$template = array(
			"name"         => $template_info['name'],
			"directory"    => $template_info['directory'],
			"author_name"  => $template_info['name'],
			"author_email" => $template_info['email'],
			"is_active"    => 1
		);

		$Db->insert("c_templates", $template);
		$Admin->registerLog("Installed new template : " . $code);

		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;

		break;

	case "template_remove":

		// Get template name
		$id = Http::request("id");

		// Transfer all members using this template to default
		$default_template = $Admin->selectConfig("template_default_set");

		$Db->query("SELECT directory FROM c_templates WHERE tpl_id = {$id};");
		$template_directory = $Db->fetch();

		$Db->query("UPDATE c_members SET template = '{$default_template}' WHERE template = '{$template_directory['directory']}';");

		// Delete from database
		$Db->query("DELETE FROM c_templates WHERE tpl_id = {$id};");
		$Admin->registerLog("Uninstalled template: " . $template_directory['directory']);

		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;

		break;

	case "disable_emoticon":

		// Get emoticon ID
		$id = Http::request("id", true);

		// Disable emoticon
		$Db->query("UPDATE c_emoticons SET display = 0 WHERE id = {$id};");

		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;

		break;

	case "enable_emoticon":

		// Get emoticon ID
		$id = Http::request("id", true);

		// Disable emoticon
		$Db->query("UPDATE c_emoticons SET display = 1 WHERE id = {$id};");

		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;

		break;

	case "add_moderator":

		// Get variables
		$mods_array = array();
		$room_id = Http::request("r_id", true);
		$member_id = Http::request("m_id", true);

		// Get current moderators of the room
		$Db->query("SELECT moderators FROM c_rooms WHERE r_id = {$room_id};");
		$room_moderators = $Db->fetch();

		// If field is empty, then create a new array
		if($room_moderators['moderators'] == "") {
			$moderators = array();
		}
		else {
			$moderators = unserialize($room_moderators['moderators']);
		}

		// If member is already defined as moderator, return an error
		if(in_array($member_id, $moderators)) {
			header("Location: main.php?act=rooms&p=moderators&msg=2");
			exit;
		}

		// Add new member to array and serialize
		array_push($moderators, $member_id);
		$serialized = serialize($moderators);

		// Save new data in database
		$Db->query("UPDATE c_rooms SET moderators = '{$serialized}' WHERE r_id = {$room_id};");
		$Admin->registerLog("Added moderator: member ID #" . $member_id . " to the room ID #" . $room_id);

		header("Location: main.php?act=rooms&p=moderators&msg=1");
		exit;

		break;

	case "remove_moderator":

		// Get variables
		$mods_array = array();
		$room_id = Http::request("r_id", true);
		$member_id = Http::request("m_id", true);

		// Get current moderators of the room
		$Db->query("SELECT moderators FROM c_rooms WHERE r_id = {$room_id};");
		$room_moderators = $Db->fetch();
		$moderators = unserialize($room_moderators['moderators']);

		// Remove member from array
		// Must use strict comparison (!==)
		if(($key = array_search($member_id, $moderators)) !== false) {
			unset($moderators[$key]);
		}

		// Serialize
		$serialized = serialize($moderators);

		// Save new data in database
		$Db->query("UPDATE c_rooms SET moderators = '{$serialized}' WHERE r_id = {$room_id};");
		$Admin->registerLog("Removed moderator: member ID #" . $member_id . " from the room ID #" . $room_id);

		header("Location: main.php?act=rooms&p=remove_mod&id=6&msg=1&m_id={$member_id}");
		exit;

		break;

	case "new_rank":

		$rank = array(
			"title"     => Http::request("title"),
			"min_posts" => Http::request("min_posts", true),
			"pips"      => Http::request("pips", true)
		);

		$Db->insert("c_ranks", $rank);
		$Admin->registerLog("Added new rank: " . $rank['title']);

		header("Location: main.php?act=members&p=ranks&msg=2");
		exit;

		break;

	case "delete_rank":

		$rank_id = Http::request("id", true);

		$Db->delete("c_ranks", "id = {$rank_id}");
		$Admin->registerLog("Deleted rank #" . $rank_id);

		header("Location: main.php?act=members&p=ranks&msg=3");
		exit;

		break;

	case "delete_member":

		$id = Http::request("id", true);
		$Db->update("c_members", array(
			"email"        => "",
			"password"     => "",
			"usergroup"    => 0,
			"token"        => ""
		), "m_id = {$id}");

		header("Location: main.php?act=members&p=manage");
		exit;

		break;

	case "update_member":

		$id = Http::request("id", true);
		$Db->update("c_members", $_POST, "m_id = {$id}");

		header("Location: main.php?act=members&p=edit&id={$id}&msg=1");

		break;

	case "update_usergroup":

		$id = Http::request("id", true);
		$Db->update("c_usergroups", $_POST, "g_id = {$id}");

		header("Location: main.php?act=members&p=usergroups&msg=1");

		break;
}
