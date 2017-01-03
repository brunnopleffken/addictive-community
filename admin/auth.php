<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: auth.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Http;
use \AC\Kernel\Text;

// Include files...
require_once("../kernel/Text.php");
require_once("../kernel/Http.php");
require_once("../kernel/Database.php");

// Load configuration file
$config = parse_ini_file("../config.ini");

// Load MySQL driver and connect
$Db = new Database();
$Db->Connect($config);

// Get security hash key
$Db->Query("SELECT * FROM c_config c WHERE field = 'security_salt_hash' OR field = 'security_salt_key';");
$_salt = $Db->FetchToArray();

$salt = array(
	"hash" => $_salt[0]['value'],
	"key"  => $_salt[1]['value']
);

// Get form information

if(Http::Request("username") && Http::Request("password")) {
	$username = Http::Request("username");
	$password = Text::Encrypt(Http::Request("password"), $salt);
}

// Check if user exists

$Db->Query("SELECT m_id, username, usergroup FROM c_members WHERE username = '{$username}' AND password = '{$password}';");

if($Db->Rows() != 1) {
	header("Location: index.php?error=1");
	exit;
}
else {
	$info = $Db->Fetch();

	if($info['usergroup'] != 1) {
		header("Location: index.php?error=4");
		exit;
	}

	session_start();
	$_SESSION['admin_m_id']     = $info['m_id'];
	$_SESSION['admin_username'] = $info['username'];
	$_SESSION['admin_time']     = time();

	header("Location: main.php");
}
