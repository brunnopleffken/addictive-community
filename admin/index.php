<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: index.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Html;
use \AC\Kernel\Http;

require_once("../init.php");
require_once("../kernel/Html.php");
require_once("../kernel/Http.php");

// Display error messages
// e.g.: index.php?error=1

if(Http::request("error")) {
	$error = Http::request("error");

	switch($error) {
		case 1:
			$message = Html::notification("Wrong username and/or password. Please, try again!", "failure");
			break;
		case 2:
			$message = Html::notification("Please, log-in to access the Admin Control Panel.", "warning");
			break;
		case 3:
			$message = Html::notification("This administrator session has expired.", "warning");
			break;
		case 4:
			$message = Html::notification("You don't have permission to access Admin CP.", "failure");
			break;
	}
}
else {
	$message = "";
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Addictive Community</title>
	<!-- CSS -->
	<link rel="stylesheet" href="../thirdparty/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../static/css/framework.css">
	<link rel="stylesheet" href="../static/css/wireframe.css">
	<style>
		.login-logo {
			padding: 60px 0;
		}
		.login-logo img {
			display: block;
			margin: auto;
			height: 40px;
		}
	</style>
</head>

<body onload="autofocus('username')">

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
</header>

<div class="login-logo">
	<a href="index.php">
		<img src="../static/images/logo-admin.svg" alt="Addictive Community">
	</a>
</div>

<div class="block" style="width: 400px; margin: auto;">
	<form action="auth.php" method="post">
		<?php echo $message ?>
		<div class="form-group grid">
			<label for="username" class="col-4">Username</label>
			<div class="col-8">
				<input type="text" name="username" id="username" class="form-control" required>
			</div>
		</div>
		<div class="form-group grid">
			<label for="password" class="col-4">Password</label>
			<div class="col-8">
				<input type="password" name="password" id="password" class="form-control" required>
			</div>
		</div>
		<div class="text-center">
			<input type="submit" class="btn btn-default" value="Log In">
		</div>
	</form>
</div>

<!-- JS -->
<script src="../thirdparty/jquery/jquery.min.js" type="text/javascript"></script>
<script src="../static/js/application.js" type="text/javascript"></script>
<script type="text/javascript">
    function autofocus(fieldname) {
        var field = document.getElementById(fieldname);
        field.focus();
    }
</script>

</body>
</html>
