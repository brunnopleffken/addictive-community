<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Feeds.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\Text;

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

		// XML content
		header('Content-Type: application/xml');

		// Get room information
		Database::Query("SELECT name, last_post_date FROM c_rooms WHERE r_id = {$room_id};");
		$room_info = Database::Fetch();

		// Print ATOM syndication header
		$html = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
		$html .= '<feed xmlns="http://www.w3.org/2005/Atom">' . "\n";
		$html .= '<title>' . $this->Core->config['general_community_name'] . ': ' . $room_info['name'] . '</title>' . "\n";
		$html .= '<link href="' . $this->Core->config['general_community_url'] . '"/>' . "\n";
		$html .= '<updated>' . date("Y-m-d\TH:i:s\Z", $room_info['last_post_date']) . '</updated>' . "\n";
		$html .= '<author><name>' . $this->Core->config['general_community_name'] . '</name></author>' . "\n";
		$html .= '<id>' . $this->Core->config['general_community_url'] . '</id>' . "\n\n";

		// Get threads
		Database::Query("SELECT t.t_id, t.title, t.slug, t.start_date, m.username,
				(SELECT p.post FROM c_posts p WHERE p.thread_id = t.t_id ORDER BY post_date LIMIT 1) AS post FROM c_threads t
				INNER JOIN c_members m ON (t.author_member_id = m.m_id)
				WHERE t.room_id = {$room_id} ORDER BY t.start_date DESC;");

		while($thread = Database::Fetch()) {
			// Get full URL to thread
			$thread['thread_url'] = $this->Core->config['general_community_url'] . "thread/" . $thread['t_id'] . "-" . $thread['slug'];
			$thread['tag'] = Text::Slug($this->Core->config['general_community_name']);

			// Print ATOM entries
			$html .= '<entry>' . "\n";
			$html .= '<title>' . $thread['title'] . '</title>' . "\n";
			$html .= '<link href="' . $thread['thread_url'] . '"/>' . "\n";
			$html .= '<updated>' . date("Y-m-d\TH:i:s\Z", $thread['start_date']) . '</updated>' . "\n";
			$html .= '<summary>' . strip_tags($thread['post'], "p") . '</summary>' . "\n";
			$html .= '<id>tag:' . $thread['tag'] . ',' . date("Y-m-d", $thread['start_date']) . ':' . $thread['t_id'] . '</id>' . "\n";
			$html .= '</entry>' . "\n\n";
		}

		$html .= '</feed>';

		echo trim($html);
	}
}
