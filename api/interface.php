<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: interface.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	
	class API
	{
		// Private properties
		private $DEBUG = true;
		private $Db;

		// Public properties
		public $RoomId;
		public $ThreadId;
		public $AuthorId;
		public $AuthorName;

		// ---------------------------------------------------
		// Class constructor
		// ---------------------------------------------------

		public function __construct()
		{
			// Get required files

			require_once('../config.php');
			require_once('../kernel/class.database.php');
			require_once('../kernel/class.string.php');

			// Build database property
			
			$this->Db = new Database($config);
		}

		// ---------------------------------------------------
		// Get list of rooms
		// ---------------------------------------------------

		public function GetRoomList()
		{
			$rooms = array();

			$this->Db->Query("SELECT * FROM c_rooms;");
			
			while($_results = $this->Db->Fetch()) {
				$rooms[] = $_results;
			}

			return json_encode($rooms);
		}

		// ---------------------------------------------------
		// Get list of threads
		// ---------------------------------------------------

		public function GetThreadList()
		{
			$threads = array();

			if($this->RoomId == "") {
				$threads[]['error'] = "ERROR: RoomId property is not defined.";
			}
			else {
				$this->Db->Query("SELECT * FROM c_threads WHERE room_id = " . $this->RoomId . " ORDER BY lastpost_date DESC;");
			
				while($_results = $this->Db->Fetch()) {
					$threads[] = $_results;
				}
			}

			return json_encode($threads);
		}
	}

	$api = new API();
	$api->RoomId = 5;
	print_r($api->GetThreadList());

?>