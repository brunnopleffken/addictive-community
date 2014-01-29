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
		protected $Session;

		// Community info
		public $info = array(
			"module"		=> "",
			"language"		=> "",
			"template"		=> "",
			"section_id"	=> 0,
			"room_id"		=> 0
			);

		// Member info
		public $member = array();

		// Languages/dictionary array
		public $t = array();

		// Paths
		public $p = array(
			"IMG" => "",
			"TPL" => ""
			);

		// Sections (HTML)
		private $header	= "";
		private $sidebar	= "";
		private $content	= "";

		// ---------------------------------------------------
		// Main constructor
		// ---------------------------------------------------

		public function __construct()
		{
			// ---------------------------------------------------
			// Initial configuration
			// ---------------------------------------------------

			require_once("config.php");
			$init = new Init();
			$init->Load();

			// Define classes

			$this->Db = new Database($config);
			$this->Core = new Core($this->Db);
			$this->Session = new Session($this->Db);


			// ---------------------------------------------------
			// Get module name and set user/guest session
			// ---------------------------------------------------

			$this->info['module'] = $this->Core->QueryString("module", "community");

			$this->Session->UpdateSession($this->info);

			// ---------------------------------------------------
			// Get languages and template skin
			// ---------------------------------------------------

			$this->GetLanguage($this->info['module']);
			$this->GetTemplate();

			// ---------------------------------------------------
			// Load views and controllers
			// ---------------------------------------------------

			// Get module view/controller

			ob_start();
			require_once("controllers/" . $this->info['module'] . ".php");
			require_once($this->p['TPL'] . "/" . $this->info['module'] . ".tpl.php");
			$this->content = ob_get_clean();

			// Check if a custom master template is defined

			if(isset($define['layout'])) {
				$layout = $define['layout'];
			}
			else {
				$layout = "default";
			}

			// Master template controller and template

			require_once("controllers/" . $layout . ".php");
			require_once($this->p['TPL'] . "/" . $layout . ".tpl.php");
		}

		// ---------------------------------------------------
		// Get community default language
		// ---------------------------------------------------

		private function GetLanguage($module)
		{
			if($this->Session->sInfo['member_id'] == 0) {
				// Default language
				$this->info['language'] = "en_US";
			}
			else {
				// User defined language
				$this->member['language'] = $this->info['language'];
			}

			include("languages/" . $this->info['language'] . "/global.php");			// Global language file
			@include("languages/" . $this->info['language'] . "/" . $module . ".php");	// Module file, if exists

			foreach($t as $k => $v) {
				$this->t[$k] = $v;
			}
		}

		// ---------------------------------------------------
		// Get community default template
		// ---------------------------------------------------

		private function GetTemplate()
		{
			if($this->Session->sInfo['member_id'] == 0) {
				$this->info['template'] = "default";
			}

			$this->p['TPL'] = "templates/" . $this->info['template'];
			$this->p['IMG'] = "templates/" . $this->info['template'] . "/images";
		}
	}

	$main = new Main();

?>