<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Messenger.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\i18n;

class Messenger extends Application
{
	// User member ID
	private $member_id = 0;

	/**
	 * --------------------------------------------------------------------
	 * RUN BEFORE MAIN()
	 * --------------------------------------------------------------------
	 */
	public function _BeforeAction()
	{
		// This section is for members only
		$this->Session->NoGuest();

		// Save logged in member ID into $member_id
		$this->member_id = $this->Session->member_info['m_id'];
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW INBOX
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		// Define messages
		$message_id = Http::Request("m", true);
		$notification = array("",
			Html::Notification(i18n::Translate("M_MESSAGE_1"), "success"),
			Html::Notification(i18n::Translate("M_MESSAGE_2"), "failure")
		);

		$folder = (Http::Request("folder")) ? Http::Request("folder") : "inbox";

		// Get personal messages
		if($folder == "sent") {
			$selected_folder[0] = "";
			$selected_folder[1] = "class='selected'";

			// Select SENT personal messages
			$messages = $this->Db->Query("SELECT m.pm_id, m.to_id, m.subject, m.status, m.sent_date, u.username
					FROM c_messages m INNER JOIN c_members u ON (m.to_id = u.m_id)
					WHERE m.from_id = '{$this->member_id}' ORDER BY m.sent_date DESC;");
		}
		else {
			$selected_folder[0] = "class='selected'";
			$selected_folder[1] = "";

			// Select INBOX personal messages
			$messages = $this->Db->Query("SELECT m.pm_id, m.from_id, m.subject, m.status, m.sent_date, u.username
					FROM c_messages m INNER JOIN c_members u ON (m.from_id = u.m_id)
					WHERE m.to_id = '{$this->member_id}' ORDER BY m.sent_date DESC;");
		}

		// Used storage
		$number_of_messages = $messages->num_rows;
		$max_storage_size = $this->Core->config['member_pm_storage'];
		$percentage_width = (100 / $max_storage_size) * $number_of_messages . "%";

		// Results
		$results = array();

		while($result = $messages->fetch_assoc()) {
			$result['icon_class'] = ($result['status'] == 0 && $folder == "inbox") ? "fa-envelope" : "fa-envelope-o";
			$result['subject'] = ($result['status'] == 0 && $folder == "inbox") ? "<b>" . $result['subject'] . "<b>" : $result['subject'];
			$result['sent_date'] = $this->Core->DateFormat($result['sent_date']);
			$results[] = $result;
		}

		// Page info
		$page_info['title'] = i18n::Translate("M_TITLE");
		$page_info['bc'] = array(i18n::Translate("M_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("folder", $folder);
		$this->Set("selected_folder", $selected_folder);
		$this->Set("num_results", $number_of_messages);
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
		$post = $this->Db->Query("SELECT p.*, m.username, m.signature, m.member_title, m.email, m.photo, m.photo_type
				FROM c_messages p LEFT JOIN c_members m ON (p.from_id = m.m_id)
				WHERE pm_id = {$id} AND (to_id = {$this->member_id} OR from_id = {$this->member_id});");

		if($post->num_rows == 1) {
			$message = $post->fetch_assoc();

			// If not, set message as read
			if($message['status'] == 0) {
				$time = time();
				$this->Db->Update("c_messages", array(
					"status = 1",
					"read_date = {$time}"
				), "pm_id = {$id}");
			}

			// Format content
			$message['sent_date'] = $this->Core->DateFormat($message['sent_date']);
			$message['avatar'] = $this->Core->GetAvatar($message, 198);
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
		$this->Set("enable_signature", $this->Core->config['general_member_enable_signature']);
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
		$users = array();

		// Get member name
		$member_id = $this->Session->session_info['member_id'];
		$term = Http::Request("term");

		// Get list of usernames
		$result = $this->Db->Query("SELECT m_id, username FROM c_members
				WHERE username LIKE '%{$term}%' AND usergroup <> 0 AND m_id <> {$member_id};");

		while($row = $result->fetch_assoc()) {
			$users[] = array(
				"m_id"     => $row['m_id'],
				"username" => $row['username']
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
			"to_id"     => Http::Request("to", true),
			"subject"   => Http::Request("subject"),
			"status"    => 0,
			"sent_date" => time(),
			"message"   => $_REQUEST['post']
		);

		// Send message
		$this->Db->Insert("c_messages", $pm);

		// Redirect
		$this->Core->Redirect("messenger?m=1");
	}

	/**
	 * --------------------------------------------------------------------
	 * DELETE PERSONAL MESSAGES
	 * --------------------------------------------------------------------
	 */
	public function Delete($id)
	{
		$this->layout = false;

		// Get information
		$member_id = $this->Session->session_info['member_id'];

		// Execute deletion
		if($id) {
			// Delete single message (when reading one)
			$this->Db->Delete("c_messages", "pm_id = {$id} AND to_id = {$member_id}");
		}
		else {
			// Delete multiple messages (from inbox)
			$selected_messages = Http::Request("pm");
			foreach($selected_messages as $pm_id) {
				$this->Db->Delete("c_messages", "pm_id = {$pm_id} AND to_id = {$member_id}");
			}
		}

		// Redirect
		$this->Core->Redirect("messenger");
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: FORWARDING A MESSAGE TO ANOTHER USER
	 * --------------------------------------------------------------------
	 */
	public function Forward($id)
	{
		$member_id = $this->Session->session_info['member_id'];

		if($id) {
			$this->Db->Query("SELECT `username`,`subject`,`message` FROM `c_messages`
					LEFT JOIN `c_members` ON `c_messages`.`from_id`=`c_members`.`m_id`
					WHERE `pm_id`={$id} LIMIT 1");

			$message = $this->Db->fetch();

			if($message) {
				$this->Set('message', $message);
			}
			else {
				$this->Core->Redirect("messenger");
			}
		}
		else {
			$this->Core->Redirect("messenger");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: REPLYING TO A MESSAGE
	 * --------------------------------------------------------------------
	 */
	public function Reply($id)
	{
		$member_id = $this->Session->session_info['member_id'];

		if($id) {
			//Load message user is replying to
			$this->Db->Query("SELECT `username`,`m_id` AS `reply_user_id`,`subject` FROM `c_messages`
					LEFT JOIN `c_members` ON `c_messages`.`from_id`=`c_members`.`m_id` WHERE `pm_id`={$id}
					AND `to_id`={$member_id} LIMIT 1");

			$message = $this->Db->fetch();

			if($message) {
				//Set this message so the view can use the information
				$this->Set('message', $message);
			}
			else {
				$this->Core->Redirect("messenger");
			}
		}
		else {
			$this->Core->Redirect("messenger");
		}
	}
}
