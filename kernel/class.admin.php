<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.admin.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	class Admin
	{
		private $Db;

		// Get Database class

		public function __construct($Database)
		{
			if($Database) {
				// Get Database class
				$this->Db = $Database;
			}
			else {
				return false;
			}
		}

		// Show config inside a input text or textarea field
		
		public function SelectConfig($field_name)
		{
			$this->Db->Query("SELECT value FROM c_config WHERE `index` = '{$field_name}';");
			$fetch = $this->Db->Fetch();
			
			return $fetch['value'];
		}
		
		// If 1, then checkbox is checked; otherwise, show it unchecked.
		
		public function SelectCheckbox($field_name)
		{
			$this->Db->Query("SELECT value FROM c_config WHERE `index` = '{$field_name}';");
			$fetch = $this->Db->Fetch();
			
			if($fetch['value'] == 1 or $fetch['value'] == "true") {
				$str = "<input type=\"hidden\" name=\"{$field_name}\" value=\"false\"><input type=\"checkbox\" name=\"{$field_name}\" value=\"true\" checked>";
			}
			else {
				$str = "<input type=\"hidden\" name=\"{$field_name}\" value=\"false\"><input type=\"checkbox\" name=\"{$field_name}\" value=\"true\">";
			}
			
			return $str;
		}
		
		// Update configuration table.
		// Format $config['index'] = "value" must be used!
		
		public function SaveConfig($post)
		{
			if(is_array($post)) {
				foreach($post as $k => $v) {
					$this->Db->Query("UPDATE c_config SET value = '{$v}' WHERE `index` = '{$k}';");
				}
				return true;
			}
			else {
				return false;
			}
		}
		
		// Register log
		
		public function RegisterLog($message)
		{
			$log = array(
				"member_id"	=> $_SESSION['admin_m_id'],
				"time"		=> time(),
				"message"	=> $message,
				"ip_addr"	=> $_SERVER['REMOTE_ADDR']
			);

			$this->Db->Query("INSERT INTO c_logs (member_id, time, act, ip_address) VALUES
				('{$log['member_id']}', '{$log['time']}', '{$log['message']}', '{$log['ip_addr']}');");
			
			return true;
		}
		
		// Replace true/false and 1/0 into green "Yes" or red "No"

		public function FriendlyBool($value) {
			if($value == 1 or $value == "true") {
				return "<span style='color:#090'>Yes</span>";
			}
			elseif($value == 0 or $value == "false") {
				return "<span style='color:#C00'>No</span>";
			}
			else {
				return false;
			}
		}

	}
	
?>