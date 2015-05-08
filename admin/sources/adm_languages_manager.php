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

	$Db->Query("SELECT * FROM c_languages ORDER BY name ASC;");

	while($lang = $Db->Fetch()) {
		$lang['active']  = ($lang['active'] == 1) ? "<i class='fa fa-check'></i>" : "";
		$lang['default'] = ($lang['default'] == 1) ? "<i class='fa fa-check'></i>" : "";

		Template::Add("<tr>
				<td><a href='main.php?act=languages&p=edit&id={$lang['l_id']}'><b>{$lang['name']}</b></a></td>
				<td>/languages/{$lang['directory']}</td>
				<td>{$lang['author_name']}</td>
				<td>{$lang['active']}</td>
				<td>{$lang['default']}</td>
				<td><a href='main.php?act=languages&p=edit&id={$lang['l_id']}'><i class='fa fa-pencil'></i></a></td>
				<td><a href='main.php?act=languages&p=download&id={$lang['l_id']}'><i class='fa fa-download'></i></a></td>
				<td><a href='main.php?act=languages&p=delete&id={$lang['l_id']}'><i class='fa fa-remove'></i></a></td>
			</tr>");
	}

?>

	<h1>Language Manager</h1>

	<div id="content">
		<div class="grid-row">

			<table class="table-list">
				<tr>
					<th colspan="10">
						<div class="fleft">Installed Languages</div>
						<div class="fright"><a href="main.php?act=rooms&p=add" class="button-grey-default white transition">Add Language</a></div>
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
