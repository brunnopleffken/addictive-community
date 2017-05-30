<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  https://github.com/addictivehub/addictive-community
#
#  File: index.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## -------------------------------------------------------

use \AC\Kernel\Html;

// Type dot-dot is so boring...
define("ROOT", "../../");

// Load configuration files
require_once(ROOT . "init.php");

// Load kernel drivers
require(ROOT . "kernel/Html.php");
require(ROOT . "kernel/Text.php");

// Check if .lock exists
if(file_exists(ROOT . "install/.lock")) {
	Html::throwError("The community is locked. You're unable to run the Diagnostic Tools.<br>Please, delete the file <b>install/.lock</b> and try again.");
}

// Show template
require("diagnostics.phtml");

// Lock installer again
$status = (fopen(ROOT . "install/.lock", "w")) ? 1 : 0;
