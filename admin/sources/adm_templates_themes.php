<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_templates_themes.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// List of themes

	$Db->Query("SELECT * FROM c_themes ORDER BY name ASC;");
	$themes = $Db->FetchToArray();

	foreach($themes as $theme) {
		$theme['active']  = ($theme['active'] == 1) ? "<i class='fa fa-check'></i>" : "";

		// Do not allow to remove default themes
		if($theme['directory'] == $Admin->SelectConfig("theme_default_set")) {
			$theme['default'] = "<i class='fa fa-check'></i>";
			$theme['remove'] = "-";
		}
		else {
			$theme['default'] = "";
			$theme['remove']= "<a href='main.php?act=templates&p=theme_remove&id={$theme['theme_id']}'><i class='fa fa-remove'></i></a>";
		}

		Template::Add("<tr>
				<td><a href=\"main.php?act=templates&p=theme_edit&id={$theme['theme_id']}\"><b>{$theme['name']}</b></a></td>
				<td>/themes/{$theme['directory']}</td>
				<td>{$theme['author_name']} ({$theme['author_email']})</td>
				<td>{$theme['active']}</td>
				<td>{$theme['default']}</td>
				<td><a href='main.php?act=templates&p=theme_edit&id={$theme['theme_id']}'><i class='fa fa-pencil'></i></a></td>
				<td><a href='main.php?act=templates&p=theme_download&id={$theme['theme_id']}'><i class='fa fa-download'></i></a></td>
				<td>{$theme['remove']}</td>
			</tr>");
	}

?>

	<h1>Theme Manager</h1>

	<div id="content">
		<div class="grid-row">
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
					<td class="min"></td>
					<td class="min"></td>
					<td class="min"></td>
				</tr>
				<?php echo Template::Get() ?>
			</table>
		</div>
	</div>
