<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Messenger.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

class Messenger extends Application
{
	// User member ID
	private $member_id = 0;

	/**
	 * --------------------------------------------------------------------
	 * RUN BEFORE MAIN()
	 * --------------------------------------------------------------------
	 */
	public function _BeforeFilter()
	{
		// This section is for members only
		$this->Session->NoGuest();

		// Save logged in member ID into $member_id
		$this->member_id= $this->Session->member_info['m_id'];

		// Community name in all pages
		$this->Set("community_name", $this->config['general_communityname']);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW INBOX
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		// Define messages
		$message_id = Html::Request("m");
		$notification = array("",
			Html::Notification(i18n::Translate("M_MESSAGE_1"), "success"),
			Html::Notification(i18n::Translate("M_MESSAGE_2"), "failure")
		);

		$folder = (Html::Request("folder")) ? Html::Request("folder") : "inbox";

		// Get personal messages
		if($folder == "sent") {
			$selected_folder[0] = "";
			$selected_folder[1] = "class='selected'";

			// Select SENT personal messages
			$this->Db->Query("SELECT m.pm_id, m.to_id, m.subject, m.status, m.sent_date, u.username
					FROM c_messages m INNER JOIN c_members u ON (m.to_id = u.m_id)
					WHERE m.from_id = '{$this->member_id}' ORDER BY m.sent_date DESC;");
		}
		else {
			$selected_folder[0] = "class='selected'";
			$selected_folder[1] = "";

			// Select INBOX personal messages
			$this->Db->Query("SELECT m.pm_id, m.from_id, m.subject, m.status, m.sent_date, u.username
					FROM c_messages m INNER JOIN c_members u ON (m.from_id = u.m_id)
					WHERE m.to_id = '{$this->member_id}' ORDER BY m.sent_date DESC;");
		}

		// Number of results
		$num_results = $this->Db->Rows();

		// Used storage
		$max_storage_size = $this->config['member_pm_storage'];
		$percentage_width = (200 / $max_storage_size) * $num_results . "px";

		// Results
		$results = array();

		while($result = $this->Db->Fetch()) {
			$result['icon_class'] = ($result['status'] == 1 && $folder == "inbox") ? "fa-envelope" : "fa-envelope-o";
			$result['subject']    = ($result['status'] == 1 && $folder == "inbox") ? "<b>" . $result['subject'] . "<b>" : $result['subject'];
			$result['sent_date']  = $this->Core->DateFormat($result['sent_date']);
			$results[] = $result;
		}

		// Page info
		$page_info['title'] = i18n::Translate("M_TITLE");
		$page_info['bc'] = array(i18n::Translate("M_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("folder", $folder);
		$this->Set("selected_folder", $selected_folder);
		$this->Set("num_results", $num_results);
		$this->Set("max_storage_size", $max_storage_size);
		$this->Set("percentage_width", $percentage_width);
		$this->Set("results", $results);
		$this->Set("notification", $notification[$message_id]);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: READ MESSAGE
	 * --------------------------------------------------------------------
	 */
	public function Read($id)
	{
		// Get message info and post
		$this->Db->Query("SELECT p.*, m.username, m.signature, m.member_title, m.email, m.photo, m.photo_type
				FROM c_messages p LEFT JOIN c_members m ON (p.from_id = m.m_id)
				WHERE pm_id = {$id} AND (to_id = {$this->member_id} OR from_id = {$this->member_id});");

		if($this->Db->Rows() == 1) {
			$message = $this->Db->Fetch();

			// If not, set message as read
			if($message['status'] == 1) {
				$time = time();
				$this->Db->Query("UPDATE c_messages SET status = 0, read_date = {$time} WHERE pm_id = {$id}");
			}

			// Format content
			$message['sent_date'] = $this->Core->DateFormat($message['sent_date']);
			$message['avatar'] = $this->Core->GetGravatar($message['email'], $message['photo'], 96, $message['photo_type']);
		}
		else {
			$this->Core->Redirect("messenger?m=2");
		}

		// Page info
		$page_info['title'] = i18n::Translate("M_TITLE");
		$page_info['bc'] = array(i18n::Translate("M_TITLE"), $message['subject']);
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("message", $message);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: COMPOSE NEW MESSAGE
	 * --------------------------------------------------------------------
	 */
	public function Compose()
	{
		// Page info
		$page_info['title'] = i18n::Translate("M_TITLE");
		$page_info['bc'] = array(i18n::Translate("M_TITLE"), i18n::Translate("M_COMPOSE"));
		$this->Set("page_info", $page_info);
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURN LIST OF MEMBER
	 * --------------------------------------------------------------------
	 */
	public function GetUsernames()
	{
		$this->layout = false;

		// Get member name
		$term = Html::Request("term");

		// Get list of usernames
		$this->Db->Query("SELECT m_id, username FROM c_members WHERE username LIKE '%{$term}%';");

		$users = array();

		while($result = $this->Db->Fetch()) {
			$users[] = array(
				"m_id"     => $result['m_id'],
				"username" => $result['username']
			);
		}

		echo json_encode($users);
	}

	/**
	 * --------------------------------------------------------------------
	 * SEND PERSONAL MESSAGE
	 * --------------------------------------------------------------------
	 */
	public function Send()
	{
		$this->layout = false;

		// Build register
		$pm = array(
			"from_id"   => $this->member_id,
			"to_id"     => Html::Request("to", true),
			"subject"   => Html::Request("subject"),
			"status"    => 1,
			"sent_date" => time(),
			"message"   => $_REQUEST['post']
		);

		// Send message
		$this->Db->Insert("c_messages", $pm);

		// Redirect
		$this->Core->Redirect("messenger?m=1");
	}
}
