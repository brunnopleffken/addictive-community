<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_templates_templates.php
	#  License: GPLv2
	#  Copyright: (c) 2016 - Addictive Community
	## ---------------------------------------------------

	// List of templates

	$Db->Query("SELECT * FROM c_templates ORDER BY name ASC;");
	$templates = $Db->FetchToArray();

	foreach($templates as $template) {
		$template['is_active']  = ($template['is_active'] == 1) ? "<i class='fa fa-check'></i>" : "";

		// Do not allow to remove default templates
		if($template['directory'] == $Admin->SelectConfig("template_default_set")) {
			$template['default'] = "<i class='fa fa-check'></i>";
			$template['remove'] = "-";
		}
		else {
			$template['default'] = "";
			$template['remove']= "<a href='main.php?act=templates&p=delete&id={$template['tpl_id']}'><i class='fa fa-remove'></i></a>";
		}

		Template::Add("<tr>
				<td><a href=\"main.php?act=templates&p=edit&id={$template['tpl_id']}\"><b>{$template['name']}</b></a></td>
				<td>/templates/{$template['directory']}</td>
				<td>{$template['author_name']} ({$template['author_email']})</td>
				<td>{$template['is_active']}</td>
				<td>{$template['default']}</td>
				<td><a href='main.php?act=templates&p=edit&id={$template['tpl_id']}'><i class='fa fa-pencil'></i></a></td>
				<td><a href='main.php?act=templates&p=download&id={$template['tpl_id']}'><i class='fa fa-download'></i></a></td>
				<td>{$template['remove']}</td>
			</tr>");
	}

?>

	<h1>Template Manager</h1>

	<div id="content">
		<?php echo Html::Notification("If you want to change the general appearance of your community (like images and colors), you might be looking for <a href='main.php?act=templates&p=themes'>Themes</a> instead.", "info") ?>
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
