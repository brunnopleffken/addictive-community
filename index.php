<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: index.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC;

use \AC\Kernel\Core;
use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\i18n;
use \AC\Kernel\Session\SessionState;
use \AC\Kernel\Text;

// Run initialization file before loading Main()
require("init.php");

class Main
{
	// Controller, Action and ID
	protected $controller = "";
	protected $action = "";
	protected $id = "";

	// Current theme, template and language
	private $theme;
	private $template;
	private $language;

	// Instances of non-static Kernel classes
	private $Core;

	// Configurations
	private $Config = array();

	// Controller instance
	private $instance;

	// Page content
	private $content;
	private $head;

	// Defined variables inside controller
	private $view_data;

	/**
	 * --------------------------------------------------------------------
	 * MAIN CLASS CONSTRUCTOR
	 * --------------------------------------------------------------------
	 */
	public function __construct()
	{
		// Load kernel classes
		spl_autoload_register('_AutoLoader', true, true);

		// Load configuration file
		if(is_file("config.ini")) {
			$config = parse_ini_file("config.ini");
		}
		else {
			Html::Error("Configuration file is missing.");
		}

		// If config.ini is empty, go to Addictive Community installer
		if(empty($config)) {
			header("Location: install/");
			exit;
		}

		// Instance of Database() class
		Database::Connect($config);

		// Get query strings from URL
		$this->controller = strtolower(Http::Request("c"));
		$this->action = strtolower(Http::Request("act"));
		$this->id = Http::Request("id", true);

		// If there isn't any controller defined
		if(!$this->controller) {
			$this->controller = "community";
		}

		// Initialize Session() class
		SessionState::Initialize($this->controller);
		SessionState::UpdateSession();

		// Get settings from database
		$this->_GetConfig();

		// Get current template and language
		$this->_GetTemplate();
		$this->_GetLanguage();

		// Instantiate class Core()
		$this->Core = new Core($this->Config, SessionState::$user_data);

		// OK, let's go...
		$this->_LoadController($this->controller, $this->action);
		$this->_LoadView($this->controller, $this->action);
	}

	/**
	 * --------------------------------------------------------------------
	 * LOAD CONTROLLER AND FIRST METHOD
	 * --------------------------------------------------------------------
	 */
	private function _LoadController($controller, $action = "")
	{
		// Controllers names are in UpperCamelCase, but URLs in lowercase
		$controller = $this->controller = ucwords($controller);
		$action = ($action != "") ? Text::FormatActionName($this->action) : $this->action = "Main";

		// Redirect to Error 404 page if controller doesn't exist
		if(!file_exists("controllers/" . $controller . ".php")) {
			$action = str_replace("index.php", "", $_SERVER['PHP_SELF']);
			header("Location: " . $action . "500");
		}

		// Load Application controller
		require("controllers/Application.php");
		require("controllers/" . $controller . ".php");
		$controller = "\\AC\\Controllers\\" . $controller;
		$this->instance = new $controller();

		// Create an instance of non-static Kernel classes in Application controller
		$this->instance->Core = $this->Core;

		// Execute Controller::_BeforeAction() method
		if(method_exists($this->instance, "_BeforeAction")) {
			$this->instance->_BeforeAction($this->id);
		}

		// Execute Controller with the provided action method
		if(method_exists($this->instance, $action)) {
			$this->instance->Run();
			$this->instance->$action($this->id);
		}
		else {
			$action = str_replace("index.php", "", $_SERVER['PHP_SELF']);
			header("Location: " . $action . "500");
		}

		// Execute Controller::_AfterAction() method
		if(method_exists($this->instance, "_AfterAction")) {
			$this->instance->_AfterAction($this->id);
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
			$page_info = array();

			// Get defined variables in controller
			foreach($this->view_data as $k => $v) {
				$$k = $v;
			}

			$breadcrumb = $this->Core->Breadcrumb($page_info);
			$page_title = $this->Core->PageTitle($page_info);

			// Load page content
			ob_start();
			require("templates/" . $this->template . "/" . $this->controller . "." . Text::FormatActionName($this->action) . ".phtml");
			$this->content = ob_get_clean();

			// Load master page
			require("templates/" . $this->template . "/layouts/" . $this->instance->master . ".phtml");
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * GET CONFIGURATIONS FROM DATABASE
	 * --------------------------------------------------------------------
	 */
	public function _GetConfig()
	{
		Database::Query("SELECT * FROM c_config;");
		$this->Config = Database::FetchConfig();
	}

	/**
	 * --------------------------------------------------------------------
	 * GET DEFAULT TEMPLATE OR, IF LOGGED IN, THE ONE DEFINED BY MEMBER
	 * --------------------------------------------------------------------
	 */
	private function _GetTemplate()
	{
		if(SessionState::$user_data['m_id']) {
			// Get member-defined theme
			$this->Config['theme'] = $this->theme = SessionState::$user_data['theme'];
			$this->Config['template'] = $this->template = SessionState::$user_data['template'];
		}
		else {
			// Get default theme set
			$this->Config['theme'] = $this->theme = $this->Config['theme_default_set'];
			$this->Config['template'] = $this->template = $this->Config['template_default_set'];
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * GET DEFUALT LANGUAGE OR, IF LOGGED IN, THE ONE DEFINED BY MEMBER
	 * --------------------------------------------------------------------
	 */
	private function _GetLanguage()
	{
		// Get default or member set language
		if(SessionState::$user_data['m_id']) {
			$this->language = SessionState::$user_data['language'];
		}
		else {
			$this->language = $this->Config['language_default_set'];
		}

		// Store selected language in $this->Config
		$this->Config['language'] = $this->language;

		// Load language files
		@include("languages/" . $this->language . "/default.php");
		@include("languages/" . $this->language . "/" . $this->controller . ".php");

		// Populate dictionary array
		if(@is_array($t)) {
			foreach($t as $k => $v) {
				i18n::$dictionary[$k] = $v;
			}
		}
		else {
			echo Html::Notification(
				"Language files or keywords are missing for <b>" . $this->language . "</b>.", "failure", true
			);
		}
	}
}

new Main();
