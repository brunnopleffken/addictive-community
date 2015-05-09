<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: index.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

$task = $_REQUEST['task'];

if($task == "test_database") {
	$data = array(
		'host' => $_REQUEST['host'],
		'database' => $_REQUEST['database'],
		'username' => $_REQUEST['username'],
		'password' => $_REQUEST['password']
	);

	$link = @mysqli_connect($data['host'], $data['username'], $data['password']);

	if($link) {
		$return_data = array('status' => 1);
	} else {
		$return_data = array('status' => 0);
	}

	echo json_encode($return_data);
}
