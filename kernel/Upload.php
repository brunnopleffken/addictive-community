<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Upload.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

class Upload
{
	// List of dangerous extensions, although these files can also be
	// sent by .zip or .rar files, it offers a little more secure environment.
	private $forbidden_extensions = array();

	// List of allowed extensions for upload
	private $allowed_extensions = array();

	// Uploaded file extension
	private $file_extension = "";

	// Uploaded file type
	private $file_type = "";

	// Database class
	private $Db;

	/**
	 * --------------------------------------------------------------------
	 * CONSTRUCTOR
	 * --------------------------------------------------------------------
	 */
	public function __construct($database)
	{
		// List of dangerous file types by OS
		$forbidden_mac = array("app", "command", "dmg");
		$forbidden_win = array("bat", "bin", "cmd", "com", "exe", "lnk", "msi", "pif", "scr");
		$forbidden_web = array("html", "js", "php", "phtml");
		$this->forbidden_extensions = array_merge($forbidden_win, $forbidden_mac, $forbidden_web);

		// Store database class
		$this->Db = &$database;
	}

	/**
	 * --------------------------------------------------------------------
	 * UPLOAD ATTACHMENT OF A GIVEN FILE AND STORE IT ON DATABASE (RETURNS
	 * THE ATTACHMENT ID NUMBER ON DATABASE)
	 * --------------------------------------------------------------------
	 */
	public function Attachment($file, $member = 0, $folder = "public/attachments/")
	{
		if(is_array($file) && $file['name'] != "") {
			// Get timestamp
			$timestamp = time();

			// Get filename and extension
			$filename = explode(".", $file['name']);
			$this->file_extension = strtolower(end($filename));

			// Get attachment type (to use as CSS classes)
			$this->file_type = $this->FileClass($this->file_extension);

			// Check if it's not a forbidden extension
			if(in_array($this->file_extension, $this->forbidden_extensions)) {
				Html::Error("This file extension is not allowed (.{$this->file_extension})!");
			}

			// Check if is an allowed extension (if array is not empty, of course)
			if(!empty($this->allowed_extensions) && !in_array($this->file_extension, $this->allowed_extensions)) {
				Html::Error("This file extension is not allowed (.{$this->file_extension}).");
			}

			// Full path
			$full_path = $folder . $member . "/" . $timestamp . "/";
			if(!is_dir($full_path)) {
				mkdir($full_path, 0777, true);
			}

			// Delete special characters and diacritics
			$file['name'] = preg_replace(
				"/[^a-zA-Z0-9_.]/", "",
				strtr($file['name'],
					"áàãâäéêëíóôõöúüçñÁÀÃÂÄÉÊËÍÓÔÕÖÚÜÇ ",
					"aaaaaeeeioooouucnAAAAAEEEIOOOOUUC_"
				)
			);

			// Move uploaded file to public member folder
			move_uploaded_file($file['tmp_name'], $full_path . $file['name']);
			chmod($full_path . $file['name'], 0666);

			// Insert new attachment in database
			$attachment = array(
				"member_id" => $member,
				"date"      => time(),
				"filename"  => $file['name'],
				"type"      => $this->file_type,
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

	/**
	 * --------------------------------------------------------------------
	 * GET FILE TYPE TO USE IT AS A CSS CLASS
	 * --------------------------------------------------------------------
	 */
	private function FileClass($extension)
	{
		$types = array(
			"doc"   => array("doc", "docx", "rtf", "pages", "odt", "epub"),
			"htm"   => array("html", "xml", "css", "scss", "php", "js", "sql", "aspx"),
			"img"   => array("jpg", "png", "gif", "bmp", "svg", "ico"),
			"mp3"   => array("mp3", "ogg", "wma", "m4a", "wav", "aiff"),
			"pdf"   => array("pdf", "xps"),
			"ppt"   => array("ppt", "pptx", "key", "odp"),
			"txt"   => array("txt", "csv", "md"),
			"vid"   => array("mp4", "mpeg", "avi", "mov", "wmv", "3gp", "ogv"),
			"xls"   => array("xls", "xlsx", "numbers", "ods"),
			"zip"   => array("zip", "rar", "7z", "tar", "gz", "tgz"),
			"blank" => array(),
		);

		// Find file extension in array of CSS classes
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

		// If value is not found, return "blank" file icon
		if($found == true) {
			return $ext;
		}
		else {
			return "blank";
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * GET USER-FRIENDLY FILE TYPE DESCRIPTION
	 * --------------------------------------------------------------------
	 */
	public function TranslateFileType($filetype)
	{
		$types = array(
			"doc"   => "Document",
			"htm"   => "Script",
			"img"   => "Image",
			"mp3"   => "Music/Audio",
			"pdf"   => "Portable Document Format (PDF)",
			"ppt"   => "Presentation",
			"txt"   => "Text File",
			"vid"   => "Video",
			"xls"   => "Spreadsheet",
			"zip"   => "Compressed File",
			"blank" => "File",
		);
		return $types[$filetype];
	}

	/**
	 * --------------------------------------------------------------------
	 * SET ALLOWED EXTENSIONS FOR FILE UPLOAD
	 * --------------------------------------------------------------------
	 */
	public function SetAllowedExtensions($extensions_list = array())
	{
		if(is_array($extensions_list)) {
			$this->allowed_extensions = $extensions_list;
		}
		else {
			return false;
		}
	}

	/**
	 * --------------------------------------------------------------------
	 * REMOVE ALL FORBIDDEN FILE RESTRICTIONS
	 * NOTE: USE IT CAREFULLY! YOUR MEMBERS COULD SEND HAZARDOUS FILES TO
	 * OTHER USERS NOT WARNED IN ADVANCE.
	 * --------------------------------------------------------------------
	 */
	public function UnlockForbiddenExtensions()
	{
		$this->forbidden_extensions = array();
		return true;
	}
}
