<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_languages_manager.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// List of languages

	$Db->Query("SELECT * FROM c_templates ORDER BY name ASC;");

	while($template = $Db->Fetch()) {
		$template['active']  = ($template['active'] == 1) ? "<img src=\"images/tick.png\">" : "";
		//$template['default'] = ($template['default'] == 1) ? "<img src=\"images/tick.png\">" : "";

		Template::Add("<tr>
				<td><a href=\"main.php?act=languages&p=edit&id={$template['tpl_id']}\"><b>{$template['name']}</b></a></td>
				<td>/languages/{$template['directory']}</td>
				<td>{$template['author_name']}</td>
				<td>{$template['active']}</td>
				<td></td>
				<td><a href=\"main.php?act=languages&p=edit&id={$template['tpl_id']}\"><img src=\"images/edit.png\"></a></td>
				<td><a href=\"main.php?act=languages&p=download&id={$template['tpl_id']}\"><img src=\"images/download-files.png\"></a></td>
				<td><!-- <a href=\"main.php?act=languages&p=delete&id={$template['tpl_id']}\"><img src=\"images/delete.png\"></a> --></td>
			</tr>");
	}

?>

	<h1>Template Manager</h1>

	<div id="content">
		<div class="grid-row">

			<table class="table-list">
				<tr>
					<th colspan="10">
						<div class="fleft">Installed Templates</div>
						<div class="fright"></div>
					</th>
				</tr>
				<tr class="subtitle">
					<td>Template Name</td>
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
