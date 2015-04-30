<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_languages_edit.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// Get language info

	$id = Html::Request("id");

	$Db->Query("SELECT * FROM c_languages WHERE l_id = '{$id}';");
	$lang = $Db->Fetch();

	// Get list of files

	$handle = opendir("../languages/" . $lang['directory']);

	while($file = readdir($handle)) {
		if($file != "." && $file != ".." && $file != "index.html" && $file != "_language.json") {
			$url = preg_replace("#(.+)\.php#", "$1", $file);

			Template::Add("<tr>
					<td><a href='?act=languages&p=file&id={$url}&dir={$lang['directory']}'><b>{$file}</b></a></td>
					<td><a href='?act=languages&p=file&id={$url}&dir={$lang['directory']}'><i class='fa fa-pencil'></i></a></td>
				</tr>");
		}
	}

?>

	<h1>Manage Language: <?php echo $lang['name'] ?></h1>

	<div id="content">
		<div class="grid-row">

			<table class="table-list">
				<tr>
					<th colspan="10">
						<div class="fleft">Language Files</div>
					</th>
				</tr>
				<tr class="subtitle">
					<td>File</td>
					<td class="min"></td>
				</tr>
				<?php echo Template::Get() ?>
			</table>

			<table class="table-list">
				<tr>
					<th colspan="10">
						<div class="fleft">Settings</div>
					</th>
				</tr>
				<tr>
					<td class="title-fixed">Language Name</td>
					<td><input type="text" name="name" class="medium" value="<?php __($lang['name']) ?>"></td>
				</tr>
				<tr>
					<td class="title-fixed">Directory</td>
					<td>/languages/ <input type="text" name="name" class="tiny" value="<?php __($lang['directory']) ?>"></td>
				</tr>
			</table>

		</div>
	</div>
