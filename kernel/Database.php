<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Database.php
#  License: GPLv2
#  Copyright: (c) 2015 - Addictive Community
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
	public function FetchConfig($result = "");
	public function FetchToArray($result = "");
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
	private $data = array();

	// Database connection
	private $link;

	// Database result link of the last executed query
	private $query;
	private $result;

	// Log of queries of the current session
	private $log = array();

	/**
	 * --------------------------------------------------------------------
	 * OPEN A CONNECTION TO A MYSQL SERVER
	 * --------------------------------------------------------------------
	 */
	public function __construct($config = array())
	{
		$this->_Connect($config);
	}

	protected function _Connect($config = array())
	{
		// Store configuration info as class property
		$this->data = $config;

		// Connect to MySQL server
		$this->link = @mysqli_connect(
			$this->data['db_server'],
			$this->data['db_username'],
			$this->data['db_password'],
			$this->data['db_database'],
			$this->data['db_port']
		);

		// Show error message in case of error
		if(mysqli_connect_errno()) {
			$this->Exception(mysqli_connect_error());
		}
		else {
			// Set response charset to UTF-8
			$this->Query("SET NAMES UTF8;");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * SENDS A UNIQUE QUERY TO THE CURRENTLY ACTIVE DATABASE
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
	 * FETCH A RESULT ROW AS AN ASSOCIATIVE ARRAY (USED IN "WHILE" LOOPS)
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
	 * FETCH A RESULT ROW AS AN ASSOCIATIVE ARRAY TO BE USED IN CONFIG
	 * --------------------------------------------------------------------
	 */
	public function FetchConfig($result = "")
	{
		// If any SQL command is passed as parameter...
		if($result == "") {
			$result = $this->query;
		}

		while($_result = mysqli_fetch_assoc($result)) {
			$_retval[$_result['field']] = $_result['value'];
		}

		return $_retval;
	}

	/**
	 * --------------------------------------------------------------------
	 * FETCH A RESULT ROW AS AS A REGULAR ASSOCIATIVE ARRAY
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
	 * GET NUMBER OF ROWS IN RESULT (FOR SELECT STATEMENTS ONLY)
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
	 * NUMBER OF ROWS AFFECTED BY THE LAST INSERT/UPDATE/REPLACE/DELETE OP.
	 * --------------------------------------------------------------------
	 */
	public function AffectedRows($result = "")
	{
		if($result == "") {
			$result = $this->link;
		}

		$this->result = mysqli_affected_rows($result);

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
	 * UPDATE AN ENTRY ON DATABASE FROM AN ARRAY
	 * --------------------------------------------------------------------
	 */
	public function Update($table, $data, $where = 1)
	{
		if(is_array($data)) {
			// Check if it's an associative array or a sequential array
			if(array_keys($data) !== range(0, count($data) - 1)) {
				foreach($data as $f => $v) {
					$fields[] = $f . " = '" . $v . "'";
				}
			}
			else {
				foreach($data as $f => $v) {
					$fields[] = $v;
				}
			}
			$sql_query = "UPDATE {$table} SET " . implode(", ", $fields) . " WHERE {$where};";
		}
		else {
			$sql_query = "UPDATE {$table} SET {$data} WHERE {$where};";
		}

		$this->query = $this->Query($sql_query);

		if(!$this->query) {
			$this->Exception("An error occoured on the following query: " . $sql);
		}

		$this->log[] = $this->query;

		return $this->query;
	}

	/**
	 * --------------------------------------------------------------------
	 * DELETE ENTRIES ON DATABASE
	 * --------------------------------------------------------------------
	 */
	public function Delete($table, $where = 1)
	{
		$sql_query = "DELETE FROM {$table} WHERE {$where};";
		$this->query = $this->Query($sql_query);

		if(!$this->query) {
			$this->Exception("An error occoured on the following query: " . $sql);
		}

		$this->log[] = $this->query;

		return $this->query;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET THE ID GENERATED IN THE LAST QUERY
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
