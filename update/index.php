<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: index.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

/**
 * --------------------------------------------------------------------
 * INITIALIZATION INFORMATION
 * --------------------------------------------------------------------
 */

require("../config.php");
require("../init.php");
require("../kernel/Admin.php");
require("../kernel/Database.php");
require("../kernel/Html.php");
require("../kernel/Text.php");

define("MIN_PHP_VERSION", 5.3);
define("MIN_SQL_VERSION", 5.1);

$Db = new Database($config);
$Admin = new Admin($Db);

/**
 * --------------------------------------------------------------------
 * WHICH STEP IS THE USER IN
 * --------------------------------------------------------------------
 */

if(isset($_REQUEST['step'])) {
	$step = $_REQUEST['step'];
}
else {
	$step = 1;
}

/**
 * --------------------------------------------------------------------
 * BUILD INSTALLER
 * --------------------------------------------------------------------
 */

switch($step){

	/**
	 * --------------------------------------------------------------------
	 * STEP 1
	 * --------------------------------------------------------------------
	 */

	case 1:

		$disabled = "";
		$notification = "";
		$button = "<input type='button' value='Proceed' onclick='javascript:updater_eula()'>";

		// Check if installer is locked
		if(file_exists("../install/.lock")) {
			$disabled = "disabled";
			$notification = Html::Notification(
				"Installer is locked! Please, remove the file <b>install/.lock</b> to proceed with the update.", "failure", true
			);
			$button  = "<input type='button' value='Proceed' disabled>";
		}

		// Get EULA
		if(file_exists("../LICENSE") && $step == 1) {
			$eula = file_get_contents("../LICENSE");
		}

		$template = <<<HTML
			<div class="step-box">
				<div class="current"><h3>Step 1</h3><span class="tiny">EULA</span></div>
				<div class="next"><h3>Step 2</h3><span class="tiny">Overview</span></div>
				<div class="next"><h3>Step 3</h3><span class="tiny">Update</span></div>
			</div>

			{$notification}

			<form method="post" name="install">
				<div style="text-align: center">
					<textarea style="width: 580px; height: 300px; margin-bottom: 20px; font-family: Consolas" readonly>{$eula}</textarea>
				</div>
				<div class="input-box" style="text-align: center">
					<input type="checkbox" id="agree" {$disabled}> I agree with the End User Licence Agreement
				</div>
				<div class="input-box" style="text-align: center">
					{$button}
				</div>
			</form>
HTML;

		break;

	/**
	 * --------------------------------------------------------------------
	 * STEP 2
	 * --------------------------------------------------------------------
	 */

	case 2:

		// Second barrier to stop any unwanted reinstall
		if(file_exists("../.lock")) {
			echo Html::Notification(
				"Installer is locked! Please, remove the file <b>install/.lock</b> to proceed.", "failure", true
			);
			exit;
		}

		// Get versions
		$version_from = $Admin->SelectConfig("community_version");
		$version_to = VERSION;

		// Show warning message
		$warning = Html::Notification(
			"You should be certain that you have a complete database backup before proceeding. By continuing, you're certifying that you have saved a database backup.", "warning", true
		);

		if($version_from == $version_to) {
			$is_update = false;
			$warning = Html::Notification(
				"There are no updates available.", "failure", true
			);
		}
		else {
			$is_update = true;
		}

		// Check system environment

		if($is_update) {
			$Db->Query("SELECT VERSION() AS mysql_version;");
			$result = $Db->Fetch();

			preg_match("#[0-9]+\.[0-9]+\.[0-9]+#", $result['mysql_version'], $mysql_version);

			$info['php-version'] = PHP_VERSION;
			$info['mysql-version'] = $mysql_version[0];
			$info['memory-limit'] = @ini_get("memory_limit");

			$php_v = version_compare($info['php-version'], MIN_PHP_VERSION);
			$php_check = ($php_v >= 0) ? "<span style='color: #090'>Yes ({$info['php-version']})</span>" : "<span style='color: #900'>No ({$info['php-version']})</span>";

			$sql_v = version_compare($info['mysql-version'], MIN_SQL_VERSION);
			$sql_check = ($sql_v >= 0) ? "<span style='color: #090'>Yes ({$info['mysql-version']})</span>" : "<span style='color: #900'>No ({$info['mysql-version']})</span>";

			$environment = "<table class='table' style='width: 100%'>";
			$environment .= "<tr><td style='width: 130px'>Server Software</td><td>{$_SERVER['SERVER_SOFTWARE']} {$_SERVER['SERVER_PROTOCOL']}</td></tr>";
			$environment .= "<tr><td>PHP 5.3+</td><td>{$php_check}</td></tr>";
			$environment .= "<tr><td>MySQL 5.1+</td><td>{$sql_check}</td></tr>";
			$environment .= "</table>";

			// Ok, proceed...

			$template = <<<HTML
				<div class="step-box">
					<div class="previous"><h3>Step 1</h3><span class="tiny">EULA</span></div>
					<div class="current"><h3>Step 2</h3><span class="tiny">Overview</span></div>
					<div class="next"><h3>Step 3</h3><span class="tiny">Update</span></div>
				</div>

				{$warning}

				<div class="input-box">
					<h3>You are going to upgrade from {$version_from} to {$version_to}.</h3>
				</div>

				<div class="input-box">
					<h4>System Requirements</h4>
					{$environment}
				</div>

				<div class="center" style="padding: 10px 0">
					<a href="index.php?step=3" class="default-button">Update!</a>
				</div>
HTML;
		}
		else {
			$template = <<<HTML
				<div class="step-box">
					<div class="previous"><h3>Step 1</h3><span class="tiny">EULA</span></div>
					<div class="current"><h3>Step 2</h3><span class="tiny">Overview</span></div>
					<div class="next"><h3>Step 3</h3><span class="tiny">Update</span></div>
				</div>

				{$warning}
HTML;
		}

		break;

	/**
	 * --------------------------------------------------------------------
	 * STEP 3
	 * --------------------------------------------------------------------
	 */

	case 3:

		// Get migration model file
		require_once("migration_model.php");

		// Get current Addictive Community version
		$version_from = $Admin->SelectConfig("community_version");

		// Get corresponding migration start file (from [current version] to [latest version])
		$files = glob("migrations/" . $version_from . "-*");

		// Search for migration steps to proceed
		$migration_start_key = array_search($files[0], $migrations);
		$migrations_to_execute = serialize(array_slice($migrations, $migration_start_key));

		$template = <<<HTML
			<script language="text/javascript">
				$(document).ready(function() {
					updateModule(1);
				});
			</script>

			<div class="step-box">
				<div class="previous"><h3>Step 1</h3><span class="tiny">EULA</span></div>
				<div class="previous"><h3>Step 2</h3><span class="tiny">Overview</span></div>
				<div class="current"><h3>Step 3</h3><span class="tiny">Update</span></div>
			</div>

			<input type="hidden" id="migration_array" name="migration_array" value='{$migrations_to_execute}'>
			<input type="hidden" id="version_from" name="version_from" value='{$version_from}'>

			<h2>Update Progress</h2>

			<div id="log" style="line-height: 1.4em">
				<div class="step1">Connecting to database... <span class="ok">OK</span><span class="failed">FAILED</span></div>
				<div class="step2">Setting community to Offline Mode... <span class="ok">OK</span><span class="failed">FAILED</span></div>
				<div class="step3">Updating table structure and data... <span class="ok">OK</span><span class="failed">FAILED</span></div>
				<div class="step4">Locking installer... <span class="ok">OK</span><span class="failed">FAILED</span></div>
				<div class="step5">Setting community back online... <span class="ok">OK</span><span class="failed">FAILED</span></div>
				<input type="submit" class="default-button" value="Done!" style="margin-top: 10px" onclick="window.location='../index.php'">
			</div>
HTML;

			break;

 }

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Addictive Community</title>
	<!-- Common Files -->
	<link href="../themes/default-light/css/main.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="../thirdparty/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="../thirdparty/select2/select2.js"></script>
	<script type="text/javascript" src="../resources/main.js"></script>
	<!-- Community Installer -->
	<script type="text/javascript" src="../install/installer.js"></script>
	<script type="text/javascript" src="updater.js"></script>

	<style type="text/css">
		/* Default styles override */
		h2 { margin-top: 20px; }
		#logo { text-align: center; }

		/* New installer styles */
		#log > div { display: none; }
		#log .ok { color: #090; display: none; }
		#log .failed { color: #d00; display: none; }
		#log input { display: none; }
	</style>
</head>

<body>
	<div id="topbar">
		<div class="wrapper">
			<div class="fleft"><a href="https://github.com/brunnopleffken/addictive-community" target="_blank" class="transition">View Addictive Community on GitHub</a></div>
			<div class="fright"><a href="../" target="_blank" class="transition">Go back to your community</a></div>
			<div class="fix"></div>
		</div>
	</div>

	<div class="wrapper">
		<div id="logo">
			<img src="../themes/default-light/images/logo.png" alt="" height="50">
		</div>
		<div class="box" id="content" style="width: 700px; margin: auto">
			<?php echo $template ?>
		</div>
	</div>

	<div id="footer">
		<div class="wrapper center">
			Powered by Addictive Community <?php echo VERSION . "-" . CHANNEL ?> &copy; <?php echo date("Y") ?> - All rights reserved.
		</div>
	</div>
</body>
</html>
