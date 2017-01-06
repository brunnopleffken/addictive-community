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

if(Http::Request("error")) {
	$error = Http::Request("error");

	switch($error) {
		case 1:
			$message = Html::Notification("Wrong username and/or password. Please, try again!", "failure");
			break;
		case 2:
			$message = Html::Notification("Please, log-in to access the Admin Control Panel.", "warning");
			break;
		case 3:
			$message = Html::Notification("This administrator session has expired.", "warning");
			break;
		case 4:
			$message = Html::Notification("You don't have permission to access Admin CP.", "failure");
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
	<script src="../thirdparty/jquery/jquery.min.js" type="text/javascript"></script>
	<script src="../static/js/application.js" type="text/javascript"></script>
	<link href="styles/admin_style.css" type="text/css" rel="stylesheet">
	<script type="text/javascript">
		function autofocus(fieldname) {
			var field = document.getElementById(fieldname);
			field.focus();
		}
	</script>
</head>

<body onload="autofocus('username')">

	<div id="topbar">
		<div class="fleft"><a href="http://github.com/brunnopleffken/addictive-community" target="_blank" class="transition">Addictive Community</a></div>
		<div class="fright"><a href="../" target="_blank" class="toplinks transition">Back to Community &raquo;</a></div>
		<div class="fix"></div>
	</div>

	<div id="login-wrapper">
		<div style="display: table-row">
			<div style="display: table-cell; vertical-align: middle;">
				<div class="login-logo"><img src="images/logo.png"></div>
				<form action="auth.php" method="post" class="validate">
					<div id="login">
						<?php echo $message ?>
						<table style="width: 100%">
							<tr>
								<td>Username</td>
								<td><input type="text" id="username" name="username" class="required small"></td>
							</tr>
							<tr>
								<td>Password</td>
								<td><input type="password" name="password" class="required small"></td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" value="Log In"></td>
							</tr>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>

</body>
</html>
