<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_templates_themes.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;

// List of directories

$dir_list = array();
$dir = scandir("../themes");

foreach($dir as $k => $v) {
	if(!in_array($v, array(".", "..")) && is_dir("../themes/" . $v)) {
		$dir_list[] = $v;
	}
}

// List of themes

$Db->Query("SELECT * FROM c_themes ORDER BY name ASC;");
$themes = $Db->FetchToArray();

foreach($themes as $theme) {
	$theme['is_active']  = ($theme['is_active'] == 1) ? "<i class='fa fa-fw fa-check'></i>" : "";

	if(in_array($theme['directory'], $dir_list)) {
		$dir_list = array_diff($dir_list, array($theme['directory']));
	}

	// Do not allow to remove default themes
	if($theme['directory'] == $Admin->SelectConfig("theme_default_set")) {
		$theme['default'] = "<i class='fa fa-fw fa-check'></i>";
		$theme['remove'] = "-";
	}
	else {
		$theme['default'] = "";
		$theme['remove']= "<a href='process.php?do=theme_remove&id={$theme['theme_id']}'><i class='fa fa-fw fa-remove'></i></a>";
	}

	// Never allow to remove English language pack
	// We use English language as fallback in case of faulty i18n packs
	if($theme['directory'] == "en_US") {
		$theme['remove'] = "-";
	}

	Template::Add("<tr>
			<td><a href='main.php?act=templates&p=theme_edit&id={$theme['theme_id']}'><b>{$theme['name']}</b></a></td>
			<td>/themes/{$theme['directory']}</td>
			<td>{$theme['author_name']} ({$theme['author_email']})</td>
			<td class='min'>{$theme['is_active']}</td>
			<td class='min'>{$theme['default']}</td>
			<td class='min'><a href='main.php?act=templates&p=theme_edit&id={$theme['theme_id']}'><i class='fa fa-fw fa-pencil'></i></a></td>
			<!--<td class='min'><a href='main.php?act=templates&p=theme_download&id={$theme['theme_id']}'><i class='fa fa-fw fa-download'></i></a></td>-->
			<td class='min'>{$theme['remove']}</td>
		</tr>");
}

// If there is uninstalled languages, show on list

$not_installed_languages = "";

if(!empty($dir_list)) {
	$not_installed_languages = Html::Notification("There are theme packs available to install.", "warning");
	foreach($dir_list as $theme) {
		$theme_info = json_decode(file_get_contents("../themes/" . $theme . "/_theme.json"), true);

		Template::Add("<tr>
				<td style='color:#bbb'><b>{$theme_info['name']}</b></td>
				<td style='color:#bbb'>/themes/{$theme}</td>
				<td style='color:#bbb'>{$theme_info['author']} ({$theme_info['email']})</td>
				<td style='color:#bbb' colspan='2'>Not installed</td>
				<td colspan='3'><a href='process.php?do=install_theme&id={$theme}' title='Install'><i class='fa fa-fw fa-gears'></i></a></td>
			</tr>");
	}
}

?>

<h1>Theme Manager</h1>

<div id="content">
	<div class="grid-row">
		<?php echo $not_installed_languages ?>
		<table class="table-list">
			<tr>
				<th colspan="10">
					<div class="fleft">Installed Themes</div>
					<div class="fright"></div>
				</th>
			</tr>
			<tr class="subtitle">
				<td>Theme Name</td>
				<td>Directory</td>
				<td>Author</td>
				<td>Active</td>
				<td>Default</td>
				<td class="min">Edit</td>
				<!--<td class="min">Download</td>-->
				<td class="min">Delete</td>
			</tr>
			<?php echo Template::Get() ?>
		</table>
	</div>
</div>
