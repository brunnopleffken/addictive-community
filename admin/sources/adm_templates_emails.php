<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_languages_manager.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Template;

// Update e-mails (if POST exists)

$message = "";

if($_POST) {
	foreach($_POST as $k => $v) {
		$content = $v;
		Database::query("UPDATE c_emails SET content = '{$content}' WHERE type = '{$k}';");
	}

	$message = Html::notification("E-mail templates has been successfully updated.", "success");
}

// List of e-mail messages

Database::query("SELECT * FROM c_emails ORDER BY type ASC;");

while($template = Database::fetch()) {
	$template['content'] = str_replace("<br />", "", $template['content']);
	Template::add("<tr>
			<td class='font-w600'>{$template['description']}</td>
			<td><textarea name='{$template['type']}' rows='8' class='form-control span-9'>{$template['content']}</textarea></td>
		</tr>");
}

?>

<h1>E-mail Messages</h1>

<?php echo $message ?>

<div class="block">
	<?php echo Html::notification("<u>Do not</u> change the order of the variables (%s and %d symbols are variables). You can use HTML tags, e.g: for line breaks, use &lt;br&gt;.", "warning"); ?>
	<form action="main.php?act=templates&p=emails" method="post">
		<table class="table">
			<thead>
				<tr>
					<th colspan="10">
						<div class="fleft">E-mail Messages Templates</div>
						<div class="fright"></div>
					</th>
				</tr>
				<tr>
					<td>Template Name</td>
					<td>Message</td>
				</tr>
			</thead>
			<?php echo Template::get() ?>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save E-mail Messages">
		</div>
	</form>
</div>
