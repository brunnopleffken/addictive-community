<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Database.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

/**
 * --------------------------------------------------------------------
 * DATABASE CONNECTION CLASS INTERFACE
 * --------------------------------------------------------------------
 */
interface IDatabase
{
	public function Query($query);
	public function Fetch($result = "");
	public function FetchArray($result = "");
	public function Rows($result = "");
	public function Insert($table, $array);
	public function Update($table, $array, $where);
	public function GetLastID();
}

/**
 * --------------------------------------------------------------------
 * MYSQL DATABASE CLASS
 * --------------------------------------------------------------------
 */
class Database implements IDatabase
{
	// Database connection information
	private $data = array(
		"server"   => "localhost",
		"username" => "root",
		"password" => "root",
		"database" => "homefront",
		"charset"  => "utf-8"
	);

	// Database connection
	private $link;

	// Database result link of the last executed query
	private $query;
	private $result;

	// Log of queries of the current session
	private $queries = array();

	/**
	 * --------------------------------------------------------------------
	 * CONNECT TO MYSQL DATABASE
	 * --------------------------------------------------------------------
	 */
	public function __construct($config = array())
	{
		// Store configuration info as class property
		$this->data = $config;

		// Connect to MySQL server
		$this->link = @mysqli_connect(
			$this->data['db_server'],
			$this->data['db_username'],
			$this->data['db_password'],
			$this->data['db_database']
		);

		// Show error message... in case of error...
		if(mysqli_connect_errno()) {
			$this->Exception("Unable to connect to MySQL server.");
		}
		else {
			// Set response charset to UTF-8
			$this->Query("SET NAMES UTF8;");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * SDASDASADSDA
	 * --------------------------------------------------------------------
	 */
	public function Query($sql)
	{
		// Execute SQL query
		$this->query = mysqli_query($this->link, $sql);
		$this->log[] = $sql;

		// In case of error...
		if(!$this->query) {
			$this->Exception("An error occoured on the following query: " . $sql);
		}

		return $this->query;
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURN RESULTS FROM QUERY() COMMAND
	 * --------------------------------------------------------------------
	 */
	public function Fetch($result = "")
	{
		// If any SQL command is passed as parameter...
		if($result == "") {
			$result = $this->query;
		}

		// Fetch results from database
		$this->result = mysqli_fetch_assoc($result);

		return $this->result;
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURN RESULTS FROM QUERY() COMMAND AND STORE INTO AN ARRAY
	 * --------------------------------------------------------------------
	 */
	public function FetchArray($result = "")
	{
		// If any SQL command is passed as parameter...
		if($result == "") {
			$result = $this->query;
		}

		while($_result = mysqli_fetch_assoc($result)) {
			$_retval[$_result['index']] = $_result['value'];
		}

		return $_retval;
	}

	/**
	 * --------------------------------------------------------------------
	 * FETCH RESULTS INTO AN ARRAY
	 * --------------------------------------------------------------------
	 */
	public function FetchToArray($result = "")
	{
		$_retval = array();

		// If any SQL command is passed as parameter...
		if($result == "") {
			$result = $this->query;
		}

		while($_result = mysqli_fetch_assoc($result)) {
			$_retval[] = $_result;
		}

		return $_retval;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET NUMBER OF AFFECTED ROWS
	 * --------------------------------------------------------------------
	 */
	public function Rows($result = "")
	{
		if($result == "") {
			$result = $this->query;
		}

		$this->result = mysqli_num_rows($result);

		return $this->result;
	}

	/**
	 * --------------------------------------------------------------------
	 * INSERT DATA FROM AN ARRAY INTO A TABLE
	 * --------------------------------------------------------------------
	 */
	public function Insert($table, $array)
	{
		// Loop through array
		foreach($array as $f => $v) {
			$fields[] = $f;
			$values[] = "'" . $v . "'";
		}

		// Insert into table
		$sql = "INSERT INTO {$table} (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $values) . ");";
		$this->query = mysqli_query($this->link, $sql);
		$this->log[] = $sql;

		// In case of error...
		if(!$this->query) {
			$this->Exception("An error occoured on the following query: " . $sql);
		}

		return $this->query;
	}

	/**
	 * --------------------------------------------------------------------
	 * UPDATE DATABASE TABLE FROM AN ARRAY
	 * --------------------------------------------------------------------
	 */
	public function Update($table, $array, $where)
	{
		foreach($array as $f => $v) {
			$fields[] = $f . " = '" . $v . "'";
		}

		$sql_query = "UPDATE {$table} SET " . implode(", ", $fields) . " WHERE {$where};";
		$this->query = $this->Query($sql_query);

		if(!$this->query) {
			$this->Exception("An error occoured on the following query: " . $sql);
		}

		$this->log[] = $this->query;

		return $this->query;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET LAST ID FROM LAST QUERY() COMMAND
	 * --------------------------------------------------------------------
	 */
	public function GetLastID()
	{
		$id = mysqli_insert_id($this->link);
		return $id;
	}

	/**
	 * --------------------------------------------------------------------
	 * SHOW MYSQL ERROR MESSAGE
	 * --------------------------------------------------------------------
	 */
	private function Exception($message = "")
	{
		if($message == "") {
			Html::Error(mysqli_error());
		}
		else {
			Html::Error($message);
		}
	}
}
