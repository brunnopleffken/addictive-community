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
	 * RETURN LIST (ARRAY) OF ROOMS
	 * --------------------------------------------------------------------
	 */
	private function _GetRooms()
	{
		// If member is Admin, show invisible rooms too
		if($this->Session->IsMember() && $this->Session->member_info['usergroup'] != 1) {
			$visibility = "WHERE invisible = '0'";
		}
		else {
			$visibility = "";
		}

		$rooms_result = $this->Db->Query("SELECT c_rooms.*, c_members.m_id, c_members.username, c_threads.title, c_threads.t_id,
				(SELECT COUNT(*) FROM c_threads WHERE room_id = c_rooms.r_id) AS thread_count
				FROM c_rooms LEFT JOIN c_members ON (c_members.m_id = c_rooms.lastpost_member)
				LEFT JOIN c_threads ON (c_threads.t_id = c_rooms.lastpost_thread)
				{$visibility} ORDER BY r_id ASC;");

		// Process data

		while($result = $this->Db->Fetch($rooms_result)) {
			// If last post timestamp is diff. from zero
			if($result['lastpost_date'] > 0) {
				$result['lastpost_date'] = $this->Core->DateFormat($result['lastpost_date']);
			}
			else {
				$result['lastpost_date'] = "---";
			}

			// Is this room a protected room?
			if($result['password'] != "") {
				$result['icon']  = "<i class=\"fa fa-lock fa-fw fleft\"></i>";
				$result['title'] = "<em>Protected room</em>";
			}
			else {
				$result['icon']  = "<i class=\"fa fa-comment-o fa-fw fleft\"></i>";
				$result['title'] = "<a href=\"thread/{$result['t_id']}\">{$result['title']}</a>";
			}

			// Store result in array
			$_rooms[] = $result;
		}

		return $_rooms;
	}
}
