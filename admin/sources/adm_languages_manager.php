<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_languages_manager.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Template;

// List of directories

$found_languages = array();
$dir = scandir("../languages");

foreach($dir as $k => $v) {
	if(strpos($v, ".json")) {
		$found_languages[] = $v;
	}
}

// List of installed languages

Database::query("SELECT * FROM c_languages ORDER BY name ASC;");

while($lang = Database::fetch()) {
	$lang['is_active']  = ($lang['is_active'] == 1) ? "<i class='fa fa-fw fa-check'></i>" : "";
	$lang['remove'] = "<a href='process.php?do=uninstall_language&id={$lang['l_id']}'><i class='fa fa-fw fa-remove'></i></a>";

	if(in_array($lang['file_name'] . ".json", $found_languages)) {
		$found_languages = array_diff($found_languages, array($lang['file_name'] . ".json"));
	}

	Template::add("<tr>
			<td><b>{$lang['name']}</b></td>
			<td>/languages/{$lang['file_name']}</td>
			<td>{$lang['is_active']}</td>
			<td class='' style='white-space: nowrap'>{$lang['remove']}</td>
		</tr>");
}


// If there is uninstalled languages, show on list

if(!empty($found_languages)) {
	foreach($found_languages as $language) {
		$language_info = json_decode(file_get_contents("../languages/" . $language), true);

		Template::add("<tr>
				<td class='text-muted'><b>{$language_info['name']}</b></td>
				<td class='text-muted'>/languages/{$language}</td>
				<td class='text-muted' style='white-space: nowrap'>Not installed</td>
				<td class='' style='white-space: nowrap'>
					<a href='process.php?do=install_language&id={$language}' title='Install'><i class='fa fa-fw fa-gears'></i></a>
				</td>
			</tr>");
	}
}

?>

<h1>Language Manager</h1>

<div class="block">
	<table class="table">
		<thead>
			<tr>
				<th colspan="10">
					<div class="fleft">Installed Languages</div>
				</th>
			</tr>
			<tr>
				<td>Language</td>
				<td>File name</td>
				<td class="min">Enabled</td>
				<td class="min">Actions</td>
			</tr>
		</thead>
		<?php echo Template::get() ?>
	</table>
	<?= Html::notification("Language file names must comply the ICU (International Components for Unicode) locale code, such as <i>en_US</i> for American English, <i>pt_BR</i> for Brazilian Portuguese and <i>de_DE</i> for German. A full reference guide can be seen <a href='http://demo.icu-project.org/icu-bin/locexp?d_=en' style='text-decoration:underline'>here</a>.", "info"); ?>
</div>
