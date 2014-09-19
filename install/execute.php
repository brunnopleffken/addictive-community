<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: execute.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Initialize
	// ---------------------------------------------------

	// Load kernel modules
	require_once("../kernel/class.core.php");
	require_once("../kernel/class.html.php");
	require_once("../kernel/class.string.php");
	require_once("../kernel/class.database.php");

	// Get step number
	$step = Html::Request("step");
	// Get user data
	$data = $_POST;

	// --------------------------------------------
	// OK, let's do it!
	// --------------------------------------------

	switch($step) {
		// --------------------------------------------
		// Save configuration file
		// --------------------------------------------
		case 1:
			// Check if config.php is writable (CHMOD 777)
			if(is_writable("../config.php")) {
				$handle = fopen("../config.php", "w");
				
				$fileContent = "<?php
	// Addictive Community configuration file for MySQL
	\$config['db_server']   = \"{$data['db_server']}\";
	\$config['db_username'] = \"{$data['db_username']}\";
	\$config['db_password'] = \"{$data['db_password']}\";
	\$config['db_database'] = \"{$data['db_database']}\";
	\$config['db_prefix']   = \"c_\";
?>";

				if(fwrite($handle, $fileContent)) {
					$status = 1;
					fclose($handle);
				}
				else {
					$status = 0;
				}
			}
			else {
				$status = 0; // Error
			}

			$description = "Save configuration file";
			break;

		// --------------------------------------------
		// Check information and connect to database
		// --------------------------------------------
		case 2:
			// Get brand new config.php file
			require("../config.php");
			// Try to connect to database using config.php data
			$database = new Database($config);

			$status      = ($database) ? 1 : 0;
			$description = "Check information and connect to database";
			break;

		// --------------------------------------------
		// Extract table structure
		// --------------------------------------------
		case 3:
			// Get config file and connect to Database
			require("../config.php");
			$database = new Database($config);

			// Avoid PHP timeout
			set_time_limit(0);

			// Get SQL file and its content
			$file = "sql/tables.sql";
			$handle = fopen($file, "r");

			if($handle) {
				$sqlQuery = fread($handle, filesize($file));
				$queries = explode("\n", $sqlQuery);

				foreach($queries as $value) {
					$query = $database->Query($value);
				}

				$status = ($query) ? 1 : 0;
			}
			else {
				$status = 0;
			}

			$description = "Extract table structure";
			break;

		// --------------------------------------------
		// Insert initial data and settings
		// --------------------------------------------
		case 4:
			// Get config file and connect to Database
			require("../config.php");
			$database = new Database($config);
			$errors = false;

			// Insert static data from data.sql

			$file = "sql/data.sql";
			$handle = fopen($file, "r");

			if($handle) {
				$sqlQuery = fread($handle, filesize($file));
				$queries = explode("\n", $sqlQuery);

				foreach($queries as $value) {
					$query = $database->Query($value);
					if(!$query) {
						$errors = true;
					}
				}
			}

			// --------------------------------------------
			// Insert dynamic data
			// --------------------------------------------

			$communityInfo = array(
				'timestamp'      => time(),
				'community_name' => String::Sanitize($data['community_name']),
				'community_url'  => String::Sanitize($data['community_name'])
			);

			// Insert sample room, thread and post

			$sql[] = "INSERT INTO `c_rooms` (`r_id`, `name`, `description`, `url`, `order_n`, `lastpost_date`, `lastpost_thread`, `lastpost_member`, `invisible`, `rules_title`, `rules_text`, `rules_visible`, `read_only`, `password`, `upload`, `perm_view`, `perm_post`, `perm_reply`) VALUES (1, 'A Test Room', 'You can edit or remove this room at any time.', NULL, 1, {$communityInfo['timestamp']}, 10, 2, 0, '', '', 0, 0, '', 1, 'a:5:{i:0;s:3:\"V_1\";i:1;s:3:\"V_2\";i:2;s:3:\"V_3\";i:3;s:3:\"V_4\";i:4;s:3:\"V_5\";}', 'a:3:{i:0;s:3:\"V_1\";i:1;s:3:\"V_2\";i:2;s:3:\"V_3\";}', 'a:3:{i:0;s:3:\"V_1\";i:1;s:3:\"V_2\";i:2;s:3:\"V_3\";}');";
			$sql[] = "INSERT INTO `c_threads` (`t_id`, `title`, `author_member_id`, `replies`, `views`, `start_date`, `room_id`, `tags`, `announcement`, `lastpost_date`, `lastpost_member_id`, `moved_to`, `locked`, `approved`, `with_bestanswer`) VALUES (1, 'Welcome', 1, 1, 0, {$communityInfo['timestamp']}, 1, NULL, 0, {$communityInfo['timestamp']}, 1, NULL, 0, 1, 0);";
			$sql[] = "INSERT INTO `c_posts` (`p_id`, `author_id`, `thread_id`, `post_date`, `attach_id`, `attach_clicks`, `ip_address`, `post`, `edit_time`, `edit_author`, `best_answer`, `first_post`) VALUES (1, 1, 1, {$communityInfo['timestamp']}, NULL, NULL, '127.0.0.1', 'Welcome to your new Addictive Community.\nThis is simply a test message confirming that the installation was successful.', NULL, NULL, 0, 1);";

			// Insert configuration file

			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (1, 'general_communityname', '{$communityInfo['community_name']}', 'Your community name');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (2, 'general_communityurl', '{$communityInfo['community_url']}', 'Absolute URL to your community');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (3, 'general_websitename', 'My Website', 'Your website name');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (4, 'general_websiteurl', 'http://', 'Your website root URL');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (5, 'general_communitylogo', 'logo.png', 'File name (inside /images folder)');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (6, 'general_sidebar_online', 'true', 'Show users online in sidebar');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (7, 'general_sidebar_stats', 'true', 'Show statistics in sidebar');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (8, 'date_long_format', 'd M Y, H:i', 'Date and time long format');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (9, 'date_default_offset', '-3', 'Default offset for timezones (in seconds)');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (10, 'thread_posts_hot', '15', 'Minimum replies to a thread become a hot thread');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (11, 'seo_description', 'An Addictive Services product.', 'Description HTML Meta tags');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (12, 'seo_keywords', 'addictive, community, board', 'Keywords for HTML Meta tags');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (13, 'date_short_format', 'd M Y', 'Date short format');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (14, 'general_session_expiration', '900', 'Expires user session after X seconds (also cuts off from Members Online list)');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (15, 'thread_posts_per_page', '10', 'Number of posts per page in a thread');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (16, 'thread_best_answer_all_pages', 'false', 'Display the best answer on top of all thread pages (not only the first)');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (17, 'thread_obsolete', 'false', 'Turns a thread obsolete after X days, suggesting the member to create another thread');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (18, 'thread_obsolete_value', '30', 'Number of days for a thread become obsolete');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (19, 'emoticon_default_set', 'default', 'Sets default emoticon set');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (20, 'thread_allow_emoticons', 'true', 'Allow emoticons on thread posts');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (21, 'general_allow_guest_post', 'false', 'Allow guests to post');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (22, 'general_offline', 'false', 'Community is offline. Only administrators are allowed to view the board.');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (23, 'general_disable_registrations', 'false', 'The community is closed for new registrations.');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (24, 'general_bread_separator', '>', 'Breadcrumb separator.');";
			$sql[] = "INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES (25, 'member_pm_storage', '100', 'Storage size for personal messages');";

			foreach($sql as $value) {
				$query = $database->Query($value);
				if(!$query) {
					$errors = true;
				}
			}

			$status      = ($errors) ? 0 : 1;
			$description = "Insert initial data and settings";
			break;

		// --------------------------------------------
		// Save user information
		// --------------------------------------------
		case 5:
			// Get config file and connect to Database
			require("../config.php");
			$database = new Database($config);

			// Get administrator account data
			$adminInfo = array(
				'username' => String::Sanitize($data['admin_username']),
				'password' => String::PasswordEncrypt($data['admin_password']),
				'email'    => String::Sanitize($data['admin_email']),
				'joined'   => time()
			);

			// Build SQL

			$sqlInsertAdmin = "INSERT INTO `c_members` (`username`, `password`, `email`, `hide_email`, `ip_address`, `joined`, `usergroup`, `member_title`, `location`, `profile`, `gender`, `b_day`, `b_month`, `b_year`, `photo`, `photo_type`, `website`, `im_windowslive`, `im_skype`, `im_facebook`, `im_twitter`, `im_yim`, `im_aol`, `posts`, `lastpost_date`, `signature`, `template`, `language`, `warn_level`, `warn_date`, `last_activity`, `time_offset`, `dst`, `show_email`, `show_birthday`, `show_gender`) VALUES ('{$adminInfo['username']}', '{$adminInfo['password']}', '{$adminInfo['email']}', 0, '', {$adminInfo['joined']}, 1, '', '', '', '', 0, 0, 0, '', 'gravatar', '0', '', '', '', '', '', '', 1, 1367848084, '', 'default', 'en_US', NULL, NULL, 0, '0', 0, 1, 1, 1)";

			$insertAdmin = $database->Query($sqlInsertAdmin);

			$status      = ($insertAdmin) ? 1 : 0;
			$description = "Save user information";
			break;

		// --------------------------------------------
		// Lock installer
		// --------------------------------------------
		case 6:
			$status = (fopen(".lock", "w")) ? 1 : 0;
			$description = "Lock installer";
			break;

		default:
			$status = 0;
	}

	$data = array(
		'status' => $status,
		'step'   => $step,
		'time'   => microtime(),
		'desc'   => $description
	);

	echo json_encode($data);

?>
