<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: template.php
#  Release: v1.0.0
#  Copyright: (c) 2014 - Addictive Software
## ---------------------------------------------------

$template['header'] = <<<HTML
<!DOCTYPE html>
<html>
<head>
	<title>Addictive Community - Administration Panel</title>
	<meta charset="utf-8">
	<link href="styles/admin_style.css" type="text/css" rel="stylesheet">
	<link href="../thirdparty/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet">
</head>

<body>

	<div id="topbar">
		<div class="wrapper">
			<div class="fleft">
				<a href="../" target="_blank" class="toplinks transition">&laquo; Back to your Community</a>
			</div>
			<div class="fright"><a href="https://github.com/brunnopleffken/addictive-community" target="_blank" class="transition">View Addictive Comunity on GitHub</a></div>
			<div class="fix"></div>
		</div>
	</div>

	<div id="logo">
		<div class="wrapper">
			<a href="main.php" title="Admin CP Dashboard"><img src="images/logo.png" class="logo-image"></a>
		</div>
	</div>

	<div class="wrapper">
HTML;


	$template['footer'] = <<<HTML
	</div>

	<div id="footer">
		<div class="wrapper center">
			<span>Powered by Addictive Community &copy; 2014 - All rights reserved.</span>
		</div>
	</div>

</body>
</html>
HTML;

?>
