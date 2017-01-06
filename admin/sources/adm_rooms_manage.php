<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_rooms_manage.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;

// Messages

$msg = (Http::Request("msg")) ? Http::Request("msg") : "";

switch($msg) {
	case 1:
		$message = Html::Notification("The new room has been successfully created.", "success");
		break;
	case 2:
		$message = Html::Notification("The room has been successfully edited.", "success");
		break;
	case 3:
		$message = Html::Notification("The room has been successfully deleted.", "success");
		break;
	case 4:
		$message = Html::Notification("The room has been successfully resynchronized.", "success");
		break;
	case 5:
		$message = Html::Notification("The new category has been successfully created.", "success");
		break;
	default:
		$message = "";
		break;
}

// Room list

Database::Query("SELECT * FROM c_rooms ORDER BY name;");

while($room = Database::Fetch()) {
	Template::Add("
		<tr>
			<td>
				<b style='font-size:15px'>{$room['name']}</b><br>
				{$room['description']}
			</td>
			<td class='min'><a href='main.php?act=rooms&p=edit&id={$room['r_id']}' title='Edit'><i class='fa fa-pencil'></i></a></td>
			<td class='min'><a href='main.php?act=rooms&p=delete&id={$room['r_id']}' title='Delete'><i class='fa fa-remove'></i></a></td>
			<td class='min'><a href='main.php?act=rooms&p=resync&id={$room['r_id']}' title='Resynchronize'><i class='fa fa-refresh'></i></a></td>
		</tr>
	");
}

?>

<h1>Manage Rooms</h1>

<div id="content">
	<div class="grid-row">
		<form action="process.php?do=newroom" method="post">
			<?php echo $message; ?>
			<table class="table-list">
				<tr>
					<th colspan="5">
						<div class="fleft">Rooms Overview</div>
						<div class="fright"><a href="main.php?act=rooms&p=add" class="button-grey-default white transition">Add New Room</a></div>
					</th>
				</tr>
				<tr class="subtitle">
					<td>Room</td>
					<td>Edit</td>
					<td>Delete</td>
					<td>Resync</td>
				</tr>
				<?php echo Template::Get(); ?>
			</table>
		</form>
	</div>
</div>
