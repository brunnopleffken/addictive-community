<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.upload.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	class Upload
	{
		/**
		 * List of dangerous extensions, although these files can also be
		 * sent by .zip or .rar files, it offers a little more secure environment.
		 * @var array
		 */
		private $forbiddenExtensions = array();

		/**
		 * List of allowed extensions for upload
		 * @var array
		 */
		private $allowedExtensions = array();

		/**
		 * Database class
		 * @var Database
		 */
		private $Database;

		// ---------------------------------------------------
		// Constructor
		// ---------------------------------------------------

		public function __construct($databaseClass)
		{
			// List of dangerous file types by OS
			$forbiddenMac = array("app", "command");
			$forbiddenWin = array("bat", "bin", "cmd", "com", "exe", "lnk", "msi", "pif", "src");
			$this->forbiddenExtensions = array_merge($forbiddenWin, $forbiddenMac);
		}

		// ---------------------------------------------------
		// Upload attachment of a given file and store it on
		// database (returns the attach ID number on DB)
		// ---------------------------------------------------

		public function Attachment($file, $member, $folder = "public/attachments/")
		{
			if(is_array($file)) {
				// Full path
				$fullPath = $folder . $member;

				// Create folder, if it doesn't exists
				if(!is_dir($fullPath)) {
					mkdir($fullPath);
				}
			}
			else {
				return false;
			}
		}

		// ---------------------------------------------------
		// Set allowed extensions for file upload
		// ---------------------------------------------------
		
		public function SetAllowedExt($extList)
		{
			if(is_array($extList)) {
				$this->allowedExtensions = $extList;
			}
			else {
				return false;
			}
		}

		// ---------------------------------------------------
		// Removes all forbidden files restrictions.
		// USE IT CAREFULLY! Your members could send hazardous
		// files to other users not warned in advance.
		// ---------------------------------------------------
		
		public function UnlockForbiddenExtensions()
		{
			return $this->forbiddenExtensions = array();
		}
	}

?>