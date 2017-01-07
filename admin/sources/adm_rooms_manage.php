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
				<p>
					<b style='font-size:15px'>{$room['name']}</b><br>
					{$room['description']}
				</p>
			</td>
			<td class='min text-center'><a href='main.php?act=rooms&p=edit&id={$room['r_id']}' title='Edit'><i class='fa fa-pencil'></i></a></td>
			<td class='min text-center'><a href='main.php?act=rooms&p=delete&id={$room['r_id']}' title='Delete'><i class='fa fa-remove'></i></a></td>
			<td class='min text-center'><a href='main.php?act=rooms&p=resync&id={$room['r_id']}' title='Resynchronize: recount threads, replies and last post information'><i class='fa fa-refresh'></i></a></td>
		</tr>
	");
}

?>

<div class="header">
	<h1>Manage Rooms</h1>
	<div class="header-buttons">
		<a href="main.php?act=rooms&p=add" class="btn btn-default font-w600">Add New Room</a>
	</div>
</div>

<div class="block">
	<form action="process.php?do=newroom" method="post">
		<?php echo $message; ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="5">Rooms Overview</th>
				</tr>
				<tr>
					<td>Room</td>
					<td>Edit</td>
					<td>Delete</td>
					<td>Resync</td>
				</tr>
			</thead>
			<?php echo Template::Get(); ?>
		</table>
	</form>
</div>
