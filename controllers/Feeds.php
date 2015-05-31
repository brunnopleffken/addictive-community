<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Feeds.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
## -------------------------------------------------------

class Feeds extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * ATOM SYNDICATION: ROOMS
	 * --------------------------------------------------------------------
	 */
	public function Room($room_id)
	{
		$this->layout = false;

		// Get room information
		$this->Db->Query("SELECT name, lastpost_date FROM c_rooms WHERE r_id = {$room_id};");
		$room_info = $this->Db->Fetch();

		// Print ATOM syndication header
		echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
		echo '<feed xmlns="http://www.w3.org/2005/Atom">' . "\n";
		echo '<title>' . $this->Core->config['general_community_name'] . ': ' . $room_info['name'] . '</title>' . "\n";
		echo '<link href="' . $this->Core->config['general_community_url'] . '"/>' . "\n";
		echo '<updated>' . date("Y-m-d\TH:i:s\Z", $room_info['lastpost_date']) . '</updated>' . "\n";
		echo '<author><name>' . $this->Core->config['general_community_name'] . '</name></author>' . "\n";
		echo '<id>' . $this->Core->config['general_community_url'] . '</id>' . "\n\n";

		// Get threads
		$this->Db->Query("SELECT t.t_id, t.title, t.slug, t.start_date, p.post, m.username FROM c_threads t
				INNER JOIN c_posts p ON (t.t_id = p.thread_id)
				INNER JOIN c_members m ON (t.author_member_id = m.m_id)
				WHERE t.room_id = {$room_id} ORDER BY t.start_date DESC;");

		while($thread = $this->Db->Fetch()) {
			// Get full URL to thread
			$thread['thread_url'] = $this->Core->config['general_community_url'] . "thread/" . $thread['t_id'] . "-" . $thread['slug'];
			$thread['tag'] = String::Slug($this->Core->config['general_community_name']);

			// Print ATOM entries
			echo '<entry>' . "\n";
			echo '<title>' . $thread['title'] . '</title>' . "\n";
			echo '<link href="' . $thread['thread_url'] . '"/>' . "\n";
			echo '<updated>' . date("Y-m-d\TH:i:s\Z", $thread['start_date']) . '</updated>' . "\n";
			echo '<summary>' . strip_tags($thread['post'], "p") . '</summary>' . "\n";
			echo '<id>tag:' . $thread['tag'] . ',' . date("Y-m-d", $thread['start_date']) . ':' . $thread['t_id'] . '</id>' . "\n";
			echo '</entry>' . "\n\n";
		}

		echo '</feed>';
	}
}
