<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Community.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
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

		$rooms_result = $this->Db->Query("SELECT c_rooms.*, c_members.m_id, c_members.username,
				c_threads.title, c_threads.t_id, c_threads.slug,
				(SELECT COUNT(*) FROM c_threads WHERE room_id = c_rooms.r_id) AS thread_count
				FROM c_rooms LEFT JOIN c_members ON (c_members.m_id = c_rooms.lastpost_member)
				LEFT JOIN c_threads ON (c_threads.t_id = c_rooms.lastpost_thread)
				{$visibility} ORDER BY r_id ASC;");

		// Process data

		while($result = $this->Db->Fetch($rooms_result)) {
			// If last post timestamp is not zero / no posts
			if($result['lastpost_date'] > 0) {
				$result['lastpost_date'] = $this->Core->DateFormat($result['lastpost_date']);
			}
			else {
				$result['lastpost_date'] = "---";
			}
			
			// If thread and/or last poster username is empty, show dashes instead
			if($result['title'] == null) {
				$result['title'] = "---";
			}
			if($result['username'] == null) {
				$result['username'] = "---";
			}

			// Is this room a read only, protected or invisible room?
			// The order of relevance is from down to up
			if($result['read_only'] == 1) {
				$result['icon']  = "<i class='fa fa-file-text-o fa-fw'></i>";
				$result['title'] = "<a href='thread/{$result['t_id']}-{$result['slug']}'>{$result['title']}</a>";
			}
			elseif($result['password'] != "") {
				$result['icon']  = "<i class='fa fa-lock fa-fw'></i>";
				$result['title'] = "<em>Protected room</em>";
			}
			elseif($result['invisible'] == 1) {
				$result['icon']  = "<i class='fa fa-user-secret fa-fw'></i>";
				$result['title'] = "<a href='thread/{$result['t_id']}-{$result['slug']}'>{$result['title']}</a>";
			}
			else {
				$result['icon']  = "<i class='fa fa-comment-o fa-fw'></i>";
				$result['title'] = "<a href='thread/{$result['t_id']}-{$result['slug']}'>{$result['title']}</a>";
			}

			// Save result in array
			$_rooms[] = $result;
		}

		return $_rooms;
	}
}
