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
	// Get user's personal messages
	// ---------------------------------------------------

	// Get member ID
	$m_id = $this->Session->sInfo['member_id'];

	// ---------------------------------------------------
	// Which page is the user viewing?
	// ---------------------------------------------------
	
	// Which action is the user taking
	$view = (Html::Request("view")) ? Html::Request("view") : "inbox";
	
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
				$result['icon_class'] = ($result['status'] == 0) ? "fa-envelope" : "fa-envelope-o";
				$result['subject'] = ($result['status'] == 0) ? "<b>" . $result['subject'] . "<b>" : $result['subject'];

				$result['sent_date'] = $this->Core->DateFormat($result['sent_date']);

				$results[] = $result;
			}

			break;

		case "compose":
			break;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = "Messenger";
	$pageinfo['bc'] = array("Messenger");

?>