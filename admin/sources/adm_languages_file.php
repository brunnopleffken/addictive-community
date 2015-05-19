<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_languages_file.php
	#  License: GPLv2
	#  Copyright: (c) 2015 - Addictive Community
	## ---------------------------------------------------

	// Get variables

	$name = Html::Request("id");
	$dir  = Html::Request("dir");

	// Get file content

	$file_path = "../languages/" . $dir . "/" . $name . ".php";

	require_once($file_path);

	// Build table

	foreach($t as $k => $v) {
		Template::Add("<tr>
				<td class='title-fixed'>{$k}<input type='hidden' name='index[]' value='{$k}'></td>
				<td><textarea name='{$k}' rows='3' cols='64'>{$v}</textarea></td>
			</tr>");
	}

?>

	<h1>Editing language file: <?php echo ucwords($name) ?></h1>

	<div id="content">
		<div class="grid-row">
			<form action="process.php?do=savelang" method="post">
				<table class="table-list">
					<tr>
						<th colspan="10">
							<div class="fleft">Language File</div>
						</th>
					</tr>
					<tr class="subtitle">
						<td>Index Name</td>
						<td>Translation</td>
					</tr>
					<?php echo Template::Get() ?>
				</table>
				<div class="box fright">
					<input type="hidden" name="file" value="<?php echo $name ?>">
					<input type="hidden" name="dir" value="<?php echo $dir ?>">
					<input type="submit" value="Save File">
				</div>
			</form>
		</div>
	</div>
