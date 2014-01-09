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
			"module"	=> "community",
			"template"	=> 1
			);

		public $user = array();

		// Sections

		public $header	= "";
		public $sidebar	= "";
		public $content	= "";

		// ---------------------------------------------------
		// Constructor
		// ---------------------------------------------------

		public function __construct()
		{
			include("config.php");

			$init = new Init();
			$init->Load();

			$this->Db = new Database($config);
			$this->Core = new Core($this->Db);

			ob_start();
			require_once("controllers/" . $this->info['module'] . ".php");
			require_once("templates/" . $this->info['template'] . "/" . $this->info['module'] . ".php");
			$this->content = ob_get_clean();

			require_once("templates/" . $this->info['template'] . "/default.php");
			require_once("controllers/default.php");
		}
	}

	$main = new Main();

?>