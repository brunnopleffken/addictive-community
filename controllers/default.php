<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: default.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	// ---------------------------------------------------
	// Format page title
	// ---------------------------------------------------
	
	if($pageinfo['title'] != "") {
		$html['title'] = $pageinfo['title'] . " - ";
	}
	else {
		$html['title'] = "";
	}

	// ---------------------------------------------------
	// Format breadcrumbs
	// ---------------------------------------------------
	
	$html['breadcrumb'] = "";

	foreach($pageinfo['bc'] as $item) {
		$html['breadcrumb'] .= " &raquo; " . $item;
	}

?>