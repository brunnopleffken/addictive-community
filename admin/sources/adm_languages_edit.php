<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_languages_edit.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;

$id = Http::request("id");
$notification = "";

// Change language name

if(Http::request("language_name")) {
	$new_language_name = Http::request("language_name");

	Database::query("UPDATE c_languages SET name = '{$new_language_name}' WHERE l_id = '{$id}';");

	$notification = Html::notification(
		"You have successfylly changed the language name to {$new_language_name}", "success"
	);
}

// Get language info

Database::query("SELECT * FROM c_languages WHERE l_id = '{$id}';");
$lang = Database::fetch();

// Get list of files

$handle = opendir("../languages/" . $lang['directory']);

while($file = readdir($handle)) {
	if($file != "." &&
		$file != ".." &&
		$file != "_language.json" &&
		$file != ".DS_Store"
	) {

		$url = preg_replace("#(.+)\.php#", "$1", $file);

		if(!is_writable("../languages/" . $lang['directory'] . "/" . $file)) {
			$notification = Html::notification(
				"The language files in this directory are not writable. Please, set all files to CHMOD 777.", "failure", true
			);
			$edit = "<i class='fa fa-ban'></i>
</span>";
		}
		else {
			$edit = "<a href='?act=languages&p=file&id={$url}&dir={$lang['directory']}'><i class='fa fa-pencil'></i></a>";
		}

		Template::add("<tr>
				<td><a href='?act=languages&p=file&id={$url}&dir={$lang['directory']}'><b>{$file}</b></a></td>
				<td>{$edit}</td>
			</tr>");
	}
}

?>

<h1>Manage Language: <?php echo $lang['name'] ?></h1>

<div class="block">
	<?php echo $notification ?>
	<table class="table">
		<thead>
			<tr>
				<th colspan="10">
					<div class="fleft">Language Files</div>
				</th>
			</tr>
			<tr>
				<td>File</td>
				<td class="min"></td>
			</tr>
		</thead>
		<?php echo Template::get() ?>
	</table>

	<table class="table">
		<thead>
			<tr>
				<th colspan="10"><div class="fleft">Settings</div></th>
			</tr>
		</thead>
		<tr>
			<td class="font-w600">Language Name</td>
			<td>
				<form action="#" method="post">
					<input type="text" name="language_name" class="form-control span-3" value="<?php __($lang['name']) ?>">
					<input type="submit" class="btn btn-default" value="Save Settings">
				</form>
			</td>
		</tr>
	</table>
</div>
