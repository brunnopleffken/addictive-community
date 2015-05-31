<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Usercp.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
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
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: USER CONTROL PANEL - DASHBOARD
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		// Define selected menu item
		$menu = array("selected", "", "", "", "", "");

		// Get total posts
		$posts_total = $this->Session->member_info['posts'];

		// Get register date
		$register_date_timestamp = $this->Session->member_info['joined'];
		$register_date = $this->Core->DateFormat($register_date_timestamp);

		// Calculate average of posts per day
		$days = (time() - $register_date_timestamp) / DAY;
		$average_posts = round($posts_total / floor($days), 1);

		// Get number of private messages
		$this->Db->Query("SELECT COUNT(*) AS total FROM c_messages
				WHERE to_id = {$this->Session->member_info['m_id']};");
		$pm = $this->Db->Fetch();

		$space_left = $this->config['member_pm_storage'] - $pm['total'];

		// Page info
		$page_info['title'] = i18n::Translate("C_TITLE");
		$page_info['bc'] = array(i18n::Translate("C_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("menu", $menu);
		$this->Set("posts_total", $posts_total);
		$this->Set("register_date", $register_date);
		$this->Set("posts_average", $average_posts);
		$this->Set("pm_total", $pm['total']);
		$this->Set("pm_space_left", $space_left);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: USER CONTROL PANEL - PROFILE
	 * --------------------------------------------------------------------
	 */
	public function Profile()
	{
		// Define selected menu item
		$menu = array("", "selected", "", "", "", "");

		// Define messages
		$message_id = Html::Request("m");
		$notification = array("",
			Html::Notification(i18n::Translate("C_MESSAGE_1"), "success")
		);

		// Gender
		if($this->Session->member_info['gender'] == "F") {
			$profile['male']   = "";
			$profile['female'] = "selected";
		}
		else {
			$profile['male']   = "selected";
			$profile['female'] = "";
		}

		// Page info
		$page_info['title'] = i18n::Translate("C_TITLE");
		$page_info['bc'] = array(i18n::Translate("C_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("menu", $menu);
		$this->Set("member", $this->Session->member_info);
		$this->Set("profile", $profile);
		$this->Set("notification", $notification[$message_id]);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: USER CONTROL PANEL - EDIT USER PHOTO
	 * --------------------------------------------------------------------
	 */
	public function Photo()
	{
		// Define selected menu item
		$menu = array("", "", "selected", "", "", "");

		// Define messages
		$message_id = Html::Request("m");
		$notification = array("",
			Html::Notification(i18n::Translate("C_MESSAGE_2"), "success")
		);

		// Gravatar or custom photo?
		$photo_info['gravatar'] = "";
		$photo_info['facebook'] = "";
		$photo_info['custom']   = "";

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

		// Page info
		$page_info['title'] = i18n::Translate("C_TITLE");
		$page_info['bc'] = array(i18n::Translate("C_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("menu", $menu);
		$this->Set("photo_info", $photo_info);
		$this->Set("notification", $notification[$message_id]);
		$this->Set("enable_upload", $this->config['general_member_enable_avatar_upload']);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: USER CONTROL PANEL - EDIT SIGNATURE
	 * --------------------------------------------------------------------
	 */
	public function Signature()
	{
		// Define selected menu item
		$menu = array("", "", "", "selected", "", "");

		// Define messages
		$message_id = Html::Request("m");
		$notification = array("",
			Html::Notification(i18n::Translate("C_MESSAGE_3"), "success")
		);

		// Page info
		$page_info['title'] = i18n::Translate("C_TITLE");
		$page_info['bc'] = array(i18n::Translate("C_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("menu", $menu);
		$this->Set("notification", $notification[$message_id]);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: USER CONTROL PANEL - COMMUNITY SETTINGS
	 * --------------------------------------------------------------------
	 */
	public function Settings()
	{
		// Define selected menu item
		$menu = array("", "", "", "", "selected", "");

		// Define messages
		$message_id = Html::Request("m");
		$notification = array("",
			Html::Notification(i18n::Translate("C_MESSAGE_4"), "success")
		);

		// Timezone list
		$tz_offset = array(
			"-12" => "(UTC-12:00) International Date Line West",
			"-11" => "(UTC-11:00) Midway Island, Samoa",
			"-10" => "(UTC-10:00) Hawaii",
			"-9"  => "(UTC-09:00) Alaska",
			"-8"  => "(UTC-08:00) Pacific Time (US & Canada), Tijuana",
			"-7"  => "(UTC-07:00) Mountain Time (US & Canada), Chihuahua, La Paz",
			"-6"  => "(UTC-06:00) Central Time (US & Canada), Cental America, Ciudad de México",
			"-5"  => "(UTC-05:00) Eastern Time (US & Canada), Bogotá, Lima, Rio Branco",
			"-4"  => "(UTC-04:00) Atlantic Time (Canada), Caracas, Santiago, Manaus",
			"-3"  => "(UTC-03:00) Brasília, São Paulo, Buenos Aires, Montevideo",
			"-2"  => "(UTC-02:00) Mid-Atlantic",
			"-1"  => "(UTC-01:00) Azores, Cape Verde Is.",
			"0"   => "(UTC&#177;00:00) London, Lisboa, Reykjavík, Dublin",
			"1"   => "(UTC+01:00) Paris, Amsterdam, Berlin, Bern, Roma, West Central Africa",
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
			$settings['tz_list'] .= "<option value='{$tz_value}' {$selected}>{$tz_name}</option>\n";
		}

		// Language list
		$settings['lang_list'] = "";
		$this->Db->Query("SELECT * FROM c_languages WHERE is_active = 1 ORDER BY name;");

		while($lang = $this->Db->Fetch()) {
			$selected = ($this->Session->member_info['language'] == $lang['directory']) ? "selected" : "";
			$settings['lang_list'] .= "<option value='{$lang['directory']}' {$selected}>{$lang['name']}</option>\n";
		}

		// Template list
		$settings['theme_list'] = "";
		$this->Db->Query("SELECT * FROM c_themes WHERE is_active = 1 ORDER BY name;");

		while($theme = $this->Db->Fetch()) {
			$selected = ($this->Session->member_info['theme'] == $theme['directory']) ? "selected" : "";
			$settings['theme_list'] .= "<option value='{$theme['directory']}' {$selected}>{$theme['name']}</option>\n";
		}

		// Page info
		$page_info['title'] = i18n::Translate("C_TITLE");
		$page_info['bc'] = array(i18n::Translate("C_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("menu", $menu);
		$this->Set("settings", $settings);
		$this->Set("notification", $notification[$message_id]);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: USER CONTROL PANEL - PASSWORD
	 * --------------------------------------------------------------------
	 */
	public function Password()
	{
		// Define selected menu item
		$menu = array("", "", "", "", "", "selected");

		// Define messages
		$message_id = Html::Request("m");
		$notification = array("",
			Html::Notification(i18n::Translate("C_MESSAGE_5"), "success"),
			Html::Notification(i18n::Translate("C_MESSAGE_6"), "failure"),
			Html::Notification(i18n::Translate("C_MESSAGE_7"), "failure")
		);

		// Page info
		$page_info['title'] = i18n::Translate("C_TITLE");
		$page_info['bc'] = array(i18n::Translate("C_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("menu", $menu);
		$this->Set("notification", $notification[$message_id]);
	}

	/**
	 * --------------------------------------------------------------------
	 * SAVE: MEMBER PROFILE
	 * --------------------------------------------------------------------
	 */
	public function SaveProfile()
	{
		$this->layout = false;

		// Get values
		$info = array(
			"email"        => Html::Request("email"),
			"member_title" => Html::Request("member_title"),
			"location"     => Html::Request("location"),
			"profile"      => Html::Request("profile"),
			"b_day"        => Html::Request("b_day"),
			"b_month"      => Html::Request("b_month"),
			"b_year"       => Html::Request("b_year"),
			"gender"       => Html::Request("gender"),
			"website"      => Html::Request("website"),
			"im_facebook"  => Html::Request("im_facebook"),
			"im_twitter"   => Html::Request("im_twitter")
		);

		// Save and redirect...
		$this->Db->Update("c_members", $info, "m_id = {$this->member_id}");
		$this->Core->Redirect("usercp/profile?m=1");
	}

	/**
	 * --------------------------------------------------------------------
	 * SAVE: MEMBER PHOTO
	 * --------------------------------------------------------------------
	 */
	public function SavePhoto()
	{
		$this->layout = false;

		// Get photo type
		$photo_type = Html::Request("photo_type");

		// Do processes!
		if($photo_type == "gravatar" || $photo_type == "facebook") {
			// Change photo type to 'gravatar'
			$this->Db->Query("UPDATE c_members SET photo_type = '{$photo_type}' WHERE m_id = '{$this->member_id}';");
			$this->Core->Redirect("usercp/photo?m=1");
		}
		else {
			// User photo already hosted on community's server
			if($_FILES['file_upload']['name'] == "") {
				$this->Db->Query("UPDATE c_members SET photo_type = '{$photo_type}' WHERE m_id = '{$this->member_id}';");
				$this->Core->Redirect("usercp/photo?m=1");
			}
			else {
				// Allowed extensions (JPEG, GIF and PNG)
				$allowed_extensions = array("jpg", "gif", "png");
				$file_extension = end(explode(".", $_FILES['file_upload']['name']));

				if(in_array($file_extension, $allowed_extensions)) {
					// Select current photo, if exists
					$this->Db->Query("SELECT photo FROM c_members WHERE m_id = '{$this->member_id}';");
					$current_photo = $this->Db->Fetch();
					$current_photo = ($current_photo['photo'] != "") ? $current_photo['photo'] : null;

					// Delete special characters and diacritics
					$_FILES['file_upload']['name'] = preg_replace(
						"/[^a-zA-Z0-9_.]/", "",
						strtr($_FILES['file_upload']['name'],
							"áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ",
							"aaaaeeiooouucAAAAEEIOOOUUC_"
						)
					);

					// Delete current photo, if exists (avoid duplicate files)
					if(is_file("public/avatar/{$current_photo}")) {
						unlink("public/avatar/{$current_photo}");
					}

					// Do upload!
					$new_file_name = $this->member_id . "." . $file_extension;
					move_uploaded_file($_FILES['file_upload']['tmp_name'], "public/avatar/" . $new_file_name);

					$this->Db->Query("UPDATE c_members SET photo_type = '{$photo_type}',
							photo = '{$new_file_name}' WHERE m_id = '{$this->member_id}';");

					// Redirect
					$this->Core->Redirect("usercp/photo?m=1");
				}
				exit;
			}
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * SAVE: SIGNATURE
	 * --------------------------------------------------------------------
	 */
	public function SaveSignature()
	{
		$this->layout = false;

		// Get values
		$info = array(
			"signature" => $_POST['signature']
		);

		// Save and redirect...
		$this->Db->Update("c_members", $info, "m_id = {$this->member_id}");
		$this->Core->Redirect("usercp/signature?m=1");
	}

	/**
	 * --------------------------------------------------------------------
	 * SAVE:COMMUNITY SETTINGS
	 * --------------------------------------------------------------------
	 */
	public function SaveSettings()
	{
		$this->layout = false;

		// Get values
		$info = array(
			"theme"       => Html::Request("theme"),
			"language"    => Html::Request("language"),
			"time_offset" => Html::Request("timezone")
		);

		// Save and redirect...
		$this->Db->Update("c_members", $info, "m_id = {$this->member_id}");
		$this->Core->Redirect("usercp/settings?m=1");
	}

	/**
	 * --------------------------------------------------------------------
	 * SAVE: NEW PASSWORD
	 * --------------------------------------------------------------------
	 */
	public function SavePassword()
	{
		$this->layout = false;

		// Hash
		$salt = array(
			"hash" => $this->config['security_salt_hash'],
			"key"  => $this->config['security_salt_key']
		);

		// Get values
		$current   = String::PasswordEncrypt(Html::Request("current"), $salt);
		$new_pass  = String::PasswordEncrypt(Html::Request("new_password"), $salt);
		$conf_pass = String::PasswordEncrypt(Html::Request("conf_password"), $salt);

		// Check if member and password matches
		$this->Db->Query("SELECT COUNT(*) AS result FROM c_members WHERE m_id = '{$this->member_id}' AND password = '{$current}';");
		$count = $this->Db->Fetch();

		if($count['result'] == 0) {
			// If old password is wrong: redirect and show error message
			$this->Core->Redirect("usercp/password?m=2");
		}
		elseif($new_pass != $conf_pass) {
			// If password does not match: redirect and show error message
			$this->Core->Redirect("usercp/password?m=3");
		}

		// Continue...
		$info = array("password" => $new_pass);
		$this->Db->Update("c_members", $info, "m_id = {$this->member_id}");

		// Redirect
		$this->Core->Redirect("usercp/password?m=1");
	}
}
