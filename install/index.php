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

	require("../init.php");
	require("../kernel/Html.php");
	require("../kernel/Database.php");

	define("MIN_PHP_VERSION", 5.3);
	define("MIN_SQL_VERSION", 5.1);

	/**
	 * --------------------------------------------------------------------
	 * INSTALLER CLASS INHERITS THE MAIN DATABASE CLASS
	 * --------------------------------------------------------------------
	 */

	class Installer extends Database
	{
		public $input = array();

		public function InstallerDB()
		{
			$this->Connect($this->input);
		}
	}

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
			$button = "<input type='button' value='Proceed' onclick='javascript:eula()'>";

			// Check if installer is locked

			if(file_exists(".lock")) {
				$disabled = "disabled";
				$notification = Html::Notification(
					"Installer is locked! Please, remove the file <b>install/.lock</b> to proceed.", "failure", true
				);
				$button  = "<input type='button' value='Proceed' disabled>";
			}

			// Get EULA

			if(file_exists("eula.txt") && $step == 1) {
				$eula = file_get_contents("eula.txt");
			}

			$template = <<<HTML
				<div class="step-box">
					<div class="current"><h3>Step 1</h3><span class="tiny">EULA</span></div>
					<div class="next"><h3>Step 2</h3><span class="tiny">Requirements</span></div>
					<div class="next"><h3>Step 3</h3><span class="tiny">Database Settings</span></div>
					<div class="next"><h3>Step 4</h3><span class="tiny">Community Settings</span></div>
					<div class="next"><h3>Step 5</h3><span class="tiny">Install</span></div>
				</div>

				{$notification}

				<form method="post" name="install">
					<div style="text-align: center">
						<textarea style="width: 550px; height: 300px; margin-bottom: 20px" readonly>{$eula}</textarea>
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

			// Check system environment
			// Show notification message about tables prefixes
			$mysql_information = Html::Notification(
				"MySQL version will be checked in step 4.", "info", true
			);

			$info['php-version'] = PHP_VERSION;
			$info['memory-limit'] = @ini_get("memory_limit");

			$php_v = version_compare($info['php-version'], MIN_PHP_VERSION);
			$php_check = ($php_v >= 0) ? "<span style='color: #090'>Yes ({$info['php-version']})</span>" : "<span style='color: #900'>No ({$info['php-version']})</span>";

			$environment = "<table class='table' style='width: 400px;'>";
			$environment .= "<tr><td style='width:190px'>Server Software</td><td>{$_SERVER['SERVER_SOFTWARE']} {$_SERVER['SERVER_PROTOCOL']}</td></tr>";
			$environment .= "<tr><td>PHP 5.3+</td><td>{$php_check}</td></tr>";
			$environment .= "</table>";

			// Check PHP extensions

			$extensions = get_loaded_extensions();
			$required = array("gd", "libxml", "json");

			$ext_name = array(
				"gd"     => "GD Library",
				"libxml" => "DOM XML Handling",
				"json"   => "JSON Support"
			);

			$extensions_ok = "<table class='table' style='width: 300px'>";

			foreach($required as $data) {
				$status = (in_array($data, $extensions)) ? "<span style='color: #090'>Yes</span>" : "<span style='color: #c00'>No</span>";
				$extensions_ok .= "<tr><td style='width:190px'>" . $ext_name[$data] . " ({$data})</td><td>{$status}</td></tr>";
			}

			$extensions_ok .= "</table>";

			// Check Apache extensions

			if(apache_get_modules()) {
				if(in_array("mod_rewrite", apache_get_modules())) {
					$mod_rewrite_ok = "<span style='color: #090'>Yes</span>";
				}
				else {
					$mod_rewrite_ok = "<span style='color: #c00'>No</span>";
					$disabled = "disabled='disabled'";
				}
			}
			else {
				$mod_rewrite_ok = "<span style='color: #ddd'>Not using Apache server</span>";
			}

			$apache_extensions = "<table class='table' style='width: 300px'>";
			$apache_extensions .= "<tr><td style='width:190px'>mod_rewrite</td><td>{$mod_rewrite_ok}</td></tr>";
			$apache_extensions .= "</table>";

			// Check folders
			$disabled = "";

			// root/config.php
			if(is_writable("../config.php")) {
				if(filesize("../config.php") == 0) {
					$file_conf = "<span style='color: #090'>Writable</span>";
				}
				else {
					$file_conf = "<span style='color: #C00'>File not empty</span>";
					$disabled = "disabled='disabled'";
				}
			}
			else {
				$file_conf = "<span style='color: #C00'>Not writable</span>";
				$disabled = "disabled='disabled'";
			}

			// root/install
			if(is_writable("../install")) {
				$dir_install = "<span style='color: #090'>Writable</span>";
			}
			else {
				$dir_install = "<span style='color: #C00'>Not writable</span>";
				$disabled = "disabled='disabled'";
			}

			// root/public/attachments
			if(is_writable("../public/attachments/")) {
				$dir_attach = "<span style='color: #090'>Writable</span>";
			}
			else {
				$dir_attach = "<span style='color: #C00'>Not writable</span>";
				$disabled = "disabled='disabled'";
			}

			// root/public/avatar
			if(is_writable("../public/avatar/")) {
				$dir_avatar = "<span style='color: #090'>Writable</span>";
			}
			else {
				$dir_avatar = "<span style='color: #C00'>Not writable</span>";
				$disabled = "disabled='disabled'";
			}

			$folders = "<table class='table' style='width: 300px'>";
			$folders .= "<tr><td style='width:190px'>/config.php</td><td>{$file_conf}</td></tr>";
			$folders .= "<tr><td>/install</td><td>{$dir_install}</td></tr>";
			$folders .= "<tr><td>/public/attachments</td><td>{$dir_attach}</td></tr>";
			$folders .= "<tr><td>/public/avatar</td><td>{$dir_avatar}</td></tr>";
			$folders .= "</table>";

			if($disabled != "") {
				$notification = Html::Notification(
					"There are still some things to do on your server environment.", "failure", true
				);
			}
			else {
				$notification = "";
			}

			// Do template!

			$template = <<<HTML
				<div class="step-box">
					<div class="previous"><h3>Step 1</h3><span class="tiny">EULA</span></div>
					<div class="current"><h3>Step 2</h3><span class="tiny">Requirements</span></div>
					<div class="next"><h3>Step 3</h3><span class="tiny">Database Settings</span></div>
					<div class="next"><h3>Step 4</h3><span class="tiny">Community Settings</span></div>
					<div class="next"><h3>Step 5</h3><span class="tiny">Install</span></div>
				</div>

				{$mysql_information}
				{$notification}

				<form action="index.php?step=3" method="post">
					<div class="input-box">
						<h4>System Environment</h4>
						{$environment}
					</div>
					<div class="input-box">
						<h4>PHP Extensions</h4>
						{$extensions_ok}
					</div>
					<div class="input-box">
						<h4>Apache Modules</h4>
						{$apache_extensions}
					</div>
					<div class="input-box">
						<h4>Files and Folders</h4>
						{$folders}
					</div>
					<div class="input-box" style="text-align: center">
						<input type="button" value="Proceed" onclick="javascript:window.location.replace('index.php?step=3')" {$disabled}>
					</div>
				</form>
HTML;

			break;

		/**
		 * --------------------------------------------------------------------
		 * STEP 3
		 * --------------------------------------------------------------------
		 */

		case 3:

			// Second barrier to stop any unwanted reinstall

			if(file_exists(".lock")) {
				echo Html::Notification(
					"Installer is locked! Please, remove the file <b>install/.lock</b> to proceed.", "failure", true
				);
				exit;
			}

			// Show notification message about tables prefixes
			$notification = Html::Notification(
				"Don't worry, all tables are prefixed with <b>c_</b>.", "info", true
			);

			// Ok, proceed...

			$template = <<<HTML
				<div class="step-box">
					<div class="previous"><h3>Step 1</h3><span class="tiny">EULA</span></div>
					<div class="previous"><h3>Step 2</h3><span class="tiny">Requirements</span></div>
					<div class="current"><h3>Step 3</h3><span class="tiny">Database Settings</span></div>
					<div class="next"><h3>Step 4</h3><span class="tiny">Community Settings</span></div>
					<div class="next"><h3>Step 5</h3><span class="tiny">Install</span></div>
				</div>

				{$notification}

				<form action="index.php?step=4" method="post" id="database-form">
					<div class="input-box">
						<div class="label">MySQL Host</div>
						<div class="field"><input type="text" name="host" class="required small"></div>
					</div>
					<div class="input-box">
						<div class="label">MySQL Port</div>
						<div class="field"><input type="text" name="port" value="3306" class="required tiny"></div>
					</div>
					<div class="input-box">
						<div class="label">Database</div>
						<div class="field"><input type="text" name="database" class="required small"></div>
					</div>
					<div class="input-box">
						<div class="label">Username</div>
						<div class="field"><input type="text" name="username" class="required small"></div>
					</div>
					<div class="input-box">
						<div class="label">Password</div>
						<div class="field"><input type="password" name="password" class="small"></div>
					</div>
					<div class="input-box" style="text-align: center"><input type="submit" value="Proceed"></div>
				</form>
HTML;

			break;

		/**
		 * --------------------------------------------------------------------
		 * STEP 4
		 * --------------------------------------------------------------------
		 */

		case 4:

		session_start();
		
			$installer = new Installer();		
			$_SESSION['db_server']   = $installer->input['db_server']   = $_REQUEST['host'];
			$_SESSION['db_database'] = $installer->input['db_database'] = $_REQUEST['database'];
			$_SESSION['db_username'] = $installer->input['db_username'] = $_REQUEST['username'];
			$_SESSION['db_password'] = $installer->input['db_password'] = $_REQUEST['password'];
			$_SESSION['db_port']     = $installer->input['db_port']     = $_REQUEST['port'];

			// Connect to database and get information
			$installer->InstallerDB();
	
			$dir = str_replace("install", "", getcwd());
			$url = str_replace("install/index.php", "", $_SERVER['HTTP_REFERER']);
			$url = preg_replace("#\?(.+?)*#", "", $url);

			// Get and compare MySQL version
			$installer->Query("SELECT VERSION() AS mysql_version;");
			$result = $installer->Fetch();
			preg_match("#[0-9]+\.[0-9]+\.[0-9]+#", $result['mysql_version'], $mysql_version);
			$info['mysql-version'] = $mysql_version[0];
			$sql_v = version_compare($info['mysql-version'], MIN_SQL_VERSION);
			if($sql_v >= 0) {
				// Show notification message about correct MYSQL Version
				$mysql_information = Html::Notification(
					"MySQL version is correct!", "info", true
				);
				$button_lock = "";
			}
			else {
				// Show notification message about wrong MYSQL Version
				$mysql_information = Html::Notification(
					"MySQL version is incorrect! You can't continue. Required version of MySQL is 5.1 or higher.", "info", true
				);
				$button_lock = "disabled";
			}

			// Languages
			$dir_list = array();
			$lang_list = "";
			$lang_dir = scandir("../languages");

			foreach($lang_dir as $k => $v) {
				if(strpos($v, "_") && is_dir("../languages/" . $v)) {
					$dir_list[] = $v;
				}
			}

			foreach($dir_list as $language) {
				$language_info = json_decode(file_get_contents("../languages/" . $language . "/_language.json"), true);
				$selected = ($language_info['directory'] == "en_US") ? "selected" : "";
				$lang_list .= "<option value='{$language_info['directory']}' {$selected}>{$language_info['name']}</option>";
			}

			// Timezone list
			$tz_offset = array(
				"-12" => "(UTC-12:00) International Date Line West",
				"-11" => "(UTC-11:00) Midway Island, Samoa",
				"-10" => "(UTC-10:00) Hawaii",
				"-9"  => "(UTC-09:00) Alaska",
				"-8"  => "(UTC-08:00) Pacific Time (US & Canada), Tijuana",
				"-7"  => "(UTC-07:00) Mountain Time (US & Canada), Chihuahua, La Paz",
				"-6"  => "(UTC-06:00) Central Time (US & Canada), Cental America, Ciudad de México",
				"-5"  => "(UTC-05:00) Eastern Time (US & Canada), Bogotá, Lima, Rio Branco",
				"-4"  => "(UTC-04:00) Atlantic Time (Canada), Caracas, Santiago, Manaus",
				"-3"  => "(UTC-03:00) Brasília, São Paulo, Buenos Aires, Montevideo",
				"-2"  => "(UTC-02:00) Mid-Atlantic",
				"-1"  => "(UTC-01:00) Azores, Cape Verde Is.",
				"0"   => "(UTC&#177;00:00) London, Lisboa, Reykjavík, Dublin",
				"1"   => "(UTC+01:00) Paris, Amsterdam, Berlin, Roma, Stockholm, West Central Africa",
				"2"   => "(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius",
				"3"   => "(UTC+03:00) Moscow, St. Petersburg, Nairobi, Kuwait, Baghdad",
				"4"   => "(UTC+04:00) Abu Dhabi, Baku, Muscat, Yerevan",
				"5"   => "(UTC+05:00) Islamabad, Karachi, Yekaterinburg, Tashkent",
				"5.5" => "(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi",
				"6"   => "(UTC+06:00) Astana, Dhaka, Almaty, Novosibirsk",
				"6.5" => "(UTC+06:30) Yangon (Rangoon)",
				"7"   => "(UTC+07:00) Bangkok, Hanoi, Jakarta, Krasnoyarsk",
				"8"   => "(UTC+08:00) Beijing, Hong Kong, Kuala Lumpur, Singapore, Perth, Taipei",
				"9"   => "(UTC+09:00) Tokyo, Osaka, Seoul, Yakutsk, Sapporo",
				"9.5" => "(UTC+09:30) Adelaide, Darwin",
				"10"  => "(UTC+10:00) Brisbane, Canberra, Melbourne, Sydney, Vladivostok",
				"11"  => "(UTC+11:00) Magadan, Solomon Is., New Caledonia",
				"12"  => "(UTC+12:00) Auckland, Wellington, Fiji, Marshall Is."
			);

			$tz_list = "";

			foreach($tz_offset as $tz_value => $tz_name) {
				$selected = ($tz_value == 0) ? "selected" : "";
				$tz_list .= "<option value='{$tz_value}' {$selected}>{$tz_name}</option>\n";
			}


			$template = <<<HTML
				<div class="step-box">
					<div class="previous"><h3>Step 1</h3><span class="tiny">EULA</span></div>
					<div class="previous"><h3>Step 2</h3><span class="tiny">Requirements</span></div>
					<div class="previous"><h3>Step 3</h3><span class="tiny">Database Settings</span></div>
					<div class="current"><h3>Step 4</h3><span class="tiny">Community Settings</span></div>
					<div class="next"><h3>Step 5</h3><span class="tiny">Install</span></div>
				</div>

				{$mysql_information}

				<form action="index.php?step=5" method="post">

					<div class="input-box">
						<div class="label">Community Name</div>
						<div class="field"><input type="text" name="community" class="medium"></div>
					</div>

					<h2>Default Settings</h2>

					<div class="input-box">
						<div class="label">Language</div>
						<div class="field">
							<select name="language" class="small select2">
								{$lang_list}
							</select>
						</div>
					</div>
					<div class="input-box">
						<div class="label">Timezone</div>
						<div class="field">
							<select name="timezone" class="full select2">
								{$tz_list}
							</select>
						</div>
					</div>

					<h2>Paths and URLs</h2>

					<div class="input-box">
						<div class="label">Installation Path</div>
						<div class="field"><input type="text" name="install_path" class="medium" value="{$dir}"></div>
					</div>
					<div class="input-box">
						<div class="label">Installation URL</div>
						<div class="field"><input type="text" name="install_url" class="medium" value="{$url}"></div>
					</div>

					<h2>Administrator Account</h2>

					<div class="input-box">
						<div class="label">Username</div>
						<div class="field"><input type="text" name="adm_username" class="small"></div>
					</div>
					<div class="input-box">
						<div class="label">Password</div>
						<div class="field"><input type="password" name="adm_password" id="adm_password" class="small"></div>
					</div>
					<div class="input-box">
						<div class="label">Re-type Password</div>
						<div class="field"><input type="password" name="adm_password2" id="adm_password2" class="small" onblur="javascript:checkPasswordMatch()"></div>
					</div>
					<div class="input-box">
						<div class="label">E-mail</div>
						<div class="field"><input type="text" name="adm_email" class="medium"></div>
					</div>

					<div class="input-box" style="text-align: center">
						<input type="hidden" name="db_server" value="{$_SESSION['db_server']}">
						<input type="hidden" name="db_database" value="{$_SESSION['db_database']}">
						<input type="hidden" name="db_username" value="{$_SESSION['db_username']}">
						<input type="hidden" name="db_password" value="{$_SESSION['db_password']}">
						<input type="hidden" name="db_port" value="{$_SESSION['db_port']}">
						<input type="submit" value="Proceed" {$button_lock}>
					</div>

				</form>
HTML;

			session_destroy();

			break;

		/**
		 * --------------------------------------------------------------------
		 * STEP 5
		 * --------------------------------------------------------------------
		 */

		case 5:

			$template = <<<HTML
				<script type="text/javascript">
					$(document).ready(function() {
						installModule(1);
					});
				</script>

				<div class="step-box">
					<div class="previous"><h3>Step 1</h3><span class="tiny">EULA</span></div>
					<div class="previous"><h3>Step 2</h3><span class="tiny">Requirements</span></div>
					<div class="previous"><h3>Step 3</h3><span class="tiny">Database Settings</span></div>
					<div class="previous"><h3>Step 4</h3><span class="tiny">Community Settings</span></div>
					<div class="current"><h3>Step 5</h3><span class="tiny">Install</span></div>
				</div>

				<input type="hidden" id="db_server" value="{$_REQUEST['db_server']}">
				<input type="hidden" id="db_database" value="{$_REQUEST['db_database']}">
				<input type="hidden" id="db_username" value="{$_REQUEST['db_username']}">
				<input type="hidden" id="db_password" value="{$_REQUEST['db_password']}">
				<input type="hidden" id="db_port" value="{$_REQUEST['db_port']}">
				<input type="hidden" id="community_name" value="{$_REQUEST['community']}">
				<input type="hidden" id="community_path" value="{$_REQUEST['install_path']}">
				<input type="hidden" id="community_url" value="{$_REQUEST['install_url']}">
				<input type="hidden" id="default_language" value="{$_REQUEST['language']}">
				<input type="hidden" id="default_timezone" value="{$_REQUEST['timezone']}">
				<input type="hidden" id="admin_username" value="{$_REQUEST['adm_username']}">
				<input type="hidden" id="admin_password" value="{$_REQUEST['adm_password']}">
				<input type="hidden" id="admin_email" value="{$_REQUEST['adm_email']}">

				<h2>Installation Progress</h2>

				<div id="log" style="line-height: 1.4em">
					<div class="step1">Saving configuration file... <span class="ok">OK</span><span class="failed">FAILED</span></div>
					<div class="step2">Checking saved information and connecting to database... <span class="ok">OK</span><span class="failed">FAILED</span></div>
					<div class="step3">Extracting table structure... <span class="ok">OK</span><span class="failed">FAILED</span></div>
					<div class="step4">Inserting initial data and settings... <span class="ok">OK</span><span class="failed">FAILED</span></div>
					<div class="step5">Saving user information... <span class="ok">OK</span><span class="failed">FAILED</span></div>
					<div class="step6">Locking installer... <span class="ok">OK</span><span class="failed">FAILED</span></div>
					<input type="submit" class="default-button" value="Let's Go!" style="margin-top: 10px" onclick="window.location='../index.php'">
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
	<link rel="stylesheet" href="../thirdparty/select2/select2.css">
	<link href="../themes/default-light/css/main.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="../thirdparty/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="../thirdparty/select2/select2.js"></script>
	<script type="text/javascript" src="../resources/main.js"></script>
	<!-- Community Installer -->
	<script type="text/javascript" src="installer.js"></script>

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
