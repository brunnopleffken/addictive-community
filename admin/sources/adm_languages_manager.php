<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_languages_manager.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// List of directories

	$dir_list = array();
	$dir = scandir("../languages");

	foreach($dir as $k => $v) {
		if(strpos($v, "_") && is_dir("../languages/" . $v)) {
			$dir_list[] = $v;
		}
	}

	// List of languages

	$Db->Query("SELECT * FROM c_languages ORDER BY name ASC;");

	while($lang = $Db->Fetch()) {
		$lang['is_active']  = ($lang['is_active'] == 1) ? "<i class='fa fa-fw fa-check'></i>" : "";

		if($lang['is_default'] == 1){
			$lang['is_default'] = "<i class='fa fa-fw fa-check'></i>";
			$lang['remove'] = "-";
		}
		else {
			$lang['is_default'] = "";
			$lang['remove'] = "<a href='main.php?act=languages&p=delete&id={$lang['l_id']}'><i class='fa fa-fw fa-remove'></i></a>";
		}

		if(in_array($lang['directory'], $dir_list)) {
			$dir_list = array_diff($dir_list, array($lang['directory']));
		}

		Template::Add("<tr>
				<td><a href='main.php?act=languages&p=edit&id={$lang['l_id']}'><b>{$lang['name']}</b></a></td>
				<td>/languages/{$lang['directory']}</td>
				<td>{$lang['author_name']} ({$lang['author_email']})</td>
				<td>{$lang['is_active']}</td>
				<td>{$lang['is_default']}</td>
				<td><a href='main.php?act=languages&p=edit&id={$lang['l_id']}'><i class='fa fa-fw fa-pencil'></i></a></td>
				<td><a href='main.php?act=languages&p=download&id={$lang['l_id']}'><i class='fa fa-fw fa-download'></i></a></td>
				<td>{$lang['remove']}</td>
			</tr>");
	}

	// If there is uninstalled languages, show on list

	if(!empty($dir_list)) {
		$not_installed_languages = Html::Notification("There are language packs available to install. Click on the gears icon to install.", "warning");
		foreach($dir_list as $language) {
			$language_info = json_decode(file_get_contents("../languages/" . $language . "/_language.json"), true);

			Template::Add("<tr>
					<td style='color:#bbb'><b>{$language_info['name']}</b></td>
					<td style='color:#bbb'>/languages/{$language}</td>
					<td style='color:#bbb'>{$language_info['author_name']} ({$language_info['author_email']})</td>
					<td style='color:#bbb' colspan='2'>Not installed</td>
					<td colspan='3'><a href='process.php?do=install_language&id={$language}'><i class='fa fa-fw fa-gears'></i></a></td>
				</tr>");
		}
	}
	else {
		$not_installed_languages = Html::Notification("Language directories must comply the ICU (International Components for Unicode) locale code, such as <i>en_US</i> for American English and <i>de_DE</i> for German. A full reference guide can be seen <a href='http://demo.icu-project.org/icu-bin/locexp?d_=en'>here</a>.", "info");
	}

?>

	<h1>Language Manager</h1>

	<div id="content">
		<div class="grid-row">
			<?php echo $not_installed_languages ?>
			<table class="table-list">
				<tr>
					<th colspan="10">
						<div class="fleft">Installed Languages</div>
					</th>
				</tr>
				<tr class="subtitle">
					<td>Language</td>
					<td>Directory</td>
					<td>Author</td>
					<td>Active</td>
					<td>Default</td>
					<td class="min"></td>
					<td class="min"></td>
					<td class="min"></td>
				</tr>
				<?php echo Template::Get() ?>
			</table>
		</div>
	</div>
