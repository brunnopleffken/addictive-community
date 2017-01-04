<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Profile.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\i18n;
use \AC\Kernel\Template;
use \AC\Kernel\Text;
use \AC\Kernel\Upload;

class Profile extends Application
{
	// Basic memeber information
	public $info = array();

	/**
	 * --------------------------------------------------------------------
	 * COMMON ACTIONS: RETURN MEMBER INFORMATION
	 * --------------------------------------------------------------------
	 */
	public function _BeforeAction($id)
	{
		// Fetch member information
		Database::Query("SELECT c_members.*, c_usergroups.name,
				(SELECT COUNT(*) FROM c_posts WHERE c_posts.author_id = c_members.m_id AND best_answer = 1) as bestanswers
				FROM c_members LEFT JOIN c_usergroups ON (c_usergroups.g_id = c_members.usergroup)
				WHERE m_id = '{$id}';");
		$info = Database::Fetch();

		if($info['usergroup'] == 0 || empty($info)) {
			$this->Core->Redirect("failure?t=deleted_member");
			exit;
		}

		// Member avatar
		$info['avatar'] = $this->Core->GetAvatar($info, 300);
		$info['cover'] = $this->Core->GetAvatar($info, 1024);

		// Readable join date
		$info['joined'] = $this->Core->DateFormat($info['joined'], "short");

		$this->info = $info;

		// Return $profile_member_id in all pages
		$this->Set("profile_member_id", $id);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW PROFILE MAIN PAGE
	 * --------------------------------------------------------------------
	 */
	public function Index($id)
	{
		// Birthday and age
		$has_birthday = ($this->info['b_year']) ? true : false;
		if($has_birthday) {
			$this->info['birthday_timestamp'] = mktime(12, 0, 0, $this->info['b_month'], $this->info['b_day'], $this->info['b_year']);
			$this->info['birthday'] = $this->Core->DateFormat($this->info['birthday_timestamp'], "short");
			$this->info['age'] = Text::MemberAge($this->info['birthday_timestamp']);
		}

		// Member gender icon
		switch($this->info['gender']) {
			case 'M':
				$this->info['gender'] = "<i class='fa fa-fw fa-mars'></i> Male";
				break;
			case 'F':
				$this->info['gender'] = "<i class='fa fa-fw fa-venus'></i> Female";
				break;
			default:
				$this->info['gender'] = "---";
				break;
		}

		// Location and Google Maps link
		$this->info['location_encoded'] = urlencode($this->info['location']);
		$this->info['location'] = "<a href='https://maps.google.com/maps?q={$this->info['location_encoded']}' target='_blank'>{$this->info['location']}</a>";

		// Personal website link
		$this->info['website'] = "<a href='{$this->info['website']}' rel='nofollow' target='_blank'>{$this->info['website']}</a>";

		// Member e-mail
		if($this->info['hide_email'] == 1) {
			$this->info['email'] = "<em>" . i18n::Translate("P_PRIVATE") . "</em>";
		}
		else {
			$this->info['email'] = "<a href='mailto:" . $this->info['email'] . "'>" . $this->info['email'] . "</a>";
		}

		// Page info
		$page_info['title'] = $this->info['username'];
		$page_info['bc'] = array(i18n::Translate("P_PROFILE") . ": " . $this->info['username']);
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("info", $this->info);
		$this->Set("has_birthday", $has_birthday);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW LAST POSTS
	 * --------------------------------------------------------------------
	 */
	public function Posts($id)
	{
		// Select threads
		Database::Query("SELECT t_id, title, slug, start_date FROM c_threads
				WHERE author_member_id = '{$id}' AND approved = '1'
				ORDER BY start_date DESC LIMIT 5;");

		while($threads = Database::Fetch()) {
			$threads['start_date'] = $this->Core->DateFormat($threads['start_date'], "long");
			Template::Add ("<tr>
				<td class='min text-muted'>{$threads['start_date']}</td>
				<td><a href='thread/{$threads['t_id']}-{$threads['slug']}'>{$threads['title']}</a></td>
			</tr>");
		}

		$thread_list = Template::Get();
		Template::Clean();

		// Select posts
		Database::Query("SELECT p.post_date, p.post, t.t_id, t.title, t.slug FROM c_posts p
				INNER JOIN c_threads t ON (t.t_id = p.thread_id)
				WHERE author_id = '{$id}'
				ORDER BY post_date DESC LIMIT 5;");

		while($posts = Database::Fetch()) {
			$posts['post_date'] = $this->Core->DateFormat($posts['post_date'], "long");
			Template::Add("<tr>
				<td class='min text-muted'>{$posts['post_date']}</td>
				<td><a href='thread/{$posts['t_id']}-{$posts['slug']}'><b>{$posts['title']}</b></a></td>
			</tr>
			<tr>
				<td colspan='2' class='parsing' style='border-bottom: 1px solid #eee; padding: 0 10px 10px 10px'>
					{$posts['post']}
				</td>
			</tr>");
		}

		$post_list = Template::Get();
		Template::Clean();

		// Page info
		$page_info['title'] = $this->info['username'];
		$page_info['bc'] = array(i18n::Translate("P_PROFILE") . ": " . $this->info['username']);
		$this->Set("page_info", $page_info);

		// Return HTML templates
		$this->Set("info", $this->info);
		$this->Set("thread_list", $thread_list);
		$this->Set("post_list", $post_list);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW MEMBER ATTACHMENTS
	 * --------------------------------------------------------------------
	 */
	public function Attachments($id)
	{
		$attachments = array();

		// Instance of Upload() class
		$Upload = new Upload($this->Db);

		// Select all attachments of a user
		Database::Query("SELECT * FROM c_attachments WHERE member_id = '{$id}' AND private = 0;");

		while($result = Database::Fetch()) {
			$url = "public/attachments/{$id}/{$result['date']}/{$result['filename']}";

			$result['icon'] = "<div class='file-icon {$result['type']}' style='font-size: 24px'></div>";
			$result['filename'] = "<a href='{$url}' target='_blank'>{$result['filename']}</a>";
			$result['type'] = $Upload->TranslateFileType($result['type']);
			$result['size'] = Text::FileSizeFormat($result['size']);

			$attachments[] = $result;
		}

		// Page info
		$page_info['title'] = $this->info['username'];
		$page_info['bc'] = array(i18n::Translate("P_PROFILE") . ": " . $this->info['username']);
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("info", $this->info);
		$this->Set("attachments", $attachments);
	}
}
