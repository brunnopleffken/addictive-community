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

$id = Http::Request("id");
$notification = "";

// Change language name

if(Http::Request("language_name")) {
	$new_language_name = Http::Request("language_name");

	Database::Query("UPDATE c_languages SET name = '{$new_language_name}' WHERE l_id = '{$id}';");

	$notification = Html::Notification(
		"You have successfylly changed the language name to {$new_language_name}", "success"
	);
}

// Get language info

Database::Query("SELECT * FROM c_languages WHERE l_id = '{$id}';");
$lang = Database::Fetch();

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
			$notification = Html::Notification(
				"The language files in this directory are not writable. Please, set all files to CHMOD 777.", "failure", true
			);
			$edit = "<i class='fa fa-ban'></i>
</span>";
		}
		else {
			$edit = "<a href='?act=languages&p=file&id={$url}&dir={$lang['directory']}'><i class='fa fa-pencil'></i></a>";
		}

		Template::Add("<tr>
				<td><a href='?act=languages&p=file&id={$url}&dir={$lang['directory']}'><b>{$file}</b></a></td>
				<td>{$edit}</td>
			</tr>");
	}
}

?>

<h1>Manage Language: <?php echo $lang['name'] ?></h1>

<div id="content">
	<div class="grid-row">

		<?php echo $notification ?>

		<table class="table-list">
			<tr>
				<th colspan="10">
					<div class="fleft">Language Files</div>
				</th>
			</tr>
			<tr class="subtitle">
				<td>File</td>
				<td class="min"></td>
			</tr>
			<?php echo Template::Get() ?>
		</table>

		<table class="table-list">
			<tr>
				<th colspan="10"><div class="fleft">Settings</div></th>
			</tr>
			<tr>
				<td class="title-fixed">Language Name</td>
				<td>
					<form action="#" method="post">
						<input type="text" name="language_name" class="medium" value="<?php __($lang['name']) ?>">
						<div class="fright"><input type="submit" value="Save Settings"></div>
					</form>
				</td>
			</tr>
		</table>

	</div>
</div>
