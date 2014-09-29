<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: messenger.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Define access method
	// ---------------------------------------------------

	// Deny guest access
	$this->Session->NoGuest();

	// ---------------------------------------------------
	// Get notifications
	// ---------------------------------------------------

	$msg = Html::Request("msg");
	$notification = "";

	switch ($msg) {
		case 1:
			$notification = Html::Notification("Your personal message has been successfully sent.", "success");
			break;
	}

	// ---------------------------------------------------
	// Get user's personal messages
	// ---------------------------------------------------

	// Get member ID
	$m_id = $this->member['m_id'];

	// ---------------------------------------------------
	// Which page is the user viewing?
	// ---------------------------------------------------

	// Which action is the user taking
	$view = $this->Core->QueryString("view", "inbox");

	switch($view) {
		case "inbox":
			// Select personal messages
			$this->Db->Query("SELECT m.pm_id, m.from_id, m.subject, m.status, m.sent_date, u.username "
					. "FROM c_messages m INNER JOIN c_members u ON (m.from_id = u.m_id) "
					. "WHERE m.to_id = '{$m_id}' ORDER BY m.sent_date DESC;");

			// Number of results
			$numResults = $this->Db->Rows();

			// Used storage
			$maxStorageSize = $this->Core->config['member_pm_storage'];
			$percentageWidth = (200 / $maxStorageSize) * $numResults . "px";

			// Results
			while($result = $this->Db->Fetch()) {
				$result['icon_class'] = ($result['status'] == 1) ? "fa-envelope" : "fa-envelope-o";
				$result['subject']    = ($result['status'] == 1) ? "<b>" . $result['subject'] . "<b>" : $result['subject'];
				$result['sent_date']  = $this->Core->DateFormat($result['sent_date']);

				$results[] = $result;
			}

			break;

		case "compose":
			break;

	}

	// ---------------------------------------------------
	// Set action
	// ---------------------------------------------------

	$act = Html::Request("act");

	switch($act) {
		case "delete":
			// Get selected messages
			$messages = Html::Request("pm");

			// Delete
			foreach($messages as $v) {
				$this->Db->Query("DELETE FROM c_messages WHERE pm_id = {$v} AND to_id = {$m_id}");
			}

			// Redirect
			header("Location: index.php?module=messenger");
			exit;
			break;

		case 'send':
			// Build register
			$pm = array(
				"from_id"   => $this->member['m_id'],
				"to_id"     => Html::Request("to", true),
				"subject"   => Html::Request("subject"),
				"status"    => 1,
				"sent_date" => time(),
				"message"   => Html::Request("post"),
			);

			// Insert into DB
			$this->Db->Insert("c_messages", $pm);

			// Redirect
			header("Location: index.php?module=messenger&msg=1");
			exit;
			break;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = "Messenger";
	$pageinfo['bc'] = array("Messenger");

?>
