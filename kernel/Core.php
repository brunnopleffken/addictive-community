<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Core.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

class Core
{
	// Database class
	private $Db;

	// Configurations
	private $config = array();

	/**
	 * --------------------------------------------------------------------
	 * CORE() CLASS CONSTRUCTOR
	 * --------------------------------------------------------------------
	 */
	public function __construct($database, $configurations)
	{
		// Load database layer and configurations array
		$this->Db = $database;
		$this->config = $configurations;
	}

	/**
	 * --------------------------------------------------------------------
	 * REDIRECT TO AN SPECIFIC URL
	 * --------------------------------------------------------------------
	 */
	public function Redirect($url)
	{
		$url = $this->config['general_community_url'] . $url;
		header("Location: " . $url);
		exit;
	}

	/**
	 * --------------------------------------------------------------------
	 * USE CUSTOM DATE FORMATTING
	 * --------------------------------------------------------------------
	 */
	public function DateFormat($timestamp, $format = "long")
	{
		if($format == "short") {
			$format = $this->config['date_short_format'];  // Get short format date from $_config
		}
		elseif($format == "long") {
			$format = $this->config['date_long_format'];  // Get long format date from $_config
		}

		// Get timezones and daylight saving time
		$offset = $this->config['date_default_offset'] * MINUTE * MINUTE;

		// format and return it
		$date = date($format, $timestamp + $offset);

		return $date;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET GRAVATAR, FACEBOOK OR UPLOADED MEMBER IMAGE PATH
	 * --------------------------------------------------------------------
	 */
	public function GetGravatar($email, $photo, $size = 96, $mode = "gravatar", $d = "mm", $r = "g")
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
			$url = "public/avatar/{$photo}";
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
				$translate[$shortcut] = "<img src='{$folder}/{$item['filename']}' class='emoticon'>";
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
				$breadcrumb .= " &raquo; " . $item;
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
