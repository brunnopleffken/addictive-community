<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: usercp.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Define access method
	// ---------------------------------------------------
	
	// Deny guest access
	$this->Session->NoGuest();

	// ---------------------------------------------------
	// Get member information
	// ---------------------------------------------------

	$m_id = $this->Session->sInfo['member_id'];

	// ---------------------------------------------------
	// ACTIONS
	// ---------------------------------------------------

	$act = Html::Request("act");

	switch($act) {
		
		// ---------------------------------------------------
		// Edit member profile
		// ---------------------------------------------------
		
		case "profile":
			$info = array(
				"email"			=> Html::Request("email"),
				"member_title"	=> Html::Request("member_title"),
				"location"		=> Html::Request("location"),
				"profile"		=> Html::Request("profile"),
				"b_day"			=> Html::Request("b_day"),
				"b_month"		=> Html::Request("b_month"),
				"b_year"		=> Html::Request("b_year"),
				"gender"		=> Html::Request("gender"),
				"website"		=> Html::Request("website"),
				"im_facebook"	=> Html::Request("im_facebook"),
				"im_twitter"	=> Html::Request("im_twitter")
				);

			$this->Db->Update("c_members", $info, "m_id = {$m_id}");
			header("Location: index.php?module=usercp&m=1");

			exit;
			break;
		
		// ---------------------------------------------------
		// Edit signature
		// ---------------------------------------------------
		
		case "signature":
			$info = array(
				"signature" => Html::Request("signature")
				);

			$this->Db->Update("c_members", $info, "m_id = {$m_id}");
			header("Location: index.php?module=usercp&view=signature&m=3");

			exit;
			break;

		// ---------------------------------------------------
		// Edit board settings
		// ---------------------------------------------------
		
		case "settings":
			$info = array(
				"template" => Html::Request("template"),
				"language" => Html::Request("language"),
				"timezone_offset" => Html::Request("timezone")
				);

			$this->Db->Update("c_members", $info, "m_id = {$m_id}");
			header("Location: index.php?module=usercp&view=settings&m=4");

			exit;
			break;
		
		// ---------------------------------------------------
		// Alter password
		// ---------------------------------------------------
		
		case "password":
			$current = String::PasswordEncrypt(Html::Request("current"));
			$new_pass = String::PasswordEncrypt(Html::Request("new_password"));
			$c_pass = String::PasswordEncrypt(Html::Request("c_password"));
			
			$this->Db->Query("SELECT COUNT(*) AS result FROM c_members WHERE m_id = '{$m_id}' AND password = '{$current}';");
			$count = $this->Db->Fetch();
			$_count = $count['result'];
			
			if($_count == 0) {
				// If old password is wrong: redirect and show error message
				header("Location: index.php?module=usercp&view=password&m=6");
				exit;
			}
			elseif($new_pass != $c_pass) {
				// If password does not match: redirect and show error message
				header("Location: index.php?module=usercp&view=password&m=7");
				exit;
			}
			
			// Continue...
			$info = array("password" => $new_pass);
			$this->Db->Update("c_members", $info, "m_id = {$m_id}");
			header("Location: index.php?module=usercp&view=signature&m=5");

			exit;
			break;
	}

	// ---------------------------------------------------
	// MESSAGES AND NOTIFICATIONS
	// ---------------------------------------------------

	$m = Html::Request("m");

	switch($m) {
		case 1:
			$notification = Html::Notification("Your member profile has been changed successfully.", "success");
			break;
		case 2:
			$notification = Html::Notification("Your photo has been changed successfully.", "success");
			break;
		case 3:
			$notification = Html::Notification("Your signature has been changed successfully.", "success");
			break;
		case 4:
			$notification = Html::Notification("Your settings has been changed successfully.", "success");
			break;
		case 5:
			$notification = Html::Notification("Your password has been changed successfully.", "success");
			break;
		case 6:
			$notification = Html::Notification("Your old password is incorrect. Please, try again.", "failure");
			break;
		case 7:
			$notification = Html::Notification("The new password does not match (passwords are case-sensitive). Please, try again.", "failure");
			break;
		default:
			$notification = "";
			break;
	}

	// ---------------------------------------------------
	// Which page are we viewing?
	// ---------------------------------------------------
	
	// Which action is the user taking
	$view = (Html::Request("view")) ? Html::Request("view") : "profile";
	
	switch($view) {

		// ---------------------------------------------------
		// VIEW: edit profile
		// ---------------------------------------------------

		case "profile":

			$menu = array("selected", "", "", "", "");

			$profile['female']	= "";
			$profile['male']	= "";

			if($this->member['gender'] == "F") {
				$profile['female'] = "selected";
			}
			else {
				$profile['male'] = "selected";
			}

			break;

		// ---------------------------------------------------
		// VIEW: edit photo
		// ---------------------------------------------------

		case "photo":

			$menu = array("", "selected", "", "", "");

			// Gravatar or custom photo?
			$photo_info['gravatar'] = "";
			$photo_info['custom'] = "";

			if($this->member['photo_type'] == "gravatar") {
				$photo_info['gravatar'] = "checked";
			}
			else {
				$photo_info['custom'] = "checked";
			}

			// Gravatar
			$photo_info['gravatar_img_url'] = $this->Core->GetGravatar($this->member['email'], $this->member['photo'], 120, $this->member['photo_type']);


			break;

		// ---------------------------------------------------
		// VIEW: edit signature
		// ---------------------------------------------------

		case "signature":

			$menu = array("", "", "selected", "", "");

			// ...

			break;

		// ---------------------------------------------------
		// VIEW: edit settings
		// ---------------------------------------------------

		case "settings":

			$menu = array("", "", "", "selected", "");

			$tz_offset = array(
				"-12"	=> "(UTC-12:00) International Date Line West",
				"-11"	=> "(UTC-11:00) Midway Island, Samoa",
				"-10"	=> "(UTC-10:00) Hawaii",
				"-9"	=> "(UTC-09:00) Alaska",
				"-8"	=> "(UTC-08:00) Pacific Time (US & Canada), Tijuana",
				"-7"	=> "(UTC-07:00) Mountain Time (US & Canada), Chihuahua, La Paz",
				"-6"	=> "(UTC-06:00) Central America, Central Time (US & Canada), Mexico City",
				"-5"	=> "(UTC-05:00) Eastern Time (US & Canada), Bogota, Lima, Rio Branco",
				"-4"	=> "(UTC-04:00) Atlantic Time (Canada), Caracas, Santiago, Manaus",
				"-3"	=> "(UTC-03:00) Brasilia, Sao Paulo, Buenos Aires, Montevideo",
				"-2"	=> "(UTC-02:00) Mid-Atlantic",
				"-1"	=> "(UTC-01:00) Azores, Cape Verde Is.",
				"0"		=> "(UTC&#177;00:00) London, Casablanca, Reykjavik, Dublin",
				"1"		=> "(UTC+01:00) Paris, Amsterdam, Berlin, Bern, Rome, West Central Africa",
				"2"		=> "(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius",
				"3"		=> "(UTC+03:00) Moscow, St. Petersburg, Nairobi, Kuwait, Baghdad",
				"4"		=> "(UTC+04:00) Abu Dhabi, Baku, Muscat, Yerevan",
				"5"		=> "(UTC+05:00) Islamabad, Karachi, Yekaterinburg, Tashkent",
				"5.5"	=> "(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi",
				"6"		=> "(UTC+06:00) Astana, Dhaka, Almaty, Novosibirsk",
				"6.5"	=> "(UTC+06:30) Yangon (Rangoon)",
				"7"		=> "(UTC+07:00) Bangkok, Hanoi, Jakarta, Krasnoyarsk",
				"8"		=> "(UTC+08:00) Beijing, Hong Kong, Kuala Lumpur, Singapore, Perth, Taipei",
				"9"		=> "(UTC+09:00) Tokyo, Osaka, Seoul, Yakutsk, Sapporo",
				"9.5"	=> "(UTC+09:30) Adelaide, Darwin",
				"10"	=> "(UTC+10:00) Brisbane, Canberra, Melbourne, Sydney, Vladivostok",
				"11"	=> "(UTC+11:00) Magadan, Solomon Is., New Caledonia",
				"12"	=> "(UTC+12:00) Auckland, Wellington, Fiji, Marshall Is."
			);
			
			$settings['tz_list'] = "";

			foreach($tz_offset as $value => $name) {
				if($this->member['timezone_offset'] == $value) {
					$selected = "selected";
				}
				else {
					$selected = "";
				}
				
				$settings['tz_list'] .= "<option value=\"{$value}\" {$selected}>{$name}</option>\n";
			}

			// ...

			break;

		// ---------------------------------------------------
		// VIEW: edit password
		// ---------------------------------------------------

		case "password":

			$menu = array("", "", "", "", "selected");
			break;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------
	
	// Page information
	$pageinfo['title'] = "Control Panel";
	$pageinfo['bc'] = array("Control Panel");

?>