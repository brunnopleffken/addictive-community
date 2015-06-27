<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_languages_manager.php
	#  License: GPLv2
	#  Copyright: (c) 2015 - Addictive Community
	## ---------------------------------------------------

	// Update e-mails (if POST exists)

	$message = "";

	if($_POST) {
		foreach($_POST as $k => $v) {
			$content = $v;
			$Db->Query("UPDATE c_emails SET content = '{$content}' WHERE type = '{$k}';");
		}

		$message = Html::Notification("E-mail templates has been successfully updated.", "success");
	}

	// List of e-mail messages

	$Db->Query("SELECT * FROM c_emails ORDER BY type ASC;");

	while($template = $Db->Fetch()) {
		$template['content'] = str_replace("<br />", "", $template['content']);
		Template::Add("<tr>
				<td>{$template['description']}</td>
				<td><textarea name='{$template['type']}' rows='8' style='width:500px'>{$template['content']}</textarea></td>
			</tr>");
	}

?>

	<h1>E-mail Messages</h1>

	<?php echo $message ?>

	<div id="content">
		<div class="grid-row">
			<?php echo Html::Notification("<u>Do not</u> change the position of the variables (%s and %d symbols are variables). You can use HTML tags, e.g: for line breaks, use &lt;br&gt;.", "warning"); ?>
			<form action="main.php?act=templates&p=emails" method="post">
				<table class="table-list">
					<tr>
						<th colspan="10">
							<div class="fleft">E-mail Messages Templates</div>
							<div class="fright"></div>
						</th>
					</tr>
					<tr class="subtitle">
						<td>Template Name</td>
						<td>Message</td>
					</tr>
					<?php echo Template::Get() ?>
				</table>
				<div class="fright"><input type="submit" value="Save E-mail Messages"></div>
			</form>
		</div>
	</div>
