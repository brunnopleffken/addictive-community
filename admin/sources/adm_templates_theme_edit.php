<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_templates_theme_edit.php
	#  License: GPLv2
	#  Copyright: (c) 2015 - Addictive Community
	## ---------------------------------------------------

	// Theme details

	$id = Http::Request("id");

	$Db->Query("SELECT * FROM c_themes WHERE theme_id = {$id};");
	$themes = $Db->Fetch();

	// Get CSS content

	$content = file_get_contents("../themes/" . $themes['directory'] . "/css/main.css");

?>

	<h1>Edit Theme: <?php echo $themes['name'] ?></h1>

	<div id="content">
		<?php echo Html::Notification("The Element Inspector (DevTools) of your browser is your friend. Use it to isolate specific elements or tags and view the corresponding CSS styles.", "info"); ?>
		<div class="grid-row">
			<form action="process.php?do=edit_css" method="post">
				<table class="table-list">
					<tr>
						<th colspan="10">
							<div class="fleft">Edit CSS</div>
						</th>
					</tr>
					<tr>
						<td>
							<textarea name="css" id="css" style="width: 920px; height: 500px; font-family: Consolas, Menlo, monospace"><?php echo $content ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<input type="hidden" name="theme_id" value="<?php echo $id ?>">
							<div class="fright"><input type="submit" value="Done"></div>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
