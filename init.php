<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: init.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
## -------------------------------------------------------

// Show all kind of errors
ini_set('display_errors', 'On');
error_reporting(E_ALL);

// Set default timezone to UTC (timezones are defined by user)
date_default_timezone_set("UTC");

// Set default charset for PHP as UTF-8
// In case of an action returning JSON encoded objects
header('Content-Type: text/html; charset=utf-8');

/**
 * --------------------------------------------------------------------
 * ADDICTIVE COMMUNITY VERSION
 * --------------------------------------------------------------------
 */
define("VERSION", "v0.6.0");
define("CHANNEL", "Beta"); // e.g.: Alpha, Beta, Release Candidate, Final
define("CODENAME", "Nile"); // Version codename :)

/**
 * --------------------------------------------------------------------
 * TIME DEFINITION CONSTANTS
 * --------------------------------------------------------------------
 */
define("SECONDS", 1);
define("MINUTE", 60);
define("HOUR", 3600);
define("DAY", 86400);
define("WEEK", 604800);
define("MONTH", 2592000);
define("YEAR", 31536000);

/**
 * --------------------------------------------------------------------
 * The awesome internationalization function!
 * Translation INDEX is provided in $string to locate the corresponding
 * translated string. If the INDEX does not exists, the value in
 * $string is treated simply as string (shortcut for "echo"). ;)
 * --------------------------------------------------------------------
 */
function __($string, $variables = array())
{
	echo i18n::Translate($string, $variables);
}
