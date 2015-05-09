<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_rooms_manage.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// Messages

	$msg = (Html::Request("msg")) ? Html::Request("msg") : "";

	switch($msg) {
		case 1:
			$message = Html::Notification("The new room has been created successfully.", "success");
			break;
		case 2:
			$message = Html::Notification("The room has been edited successfully.", "success");
			break;
		case 3:
			$message = Html::Notification("The room has been deleted successfully.", "success");
			break;
		default:
			$message = "";
			break;
	}

	// Room list

	$Db->Query("SELECT * FROM c_rooms ORDER BY name;");

	while($room = $Db->Fetch()) {
		Template::Add("
			<tr>
			<td class='min'><input type='checkbox' name='check' value='{$room['r_id']}'></td>
			<td>
				<b>{$room['name']}</b><br>
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
			<!-- LEFT -->
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
						<td class="min"></td>
						<td>Room</td>
						<td colspan="3">Options</td>
					</tr>
					<?php echo Template::Get(); ?>
				</table>

			</form>
		</div>

	</div>
