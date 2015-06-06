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
			$result['icon']  = "<i class='fa fa-lock fa-fw'></i>";
			$result['title'] = "<em>" . i18n::Translate("C_PROTECTED_ROOM") . "</em>";
		}
		elseif($result['invisible'] == 1) {
			$result['icon']  = "<i class='fa fa-user-secret fa-fw'></i>";
			$result['title'] = "<a href='thread/{$result['t_id']}-{$result['slug']}'>{$result['title']}</a>";
		}
		elseif($result['url'] != "") {
			$result['icon']  = "<i class='fa fa-external-link fa-fw'></i>";
			$result['redirect'] = "<div class='redirect'>" . i18n::Translate("C_REDIRECT_TO") . ": {$result['url']}</div>";
			$result['room_link'] = $result['url'];
		}
		else {
			$result['icon']  = "<i class='fa fa-comment-o fa-fw'></i>";
			$result['title'] = "<a href='thread/{$result['t_id']}-{$result['slug']}'>{$result['title']}</a>";
		}

		// Save result in array
		return $result;
	}
}
