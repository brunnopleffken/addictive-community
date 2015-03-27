<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: index.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

// Absolute path on server filesystem
define("BASEPATH", dirname(__FILE__));

// Run initialization file before loading Main()
require("init.php");

class Main
{
	// Controller, Action and ID
	public $controller = "";
	public $action = "";
	public $id = "";

	// Current template and language
	public $template;
	public $language;

	// Instances of non-static Kernel classes
	public $Core;
	public $Db;
	public $Session;

	// Configurations
	public $Config = array();

	// Controller instance
	private $instance;

	// Page content
	private $content;
	private $head;

	// Defined variables inside controller
	private $view_data;

	// Development environment
	const DEBUG = true;

	/**
	 * --------------------------------------------------------------------
	 * MAIN CLASS CONSTRUCTOR
	 * --------------------------------------------------------------------
	 */
	public function __construct()
	{
		// Load kernel classes
		$this->_LoadKernel();

		// Load configuration file
		require("config.php");

		// Instance of Database() class
		$this->Db = new Database($config);

		// Get query strings from URL
		$this->controller = strtolower(Html::Request("c"));
		$this->action = strtolower(Html::Request("act"));
		$this->id = strtolower(Html::Request("id"));

		// If there isn't any controller defined
		if(!$this->controller) {
			$this->controller = "community";
		}

		// Initialize Session() class
		$this->Session = new Session($this->Db, $this->controller);
		$this->Session->UpdateSession();

		// Get settings from database
		$this->_GetConfig();
		$this->Core = new Core($this->Db, $this->Config);

		// Get current template and language
		$this->_GetTemplate();
		$this->_GetLanguage();

		// OK, let's go...
		$this->_LoadController($this->controller, $this->action);
		$this->_LoadView($this->controller, $this->action);
	}

	/**
	 * --------------------------------------------------------------------
	 * LOAD MAIN KERNEL/CORE MODULES
	 * --------------------------------------------------------------------
	 */
	private function _LoadKernel()
	{
		require("kernel/Core.php");
		require("kernel/Database.php");
		require("kernel/Html.php");
		require("kernel/i18n.php");
		require("kernel/Session.php");
		require("kernel/String.php");
		require("kernel/Template.php");
		require("kernel/Upload.php");
	}

	/**
	 * --------------------------------------------------------------------
	 * LOAD CONTROLLER AND FIRST METHOD
	 * --------------------------------------------------------------------
	 */
	private function _LoadController($controller, $action = "")
	{
		// Controllers' name are in UpperCamelCase, but URLs in lowercase
		$_controller = $this->controller = ucwords($controller);

		// Load Application controller
		require("controllers/Application.php");

		// Load controller
		require("controllers/" . $_controller . ".php");
		$this->instance = new $_controller();

		// Get and execute action passed by URL, if any
		if($action != "") {
			$action = $this->_FormatActionName($this->action);
		}
		else {
			$action = $this->action = "Main";
		}

		// Create an instance of non-static Kernel classes in Application controller
		$this->instance->Db = $this->Db;
		$this->instance->Core = $this->Core;
		$this->instance->Session = $this->Session;

		// Create a copy of community settings in Application controller
		$this->instance->config = $this->Config;

		// Execute Controller::_beforeFilter() method
		if(method_exists($this->instance, "_beforeFilter")) {
			$this->instance->_beforeFilter($this->id);
		}

		// Execute Controller with the provided method
		$this->instance->Run();
		$this->instance->$action($this->id);

		// Execute Controller::_afterFilter() method
		if(method_exists($this->instance, "_afterFilter")) {
			$this->instance->_afterFilter($this->id);
		}

		// Get defined variables
		$this->view_data = $this->instance->Get();
	}

	/**
	 * --------------------------------------------------------------------
	 * LOAD MASTER PAGE AND VIEW
	 * --------------------------------------------------------------------
	 */
	private function _LoadView($controller, $action)
	{
		if($this->instance->HasLayout()) {
			// Get defined variables in controller
			foreach($this->view_data as $k => $v) {
				$$k = $v;
			}

			// Load page content
			ob_start();
			require("templates/" . $this->template . "/" . $this->controller . "." . ucwords($this->action) . ".php");
			$this->content = ob_get_clean();

			// Load master page
			require("templates/" . $this->template . "/" . $this->instance->master . ".php");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * CONVERT string_with_underscore TO StringWithUnderscore
	 * --------------------------------------------------------------------
	 */
	private function _FormatActionName($action_name)
	{
		$action_name = preg_replace("/(_)/", " ", $action_name);
		$action_name = preg_replace("/([\s])/", "", ucwords($action_name));
		return $action_name;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET DEFAULT TEMPLATE OR, IF LOGGED IN, THE ONE DEFINED BY MEMBER
	 * --------------------------------------------------------------------
	 */
	private function _GetTemplate()
	{
		$this->template = "default";
	}

	/**
	 * --------------------------------------------------------------------
	 * GET CONFIGURATIONS FROM DATABASE
	 * --------------------------------------------------------------------
	 */
	public function _GetConfig()
	{
		$this->Db->Query("SELECT * FROM c_config;");
		$this->Config = $this->Db->FetchArray();
	}

	/**
	 * --------------------------------------------------------------------
	 * GET DEFUALT LANGUAGE OR, IF LOGGED IN, THE ONE DEFINED BY MEMBER
	 * --------------------------------------------------------------------
	 */
	private function _GetLanguage()
	{
		$this->language = "en_US";

		// Load language files
		include("languages/" . $this->language . "/default.php");
		@include("languages/" . $this->language . "/" . $this->controller . ".php");

		// Populate dictionary array
		foreach($t as $k => $v) {
			i18n::$dictionary[$k] = $v;
		}
	}
}

$main = new Main();

?>
