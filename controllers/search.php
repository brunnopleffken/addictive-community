<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: search.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Execute search
	// ---------------------------------------------------

	// Are we searching in an specific post?

	$mode = $this->Core->QueryString("mode");

	switch($mode) {
		case "post":
			$where = "thread_id = '{$id}' AND";
			break;
		default:
			$where = "";
			break;
	}

	// Sort by relevance or date?

	$sort = $this->Core->QueryString("sort");

	if($sort == "date") {
		$order = "ORDER BY post_date DESC";
	}
	else {
		$order = "";
	}

	// Build query
	
	$_result = array();
	$keyword = $this->Core->QueryString("q");
	$keyHighlight = explode(" ", $keyword);

	$this->Db->Query("SELECT t.t_id, t.title, m.m_id, m.username, m.email, p.author_id, p.post_date, p.post,
		MATCH(p.post) AGAINST ('{$keyword}') AS relevance FROM c_posts p
		INNER JOIN c_threads t ON (p.thread_id = t.t_id)
		INNER JOIN c_members m ON (p.author_id = m.m_id)
		WHERE {$where} MATCH(post) AGAINST ('{$keyword}') {$order};");

	while($result = $this->Db->Fetch()) {
		$result['post_date'] = $this->Core->DateFormat($result['post_date']);
		$result['relevance'] = round($result['relevance'], 2);
		
		foreach($keyHighlight as $words) {
			$result['post'] = str_ireplace($words, "<b style=\"background: #ffa\">" . $words . "</b>",
				String::RemoveBBcode($result['post']));
		}
		
		$_result[] = $result;
	}
	
	$numResults = count($_result);

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------

	// Page information
	$pageinfo['title'] = "Search";
	$pageinfo['bc'] = array("Search");

?>