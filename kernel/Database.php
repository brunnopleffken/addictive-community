<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  https://github.com/addictivehub/addictive-community
#
#  File: Database.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## -------------------------------------------------------

namespace AC\Kernel;

class Database
{
	// Database connection settings
	private static $connection_config = array();

	// Database connection link
	private static $link;

	// Database result link of the last executed query
	private static $query;
	private static $resultset;

	// Log of queries of the current session
	private static $debug = false;
	private static $log = array();

	/**
	 * --------------------------------------------------------------------
	 * MANUALLY OPEN A NEW CONNECTION TO A MYSQL SERVER
	 * --------------------------------------------------------------------
	 */
	public static function connect($connection_config = array())
	{
		// Store configuration info as class property
		self::$connection_config = $connection_config;

		// Connect to MySQL server
		self::$link = @mysqli_connect(
			self::$connection_config['db_server'],
			self::$connection_config['db_username'],
			self::$connection_config['db_password'],
			self::$connection_config['db_database'],
			self::$connection_config['db_port']
		);

		// Show error message in case of error
		if(mysqli_connect_errno()) {
			self::databaseException(mysqli_connect_error());
			return false;
		}
		else {
			// Set response charset to UTF-8
			mysqli_set_charset(self::$link, "utf8");
			return true;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * SENDS A UNIQUE QUERY TO THE CURRENTLY ACTIVE DATABASE
	 * --------------------------------------------------------------------
	 */
	public static function query($sql, $ext_backtrace = 0)
	{
		// Execute SQL query
		self::$query = mysqli_query(self::$link, $sql);

		if(self::$debug) {
			$backtrace = ($ext_backtrace == 0) ? debug_backtrace() : $ext_backtrace;
			self::$log[] = [
				"sql" => preg_replace("/\n\s*/", " ", $sql),
				"backtrace" => [
					"file" => $backtrace[0]['file'],
					"line" => $backtrace[0]['line']
				]
			];
		}

		// In case of error...
		if(!self::$query) {
			self::databaseException(
				"An error occoured on the following query: " . $sql . "<br><br>"
				. "<textarea cols='90' rows='5'>" . self::$link->error . "</textarea>"
			);
		}

		return self::$query;
	}

	/**
	 * --------------------------------------------------------------------
	 * FETCH A RESULT ROW AS AN ASSOCIATIVE ARRAY (USED IN "WHILE" LOOPS)
	 * --------------------------------------------------------------------
	 */
	public static function fetch($result = null)
	{
		// If any SQL command is passed as parameter...
		if($result == null) {
			$result = self::$query;
		}

		// Fetch results from database
		self::$resultset = $result->fetch_assoc();

		return self::$resultset;
	}

	/**
	 * --------------------------------------------------------------------
	 * FETCH A RESULT ROW AS AN ASSOCIATIVE ARRAY TO BE USED IN CONFIG
	 * --------------------------------------------------------------------
	 */
	public static function fetchConfig($result = null)
	{
		// If any SQL command is passed as parameter...
		if($result == null) {
			$result = self::$query;
		}

		while($row = $result->fetch_assoc()) {
			$config[$row['field']] = $row['value'];
		}

		return $config;
	}

	/**
	 * --------------------------------------------------------------------
	 * FETCH A RESULT ROW AS AS A REGULAR ASSOCIATIVE ARRAY
	 * --------------------------------------------------------------------
	 */
	public static function fetchToArray($result = null)
	{
		$results_array = array();

		// If any SQL command is passed as parameter...
		if($result == null) {
			$result = self::$query;
		}

		while($row = $result->fetch_assoc()) {
			$results_array[] = $row;
		}

		return $results_array;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET NUMBER OF ROWS IN RESULT (FOR SELECT STATEMENTS ONLY)
	 * --------------------------------------------------------------------
	 */
	public static function rows($result = null)
	{
		if($result == null) {
			$result = self::$query;
		}

		return $result->num_rows;
	}

	/**
	 * --------------------------------------------------------------------
	 * NUMBER OF ROWS AFFECTED BY THE LAST INSERT/UPDATE/REPLACE/DELETE OP.
	 * --------------------------------------------------------------------
	 */
	public static function affectedRows()
	{
		self::$resultset = mysqli_affected_rows(self::$link);
		return self::$resultset;
	}

	/**
	 * --------------------------------------------------------------------
	 * INSERT DATA FROM AN ARRAY INTO A TABLE
	 * --------------------------------------------------------------------
	 */
	public static function insert($table, $array)
	{
		// Loop through array
		foreach($array as $f => $v) {
			$fields[] = $f;
			$values[] = "'" . $v . "'";
		}

		// Insert into table
		$sql = "INSERT INTO {$table} (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $values) . ");";

		// Save backtrace if debug is 'true' and run query
		$backtrace = (self::$debug) ? debug_backtrace() : null;
		self::$query = self::query($sql, $backtrace);

		// In case of error...
		if(!self::$query) {
			self::databaseException("An error occoured on the following query: " . $sql);
		}

		return self::$query;
	}

	/**
	 * --------------------------------------------------------------------
	 * UPDATE AN ENTRY ON DATABASE FROM AN ARRAY
	 * --------------------------------------------------------------------
	 */
	public static function update($table, $data, $where = 1)
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
			$sql = "UPDATE {$table} SET " . implode(", ", $fields) . " WHERE {$where};";
		}
		else {
			$sql = "UPDATE {$table} SET {$data} WHERE {$where};";
		}

		// Save backtrace if debug is 'true' and run query
		$backtrace = (self::$debug) ? debug_backtrace() : null;
		self::$query = self::query($sql, $backtrace);

		if(!self::$query) {
			self::databaseException("An error occoured on the following query: " . $sql);
		}

		return self::$query;
	}

	/**
	 * --------------------------------------------------------------------
	 * DELETE ENTRIES ON DATABASE
	 * --------------------------------------------------------------------
	 */
	public static function delete($table, $where = null)
	{
		if($where == null) {
			Html::throwError("You're running a Database::Delete() comand without WHERE.");
		}

		$sql = "DELETE FROM {$table} WHERE {$where};";

		// Save backtrace if debug is 'true' and run query
		$backtrace = (self::$debug) ? debug_backtrace() : null;
		self::$query = self::query($sql, $backtrace);

		if(!self::$query) {
			self::databaseException("An error occoured on the following query: " . $sql);
		}

		return self::$query;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET THE ID GENERATED IN THE LAST QUERY
	 * --------------------------------------------------------------------
	 */
	public static function getLastId()
	{
		return mysqli_insert_id(self::$link);
	}

	/**
	 * --------------------------------------------------------------------
	 * RETURN LOG OF EXECUTED QUERIES
	 * --------------------------------------------------------------------
	 */
	public static function log()
	{
		if(self::$debug) {
			$template = "<table class='table' style='margin:20px 0'>";
			$template .= "<thead><tr><th>Executed SQL Statements</th></tr></thead>";
			foreach(self::$log as $statement) {
				$template .= "<tr><td style='font-size: 12px'>";
				$template .= "<strong style='font-family:consolas,monospace'>{$statement['sql']}</strong><br>";
				$template .= "[{$statement['backtrace']['line']}] ";
				$template .= "{$statement['backtrace']['file']}";
				$template .= "</td></tr>";
			}
			$template .= "</table></div>";
		}
		else {
			$backtrace = debug_backtrace();
			$template = "<div class='alert alert-warning persistent'><strong>Unable to view logs!</strong>
				Database debug is disabled. Delete log viewer from {$backtrace[0]['file']} line {$backtrace[0]['line']}.</div>";
		}

		return $template;
	}

	/**
	 * --------------------------------------------------------------------
	 * SHOW MYSQL ERROR MESSAGE
	 * --------------------------------------------------------------------
	 */
	private static function databaseException($message = "")
	{
		if($message == "") {
			Html::throwError(mysqli_error(self::$link));
		}
		else {
			Html::throwError($message);
		}
	}
}
