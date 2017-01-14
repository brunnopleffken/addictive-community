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
	private $core;

	// Configurations
	private $config = array();

	// Controller instance
	private $instance;

	// Page content
	private $content;

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
			Html::throwError("Configuration file is missing.");
		}

		// If config.ini is empty, go to Addictive Community installer
		if(empty($config)) {
			header("Location: install/");
			exit;
		}

		// Instance of Database() class
		Database::connect($config);

		// Get query strings from URL
		$this->controller = strtolower(Http::request("c"));
		$this->action = strtolower(Http::request("act"));
		$this->id = Http::request("id", true);

		// If there isn't any controller defined
		if(!$this->controller) {
			$this->controller = "community";
		}

		// Initialize Session() class
		SessionState::initialize($this->controller);
		SessionState::updateSession();

		// Get settings from database
		$this->getConfig();

		// Get current template and language
		$this->getTemplate();
		$this->getLanguage();

		// Instantiate class Core()
		$this->core = new Core($this->config);

		// OK, let's go...
		$this->loadController();
		$this->loadView();
	}

	/**
	 * --------------------------------------------------------------------
	 * LOAD CONTROLLER AND FIRST METHOD
	 * --------------------------------------------------------------------
	 */
	private function loadController()
	{
		// Controllers names are in UpperCamelCase, but URLs in lowercase
		$controller = $this->controller = ucwords($this->controller);
		$action = ($this->action != "") ? Text::lowerCamelCase($this->action) : $this->action = "index";

		// Redirect to Error 404 page if controller doesn't exist
		if(!file_exists("controllers/" . $controller . "Controller.php")) {
			$action = str_replace("index.php", "", $_SERVER['PHP_SELF']);
			header("Location: " . $action . "500");
		}

		// Load Application controller
		require("controllers/ApplicationController.php");
		require("controllers/" . $controller . "Controller.php");
		$controller = "\\AC\\Controllers\\" . $controller;
		$this->setController($controller);

		// Execute Controller::_BeforeAction() method
		if(method_exists($this->instance, "beforeAction")) {
			$this->instance->beforeAction($this->id);
		}

		// Execute Controller with the provided action method
		if(method_exists($this->instance, $action)) {
			$this->instance->runApplication();
			$this->instance->$action($this->id);
		}
		else {
			$action = str_replace("index.php", "", $_SERVER['PHP_SELF']);
			header("Location: " . $action . "500");
		}

		// Execute Controller::_AfterAction() method
		if(method_exists($this->instance, "afterAction")) {
			$this->instance->afterAction($this->id);
		}

		// Get defined variables
		$this->view_data = $this->instance->get();
	}

	/**
	 * --------------------------------------------------------------------
	 * SET CONTROLLER TO $this->instance
	 * --------------------------------------------------------------------
	 */
	private function setController($controller)
	{
		$this->instance = new $controller($this->core);
	}

	/**
	 * --------------------------------------------------------------------
	 * LOAD MASTER PAGE AND VIEW
	 * --------------------------------------------------------------------
	 */
	private function loadView()
	{
		if($this->instance->hasLayout()) {
			$page_info = array();

			// Get defined variables in controller
			foreach($this->view_data as $k => $v) {
				$$k = $v;
			}

			$breadcrumb = $this->core->breadcrumb($page_info);
			$page_title = $this->core->pageTitle($page_info);

			// Load page content
			ob_start();
			require("templates/" . $this->template . "/" . $this->controller . "." . Text::camelCase($this->action) . ".phtml");
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
	public function getConfig()
	{
		Database::query("SELECT * FROM c_config;");
		$this->config = Database::fetchConfig();
	}

	/**
	 * --------------------------------------------------------------------
	 * GET DEFAULT TEMPLATE OR, IF LOGGED IN, THE ONE DEFINED BY MEMBER
	 * --------------------------------------------------------------------
	 */
	private function getTemplate()
	{
		if(SessionState::$user_data['m_id']) {
			// Get member-defined theme
			$this->config['theme'] = $this->theme = SessionState::$user_data['theme'];
			$this->config['template'] = $this->template = SessionState::$user_data['template'];
		}
		else {
			// Get default theme set
			$this->config['theme'] = $this->theme = $this->config['theme_default_set'];
			$this->config['template'] = $this->template = $this->config['template_default_set'];
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * GET DEFUALT LANGUAGE OR, IF LOGGED IN, THE ONE DEFINED BY MEMBER
	 * --------------------------------------------------------------------
	 */
	private function getLanguage()
	{
		$t = array();

		// Get default or member set language
		if(SessionState::$user_data['m_id']) {
			$this->language = SessionState::$user_data['language'];
		}
		else {
			$this->language = $this->config['language_default_set'];
		}

		// Store selected language in $this->Config
		$this->config['language'] = $this->language;

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
			echo Html::notification(
				"Language files or keywords are missing for <b>" . $this->language . "</b>.", "failure", true
			);
		}
	}
}

new Main();
