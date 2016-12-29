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
use \AC\Kernel\Text;
use \AC\Kernel\Template;

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

// Call required files
require_once("../init.php");
require_once("../config.php");

// Define autoloader
spl_autoload_register('_AutoLoader', true, true);

// Load kernel drivers
$Db = new Database();
$Db->Connect($config);
$Db->Query("SELECT * FROM c_config;");
$Core = new Core($Db, $Db->FetchConfig());
$Admin = new Admin($Db);

// Update session time
$_SESSION['admin_time'] = time();

// Admin info
$Db->Query("SELECT username, time_offset FROM c_members WHERE m_id = '{$_SESSION['admin_m_id']}';");
$admin_info = $Db->Fetch();

// Define page content
$act = (Http::Request("act")) ? Http::Request("act") : "dashboard";
$p = (Http::Request("p")) ? Http::Request("p") : "main";

// ---------------------------------------------------
// Navigation menu template
// ---------------------------------------------------

function CreateMenu($section)
{
	$nav = "<div class=\"section-nav-container\">";

	if($section == "dashboard") {
	$nav .= <<<HTML
		<div class="section-navbar">
			<a href="main.php" class="nav-selected">Dashboard</a>
			<a href="main.php?act=general" class="transition">General</a>
			<a href="main.php?act=rooms" class="transition">Rooms</a>
			<a href="main.php?act=members" class="transition">Members</a>
			<a href="main.php?act=templates" class="transition">Themes & Templates</a>
			<a href="main.php?act=languages" class="transition">Languages</a>
			<a href="main.php?act=system" class="transition">System</a>
		</div>
		<div class="section-subnav">
			<a href="main.php">Community Dashboard</a>
		</div>
HTML;
	}

	if($section == "general") {
	$nav .= <<<HTML
		<div class="section-navbar">
			<a href="main.php" class="transition">Dashboard</a>
			<a href="main.php?act=general" class="nav-selected">General</a>
			<a href="main.php?act=rooms" class="transition">Rooms</a>
			<a href="main.php?act=members" class="transition">Members</a>
			<a href="main.php?act=templates" class="transition">Themes & Templates</a>
			<a href="main.php?act=languages" class="transition">Languages</a>
			<a href="main.php?act=system" class="transition">System</a>
		</div>
		<div class="section-subnav">
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
		<div class="section-navbar">
			<a href="main.php" class="transition">Dashboard</a>
			<a href="main.php?act=general" class="transition">General</a>
			<a href="main.php?act=rooms" class="nav-selected">Rooms</a>
			<a href="main.php?act=members" class="transition">Members</a>
			<a href="main.php?act=templates" class="transition">Themes & Templates</a>
			<a href="main.php?act=languages" class="transition">Languages</a>
			<a href="main.php?act=system" class="transition">System</a>
		</div>
		<div class="section-subnav">
			<a href="main.php?act=rooms&amp;p=manage">Manage Rooms</a>
			<a href="main.php?act=rooms&amp;p=add">Add New Room</a>
			<a href="main.php?act=rooms&amp;p=categories">Categories</a>
			<a href="main.php?act=rooms&amp;p=moderators">Moderators</a>
		</div>
HTML;
	}

	if($section == "members") {
	$nav .= <<<HTML
		<div class="section-navbar">
			<a href="main.php" class="transition">Dashboard</a>
			<a href="main.php?act=general" class="transition">General</a>
			<a href="main.php?act=rooms" class="transition">Rooms</a>
			<a href="main.php?act=members" class="nav-selected">Members</a>
			<a href="main.php?act=templates" class="transition">Themes & Templates</a>
			<a href="main.php?act=languages" class="transition">Languages</a>
			<a href="main.php?act=system" class="transition">System</a>
		</div>
		<div class="section-subnav">
			<a href="main.php?act=members&amp;p=add">Add New Member</a>
			<a href="main.php?act=members&amp;p=ban">Ban Member</a>
			<a href="main.php?act=members&amp;p=manage">Manage Members</a>
			<a href="main.php?act=members&amp;p=usergroups">User Groups</a>
			<a href="main.php?act=members&amp;p=ranks">Ranks</a>
		</div>
HTML;
	}

	if($section == "templates") {
	$nav .= <<<HTML
		<div class="section-navbar">
			<a href="main.php" class="transition">Dashboard</a>
			<a href="main.php?act=general" class="transition">General</a>
			<a href="main.php?act=rooms" class="transition">Rooms</a>
			<a href="main.php?act=members" class="transition">Members</a>
			<a href="main.php?act=templates" class="nav-selected">Themes & Templates</a>
			<a href="main.php?act=languages" class="transition">Languages</a>
			<a href="main.php?act=system" class="transition">System</a>
		</div>
		<div class="section-subnav">
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
		<div class="section-navbar">
			<a href="main.php" class="transition">Dashboard</a>
			<a href="main.php?act=general" class="transition">General</a>
			<a href="main.php?act=rooms" class="transition">Rooms</a>
			<a href="main.php?act=members" class="transition">Members</a>
			<a href="main.php?act=templates" class="transition">Themes & Templates</a>
			<a href="main.php?act=languages" class="nav-selected">Languages</a>
			<a href="main.php?act=system" class="transition">System</a>
		</div>
		<div class="section-subnav">
			<a href="main.php?act=languages&amp;p=manager">Language Manager</a>
			<!-- <a href="main.php?act=languages&amp;p=import">Import / Export</a> -->
			<a href="main.php?act=languages&amp;p=badwords">Bad Words</a>
		</div>
HTML;
	}

	if($section == "system") {
	$nav .= <<<HTML
		<div class="section-navbar">
			<a href="main.php" class="transition">Dashboard</a>
			<a href="main.php?act=general" class="transition">General</a>
			<a href="main.php?act=rooms" class="transition">Rooms</a>
			<a href="main.php?act=members" class="transition">Members</a>
			<a href="main.php?act=templates" class="transition">Themes & Templates</a>
			<a href="main.php?act=languages" class="transition">Languages</a>
			<a href="main.php?act=system" class="nav-selected">System</a>
		</div>
		<div class="section-subnav">
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
	<link href="styles/admin_style.css" type="text/css" rel="stylesheet">
	<link href="../thirdparty/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet">
	<script src="../thirdparty/jquery/jquery.min.js"></script>
	<!-- Code Mirror -->
	<script src="../thirdparty/codemirror/codemirror.js"></script>
	<link href="../thirdparty/codemirror/codemirror.css" type="text/css" rel="stylesheet">
	<script src="../thirdparty/codemirror/mode/css/css.js"></script>
	<!-- Admin JS -->
	<script src="admin.js"></script>
</head>

<body>

	<div id="topbar">
		<div class="wrapper">
			<div class="fleft">
				<a href="../" target="_blank" class="toplinks transition">&laquo; Go to your Community</a>
			</div>
			<div class="fright">
				<a href="https://github.com/brunnopleffken/addictive-community" target="_blank" class="transition">View Addictive Comunity on GitHub</a>
				<a href="https://github.com/brunnopleffken/addictive-community/issues" target="_blank" class="transition">Issues</a>
				<a href="https://github.com/brunnopleffken/addictive-community/blob/master/CHANGELOG.md" target="_blank" class="transition">Changelog</a>
			</div>
			<div class="fix"></div>
		</div>
	</div>

	<div id="logo">
		<div class="wrapper">
			<a href="main.php" title="Admin CP Dashboard"><img src="images/logo.png" class="logo-image"></a>
		</div>
	</div>

	<div class="wrapper">
		<?php echo CreateMenu($act); ?>
		<?php require_once("sources/adm_{$act}_{$p}.php"); ?>
	</div>

	<div id="footer">
		<div class="wrapper center">
			<span>Powered by Addictive Community <?php echo VERSION ?> &copy; 2014 - All rights reserved.</span>
		</div>
	</div>

</body>
</html>
