<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Search.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\i18n;

class Search extends Application
{
	// Search term
	private $term = false;

	// If 'no_results' is true, show start page
	private $no_search = true;

	/**
	 * --------------------------------------------------------------------
	 * VIEW SEARCH ENGINE RESULTS
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		// Declare empty variables
		$warning = "";
		$search_results = array();

		// Get term
		$this->term = Http::Request("q");

		if($this->term) {
			// Perform FULLTEXT search
			$search_results = $this->_PerformSearch();

			// Has search
			$this->no_search = false;
		}

		// Number of results
		$num_results = count($search_results);

		// Page info
		$page_info['title'] = i18n::Translate("S_TITLE");
		$page_info['bc'] = array(i18n::Translate("S_TITLE"));
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("term", $this->term);
		$this->Set("no_search", $this->no_search);
		$this->Set("num_results", $num_results);
		$this->Set("warning", $warning);
		$this->Set("results", $search_results);
	}

	/**
	 * --------------------------------------------------------------------
	 * PERFORM FULLTEXT SEARCH
	 * --------------------------------------------------------------------
	 */
	private function _PerformSearch()
	{
		// Empty results array
		$results = array();

		// Split keyword for highlighting
		$term_highlight = explode(" ", $this->term);

		// Are we searching in an specific post in a thread?
		$mode = Http::Request("mode");
		switch($mode) {
			case "post":
				$where = "thread_id = '{$id}' AND";
				break;
			default:
				$where = "";
				break;
		}

		// Sort by relevance or date?
		$sort = Http::Request("sort");
		switch($sort) {
			case "date":
				$order = "ORDER BY post_date DESC";
				break;
			default:
				$order = "";
				break;
		}

		// Perform database query
		Database::Query("SELECT t.t_id, t.title, t.slug, m.username, p.post_date, p.post FROM c_posts p
				INNER JOIN c_threads t ON (p.thread_id = t.t_id)
				INNER JOIN c_members m ON (p.author_id = m.m_id)
				WHERE {$where} MATCH(post) AGAINST ('{$this->term}') {$order}
				LIMIT 100;");

		if(Database::Rows() >= 100) {
			// Too many results
			$warning = Html::Notification("There are too many results for this search. Please try your search again with more specific keywords.", "warning", true);
		}
		else {
			while($result = Database::Fetch()) {
				$result['post_date'] = $this->Core->DateFormat($result['post_date']);

				foreach($term_highlight as $words) {
					$result['post'] = preg_replace("/\b{$words}\b/mi", "<mark>$0</mark>", $result['post']);
				}

				$results[] = $result;
			}
		}

		return $results;
	}
}
