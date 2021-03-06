<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  https://github.com/addictivehub/addictive-community
#
#  File: Upload.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## -------------------------------------------------------

namespace AC\Kernel;

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

	/**
	 * --------------------------------------------------------------------
	 * CONSTRUCTOR
	 * --------------------------------------------------------------------
	 */
	public function __construct()
	{
		// List of dangerous file types by OS
		$forbidden_mac = array("app", "command", "dmg");
		$forbidden_win = array("bat", "bin", "cmd", "com", "exe", "lnk", "msi", "pif", "scr");
		$forbidden_web = array("html", "js", "php", "phtml");
		$this->forbidden_extensions = array_merge($forbidden_win, $forbidden_mac, $forbidden_web);
	}

	/**
	 * --------------------------------------------------------------------
	 * UPLOAD ATTACHMENT OF A GIVEN FILE AND STORE IT ON DATABASE (RETURNS
	 * THE ATTACHMENT ID NUMBER ON DATABASE)
	 * --------------------------------------------------------------------
	 */
	public function sendAttachment($file, $member = 0, $folder = "public/attachments/")
	{
		if(is_array($file) && $file['name'] != "") {
			// Upload file to the designated directory
			$file = $this->sendFile($file, $member, $folder);

			// Insert new attachment in database
			$attachment = array(
				"member_id" => $member,
				"date"      => time(),
				"filename"  => $file['name'],
				"type"      => $this->file_type,
				"clicks"    => 0,
				"size"      => $file['size']
			);

			Database::insert("c_attachments", $attachment);
			return Database::getLastId();
		}
		else {
			return 0;
		}
	}

	private function sendFile($file, $member, $folder)
	{
		// Get timestamp
		$timestamp = time();

		// Validate maximum attachment file size
		Database::query("SELECT value FROM c_config WHERE field = 'general_max_attachment_size';");
		$max_attachment_size_mb = Database::fetch();
		$max_attachment_size_bytes = $max_attachment_size_mb['value'] * 1048576;

		if($file['size'] >= $max_attachment_size_bytes) {
			Html::throwError("The uploaded file size exceeds " . $max_attachment_size_mb['value'] . "MB!");
		}

		// Get filename and extension
		$filename = explode(".", $file['name']);
		$this->file_extension = strtolower(end($filename));

		// Get attachment type (to use as CSS classes)
		$this->file_type = $this->fileClass($this->file_extension);

		// Check if it's not a forbidden extension
		if(in_array($this->file_extension, $this->forbidden_extensions)) {
			Html::throwError("This file extension is not allowed (.{$this->file_extension})!");
		}

		// Check if is an allowed extension (if array is not empty, of course)
		if(!empty($this->allowed_extensions) && !in_array($this->file_extension, $this->allowed_extensions)) {
			Html::throwError("This file extension is not allowed (.{$this->file_extension}).");
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
		$full_image_path = $full_path . $file['name'];
		move_uploaded_file($file['tmp_name'], $full_image_path);
		chmod($full_image_path, 0666);

		return $file;
	}

	/**
	 * --------------------------------------------------------------------
	 * GET FILE TYPE TO USE IT AS A CSS CLASS
	 * --------------------------------------------------------------------
	 */
	private function fileClass($extension)
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
	public function translateFileType($filetype)
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
	public function setAllowedExtensions($extensions_list = array())
	{
		if(is_array($extensions_list)) {
			$this->allowed_extensions = $extensions_list;
			return true;
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
	public function unlockForbiddenExtensions()
	{
		$this->forbidden_extensions = array();
		return true;
	}
}
