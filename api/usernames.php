<?php

	// Get required files

	require_once('../config.php');
	require_once('../kernel/class.database.php');
	require_once('../kernel/class.html.php');
	require_once('../kernel/class.string.php');

	// Build database property

	$Db = new Database($config);
	$term = Html::Request("term");

	// Query usernames

	$Db->Query("SELECT m_id, username FROM c_members WHERE username LIKE '%{$term}%';");

	$users = array();
	
	while($result = $Db->Fetch()) {
		$users[] = array(
			"m_id"     => $result['m_id'],
			"username" => $result['username']
		);
	}

	echo json_encode($users);

?>