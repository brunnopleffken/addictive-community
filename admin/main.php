<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: main.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Admin;
use \AC\Kernel\Core;
use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\i18n;
use \AC\Kernel\Session;
use \AC\Kernel\Text;
use \AC\Kernel\Template;

// Call required files
require_once("../init.php");

// Load configuration file
$config = parse_ini_file("../config.ini");

// Define autoloader
spl_autoload_register('autoLoaderClass', true, true);

// First... check if the login sessions exists!
Session::init();
if(!Session::retrieve("admin_m_id")) {
	header("Location: index.php?error=2");
}

// If we have a validate session, check the running time.
// If it's older than 30 minutes, ask for a log in
if(Session::retrieve("admin_time") < (time() - 60 * 30)) {
	Session::destroy();
	header("Location: index.php?error=3");
}

// Load kernel drivers
Database::connect($config);
Database::query("SELECT * FROM c_config;");
$Core = new Core(Database::fetchConfig());
$Admin = new Admin();

// Update session time
$_SESSION['admin_time'] = time();

// Admin info
Database::query("SELECT username, time_offset FROM c_members WHERE m_id = '{$_SESSION['admin_m_id']}';");
$admin_info = Database::fetch();

// Define page content
$act = (Http::request("act")) ? Http::request("act") : "dashboard";
$p = (Http::request("p")) ? Http::request("p") : "main";


/**
 * Create a navigation menu bar
 *
 * @param $section
 * @return string
 */
function CreateMenu($section)
{
	$nav = "<div class=\"section-nav-container\">";

	if($section == "dashboard") {
	$nav .= <<<HTML
		<div class="nav-top">
			<a href="main.php" class="active">Dashboard</a>
			<a href="main.php?act=general">General</a>
			<a href="main.php?act=rooms">Rooms</a>
			<a href="main.php?act=members">Members</a>
			<a href="main.php?act=templates">Themes & Templates</a>
			<a href="main.php?act=languages">Languages</a>
			<a href="main.php?act=system">System</a>
		</div>
		<div class="nav-bottom">
			<a href="main.php">Community Dashboard</a>
		</div>
HTML;
	}

	if($section == "general") {
	$nav .= <<<HTML
		<div class="nav-top">
			<a href="main.php">Dashboard</a>
			<a href="main.php?act=general" class="active">General</a>
			<a href="main.php?act=rooms">Rooms</a>
			<a href="main.php?act=members">Members</a>
			<a href="main.php?act=templates">Themes & Templates</a>
			<a href="main.php?act=languages">Languages</a>
			<a href="main.php?act=system">System</a>
		</div>
		<div class="nav-bottom">
			<a href="main.php?act=general&amp;p=calendars">Calendars</a>
			<a href="main.php?act=general&amp;p=community">Community</a>
			<a href="main.php?act=general&amp;p=cookies">Cookies</a>
			<a href="main.php?act=general&amp;p=date">Date/Time</a>
			<a href="main.php?act=general&amp;p=email">E-mail</a>
			<a href="main.php?act=general&amp;p=pm">PM</a>
			<a href="main.php?act=general&amp;p=profiles">Profiles</a>
			<a href="main.php?act=general&amp;p=security">Security</a>
			<a href="main.php?act=general&amp;p=topics_posts">Threads/Posts</a>
			<a href="main.php?act=general&amp;p=warnings">Warnings</a>
		</div>
HTML;
	}

	if($section == "rooms") {
	$nav .= <<<HTML
		<div class="nav-top">
			<a href="main.php">Dashboard</a>
			<a href="main.php?act=general">General</a>
			<a href="main.php?act=rooms" class="active">Rooms</a>
			<a href="main.php?act=members">Members</a>
			<a href="main.php?act=templates">Themes & Templates</a>
			<a href="main.php?act=languages">Languages</a>
			<a href="main.php?act=system">System</a>
		</div>
		<div class="nav-bottom">
			<a href="main.php?act=rooms&amp;p=manage">Manage Rooms</a>
			<a href="main.php?act=rooms&amp;p=add">Add New Room</a>
			<a href="main.php?act=rooms&amp;p=categories">Categories</a>
			<a href="main.php?act=rooms&amp;p=moderators">Moderators</a>
		</div>
HTML;
	}

	if($section == "members") {
	$nav .= <<<HTML
		<div class="nav-top">
			<a href="main.php">Dashboard</a>
			<a href="main.php?act=general">General</a>
			<a href="main.php?act=rooms">Rooms</a>
			<a href="main.php?act=members" class="active">Members</a>
			<a href="main.php?act=templates">Themes & Templates</a>
			<a href="main.php?act=languages">Languages</a>
			<a href="main.php?act=system">System</a>
		</div>
		<div class="nav-bottom">
			<a href="main.php?act=members&amp;p=manage">Manage Members</a>
			<a href="main.php?act=members&amp;p=add">Add New Member</a>
			<a href="main.php?act=members&amp;p=ban">Ban Member</a>
			<a href="main.php?act=members&amp;p=usergroups">User Groups</a>
			<a href="main.php?act=members&amp;p=ranks">Ranks</a>
		</div>
HTML;
	}

	if($section == "templates") {
	$nav .= <<<HTML
		<div class="nav-top">
			<a href="main.php">Dashboard</a>
			<a href="main.php?act=general">General</a>
			<a href="main.php?act=rooms">Rooms</a>
			<a href="main.php?act=members">Members</a>
			<a href="main.php?act=templates" class="active">Themes & Templates</a>
			<a href="main.php?act=languages">Languages</a>
			<a href="main.php?act=system">System</a>
		</div>
		<div class="nav-bottom">
			<a href="main.php?act=templates&amp;p=themes">Theme Manager</a>
			<a href="main.php?act=templates&amp;p=templates">Templates</a>
			<a href="main.php?act=templates&amp;p=emails">E-mails</a>
			<a href="main.php?act=templates&amp;p=emoticons">Emoticons</a>
			<a href="main.php?act=templates&amp;p=help">Help Topics</a>
			<!-- <a href="main.php?act=templates&amp;p=import">Import / Export</a> -->
			<!-- <a href="main.php?act=templates&amp;p=tools">Tools</a> -->
		</div>
HTML;
	}

	if($section == "languages") {
	$nav .= <<<HTML
		<div class="nav-top">
			<a href="main.php">Dashboard</a>
			<a href="main.php?act=general">General</a>
			<a href="main.php?act=rooms">Rooms</a>
			<a href="main.php?act=members">Members</a>
			<a href="main.php?act=templates">Themes & Templates</a>
			<a href="main.php?act=languages" class="active">Languages</a>
			<a href="main.php?act=system">System</a>
		</div>
		<div class="nav-bottom">
			<a href="main.php?act=languages&amp;p=manager">Language Manager</a>
			<!-- <a href="main.php?act=languages&amp;p=import">Import / Export</a> -->
			<a href="main.php?act=languages&amp;p=badwords">Bad Words</a>
		</div>
HTML;
	}

	if($section == "system") {
	$nav .= <<<HTML
		<div class="nav-top">
			<a href="main.php">Dashboard</a>
			<a href="main.php?act=general">General</a>
			<a href="main.php?act=rooms">Rooms</a>
			<a href="main.php?act=members">Members</a>
			<a href="main.php?act=templates">Themes & Templates</a>
			<a href="main.php?act=languages">Languages</a>
			<a href="main.php?act=system" class="active">System</a>
		</div>
		<div class="nav-bottom">
			<a href="main.php?act=system&amp;p=database">Database Toolbox</a>
			<a href="main.php?act=system&amp;p=logs">Logs</a>
			<!-- <a href="main.php?act=system&amp;p=statistics">Statistics</a> -->
			<a href="main.php?act=system&amp;p=server">Server Environment</a>
			<a href="main.php?act=system&amp;p=optimization">System Optimization</a>
			<a href="https://github.com/brunnopleffken/addictive-community/issues" target="_blank">Found a bug?</a>
		</div>
HTML;
	}

	$nav .= "</div>";

	return $nav;
}

?><!DOCTYPE html>
<html>
<head>
	<title>Addictive Community - Administration Panel</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../thirdparty/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../thirdparty/codemirror/codemirror.css">
	<link rel="stylesheet" href="../static/css/framework.css">
	<link rel="stylesheet" href="../static/css/wireframe.css">
	<!-- JS Files -->
	<script src="../thirdparty/jquery/jquery.min.js"></script>
	<script src="../thirdparty/codemirror/codemirror.js"></script>
	<script src="../thirdparty/codemirror/mode/css/css.js"></script>
	<script src="admin.js"></script>
	<!-- Admin JS -->

	<style>
		.wrapper { margin: auto; width: 1080px; }
		.nav { margin-bottom: 30px; }

		/* Rewrite some elements */
		.alert { margin-bottom: 10px; }
		td.font-w600 { width: 200px; }
		td.font-w600 > small { display: block; font-size: 12px; font-weight: normal; font-style: italic; margin-top: 5px; }
		td a i { color: #000; }

		/* Addictive Community update */
		.loader > img { float: left; height: 16px; margin-right: 5px; width: 16px; }
		.update-message { display: none; line-height: 16px; }
		.fail { color: #b00; }
		.done { color: #090; }
	</style>
</head>

<body>

<header>
		<div class="top-half outer">
			<div class="row space-between">
				<div class="col-flexible text-right">
					<a href="https://github.com/brunnopleffken/addictive-community" target="_blank" class="transition">View Addictive Comunity on GitHub</a>
					<a href="https://github.com/brunnopleffken/addictive-community/issues" target="_blank" class="transition">Issues</a>
					<a href="https://github.com/brunnopleffken/addictive-community/blob/master/CHANGELOG.md" target="_blank" class="transition">Changelog</a>
				</div>
				<div class="col-flexible hide-xs">
					<a href="../" target="_blank" class="toplinks transition">&laquo; Go to your Community</a>
				</div>
			</div>
		</div>
		<div class="bottom-half outer">
			<div class="wrapper">
				<div class="row space-between">
					<a href="main.php">
						<img src="../static/images/logo-admin.svg" class="logo" alt="Addictive Community">
					</a>
				</div>
			</div>
		</div>
</header>

<div class="wrapper">
	<div class="nav">
		<?php echo CreateMenu($act); ?>
	</div>

	<?php require_once("sources/adm_{$act}_{$p}.php"); ?>
</div>

<footer class="text-center">
	Powered by
	<a href="https://github.com/brunnopleffken/addictive-community" target="_blank">Addictive Community</a>
	<?= VERSION . "-" . CHANNEL ?> &copy; <?= date("Y") ?> - All rights reserved.
</footer>

</body>
</html>
