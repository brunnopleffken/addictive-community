<?php
	
	// ---------------------------------------------------
	//  ADDICTIVE COMMUNITY
	// ---------------------------------------------------
	// Created by Brunno Pleffken Hosti
	// 
	// Website: www.addictive.com.br
	// E-mail: brunno.pleffken@addictive.com.br
	// Release: December/2012
	// ---------------------------------------------------
	
	
	require_once("../init.php");
	require_once("../kernel/class.core.php");
	
	// Display error messages
	// e.g.: index.php?error=1
	
	if(isset($_REQUEST['error']))
	{
		$error = $_REQUEST['error'];
		$notification = new Notification;
		
		switch($error)
		{
			case 1:
				$message = $notification->ShowNotif("Wrong username and/or password. Please, try again!", "failure");
				break;
			case 2:
				$message = $notification->ShowNotif("Please, log-in to access the Admin Control Panel.", "warning");
				break;
			case 3:
				$message = $notification->ShowNotif("This administration session has expired.", "warning");
				break;
		}
	}
	else
	{
		$message = "";
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Addictive Community</title>
	<link href="styles/admin_style.css" type="text/css" rel="stylesheet">
	<script type="text/javascript">
	
		function autofocus(fieldname)
		{
			var field = document.getElementById(fieldname);
			field.focus();
		}
	
	</script>
</head>

<body onload="javascript:autofocus('username')">

	<div id="topbar">
		<div class="wrapper">
			<div class="fleft"><a href="" class="transition">Addictive Services</a></div>
			<div class="fright"><a href="" class="toplinks transition">Back to Community &raquo;</a></div>
			<div class="fix"></div>
		</div>
	</div>
	
	<div id="login-wrapper"><div style="display: table-row"><div style="display: table-cell; vertical-align: middle;">

		<div class="login-logo"><img src="images/logo.png"></div>
		
		<form action="auth.php" method="post">
		
			<div id="login">
				<?php echo $message ?>
				<table style="width: 100%">
					<tr>
						<td>Username</td>
						<td><input type="text" id="username" name="username" class="small"></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type="password" name="password" class="small"></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="Log In"></td>
					</tr>
				</table>
			</div>
			
		</form>
	
	</div></div></div>
	

</body>
</html>