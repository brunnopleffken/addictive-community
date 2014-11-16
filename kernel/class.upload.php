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
		// List of dangerous extensions, although these files can also be
		// sent by .zip or .rar files, it offers a little more secure environment.
		private $forbiddenExtensions = array();

		// List of allowed extensions for upload
		private $allowedExtensions = array();
		
		// Uploaded file extension
		private $fileExtension = "";
		
		// Uploaded file type
		private $fileType = "";

		// Database class
		private $Db;

		// ---------------------------------------------------
		// Constructor
		// ---------------------------------------------------

		public function __construct($databaseClass)
		{
			// List of dangerous file types by OS
			$forbiddenMac = array("app", "command", "dmg");
			$forbiddenWin = array("bat", "bin", "cmd", "com", "exe", "lnk", "msi", "pif", "src");
			$this->forbiddenExtensions = array_merge($forbiddenWin, $forbiddenMac);
			
			// Store database class
			$this->Db = &$databaseClass;
		}

		// ---------------------------------------------------
		// Upload attachment of a given file and store it on
		// database (returns the attach ID number on DB)
		// ---------------------------------------------------

		public function Attachment($file, $member, $folder = "public/attachments/")
		{
			if(is_array($file) && $file['name'] != "") {
				// Full path
				$fullPath = $folder . $member . "/";
				if(!is_dir($fullPath)) {
					mkdir($fullPath, 0777);
				}
				
				// Get filename and extension
				$filename = explode(".", $file['name']);
				$this->fileExtension = strtolower(end($filename));
				
				// Get attachment type (to use as CSS classes)
				$this->fileType = $this->FileClass($this->fileExtension);
				
				// Check if it's not a forbidden extension
				if(in_array($this->fileExtension, $this->forbiddenExtensions)) {
					Html::Error("This file extension is not allowed (." . $this->fileExtension . ")!");
				}
				
				// Delete special characters and diacritics
				$file['name'] = preg_replace(
						"/[^a-zA-Z0-9_.]/", "",
						strtr($file['name'],
								"áàãâäéêëíóôõöúüçñÁÀÃÂÄÉÊËÍÓÔÕÖÚÜÇ ",
								"aaaaaeeeioooouucnAAAAAEEEIOOOOUUC_")
						);
				
				// Move uploaded file to public member folder
				move_uploaded_file($file['tmp_name'], $fullPath . $file['name']);
				chmod($fullPath . $file['name'], 0666);
				
				// Insert new attachment in database
				$attachment = array(
					"member_id" => $member,
					"date"      => time(),
					"filename"  => $file['name'],
					"type"      => $this->fileType,
					"clicks"    => 0,
					"size"      => $file['size']
				);
				
				$this->Db->Insert("c_attachments", $attachment);
				return $this->Db->GetLastID();
			}
			else {
				return 0;
			}
		}
		
		// ---------------------------------------------------
		// Get file type to use as CSS class
		// ---------------------------------------------------
		
		private function FileClass($extension)
		{
			$types = array(
				"doc" => array("doc", "docx", "rtf", "pages"),
				"htm" => array("html", "xml", "css", "scss", "php", "js", "sql", "aspx"),
				"img" => array("jpg", "png", "gif", "bmp", "svg", "ico"),
				"mp3" => array("mp3", "ogg", "wma", "m4a", "wav", "aiff"),
				"pdf" => array("pdf"),
				"ppt" => array("ppt", "pptx", "key"),
				"txt" => array("txt", "csv", "md"),
				"vid" => array("mp4", "mpeg", "avi", "mov", "wmv", "3gp"),
				"xls" => array("xls", "xlsx", "numbers"),
				"zip" => array("zip", "rar", "7z", "tar", "gz"),
				"blank" => array(),
			);
			
			foreach($types as $k => $v) {
				if(in_array($extension, $v)) {
					$found = true;
					$ext = $k;
					break;
				}
				else {
					$found = false;
				}
			}
			
			if($found == true) {
				return $ext;
			}
			else {
				return "blank";
			}
		}

		public function TranslateFileType($filetype)
		{
			$types = array(
				"doc" => "Document",
				"htm" => "Script",
				"img" => "Image",
				"mp3" => "Music/Audio",
				"pdf" => "Portable Document Format (PDF)",
				"ppt" => "Presentation",
				"txt" => "Text File",
				"vid" => "Video",
				"xls" => "Spreadsheet",
				"zip" => "Compressed File",
				"blank" => "File",
			);

			return $types[$filetype];
		}

		// ---------------------------------------------------
		// Set allowed extensions for file upload
		// ---------------------------------------------------
		
		public function SetAllowedExtensions($extList)
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
			$this->forbiddenExtensions = array();
			return true;
		}
	}

?>