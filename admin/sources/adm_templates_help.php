<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_templates_help.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;

// Messages

$msg = (Http::request("msg")) ? Http::request("msg") : "";

switch($msg) {
	case 1:
		$message = Html::notification("The new help topic has been successfully added.", "success");
		break;
	default:
		$message = "";
		break;
}

// Room list

Database::query("SELECT * FROM c_help ORDER BY title;");

while($topic = Database::fetch()) {
	Template::add("
		<tr>
			<td>
				<b>{$topic['title']}</b><br>
				{$topic['short_desc']}
			</td>
			<td class='min'><a href='main.php?act=templates&p=helpedit&id={$topic['h_id']}'><i class='fa fa-pencil'></i></a></td>
			<td class='min'><a href='main.php?act=templates&p=helpdelete&id={$topic['h_id']}'><i class='fa fa-remove'></i></a></td>
		</tr>
	");
}

?>

<div class="header">
	<h1>Help Topics</h1>
	<div class="header-buttons">
		<a href="main.php?act=templates&p=add_help" class="btn btn-default font-w600">Add New Topic</a>
	</div>
</div>

<div class="block">
	<form action="process.php?do=newroom" method="post">
		<?php echo $message; ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="5">Help Topic List</th>
				</tr>
				<tr>
					<td>Topic</td>
					<td colspan="3" class="min">Options</td>
				</tr>
			</thead>
			<?php echo Template::get(); ?>
		</table>
	</form>
</div>
