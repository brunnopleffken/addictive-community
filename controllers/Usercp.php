<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Usercp.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

class Usercp extends Application
{
	// User member ID
	private $member_id = 0;

	/**
	 * --------------------------------------------------------------------
	 * USER CONTROL PANEL IS FOR MEMBER ONLY. IF GUEST, THEN REDIRECT.
	 * --------------------------------------------------------------------
	 */
	public function _BeforeFilter()
	{
		// This section is for members only
		$this->Session->NoGuest();

		// Save logged in member ID into $member_id
		$this->member_id= $this->Session->member_info['m_id'];

		// Community name in all pages
		$this->Set("community_name", $this->config['general_communityname']);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: USER CONTROL PANEL - HOME
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		// Define selected menu item
		$menu = array("selected", "", "", "", "");

		$profile['female'] = "";
		$profile['male']   = "";

		if($this->Session->member_info['gender'] == "F") {
			$profile['female'] = "selected";
		}
		else {
			$profile['male'] = "selected";
		}

		// Return variables
		$this->Set("menu", $menu);
		$this->Set("member", $this->Session->member_info);
		$this->Set("profile", $profile);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: USER CONTROL PANEL - EDIT USER PHOTO
	 * --------------------------------------------------------------------
	 */
	public function Photo()
	{
		// Define selected menu item
		$menu = array("", "selected", "", "", "");

		// Gravatar or custom photo?
		$photo_info['gravatar'] = "";
		$photo_info['facebook'] = "";
		$photo_info['custom'] = "";

		// Notification if Facebook account is not set
		$facebook_info = "";
		if($this->Session->member_info['photo_type'] == "gravatar") {
			$photo_info['gravatar'] = "checked";
		}
		elseif($this->Session->member_info['photo_type'] == "facebook") {
			$photo_info['facebook'] = "checked";
		}
		else {
			$photo_info['custom'] = "checked";
		}

		if($this->Session->member_info['im_facebook'] == "") {
			$photo_info['facebook'] = "disabled";
			$facebook_info = Html::Notification(
				"You must fill in the \"Facebook\" text field in order to use your Facebook photo as avatar.", "info"
			);
		}

		// Gravatar and Facebook image
		$photo_info['gravatar_image'] = $this->Core->GetGravatar(
			$this->Session->member_info['email'], $this->Session->member_info['photo'], 240, "gravatar"
		);
		$photo_info['facebook_image'] = $this->Core->GetGravatar(
			$this->Session->member_info['email'], $this->Session->member_info['photo'], 240, "facebook"
		);

		// Return variables
		$this->Set("menu", $menu);
		$this->Set("photo_info", $photo_info);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: USER CONTROL PANEL - EDIT SIGNATURE
	 * --------------------------------------------------------------------
	 */
	public function Signature()
	{
		// Define selected menu item
		$menu = array("", "", "selected", "", "");

		// Return variables
		$this->Set("menu", $menu);
		$this->Set("member_info", $this->Session->member_info);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: USER CONTROL PANEL - COMMUNITY SETTINGS
	 * --------------------------------------------------------------------
	 */
	public function Settings()
	{
		// Define selected menu item
		$menu = array("", "", "", "selected", "");

		// Timezone list
		$tz_offset = array(
			"-12" => "(UTC-12:00) International Date Line West",
			"-11" => "(UTC-11:00) Midway Island, Samoa",
			"-10" => "(UTC-10:00) Hawaii",
			"-9"  => "(UTC-09:00) Alaska",
			"-8"  => "(UTC-08:00) Pacific Time (US & Canada), Tijuana",
			"-7"  => "(UTC-07:00) Mountain Time (US & Canada), Chihuahua, La Paz",
			"-6"  => "(UTC-06:00) Central Time (US & Canada), Cental America, Mexico City",
			"-5"  => "(UTC-05:00) Eastern Time (US & Canada), Bogota, Lima, Rio Branco",
			"-4"  => "(UTC-04:00) Atlantic Time (Canada), Caracas, Santiago, Manaus",
			"-3"  => "(UTC-03:00) Brasilia, Sao Paulo, Buenos Aires, Montevideo",
			"-2"  => "(UTC-02:00) Mid-Atlantic",
			"-1"  => "(UTC-01:00) Azores, Cape Verde Is.",
			"0"   => "(UTC&#177;00:00) London, Lisboa, Reykjavik, Dublin",
			"1"   => "(UTC+01:00) Paris, Amsterdam, Berlin, Bern, Rome, West Central Africa",
			"2"   => "(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius",
			"3"   => "(UTC+03:00) Moscow, St. Petersburg, Nairobi, Kuwait, Baghdad",
			"4"   => "(UTC+04:00) Abu Dhabi, Baku, Muscat, Yerevan",
			"5"   => "(UTC+05:00) Islamabad, Karachi, Yekaterinburg, Tashkent",
			"5.5" => "(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi",
			"6"   => "(UTC+06:00) Astana, Dhaka, Almaty, Novosibirsk",
			"6.5" => "(UTC+06:30) Yangon (Rangoon)",
			"7"   => "(UTC+07:00) Bangkok, Hanoi, Jakarta, Krasnoyarsk",
			"8"   => "(UTC+08:00) Beijing, Hong Kong, Kuala Lumpur, Singapore, Perth, Taipei",
			"9"   => "(UTC+09:00) Tokyo, Osaka, Seoul, Yakutsk, Sapporo",
			"9.5" => "(UTC+09:30) Adelaide, Darwin",
			"10"  => "(UTC+10:00) Brisbane, Canberra, Melbourne, Sydney, Vladivostok",
			"11"  => "(UTC+11:00) Magadan, Solomon Is., New Caledonia",
			"12"  => "(UTC+12:00) Auckland, Wellington, Fiji, Marshall Is."
		);

		$settings['tz_list'] = "";

		foreach($tz_offset as $tz_value => $tz_name) {
			$selected = ($this->Session->member_info['time_offset'] == $tz_value) ? "selected" : "";
			$settings['tz_list'] .= "<option value=\"{$tz_value}\" {$selected}>{$tz_name}</option>\n";
		}

		// Language list
		$settings['lang_list'] = "";
		$this->Db->Query("SELECT * FROM c_languages WHERE active = 1 ORDER BY name;");

		while($lang = $this->Db->Fetch()) {
			$selected = ($this->Session->member_info['language'] == $lang['directory']) ? "selected" : "";
			$settings['lang_list'] .= "<option value=\"{$lang['directory']}\" {$selected}>{$lang['name']}</option>\n";
		}

		// Template list
		$settings['template_list'] = "";
		$this->Db->Query("SELECT * FROM c_templates WHERE active = 1 ORDER BY name;");

		while($template = $this->Db->Fetch()) {
			$selected = ($this->Session->member_info['template'] == $template['directory']) ? "selected" : "";
			$settings['template_list'] .= "<option value=\"{$template['directory']}\" {$selected}>{$template['name']}</option>\n";
		}

		// Return variables
		$this->Set("menu", $menu);
		$this->Set("settings", $settings);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: USER CONTROL PANEL - PASSWORD
	 * --------------------------------------------------------------------
	 */
	public function Password()
	{
		// Define selected menu item
		$menu = array("", "", "", "", "selected");

		// Return variables
		$this->Set("menu", $menu);
	}
}
