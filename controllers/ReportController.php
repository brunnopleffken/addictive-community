<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  https://github.com/addictivehub/addictive-community
#
#  File: Report.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\Http;
use \AC\Kernel\Session\SessionState;

class Report extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * PERFORM ACTIONS BEFORE RUN API METHODS
	 * --------------------------------------------------------------------
	 */
	public function beforeAction()
	{
		// Yeah, to avoid SPAM guests cannot send reports
		SessionState::noGuest();
	}

	/**
	 * --------------------------------------------------------------------
	 * REPORT A POST
	 * --------------------------------------------------------------------
	 */
	public function post($post_id)
	{
		$this->master = "Ajax";

		// Return variables
		$this->Set("post_id", $post_id);
	}

	/**
	 * --------------------------------------------------------------------
	 * REPORT A THREAD
	 * --------------------------------------------------------------------
	 */
	public function thread($thread_id)
	{
		$this->master = "Ajax";

		// Return variables
		$this->Set("thread_id", $thread_id);
	}

	/**
	 * --------------------------------------------------------------------
	 * SAVE REPORT ON DATABASE
	 * --------------------------------------------------------------------
	 */
	public function save()
	{
		$this->layout = false;

		// Check if user is reporting a post or a thread
		if(!Http::request("post_id", true)) {
			$mode = "thread";
			$thread_id = Http::request("thread_id", true);
		}
		else {
			$mode = "post";
			$post_id = Http::request("post_id", true);

			Database::query("SELECT thread_id FROM c_posts WHERE p_id = {$post_id};");
			$result = Database::fetch();

			$thread_id = $result['thread_id'];
		}

		// Build report
		$report_info = array(
			"description" => Http::request("description"),
			"reason"      => Http::request("reason", true),
			"date"        => time(),
			"sender_id"   => Http::request("member_id", true),
			"ip_address"  => $_SERVER['REMOTE_ADDR'],
			"post_id"     => ($post_id) ? $post_id : "0",
			"thread_id"   => ($thread_id) ? $thread_id : "0",
			"referer"     => $_SERVER['HTTP_REFERER']
		);

		// Save report on DB
		Database::insert("c_reports", $report_info);

		// Redirect to its respective notification
		if($mode == "thread") {
			$this->Core->redirect("thread/" . $report_info['thread_id'] . "?m=1");
		}
		else {
			$this->Core->redirect("thread/" . $report_info['thread_id'] . "?m=2");
		}
	}
}
