<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_templates_theme_edit.php
	#  License: GPLv2
	#  Copyright: (c) 2016 - Addictive Community
	## ---------------------------------------------------

	// Theme details
	$id = Http::Request("id");
	$Db->Query("SELECT * FROM c_themes WHERE theme_id = {$id};");
	$themes = $Db->Fetch();

	$filename = "../themes/" . $themes['directory'] . "/css/main.css";

	// Notifications
	$not_writable = false;
	$notification = Html::Notification(
		"The Element Inspector (DevTools) of your browser is your friend. Use it to isolate specific elements or tags and view the corresponding CSS styles.", "info"
	);

	if(!is_writable($filename)) {
		$notification = Html::Notification(
			"The file <b>../themes/{$themes['directory']}/css/main.css</b> should be writable (CHMOD 777).", "failure"
		);
		$not_writable = true;
	}

	// Get CSS content
	$content = file_get_contents($filename);

?>

	<h1>Edit Theme: <?php echo $themes['name'] ?></h1>

	<div id="content">
		<?php echo $notification; ?>
		<div class="grid-row">
			<form action="process.php?do=edit_css" method="post">
				<?php if(!$not_writable): ?>
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
								<input type="hidden" name="css_file" value="<?php echo $filename ?>">
								<div class="fright"><input type="submit" value="Done"></div>
							</td>
						</tr>
					</table>
				<?php endif; ?>
			</form>
		</div>
	</div>
