<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: index.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
## ---------------------------------------------------

// Type dot-dot is so boring...
define("ROOT", "../../");

// Load configuration files
require_once(ROOT . "init.php");

// Load kernel drivers
require(ROOT . "kernel/Html.php");
require(ROOT . "kernel/String.php");

// Check if .lock exists
if(file_exists(ROOT . "install/.lock")) {
	Html::Error("The community is locked. You're unable to run the Diagnostic Tools.<br>Please, delete the file <b>install/.lock</b> and try again.");
}

// Show template
require("diagnostics.phtml");

// Lock installer again
$status = (fopen(ROOT . "install/.lock", "w")) ? 1 : 0;
