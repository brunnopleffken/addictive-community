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
				if($this->member['time_offset'] == $value) {
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