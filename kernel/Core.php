<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Core.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
## -------------------------------------------------------

class Core
{
	// Community configurations
	public $config = array();

	// Database class
	private $Db;

	// Logged member information
	private $member_info = array();

	/**
	 * --------------------------------------------------------------------
	 * CORE() CLASS CONSTRUCTOR
	 * --------------------------------------------------------------------
	 */
	public function __construct($database, $configurations, $member_info = array())
	{
		// Load database layer and configurations array
		$this->Db = $database;
		$this->config = $configurations;
		$this->member_info = $member_info;
	}

	/**
	 * --------------------------------------------------------------------
	 * REDIRECT TO AN SPECIFIC URL
	 * --------------------------------------------------------------------
	 */
	public function Redirect($url)
	{
		if($url == "HTTP_REFERER") {
			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit;
		}
		else {
			$url = $this->config['general_community_url'] . $url;
			header("Location: " . $url);
			exit;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * USE CUSTOM DATE FORMATTING
	 * --------------------------------------------------------------------
	 */
	public function DateFormat($timestamp, $format = "long")
	{
		// Get long/short time formats from configurations table
		if($format == "long") {
			$format = $this->config['date_long_format'];  // Get long format date from $_config
		}
		else {
			$format = $this->config['date_short_format'];  // Get short format date from $_config
		}

		// Get timezone offset
		$user_offset = (isset($this->member_info['time_offset'])) ? $this->member_info['time_offset'] : $this->config['date_default_offset'];
		$timezone_offset = $user_offset * HOUR;

		// format and return it
		$date = date($format, $timestamp + $timezone_offset);

		return $date;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET GRAVATAR, FACEBOOK OR UPLOADED MEMBER IMAGE PATH
	 * --------------------------------------------------------------------
	 */
	public function GetGravatar($email, $photo, $size = 96, $mode = "gravatar", $section = "public", $d = "mm", $r = "g")
	{
		if($mode == "gravatar") {
			$url = "http://www.gravatar.com/avatar/";
			$url .= md5(strtolower(trim($email)));
			$url .= "?s={$size}&amp;d={$d}&amp;r={$r}";
		}
		elseif($mode == "facebook") {
			$get_facebook = $this->Db->Query("SELECT im_facebook FROM c_members WHERE email = '{$email}';");
			$facebook_photo = $this->Db->Fetch($get_facebook);
			$url = "https://graph.facebook.com/{$facebook_photo['im_facebook']}/picture?width={$size}&height={$size}";
		}
		elseif($mode == "custom") {
			// Modify relative path when viewing in Admin CP
			if($section == "public") {
				$url = "public/avatar/{$photo}";
			}
			else {
				$url = "../public/avatar/{$photo}";
			}
		}

		return $url;
	}

	/**
	 * --------------------------------------------------------------------
	 * PARSE EMOTICONS INSIDE POSTS AND MESSAGES :)
	 * --------------------------------------------------------------------
	 */
	public function ParseEmoticons($text, $emoticons)
	{
		if($this->config['thread_allow_emoticons'] == true) {
			// Empty array to store emoticons :O
			$translate = array();

			// Folder where images are located in ;)
			$folder = "public/emoticons/" . $this->config['emoticon_default_set'];

			foreach($emoticons as $item) {
				$shortcut = String::Sanitize($item['shortcut']);
				$translate[$shortcut] = "<img src='{$folder}/{$item['filename']}' class='emoticon' alt='{$item['shortcut']}'>";
			}

			return strtr(html_entity_decode($text), $translate);
		}
		else {
			return $text;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * CREATE CANONICAL TAG URL
	 * --------------------------------------------------------------------
	 */
	public function CanonicalTag($thread_id)
	{
		$url = $this->config['general_community_url'] . "index.php?module=thread&id=" . $thread_id;
		return $url;
	}

	/**
	 * --------------------------------------------------------------------
	 * CREATE CANONICAL TAG URL
	 * --------------------------------------------------------------------
	 */
	public function Breadcrumb($page_info = array()) {
		$breadcrumb = "";

		if(!empty($page_info)) {
			foreach($page_info['bc'] as $item) {
				$breadcrumb .= " " . $this->config['general_bread_separator'] . " " . $item;
			}
		}

		return $breadcrumb;
	}

	/**
	 * --------------------------------------------------------------------
	 * CREATE CANONICAL TAG URL
	 * --------------------------------------------------------------------
	 */
	public function PageTitle($page_info) {
		$title = (isset($page_info['title'])) ? $page_info['title'] . " - " : "";
		return $title;
	}
}
