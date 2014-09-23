<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.database.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ============================================================
	// CLASS INTERFACE
	// Main interface for Database() class
	// ============================================================
	
	interface IMySQL
	{
		public function Query($query);
		public function Fetch($result = "");
		public function Rows($result = "");
		public function Select($table, $where = "");
		public function Insert($table, $array);
		public function Delete($table, $where);
		public function GetLastID();
	}


	// ============================================================
	// MYSQL ABSTRACTION LAYER
	// Abstraction class for MySQL
	// ============================================================

	class Database implements IMySQL
	{
		private $prefix = "c_";
		
		private $link;		// MySQL connection reference
		private $query;		// store results from mysql_query()
		private $result;	// store results from mysql_fetch_array()
		
		public $log = array();
		
		// ---------------------------------------------------
		// Connect to MySQL database
		// ---------------------------------------------------

		public function __construct($config = array())
		{
			// Automatically connect to database if $config is passed as param
			if(!empty($config)) {
				$this->_Connect($config);
			}
			else {
				return false;
			}
		}
		
		protected function _Connect($config)
		{
			$this->prefix = @$config['db_prefix'];
			$this->link = @mysql_connect($config['db_server'], $config['db_username'], $config['db_password']);

			if(!$this->link) {
				$this->MysqlException();
				return false;
			}

			mysql_select_db($config['db_database']);
			$this->Query("SET NAMES UTF8;");
		}
		
		// ---------------------------------------------------
		// Send SQL command to server
		// ---------------------------------------------------
		
		public function Query($query)
		{
			if($this->prefix != "c_") {
				$query = preg_replace("/c_/", $this->prefix, $query);
			}

			$this->query = mysql_query($query, $this->link);
			
			if(!$this->query) {
				$this->MysqlException();
			}
			
			$this->log[] = $query;
			
			return $this->query;
		}
		
		// ---------------------------------------------------
		// Return results from Query(string) command
		// ---------------------------------------------------

		public function Fetch($result = "")
		{
			if($result == "") {
				$result = $this->query;
			}
			
			$this->result = mysql_fetch_array($result, MYSQL_ASSOC);
			
			return $this->result;
		}
		
		// ---------------------------------------------------
		// Get number of affected rows
		// ---------------------------------------------------

		public function Rows($result = "")
		{
			if($result == "") {
				$result = $this->query;
			}
			
			$this->result = mysql_num_rows($result);
			
			return $this->result;
		}
		
		// ---------------------------------------------------
		// Get data from a table
		// ---------------------------------------------------
		
		public function Select($table, $where = "")
		{
			if($where != "") {
				$where = "WHERE " . $where;
			}
			
			$query = "SELECT * FROM {$table} {$where};";
			$this->query = mysql_query($query);

			if(!$this->query) {
				$this->MysqlException();
			}
			
			$this->log[] = $query;
			return $this->query;
		}
		
		// ---------------------------------------------------
		// Insert data in a table from an array
		// ---------------------------------------------------

		public function Insert($table, $array)
		{
			foreach($array as $f => $v) {
				$fields[] = $f;
				$values[] = "'" . $v . "'";
			}
			
			$query = "INSERT INTO {$table} (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $values) . ");";
			$this->query = mysql_query($query);
			
			if(!$this->query) {
				$this->MysqlException();
			}
			
			$this->log[] = $query;
			return $this->query;
		}
		
		// ---------------------------------------------------
		// Update table from an array
		// ---------------------------------------------------

		public function Update($table, $array, $where)
		{
			foreach($array as $f => $v) {
				$fields[] = $f . " = '" . $v . "'";
			}
			
			$sql_query = "UPDATE {$table} SET " . implode(", ", $fields) . " WHERE {$where};";
			$this->query = $this->Query($sql_query);
			
			if(!$this->query) {
				$this->MysqlException();
			}
			
			$this->log[] = $query;
			
			return $this->query;
		}
		
		// ---------------------------------------------------
		// Delete an specific registry
		// ---------------------------------------------------

		public function Delete($table, $where)
		{
			$query = "DELETE FROM {$table} WHERE {$where};";
			$temp = mysql_query($query);
			$log[] = $query;
			return $temp;
		}
		
		// ---------------------------------------------------
		// Get last ID from last Query command (from AI)
		// ---------------------------------------------------

		public function GetLastID()
		{
			$id = mysql_insert_id($this->link);
			return $id;
		}

		// ---------------------------------------------------
		// In case of error...
		// ---------------------------------------------------

		private function MysqlException()
		{
			Html::Error("<b>MySQL Error:</b> " . mysql_error());
			exit;
		}
	}

?>