<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: index.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// --------------------------------------------
	// Initialization information
	// --------------------------------------------

	require_once("../init.php");
	require_once("../kernel/class.html.php");
	require_once("../kernel/class.database.php");

	$init = new Init();

	define("MIN_PHP_VERSION", 5.3);
	define("MIN_SQL_VERSION", 5.1);

	// --------------------------------------------
	// Installer class inherits main Database class
	// --------------------------------------------

	class Installer extends Database
	{
		public $input = array();

		public function InstallerDB()
		{
			$this->mysql['host']     = $this->input['db_server'];
			$this->mysql['user']     = $this->input['db_username'];
			$this->mysql['password'] = $this->input['db_password'];
			$this->mysql['dbase']    = $this->input['db_database'];

			$config = $this->input;
			$this->_Connect($config);
		}
	}

	// --------------------------------------------
	// Which step is the user in
	// --------------------------------------------

	if(isset($_REQUEST['step'])) {
		$step = $_REQUEST['step'];
	}
	else {
		$step = 1;
	}

	if(file_exists("eula.txt") && $step == 1) {
		$eula = file_get_contents("eula.txt");
	}


// --------------------------------------------
// Build installer
// --------------------------------------------

switch($step)
{

	// --------------------------------------------
	// Step 1
	// --------------------------------------------

	case 1:

		$tpl = <<<HTML
		<div class="step-box">
			<div class="step-box-current"><h3>Step 1</h3><span class="tiny">EULA</span></div>
			<div class="step-box-next"><h3>Step 2</h3><span class="tiny">Database Settings</span></div>
			<div class="step-box-next"><h3>Step 3</h3><span class="tiny">Requirements</span></div>
			<div class="step-box-next"><h3>Step 4</h3><span class="tiny">Community Settings</span></div>
			<div class="step-box-next"><h3>Step 5</h3><span class="tiny">Install</span></div>
		</div>

		<form method="post" name="install">
			<div style="text-align: center">
				<textarea style="width: 550px; height: 300px; margin-bottom: 20px" readonly>{$eula}</textarea>
			</div>
			<div class="input-box" style="text-align: center">
				<label><input type="checkbox" id="agree"> I agree with the End User Licence Agreement</label>
			</div>
			<div class="input-box" style="text-align: center">
				<input type="button" value="Proceed" onclick="javascript:eula()">
			</div>
		</form>
HTML;

		break;

	// --------------------------------------------
	// Step 2
	// --------------------------------------------

	case 2:

		$tpl = <<<HTML
		<div class="step-box">
			<div class="step-box-previous"><h3>Step 1</h3><span class="tiny">EULA</span></div>
			<div class="step-box-current"><h3>Step 2</h3><span class="tiny">Database Settings</span></div>
			<div class="step-box-next"><h3>Step 3</h3><span class="tiny">Requirements</span></div>
			<div class="step-box-next"><h3>Step 4</h3><span class="tiny">Community Settings</span></div>
			<div class="step-box-next"><h3>Step 5</h3><span class="tiny">Install</span></div>
		</div>

		<form action="index.php?step=3" method="post" class="validate">

			<div class="input-box">
				<div class="input-box-label">MySQL Host</div>
				<div class="input-box-field"><input type="text" name="host" class="required medium"></div>
			</div>
			<div class="input-box">
				<div class="input-box-label">Database</div>
				<div class="input-box-field"><input type="text" name="database" class="required small"></div>
			</div>
			<div class="input-box">
				<div class="input-box-label">Username</div>
				<div class="input-box-field"><input type="text" name="username" class="required small"></div>
				<div class="input-box-label">Password</div>
				<div class="input-box-field"><input type="password" name="password" class="required small"></div>
			</div>
			<div class="input-box" style="text-align: center"><input type="submit" value="Proceed"></div>

		</form>
HTML;

		break;

	// --------------------------------------------
	// Step 3
	// --------------------------------------------

	case 3:

		session_start();

		// Connect to database and get information
		$installer = new Installer;

		$_SESSION['db_server']   = $installer->input['db_server']   = $_REQUEST['host'];
		$_SESSION['db_database'] = $installer->input['db_database'] = $_REQUEST['database'];
		$_SESSION['db_username'] = $installer->input['db_username'] = $_REQUEST['username'];
		$_SESSION['db_password'] = $installer->input['db_password'] = $_REQUEST['password'];

		$installer->InstallerDB();

		$installer->Query("SELECT VERSION() AS mysql_version;");
		$result = $installer->Fetch();

		// Check system environment

		preg_match('#[0-9]+\.[0-9]+\.[0-9]+#', $result['mysql_version'], $mysql_version);

		$info['php-version'] = PHP_VERSION;
		$info['mysql-version'] = $mysql_version[0];
		$info['memory-limit'] = @ini_get("memory_limit");

		$php_v = version_compare($info['php-version'], MIN_PHP_VERSION);
		$php_check = ($php_v >= 0) ? "<span style=\"color: #090\">Yes ({$info['php-version']})</span>" : "<span style=\"color: #900\">No ({$info['php-version']})</span>";

		$sql_v = version_compare($info['mysql-version'], MIN_SQL_VERSION);
		$sql_check = ($sql_v >= 0) ? "<span style=\"color: #090\">Yes (v{$info['mysql-version']})</span>" : "<span style=\"color: #900\">No ({$info['mysql-version']})</span>";

		$environment = "<table class=\"table\" style=\"width: 400px;\">";
		$environment .= "<tr><td>Server Software</td><td>{$_SERVER['SERVER_SOFTWARE']} {$_SERVER['SERVER_PROTOCOL']}</td></tr>";
		$environment .= "<tr><td>PHP 5.3+</td><td>{$php_check}</td></tr>";
		$environment .= "<tr><td>MySQL 5.1+</td><td>{$sql_check}</td></tr>";
		$environment .= "</table>";

		// Check extensions

		$extensions = get_loaded_extensions();
		$required = array("gd", "libxml", "json");

		$ext_name = array(
			"gd"		=> "GD Library",
			"libxml"	=> "DOM XML Handling",
			"json"		=> "JSON Support"
		);

		$extensions_ok = "<table class=\"table\" style=\"width: 300px\">";

		foreach($required as $data) {
			if(in_array($data, $extensions)) {
				$status = "<span style=\"color: #090\">Yes</span>";
			}
			else {
				$status = "<span style=\"color: #C00\">No</span>";
			}

			$extensions_ok .= "<tr><td>" . $ext_name[$data] . " ({$data})</td><td>{$status}</td></tr>";
		}

		$extensions_ok .= "</table>";

		// Check folders
		$disabled = "";

		// root/config.php
		if(is_writable("../config.php")) {
			$file_conf = "<span style=\"color: #090\">Writable</span>";
		}
		else {
			$file_conf = "<span style=\"color: #C00\">Not writable</span>";
			$disabled = "disabled='disabled'";
		}

		// root/install
		if(is_writable("../install")) {
			$dir_install = "<span style=\"color: #090\">Writable</span>";
		}
		else {
			$dir_install = "<span style=\"color: #C00\">Not writable</span>";
			$disabled = "disabled='disabled'";
		}

		// root/public/attachments
		if(is_writable("../uploads/")) {
			$dir_uploads = "<span style=\"color: #090\">Writable</span>";
		}
		else {
			$dir_uploads = "<span style=\"color: #C00\">Not writable</span>";
			$disabled = "disabled='disabled'";
		}

		// root/public/attachments
		if(is_writable("../public/attachments/")) {
			$dir_attach = "<span style=\"color: #090\">Writable</span>";
		}
		else {
			$dir_attach = "<span style=\"color: #C00\">Not writable</span>";
			$disabled = "disabled='disabled'";
		}

		// root/public/avatar
		if(is_writable("../public/avatar/")) {
			$dir_avatar = "<span style=\"color: #090\">Writable</span>";
		}
		else {
			$dir_avatar = "<span style=\"color: #C00\">Not writable</span>";
			$disabled = "disabled='disabled'";
		}

		$folders = "<table class=\"table\" style=\"width: 300px\">";
		$folders .= "<tr><td>/config.php</td><td>{$file_conf}</td></tr>";
		$folders .= "<tr><td>/install</td><td>{$dir_install}</td></tr>";
		$folders .= "<tr><td>/public/attachments</td><td>{$dir_attach}</td></tr>";
		$folders .= "<tr><td>/public/avatar</td><td>{$dir_avatar}</td></tr>";
		$folders .= "<tr><td>/uploads</td><td>{$dir_uploads}</td></tr>";
		$folders .= "</table>";


		// Do template!

		$tpl = <<<HTML
		<div class="step-box">
			<div class="step-box-previous"><h3>Step 1</h3><span class="tiny">EULA</span></div>
			<div class="step-box-previous"><h3>Step 2</h3><span class="tiny">Database Settings</span></div>
			<div class="step-box-current"><h3>Step 3</h3><span class="tiny">Requirements</span></div>
			<div class="step-box-next"><h3>Step 4</h3><span class="tiny">Community Settings</span></div>
			<div class="step-box-next"><h3>Step 5</h3><span class="tiny">Install</span></div>
		</div>

		<form action="index.php?step=3" method="post">

			<div class="input-box">
				<h4>System Environment</h4>
				{$environment}
			</div>

			<div class="input-box">
				<h4>Extensions</h4>
				{$extensions_ok}
			</div>

			<div class="input-box">
				<h4>Files and Folders</h4>
				{$folders}
			</div>

			<div class="input-box" style="text-align: center"><input type="button" value="Proceed" onclick="javascript:window.location.replace('index.php?step=4')" {$disabled}></div>

		</form>
HTML;

		break;

	// --------------------------------------------
	// Step 4
	// --------------------------------------------

	case 4:

		session_start();

		$dir = str_replace("install", "", getcwd());
		$url = str_replace("install/index.php", "", $_SERVER['HTTP_REFERER']);
		$url = preg_replace("#\?(.+?)*#", "", $url);

		$tpl = <<<HTML
		<div class="step-box">
			<div class="step-box-previous"><h3>Step 1</h3><span class="tiny">EULA</span></div>
			<div class="step-box-previous"><h3>Step 2</h3><span class="tiny">Database Settings</span></div>
			<div class="step-box-previous"><h3>Step 3</h3><span class="tiny">Requirements</span></div>
			<div class="step-box-current"><h3>Step 4</h3><span class="tiny">Community Settings</span></div>
			<div class="step-box-next"><h3>Step 5</h3><span class="tiny">Install</span></div>
		</div>

		<form action="index.php?step=5" method="post">

			<div class="input-box">
				<div class="input-box-label">Community Name</div>
				<div class="input-box-field"><input type="text" name="community" class="medium"></div>
			</div>

			<h2>Paths and URLs</h2><br>

			<div class="input-box">
				<div class="input-box-label">Installation Path</div>
				<div class="input-box-field"><input type="text" name="adm_username" class="medium" value="{$dir}"></div>
			</div>
			<div class="input-box">
				<div class="input-box-label">Installation URL</div>
				<div class="input-box-field"><input type="text" name="adm_username" class="medium" value="{$url}"></div>
			</div>

			<h2>Administrator Account</h2><br>

			<div class="input-box">
				<div class="input-box-label">Username</div>
				<div class="input-box-field"><input type="text" name="adm_username" class="small"></div>
			</div>
			<div class="input-box">
				<div class="input-box-label">Password</div>
				<div class="input-box-field"><input type="password" name="adm_password" id="adm_password" class="small"></div>
				<div class="input-box-label">Re-type Password</div>
				<div class="input-box-field"><input type="password" name="adm_password2" id="adm_password2" class="small" onblur="javascript:checkPasswordMatch()"></div>
			</div>
			<div class="input-box">
				<div class="input-box-label">E-mail</div>
				<div class="input-box-field"><input type="text" name="adm_email" class="medium"></div>
			</div>

			<div class="input-box" style="text-align: center">
				<input type="hidden" name="db_server" value="{$_SESSION['db_server']}">
				<input type="hidden" name="db_database" value="{$_SESSION['db_database']}">
				<input type="hidden" name="db_username" value="{$_SESSION['db_username']}">
				<input type="hidden" name="db_password" value="{$_SESSION['db_password']}">
				<input type="submit" value="Proceed">
			</div>

		</form>
HTML;

		session_destroy();

		break;

	// --------------------------------------------
	// Step 5
	// --------------------------------------------

	case 5:
		$tpl = <<<HTML
		<script type="text/javascript">
			$(document).ready(function() {
				installModule(1);
			});
		</script>

		<div class="step-box">
			<div class="step-box-previous"><h3>Step 1</h3><span class="tiny">EULA</span></div>
			<div class="step-box-previous"><h3>Step 2</h3><span class="tiny">Database Settings</span></div>
			<div class="step-box-previous"><h3>Step 3</h3><span class="tiny">Requirements</span></div>
			<div class="step-box-previous"><h3>Step 4</h3><span class="tiny">Community Settings</span></div>
			<div class="step-box-current"><h3>Step 5</h3><span class="tiny">Install</span></div>
		</div>

		<input type="hidden" id="db_server" value="{$_REQUEST['db_server']}">
		<input type="hidden" id="db_database" value="{$_REQUEST['db_database']}">
		<input type="hidden" id="db_username" value="{$_REQUEST['db_username']}">
		<input type="hidden" id="db_password" value="{$_REQUEST['db_password']}">
		<input type="hidden" id="community_name" value="{$_REQUEST['community']}">
		<input type="hidden" id="admin_username" value="{$_REQUEST['adm_username']}">
		<input type="hidden" id="admin_password" value="{$_REQUEST['adm_password']}">
		<input type="hidden" id="admin_email" value="{$_REQUEST['adm_email']}">

		<h4>Installation Progress</h4>

		<div id="log" style="line-height: 1.4em">
			<div class="step1">Saving configuration file... <span class="ok">OK</span><span class="failed">FAILED</span></div>
			<div class="step2">Checking information and connecting to database... <span class="ok">OK</span><span class="failed">FAILED</span></div>
			<div class="step3">Extracting table structure... <span class="ok">OK</span><span class="failed">FAILED</span></div>
			<div class="step4">Inserting initial data and settings... <span class="ok">OK</span><span class="failed">FAILED</span></div>
			<div class="step5">Saving user information... <span class="ok">OK</span><span class="failed">FAILED</span></div>
			<div class="step6">Locking installer... <span class="ok">OK</span><span class="failed">FAILED</span></div>
			<input type="submit" value="Let's Go!" style="margin-top: 10px">
		</div>
HTML;

		break;

}

// --------------------------------------------
// HTML template below
// --------------------------------------------

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Addictive Community</title>
	<link href="../templates/default/css/main.css" type="text/css" rel="stylesheet">
	<link href="../admin/styles/admin_style.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="../resources/jquery.min.js"></script>
	<script type="text/javascript" src="../resources/select2/select2.js"></script>
	<script type="text/javascript" src="../resources/main.js"></script>

	<script type="text/javascript">

		function eula() {
			checkbox = document.getElementById("agree");
			if(checkbox.checked == false) {
				alert("You must agree to the EULA to proceed with installation.");
				return false;
			}
			else {
				window.location.replace("index.php?step=2");
			}
		}

		function checkPasswordMatch() {
			password = document.getElementById("adm_password");
			confirm = document.getElementById("adm_password2");

			if(confirm.value != "") {
				if(confirm.value != password.value) {
					alert("Administrator passwords does not match!");
					confirm.style.background = "#FFE4E1";
				}
				else {
					confirm.style.background = "transparent";
				}
			}
		}

		// Run installer

		function installModule(id) {
			var installData = {
				db_server: $('#db_server').val(),
				db_database: $('#db_database').val(),
				db_username: $('#db_username').val(),
				db_password: $('#db_password').val(),
				community_name: $('#community_name').val(),
				admin_username: $('#admin_username').val(),
				admin_password: $('#admin_password').val(),
				admin_email: $('#admin_email').val(),
			};

			$.ajax({
				url: 'execute.php?step=' + id,
				dataType: 'json',
				type: 'post',
				data: installData,
				beforeSend: function() {
					console.log("Initializing step " + id);
					$('.step' + id).show();
				}
			})
			.done(function(data) {
				console.log(data);
				console.log("Step " + id + ", success!");

				if(data.status == 1) {
					$('.step' + id + ' .ok').show();
					id++;
					installModule(id);
				}
				else {
					$('.step' + id + ' .failed').show();
				}
			})
			.fail(function(data) {
				console.log(data);
				console.log("Step " + id + ", error!");
				$('.step' + id + ' .failed').show();
			})
			.always(function() {
				if(id == 7) {
					$('#log input').fadeIn();
				}
			});
		}
	</script>

	<style type="text/css">
		#log > div { display: none; }
		#log .ok { color: #090; display: none; }
		#log .failed { color: #d00; display: none; }
		#log input { display: none; }
	</style>
</head>

<body>

	<div id="topbar">
		<div class="wrapper">
			<div class="fleft"><a href="http://www.addictive.com.br" target="_blank" class="transition">Addictive Community Website</a></div>
			<div class="fright"></div>
			<div class="fix"></div>
		</div>
	</div>

	<div class="wrapper">
		<div id="logo">
			<div class="wrapper">
				<img src="../admin/images/logo.png" class="logo-image">
			</div>
		</div>
		<div id="content" style="width: 700px">
			<?php echo $tpl; ?>
		</div>
	</div>

	<div id="footer">
		<div class="wrapper">
			<span class="fright">Powered by Addictive Community &copy; <?php echo date("Y") ?> - All rights reserved.</span>
		</div>
	</div>

</body>
</html>
