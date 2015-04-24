<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Search.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

class Search extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * VIEW SEARCH ENGINE RESULTS
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		// Declare empty variables
		$warning = "";
		$_result = array();

		// Get keyword
		$keyword = Html::Request("q");

		// Are we searching in an specific post?
		$mode = Html::Request("mode");
		switch($mode) {
			case "post":
				$where = "thread_id = '{$id}' AND";
				break;
			default:
				$where = "";
				break;
		}

		// Sort by relevance or date?
		$sort = Html::Request("sort");
		switch($sort) {
			case "date":
				$order = "ORDER BY post_date DESC";
				break;
			default:
				$order = "";
				break;
		}

		// Split keyword for highlighting
		$keyword_highlight = explode(" ", $keyword);

		// Perform database query
		$this->Db->Query("SELECT t.t_id, t.title, m.m_id, m.username, m.email, p.author_id, p.post_date, p.post,
				MATCH(p.post) AGAINST ('{$keyword}') AS relevance FROM c_posts p
				INNER JOIN c_threads t ON (p.thread_id = t.t_id)
				INNER JOIN c_members m ON (p.author_id = m.m_id)
				WHERE {$where} MATCH(post) AGAINST ('{$keyword}') {$order}
				LIMIT 100;");

		// Too many results
		if($this->Db->Rows() >= 90) {
			$warning = Html::Notification("There are too many results for this search. Please try your search again with more specific keywords.", "warning", true);
		}

		while($result = $this->Db->Fetch()) {
			$result['post_date'] = $this->Core->DateFormat($result['post_date']);
			$result['relevance'] = round($result['relevance'], 2);

			foreach($keyword_highlight as $words) {
				$result['post'] = preg_replace("/{$words}/mi", "<b style='background: #ffa'>$0</b>", $result['post']);
			}

			$_result[] = $result;
		}

		// Number of results
		$num_results = count($_result);

		// Return variables
		$this->Set("keyword", $keyword);
		$this->Set("num_results", $num_results);
		$this->Set("warning", $warning);
		$this->Set("results", $_result);
	}
}
