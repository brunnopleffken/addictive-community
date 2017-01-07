<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_templates_templates.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Template;

// List of directories

$dir_list = array();
$dir = scandir("../templates");

foreach($dir as $k => $v) {
	if(!in_array($v, array(".", "..")) && is_dir("../templates/" . $v)) {
		$dir_list[] = $v;
	}
}

// List of templates

Database::Query("SELECT * FROM c_templates ORDER BY name ASC;");
$templates = Database::FetchToArray();

foreach($templates as $template) {
	$template['is_active']  = ($template['is_active'] == 1) ? "<i class='fa fa-check'></i>" : "";

	if(in_array($template['directory'], $dir_list)) {
		$dir_list = array_diff($dir_list, array($template['directory']));
	}

	// Do not allow to remove default templates
	if($template['directory'] == $Admin->SelectConfig("template_default_set")) {
		$template['default'] = "<i class='fa fa-check'></i>";
		$template['remove'] = "-";
	}
	else {
		$template['default'] = "";
		$template['remove']= "<a href='process.php?do=template_remove&id={$template['tpl_id']}'><i class='fa fa-remove'></i></a>";
	}

	Template::Add("<tr>
			<td><a href=\"main.php?act=templates&p=edit&id={$template['tpl_id']}\"><b>{$template['name']}</b></a></td>
			<td>/templates/{$template['directory']}</td>
			<td>{$template['author_name']} ({$template['author_email']})</td>
			<td class='text-center'>{$template['is_active']}</td>
			<td class='text-center'>{$template['default']}</td>
			<td><a href='main.php?act=templates&p=edit&id={$template['tpl_id']}'><i class='fa fa-pencil'></i></a></td>
			<td><a href='main.php?act=templates&p=download&id={$template['tpl_id']}'><i class='fa fa-download'></i></a></td>
			<td>{$template['remove']}</td>
		</tr>");
}


// If there is uninstalled template, show on list
if(!empty($dir_list)) {
	foreach($dir_list as $template) {
		$template_info = json_decode(file_get_contents("../templates/" . $template . "/_template.json"), true);

		Template::Add("<tr>
				<td style='color:#bbb'><b>{$template_info['name']}</b></td>
				<td style='color:#bbb'>/templates/{$template}</td>
				<td style='color:#bbb'>{$template_info['author']} ({$template_info['email']})</td>
				<td style='color:#bbb' colspan='2'>Not installed</td>
				<td colspan='3'><a href='process.php?do=install_template&id={$template}' title='Install'><i class='fa fa-fw fa-gears'></i></a></td>
			</tr>");
	}
}

?>

<h1>Template Manager</h1>

<div class="block">
	<?php echo Html::Notification("If you want to change the general appearance of your community (like colors and images), you might be looking for <a href='main.php?act=templates&p=themes'>Themes</a> instead.", "info") ?>
	<table class="table">
		<thead>
			<tr>
				<th colspan="10">
					<div class="fleft">Installed Templates</div>
					<div class="fright"></div>
				</th>
			</tr>
			<tr>
				<td>Template Name</td>
				<td>Directory</td>
				<td>Author</td>
				<td class="min">Active</td>
				<td class="min">Default</td>
				<td class="min"></td>
				<td class="min"></td>
				<td class="min"></td>
			</tr>
		</thead>
		<?php echo Template::Get() ?>
	</table>
</div>

<!-- DELETE TEMPLATE LIGHTBOX -->

<div id="deleteThreadConfirm" style="display: none">
	<form action="thread/delete_post" method="post" class="validate">
		<table class="table-list no-borders" style="width:350px; margin:0">
			<tr>
				<th>
					<div class="fleft"><?php __("T_DELETE_POST") ?></div>
					<div class="fright"><a href="javascript:jQuery.fancybox.close();" class="small-button grey white transition"><?php __("T_CLOSE") ?></a></div>
				</th>
			</tr>
			<tr>
				<td class="min"><?php __("T_DELETE_NOTICE") ?></td>
			</tr>
			<tr class="footer">
				<td colspan="2" style="text-align:center; padding:10px">
					<input type="hidden" name="pid" id="delete_post_id" value="">
					<input type="hidden" name="tid" id="delete_thread_id" value="">
					<input type="hidden" name="mid" id="delete_member_id" value="">
					<input type="submit" value="<?php __("T_DELETE_POST") ?>">
				</td>
			</tr>
		</table>
	</form>
</div>
