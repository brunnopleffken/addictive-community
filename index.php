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

		// Community info
		public $info = array(
			"module"	=> "",
			"language"	=> "",
			"template"	=> ""
			);

		// Member or guest info
		public $user = array(
			"m_id"	=> 0
			);

		// Languages/dictionary array
		public $t = array();

		// Paths
		public $p = array(
			"IMG" => "",
			"TPL" => "",
			);

		// Sections (HTML)
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

			// Get required module name

			$this->info['module'] = $this->Core->QueryString("module", "community");

			// Get languages and template skin

			$this->GetLanguage($this->info['module']);
			$this->GetTemplate();

			// Load controllers and views

			ob_start();
			require_once("controllers/" . $this->info['module'] . ".php");
			require_once("templates/" . $this->info['template'] . "/" . $this->info['module'] . ".tpl.php");
			$this->content = ob_get_clean();

			if(isset($define['layout'])) {
				$layout = $define['layout'];
			}
			else {
				$layout = "default";
			}

			require_once("controllers/" . $layout . ".php");
			require_once("templates/" . $this->info['template'] . "/" . $layout . ".tpl.php");
		}

		// ---------------------------------------------------
		// Get community default language
		// ---------------------------------------------------

		private function GetLanguage($module)
		{
			if($this->user['m_id'] == 0) {
				$this->info['language'] = "en_US";
			}
			else {
				$this->user['language'] = $this->info['language'];
			}

			include("languages/" . $this->info['language'] . "/global.php");			// global language file
			@include("languages/" . $this->info['language'] . "/" . $module . ".php");	// optional file

			foreach($t as $k => $v) {
				$this->t[$k] = $v;
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

			$this->p['TPL'] = "templates/" . $this->info['template'];
			$this->p['IMG'] = "templates/" . $this->info['template'] . "/images";
		}
	}

	$main = new Main();

?>