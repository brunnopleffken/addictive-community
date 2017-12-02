<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  https://github.com/addictivehub/addictive-community
#
#  File: Articles.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\Http;
use \AC\Kernel\i18n;
use \AC\Kernel\Session\SessionState;
use \AC\Kernel\Text;
use \AC\Kernel\Upload;

class Articles extends Application
{
	public $master = "Articles";

	/**
	 * --------------------------------------------------------------------
	 * HOME - VIEW LAST 10 ARTICLES OF EACH CATEGORY
	 * --------------------------------------------------------------------
	 */
	public function index()
	{
		// Get last 10 articles
		Database::query("SELECT * FROM c_articles ORDER BY post_date DESC LIMIT 10;");
		$articles = Database::fetchToArray();

		// Page info
		$page_info['title'] = i18n::translate("articles.title");
		$page_info['bc'] = array(i18n::translate("articles.title"));
		$this->Set("page_info", $page_info);

		// Set variables
		$this->Set("is_member", SessionState::isMember());
		$this->Set('articles', $articles);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW AN ARTICLE
	 * --------------------------------------------------------------------
	 */
	public function read($id)
	{
		$this->Set('teste', $id);
	}

	/**
	 * --------------------------------------------------------------------
	 * CREATE NEW ARTICLE
	 * --------------------------------------------------------------------
	 */
	public function add()
	{
		// Do not allow guests
		SessionState::noGuest();

		// Get categories
		Database::query("SELECT * FROM c_article_categories;");
		$categories = Database::fetchToArray();

		// Variables
		$page_info['title'] = i18n::translate("articles.add.title");
		$page_info['bc'] = array(i18n::translate("articles.title"), i18n::translate("articles.add.title"));
		$this->Set("page_info", $page_info);

		$this->Set("categories", $categories);
	}

	/**
	 * --------------------------------------------------------------------
	 * SAVE NEW ARTICLE IN DATABASE
	 * --------------------------------------------------------------------
	 */
	public function save()
	{
		// This page has no layout
		$this->layout = false;

		// Format new article array
		$article = [
			"member_id"   => SessionState::$user_data['m_id'],
			"title"       => Http::Request("title"),
			"slug"        => Text::slug(htmlspecialchars_decode(Http::request("title"), ENT_QUOTES)),
			"content"     => str_replace("'", "&apos;", $_POST['post']),
			"post_date"   => time()
		];

		// Send attachments
		if(Http::getFile("cover_image")) {
			$Upload = new Upload();
			$article['cover_image'] = $Upload->sendAttachment(Http::getFile("cover_image"), $article['member_id']);
		}

		// Insert into database
		Database::insert("c_articles", $article);

		// Redirect
		$this->Core->redirect("articles");
	}
}
