<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  https://github.com/addictivehub/addictive-community
#
#  File: Community.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\i18n;
use \AC\Kernel\Session\SessionState;
use \AC\Kernel\Text;

class Community extends Application
{
	// List of categories
	private $categories = array();

	// List of rooms of each category
	private $rooms = array();

	/**
	 * --------------------------------------------------------------------
	 * COMMUNITY HOME
	 * --------------------------------------------------------------------
	 */
	public function index()
	{
		// Get rooms and categories
		$this->getRooms();

		// Return variables
		$this->Set("categories", $this->categories);
		$this->Set("rooms", $this->rooms);
		$this->Set("is_logged", SessionState::isMember());
	}

	/**
	 * --------------------------------------------------------------------
	 * FOR LOGGED IN MEMBERS: MARK ALL THREADS AS READ
	 * --------------------------------------------------------------------
	 */
	public function markAllAsRead()
	{
		// Overwrite cookies
		SessionState::createCookie("addictive_community_login_time", time(), 1);
		SessionState::createCookie("addictive_community_read_threads", json_encode(array()), 1);

		// Go back to community
		$this->Core->redirect("HTTP_REFERER");
	}

	/**
	 * --------------------------------------------------------------------
	 * RENDER XML FOR OPENSEARCH
	 * --------------------------------------------------------------------
	 */
	public function openSearch()
	{
		$this->layout = false;

		// XML content
		header('Content-Type: application/xml');
		$xml = '';

		$xml .= '<?xml version="1.0" encoding="UTF-8" ?>';
		$xml .= '<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="http://www.mozilla.org/2006/browser/search/">';
		$xml .= '<ShortName>' . $this->Core->config['general_community_name'] . '</ShortName>';
		$xml .= '<Description>' . $this->Core->config['seo_description'] . '</Description>';
		$xml .= '<InputEncoding>UTF-8</InputEncoding>';
		$xml .= '<Image width="16" height="16" type="image/x-icon">' . $this->Core->config['general_community_url'] . 'favicon.png</Image>';
		$xml .= '<Url type="text/html" method="get" template="' . $this->Core->config['general_community_url'] . 'search?q={searchTerms}"></Url>';
		$xml .= '</OpenSearchDescription>';

		echo $xml;
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURN LIST (ARRAY) OF ROOMS
	 * --------------------------------------------------------------------
	 */
	private function getRooms()
	{
		$now = time();

		// If member is Admin, show invisible rooms too
		if(SessionState::isMember() && SessionState::isAdmin()) {
			$visibility = "";
		}
		else {
			$visibility = "AND invisible <> '1'";
		}

		// Get categories
		$categories_result = Database::query("SELECT * FROM c_categories
				WHERE visible = 1 ORDER BY order_n, c_id;");

		while($category = Database::fetch($categories_result)) {
			// Categories
			$this->categories[$category['c_id']] = $category;

			// Get rooms from DB
			$rooms_result = Database::query("SELECT c_rooms.*, c_members.m_id, c_members.username,
					c_threads.title, c_threads.start_date, c_threads.t_id, c_threads.slug,
					(SELECT COUNT(*) FROM c_threads WHERE room_id = c_rooms.r_id) AS thread_count FROM c_rooms
					LEFT JOIN c_members ON (c_members.m_id = c_rooms.last_post_member)
					LEFT JOIN c_threads
						ON c_threads.t_id = (SELECT t.t_id FROM c_threads AS t WHERE t.room_id = c_rooms.r_id
							AND t.start_date < {$now} ORDER BY t.last_post_date DESC LIMIT 1)
					WHERE category_id = {$category['c_id']}
					{$visibility} ORDER BY name ASC;");

			// Process data
			while($rooms = Database::fetch($rooms_result)) {
				$this->rooms[$category['c_id']][] = $this->parseRooms($rooms);
			}
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * GET RAW ROOM INFO AND OUTPUTS READY CONTENT
	 * --------------------------------------------------------------------
	 */
	private function parseRooms($result)
	{
		// Get number of users online
		$online = Database::query("SELECT COUNT(*) AS total FROM c_sessions
				WHERE location_controller IN ('room', 'thread') AND location_room_id = {$result['r_id']};");

		$result['online'] = Database::fetch($online);

		// Get moderators
		$moderators_array = unserialize($result['moderators']);
		if(!empty($moderators_array)) {
			$moderators = unserialize($result['moderators']);
			$moderator_list = array();

			// Build moderators list
			foreach($moderators as $member_id) {
				$moderator_details = Database::query("SELECT m_id, username FROM c_members WHERE m_id = {$member_id};");
				$member = $moderator_details->fetch_assoc();

				$moderator_list[] = "<a href='profile/{$member['m_id']}'>{$member['username']}</a>";
			}

			$result['moderators_list'] = "<div class='community-info-moderators'>" . i18n::translate("community.moderators") . ": " . Text::toList($moderator_list) . "</div>";
		}
		else {
			$result['moderators_list'] = "";
		}

		// Check if room has unread threads
		$has_unread_threads = ($result['thread_count'] > 0) ? $this->checkUnread($result['r_id']) : false;

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
			$result['title'] = "<em>" . i18n::translate("community.protected_room") . "</em>";
		}
		elseif($result['invisible'] == 1) {
			$result['icon']  = "<i class='fa fa-user-secret fa-fw' title='Invisible room'></i>";
			$result['title'] = "<a href='thread/{$result['t_id']}-{$result['slug']}'>{$result['title']}</a>";
		}
		elseif($result['url'] != "") {
			$result['icon']  = "<i class='fa fa-external-link fa-fw' title='Redirect room'></i>";
			$result['redirect'] = "<div class='redirect'>" . i18n::translate("community.redirect_to") . ": {$result['url']}</div>";
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

		// If last post timestamp is not zero / no posts
		$result['last_post_date'] = ($result['last_post_date'] > 0) ? $this->Core->dateFormat($result['last_post_date']) : "---";

		// If room has no posts, show dashes as placeholder
		if($result['thread_count'] == 0) {
			$result['title'] = "---";
			$result['username'] = "---";
		}

		// Save result in array
		return $result;
	}

	/**
	 * --------------------------------------------------------------------
	 * CHECK IF ROOM HAS UNREAD THREADS
	 * --------------------------------------------------------------------
	 */
	private function checkUnread($room_id)
	{
		$has_unread = false;

		// Get cookies
		$read_threads_cookie = SessionState::getCookie("addictive_community_read_threads");
		$login_time_cookie = SessionState::getCookie("addictive_community_login_time");

		if($login_time_cookie) {
			// Look for threads where last_post_date is earlier than login time
			$threads = Database::query("SELECT t_id, last_post_date FROM c_threads
					WHERE room_id = {$room_id} AND last_post_date >= {$login_time_cookie};");

			// Check if the returned threads has been already read
			while($result = Database::fetch($threads)) {
				if($read_threads_cookie) {
					$read_threads = json_decode(html_entity_decode($read_threads_cookie), true);
					if(!in_array($result['t_id'], $read_threads)) {
						$has_unread = true;
					}
				}
			}
		}

		return $has_unread;
	}
}
