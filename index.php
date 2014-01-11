<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: index.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	include("init.php");

	class Main
	{
		// ---------------------------------------------------
		// Properties
		// ---------------------------------------------------

		protected $Db;
		protected $Core;

		public $info = array(
			"module"	=> "",
			"language"	=> "",
			"template"	=> ""
			);

		public $user = array(
			"m_id"	=> 0
			);

		// Sections

		private $header	= "";
		private $sidebar	= "";
		private $content	= "";

		// ---------------------------------------------------
		// Constructor
		// ---------------------------------------------------

		public function __construct()
		{
			// Intial configuration

			include("config.php");

			$init = new Init();
			$init->Load();

			// Define classes

			$this->Db = new Database($config);
			$this->Core = new Core($this->Db);

			// Get languages and template skin

			$this->GetLanguage();
			$this->GetTemplate();

			// Load templates and controllers

			$this->info['module'] = $this->Core->QueryString("module", "community");

			ob_start();
			require_once("controllers/" . $this->info['module'] . ".php");
			require_once("templates/" . $this->info['template'] . "/" . $this->info['module'] . ".tpl");
			$this->content = ob_get_clean();

			if(isset($define['layout'])) {
				$layout = $define['layout'];
			}
			else {
				$layout = "default";
			}

			require_once("controllers/" . $layout . ".php");
			require_once("templates/" . $this->info['template'] . "/" . $layout . ".tpl");
		}

		// ---------------------------------------------------
		// Get community default language
		// ---------------------------------------------------

		private function GetLanguage()
		{
			if($this->user['m_id'] == 0) {
				$this->info['template'] = "en_US";
			}
		}

		// ---------------------------------------------------
		// Get community default template
		// ---------------------------------------------------

		private function GetTemplate()
		{
			if($this->user['m_id'] == 0) {
				$this->info['template'] = "default";
			}
		}
	}

	$main = new Main();

?>