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
	include("app.php");

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
			"module"     => "",
			"language"   => "",
			"template"   => "",
			"section_id" => 0,
			"room_id"    => 0
			);

		// Default member/guest info
		// Usergroups: 1 - Admin; 2 - Mod; 3 - Member; 4 - Banned; 5 - Guest
		public $member = array(
			"m_id"      => 0,
			"usergroup" => 5
		);

		// Languages/dictionary array
		public $t = array();

		// Paths
		public $p = array(
			"IMG" => "",
			"TPL" => ""
		);

		// Sections (HTML)
		private $header  = "";
		private $sidebar = "";
		private $content = "";

		// ---------------------------------------------------
		// Main constructor
		// ---------------------------------------------------

		public function __construct()
		{
			// ---------------------------------------------------
			// Initial configuration
			// ---------------------------------------------------

			$init = new Init();
			$init->Load();

			// Load configuration file

			if(is_file("config.php")) {
				require_once("config.php");				
			}
			else {
				Html::Error("Configuration file is missing.");
			}
			
			// If config.php is empty, go to AC installer
			
			if(filesize("config.php") == 0 || empty($config)) {
				header("Location: install/");
			}

			// Define classes

			$this->Db = new Database($config);
			$this->Core = new Core($this->Db);
			$this->Session = new Session($this->Db);

			// ---------------------------------------------------
			// Get module name and set user/guest session
			// ---------------------------------------------------

			$this->info['module'] = $this->Core->QueryString("module", "community");

			$this->Session->UpdateSession($this->info);
			
			// Store member information in $this->member
			
			if($this->Session->sInfo['member_id']) {
				$m_id = $this->Session->sInfo['member_id'];
				
				$this->Db->Query("SELECT * FROM c_members "
						. "WHERE m_id = '{$m_id}';");
					
				$this->member = $this->Db->Fetch();
			}

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
				$this->info['language'] = $this->member['language'];
			}

			include("languages/" . $this->info['language'] . "/global.lang.php");           // Global language file
			@include("languages/" . $this->info['language'] . "/" . $module . ".lang.php"); // Module file, if exists
			
			foreach($t as $k => $v) {
				$this->t[$k] = $v;
			}
		}

		// ---------------------------------------------------
		// Get community default template
		// ---------------------------------------------------

		private function GetTemplate()
		{
			global $mobileBrowser;

			// Check if user is accessing from mobile device
			$userBrowser = $_SERVER['HTTP_USER_AGENT'];
			
			foreach($mobileBrowser as $v) {
				if(stristr($userBrowser, $v)) {
					$isMobile = true;
					break;
				}
				else {
					$isMobile = false;
				}
			}

			// Load mobile template or default desktop/tablet template
			if($isMobile) {
				$this->info['template'] = "mobile";
			}
			else {
				if($this->Session->sInfo['member_id'] == 0) {
					$this->info['template'] = "default";
				}
				else {
					$this->info['template'] = $this->member['template'];
				}
			}

			// Get full template path
			$this->p['TPL'] = "templates/" . $this->info['template'];
			$this->p['IMG'] = "templates/" . $this->info['template'] . "/images";
		}
		
		// ---------------------------------------------------
		// Check if user is logged in
		// ---------------------------------------------------

		public function IsMember()
		{
			if($this->member['m_id'] != 0) {
				return true;
			}
			else {
				return false;
			}
		}
		
		// ---------------------------------------------------
		// Check if a provided ID matches with logged member ID
		// ---------------------------------------------------

		public function EvaluateMember($id)
		{
			if($this->member['m_id'] == $id) {
				return true;
			}
			else {
				return false;
			}
		}
	}

	$main = new Main();

?>