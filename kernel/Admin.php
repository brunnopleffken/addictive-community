<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Admin.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Kernel;

class Admin
{
	private $Db;

	/**
	 * --------------------------------------------------------------------
	 * GET DATABASE CLASS
	 * --------------------------------------------------------------------
	 */
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

	/**
	 * --------------------------------------------------------------------
	 * SHOW CONFIGURATION INSIDE AN INPUT[TEXT] OR TEXTAREA FIELD
	 * --------------------------------------------------------------------
	 */
	public function SelectConfig($field_name)
	{
		$this->Db->Query("SELECT value FROM c_config WHERE field = '{$field_name}';");
		$fetch = $this->Db->Fetch();

		if($fetch['value']) {
			return $fetch['value'];
		}
		else {
			return false;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * AUTOMATIC DROP-DOWN (<SELECT> TAG)
	 * --------------------------------------------------------------------
	 * $options = array("key" => 0, "value" => "")
	 */
	public function Dropdown($field_name, $options, $value = "", $class = "")
	{
		$str = "<select name='{$field_name}' class='{$class}'>";

		foreach($options as $k => $v) {
			$selected = ($k == $value) ? "selected" : "";
			$str .= "<option value='{$k}' {$selected}>{$v}</option>";
		}

		$str .= "</select>";

		return $str;
	}

	/**
	 * --------------------------------------------------------------------
	 * IF 1, THEN CHECKBOX IS CHECKED, OTHERWISE SHOW IT UNCHECKED
	 * --------------------------------------------------------------------
	 */
	public function SelectCheckbox($field_name)
	{
		$this->Db->Query("SELECT value FROM c_config WHERE field = '{$field_name}';");
		$fetch = $this->Db->Fetch();

		if($fetch['value']) {
			$str = "<input type='hidden' name='{$field_name}' value='0'><input type='checkbox' name='{$field_name}' value='1' checked>";
		}
		else {
			$str = "<input type='hidden' name='{$field_name}' value='0'><input type='checkbox' name='{$field_name}' value='1'>";
		}

		return $str;
	}

	/**
	 * --------------------------------------------------------------------
	 * IF 1, THEN CHECKBOX IS CHECKED, OTHERWISE SHOW IT UNCHECKED
	 * --------------------------------------------------------------------
	 */
	public function BooleanCheckbox($field_name, $field_value)
	{
		if($field_value) {
			$str = "<input type='hidden' name='{$field_name}' value='0'><input type='checkbox' name='{$field_name}' value='1' checked>";
		}
		else {
			$str = "<input type='hidden' name='{$field_name}' value='0'><input type='checkbox' name='{$field_name}' value='1'>";
		}

		return $str;
	}

	/**
	 * --------------------------------------------------------------------
	 * UPDATE CONFIGURATION TABLE
	 * FORMAT $config['field'] = "value" MUST BE USED!
	 * --------------------------------------------------------------------
	 */
	public function SaveConfig($post)
	{
		if(is_array($post)) {
			foreach($post as $k => $v) {
				$this->Db->Query("UPDATE c_config SET value = '{$v}' WHERE field = '{$k}';");
			}
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * ADD REGISTER TO ADMIN LOG
	 * --------------------------------------------------------------------
	 */
	public function RegisterLog($message)
	{
		$log = array(
			"member_id" => $_SESSION['admin_m_id'],
			"time"      => time(),
			"message"   => $message,
			"ip_addr"   => $_SERVER['REMOTE_ADDR']
		);
		$this->Db->Query("INSERT INTO c_logs (member_id, time, act, ip_address) VALUES
			('{$log['member_id']}', '{$log['time']}', '{$log['message']}', '{$log['ip_addr']}');");

		return true;
	}

	/**
	 * --------------------------------------------------------------------
	 * REPLACE TRUE/FALSE AND 1/0 INTO GREEN "YES" AND RED "NO"
	 * --------------------------------------------------------------------
	 */
	public function FriendlyBool($value) {
		if($value) {
			return "<span style='color:#090'>Yes</span>";
		}
		elseif(!$value) {
			return "<span style='color:#C00'>No</span>";
		}
		else {
			return false;
		}
	}
}
