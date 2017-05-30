<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_languages_edit.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;

$id = Http::request("id");

$notification = Html::notification(
	"You will not be able to edit template files from here. You must access those files via FTP.", "info"
);

// Change language name

if(Http::request("template_name")) {
	$new_template_name = Http::request("template_name");

	Database::query("UPDATE c_templates SET name = '{$new_template_name}' WHERE tpl_id = '{$id}';");

	$notification = Html::notification(
		"You have successfylly changed the template name to {$new_template_name}", "success"
	);
}

// Get template info

Database::query("SELECT * FROM c_templates WHERE tpl_id = '{$id}';");
$lang = Database::fetch();

?>

<h1>Manage Template: <?php echo $lang['name'] ?></h1>

<div class="block">
	<div class="grid-row">
		<?php echo $notification ?>
		<table class="table">
			<tr>
				<th colspan="10"><div class="fleft">Settings</div></th>
			</tr>
			<tr>
				<td class="font-w600">Template Name</td>
				<td>
					<form action="#" method="post">
						<input type="text" name="template_name" class="medium" value="<?php __($lang['name']) ?>">
						<div class="fright"><input type="submit" value="Save Settings"></div>
					</form>
				</td>
			</tr>
		</table>
	</div>
</div>
