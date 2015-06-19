<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Community.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
## -------------------------------------------------------

class Community extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * COMMUNITY HOME
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		$_rooms = $this->_GetRooms();
		$this->Set("rooms", $_rooms);
		$this->Set("is_logged", $this->Session->IsMember());
	}

	/**
	 * --------------------------------------------------------------------
	 * ABOUT ADDICTIVE COMMUNITY
	 * --------------------------------------------------------------------
	 */
	public function About()
	{
		$this->master = "Ajax";

		$data = array(
			"version"  => VERSION . "-" . CHANNEL,
			"codename" => CODENAME
		);

		// Return variables
		$this->Set("data", $data);
	}

	/**
	 * --------------------------------------------------------------------
	 * FOT LOGGED IN MEMBERS: MARK ALL THREADS AS READ
	 * --------------------------------------------------------------------
	 */
	public function MarkAllAsRead()
	{
		// Overwrite cookies
		$this->Session->CreateCookie("addictive_community_login_time", time(), 1);
		$this->Session->CreateCookie("addictive_community_read_threads", json_encode(array()), 1);

		// Go back to community
		$this->Core->Redirect("HTTP_REFERER");
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURN LIST (ARRAY) OF ROOMS
	 * --------------------------------------------------------------------
	 */
	private function _GetRooms()
	{
		// If member is Admin, show invisible rooms too
		if($this->Session->IsMember() && $this->Session->member_info['usergroup'] == 1) {
			$visibility = "";
		}
		else {
			$visibility = "WHERE invisible <> '1'";
		}

		// Get rooms from DB
		$rooms_result = $this->Db->Query("SELECT c_rooms.*, c_members.m_id, c_members.username,
				c_threads.title, c_threads.t_id, c_threads.slug,
				(SELECT COUNT(*) FROM c_threads WHERE room_id = c_rooms.r_id) AS thread_count
				FROM c_rooms LEFT JOIN c_members ON (c_members.m_id = c_rooms.lastpost_member)
				LEFT JOIN c_threads ON (c_threads.t_id = c_rooms.lastpost_thread)
				{$visibility} ORDER BY r_id ASC;");

		// Process data
		while($result = $this->Db->Fetch($rooms_result)) {
			$_rooms[] = $this->_ParseRooms($result);
		}

		return $_rooms;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET RAW ROOM INFO AND OUTPUTS READY CONTENT
	 * --------------------------------------------------------------------
	 */
	private function _ParseRooms($result)
	{
		// Get number of users online
		$online = $this->Db->Query("SELECT COUNT(*) AS total FROM c_sessions
				WHERE location_type IN ('room', 'thread') AND location_room_id = {$result['r_id']};");

		$result['online'] = $this->Db->Fetch($online);

		// If last post timestamp is not zero / no posts
		$result['lastpost_date'] = ($result['lastpost_date'] > 0) ? $this->Core->DateFormat($result['lastpost_date']) : "---";

		// If thread and/or last poster username is empty, show dashes instead
		if($result['title'] == null) {
			$result['title'] = "---";
		}
		if($result['username'] == null) {
			$result['username'] = "---";
		}

		// Get moderators
		$moderators_array = unserialize($result['moderators']);
		if(!empty($moderators_array)) {
			$moderators = unserialize($result['moderators']);
			$moderator_list = array();

			// Build moderators list
			foreach($moderators as $member_id) {
				$mod_details = $this->Db->Query("SELECT m_id, username FROM c_members WHERE m_id = {$member_id};");
				$member = $this->Db->Fetch($mod_details);

				$moderator_list[] = "<a href='profile/{$member['m_id']}'>{$member['username']}</a>";
			}

			$result['moderators_list'] = "<div class='moderators'>Moderators: " . String::ToList($moderator_list) . "</div>";
		}
		else {
			$result['moderators_list'] = "";
		}

		// Check if room has unread threads
		if($result['thread_count'] > 0) {
			$has_unread_threads = $this->_CheckUnread($result['r_id']);
		}

		// Regular variables
		$result['room_link'] = "room/{$result['r_id']}";
		$result['redirect'] = ""; // Specific for redirect room

		// Is this room a read only, protected or invisible room?
		// The order of relevance is from down to up
		if($result['read_only'] == 1) {
			$result['icon']  = "<i class='fa fa-file-text-o fa-fw'></i>";
			$result['title'] = "<a href='thread/{$result['t_id']}-{$result['slug']}'>{$result['title']}</a>";
		}
		elseif($result['password'] != "") {
			$result['icon']  = "<i class='fa fa-lock fa-fw' title='Protected room'></i>";
			$result['title'] = "<em>" . i18n::Translate("C_PROTECTED_ROOM") . "</em>";
		}
		elseif($result['invisible'] == 1) {
			$result['icon']  = "<i class='fa fa-user-secret fa-fw' title='Invisible room'></i>";
			$result['title'] = "<a href='thread/{$result['t_id']}-{$result['slug']}'>{$result['title']}</a>";
		}
		elseif($result['url'] != "") {
			$result['icon']  = "<i class='fa fa-external-link fa-fw' title='Redirect room'></i>";
			$result['redirect'] = "<div class='redirect'>" . i18n::Translate("C_REDIRECT_TO") . ": {$result['url']}</div>";
			$result['room_link'] = $result['url'];
		}
		elseif($has_unread_threads) {
			$result['icon']  = "<i class='fa fa-comment fa-fw' title='Has unread threads'></i>";
			$result['title'] = "<a href='thread/{$result['t_id']}-{$result['slug']}'>{$result['title']}</a>";
		}
		else {
			$result['icon']  = "<i class='fa fa-comment-o fa-fw' title='Has no unread threads'></i>";
			$result['title'] = "<a href='thread/{$result['t_id']}-{$result['slug']}'>{$result['title']}</a>";
		}

		// Save result in array
		return $result;
	}

	/**
	 * --------------------------------------------------------------------
	 * CHECK IF ROOM HAS UNREAD THREADS
	 * --------------------------------------------------------------------
	 */
	private function _CheckUnread($room_id)
	{
		$has_unread = false;

		$threads = $this->Db->Query("SELECT t_id, lastpost_date FROM c_threads WHERE room_id = {$room_id};");

		while($result = $this->Db->Fetch($threads)) {
			$read_threads_cookie = $this->Session->GetCookie("addictive_community_read_threads");
			if($read_threads_cookie) {
				$login_time_cookie = $this->Session->GetCookie("addictive_community_login_time");
				$read_threads = json_decode(html_entity_decode($read_threads_cookie), true);
				if(!in_array($result['t_id'], $read_threads) && $login_time_cookie < $result['lastpost_date']) {
					$has_unread = true;
				}
			}
		}

		return $has_unread;
	}
}
