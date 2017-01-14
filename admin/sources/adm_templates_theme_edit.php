<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_templates_theme_edit.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;

// Theme details
$id = Http::request("id");
Database::query("SELECT * FROM c_themes WHERE theme_id = {$id};");
$themes = Database::fetch();

$filename = "../themes/" . $themes['directory'] . "/css/theme.css";

// Notifications
$not_writable = false;
$notification = Html::notification(
	"The Developer Tools of your browser is your friend. Use it to identify specific UI elements and get the corresponding CSS selectors.", "info"
);

if(!is_writable($filename)) {
	$notification = Html::notification(
		"The file <b>../themes/{$themes['directory']}/css/theme.css</b> should be writable (CHMOD 777).", "failure"
	);
	$not_writable = true;
}

// Get CSS content
$content = file_get_contents($filename);

?>

<h1>Edit Theme: <?php echo $themes['name'] ?></h1>

<div class="block">
	<?php echo $notification; ?>
	<form action="process.php?do=edit_css" method="post">
		<?php if(!$not_writable): ?>
			<table class="table">
				<thead>
					<tr>
						<th colspan="10">
							<div class="fleft">CSS File Content</div>
						</th>
					</tr>
				</thead>
				<tr>
					<td>
						<textarea name="css" id="css" style="width: 1040px; height: 500px; font-family: Consolas, Menlo, monospace"><?php echo $content ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="text-right">
						<input type="hidden" name="css_file" value="<?php echo $filename ?>">
						<input type="submit" class="btn btn-default" value="Save File">
					</td>
				</tr>
			</table>
		<?php endif; ?>
	</form>
</div>
