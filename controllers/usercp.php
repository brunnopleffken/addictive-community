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

	$m_id = $this->member['m_id'];

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
		// Edit member photo
		// ---------------------------------------------------

		case "photo":

			// Get photo type
			$photoType = Html::Request("photo_type");

			// Do processes!

			if($photoType == "gravatar" || $photoType == "facebook") {
				// Change photo type to 'gravatar'
				$this->Db->Query("UPDATE c_members SET photo_type = '{$photoType}' WHERE m_id = '{$m_id}';");
				header("Location: index.php?module=usercp&view=photo&m=2");
				exit;
			}
			else {
				// User photo already hosted on community's server
				if($_FILES['file_upload']['name'] == "") {
					$this->Db->Query("UPDATE c_members SET photo_type = '{$photoType}' WHERE m_id = '{$m_id}';");
					header("Location: index.php?module=usercp&view=photo&m=2");
					exit;
				}
				else {
					// Allowed extensions (JPEG, GIF and PNG)
					$extAllow = array("jpg", "gif", "png");
					$extFile = end(explode(".", $_FILES['file_upload']['name']));

					if(in_array($extFile, $extAllow)) {
						// Select current photo, if exists
						$this->Db->Query("SELECT photo FROM c_members WHERE m_id = '{$m_id}';");
						$currentPhoto = $this->Db->Fetch();
						$currentPhoto = ($currentPhoto['photo'] != "") ? $currentPhoto['photo'] : null;

						// Delete special characters and diacritics
						$_FILES['file_upload']['name'] = ereg_replace(
								"[^a-zA-Z0-9_.]", "",
								strtr($_FILES['file_upload']['name'],
										"áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ",
										"aaaaeeiooouucAAAAEEIOOOUUC_")
								);

						// Delete current photo, if exists (avoid duplicate files)
						if(file_exists("public/avatar/{$currentPhoto}")) {
							unlink("public/avatar/{$currentPhoto}");
						}

						// Do upload!
						$newFileName = $m_id . "." . $extFile;
						move_uploaded_file($_FILES['file_upload']['tmp_name'], "public/avatar/" . $newFileName);
						chmod(__DIR__ . "/public/avatar/" . $newFileName, 0666);

						$this->Db->Query("UPDATE c_members SET photo_type = '{$photoType}',
							photo = '{$newFileName}' WHERE m_id = '{$m_id}';");

						// Redirect
						header("Location: index.php?module=usercp&view=photo&m=2");
						exit;
					}

					exit;
				}
			}

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
				"time_offset" => Html::Request("timezone")
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
			$notification = Html::Notification(i18n::Translate("C_MESSAGE_1"), "success");
			break;
		case 2:
			$notification = Html::Notification(i18n::Translate("C_MESSAGE_2"), "success");
			break;
		case 3:
			$notification = Html::Notification(i18n::Translate("C_MESSAGE_3"), "success");
			break;
		case 4:
			$notification = Html::Notification(i18n::Translate("C_MESSAGE_4"), "success");
			break;
		case 5:
			$notification = Html::Notification(i18n::Translate("C_MESSAGE_5"), "success");
			break;
		case 6:
			$notification = Html::Notification(i18n::Translate("C_MESSAGE_6"), "failure");
			break;
		case 7:
			$notification = Html::Notification(i18n::Translate("C_MESSAGE_7"), "failure");
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
			$photo_info['facebook'] = "";
			$photo_info['custom'] = "";

			// Notification if Facebook account is not set
			$facebook_info = "";

			if($this->member['photo_type'] == "gravatar") {
				$photo_info['gravatar'] = "checked";
			}
			elseif($this->member['photo_type'] == "facebook") {
				$photo_info['facebook'] = "checked";
			}
			else {
				$photo_info['custom'] = "checked";
			}

			if($this->member['im_facebook'] == "") {
				$photo_info['facebook'] = "disabled";
				$facebook_info = Html::Notification("You must fill in the \"Facebook\" text field in order to use your Facebook photo as avatar.", "info");
			}

			// Gravatar and Facebook image
			$photo_info['gravatar_image'] = $this->Core->GetGravatar($this->member['email'], $this->member['photo'], 240, "gravatar");
			$photo_info['facebook_image'] = $this->Core->GetGravatar($this->member['email'], $this->member['photo'], 240, "facebook");

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

			// Timezone list

			$tz_offset = array(
				"-12"	=> "(UTC-12:00) International Date Line West",
				"-11"	=> "(UTC-11:00) Midway Island, Samoa",
				"-10"	=> "(UTC-10:00) Hawaii",
				"-9"	=> "(UTC-09:00) Alaska",
				"-8"	=> "(UTC-08:00) Pacific Time (US & Canada), Tijuana",
				"-7"	=> "(UTC-07:00) Mountain Time (US & Canada), Chihuahua, La Paz",
				"-6"	=> "(UTC-06:00) Central Time (US & Canada), Cental America, Mexico City",
				"-5"	=> "(UTC-05:00) Eastern Time (US & Canada), Bogota, Lima, Rio Branco",
				"-4"	=> "(UTC-04:00) Atlantic Time (Canada), Caracas, Santiago, Manaus",
				"-3"	=> "(UTC-03:00) Brasilia, Sao Paulo, Buenos Aires, Montevideo",
				"-2"	=> "(UTC-02:00) Mid-Atlantic",
				"-1"	=> "(UTC-01:00) Azores, Cape Verde Is.",
				"0"		=> "(UTC&#177;00:00) London, Lisboa, Reykjavik, Dublin",
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
				$selected = ($this->member['time_offset'] == $value) ? "selected" : "";
				$settings['tz_list'] .= "<option value=\"{$value}\" {$selected}>{$name}</option>\n";
			}

			// Language list

			$settings['lang_list'] = "";

			$this->Db->Query("SELECT * FROM c_languages WHERE active = 1 ORDER BY name;");
			while($lang = $this->Db->Fetch()) {
				$selected = ($this->info['language'] == $lang['directory']) ? "selected" : "";
				$settings['lang_list'] .= "<option value=\"{$lang['directory']}\" {$selected}>{$lang['name']}</option>\n";
			}

			// Template list

			$settings['template_list'] = "";

			$this->Db->Query("SELECT * FROM c_templates WHERE active = 1 ORDER BY name;");
			while($template = $this->Db->Fetch()) {
				$selected = ($this->info['template'] == $template['directory']) ? "selected" : "";
				$settings['template_list'] .= "<option value=\"{$template['directory']}\" {$selected}>{$template['name']}</option>\n";
			}

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
	$pageinfo['title'] = i18n::Translate("C_TITLE");
	$pageinfo['bc'] = array(i18n::Translate("C_TITLE"));

?>
