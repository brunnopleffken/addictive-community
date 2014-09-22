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
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Addictive Community - Administration Panel</title>
	<link href="styles/admin_style.css" type="text/css" rel="stylesheet">
</head>

<body>

	<div id="topbar">
		<div class="wrapper">
			<div class="fleft"><a href="http://www.addictive.com.br" target="_blank" class="transition">Addictive Services</a></div>
			<div class="fright"><a href="../" target="_blank" class="toplinks transition">Back to Community &raquo;</a></div>
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
		<div class="wrapper">
			<span class="fright">Powered by Addictive Community &copy; 2012 - All rights reserved.</span>
		</div>
	</div>

</body>
</html>
HTML;

?>