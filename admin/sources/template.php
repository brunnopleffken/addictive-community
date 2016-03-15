<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: template.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

$layout['header'] = <<<HTML
<!DOCTYPE html>
<html>
<head>
	<title>Addictive Community - Administration Panel</title>
	<meta charset="utf-8">
	<link href="styles/admin_style.css" type="text/css" rel="stylesheet">
	<link href="../thirdparty/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet">
	<script src="../thirdparty/jquery/jquery.min.js"></script>
	<!-- Code Mirror -->
	<script src="../thirdparty/codemirror/codemirror.js"></script>
	<link href="../thirdparty/codemirror/codemirror.css" type="text/css" rel="stylesheet">
	<script src="../thirdparty/codemirror/mode/css/css.js"></script>
	<!-- Admin JS -->
	<script src="admin.js"></script>
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


$layout['footer'] = <<<HTML
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
