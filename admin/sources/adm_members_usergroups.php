<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_members_usergroups.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// Notification

	$msg = (Html::Request("msg")) ? Html::Request("msg") : 0;

	switch($msg) {
		case 1:
			$message = Html::Notification("The settings has been changed successfully.", "success");
			break;
		default:
			$message = Html::Notification("You cannot remove native user groups (like Administrator, Member or Guest).", "info");
			break;
	}

	// Get usergroup list

	$Db->Query("SELECT * FROM c_usergroups");

	while($group = $Db->Fetch()) {
		$delete = ($group['stock'] == 0) ? "<img src=\"images/delete.png\">" : "-";

		Template::Add("<tr>
				<td><b>{$group['name']}</b></td>
				<td><img src=\"images/edit.png\"></td>
				<td>{$delete}</td>
			</tr>");
	}

?>

	<h1>User Groups</h1>

	<div id="content">
		<div class="grid-row">
			<?php echo $message ?>
			<form action="process.php?do=save" method="post">
				<table class="table-list">
					<tr>
						<th colspan="5">
							<div class="fleft">User Groups Overview</div>
							<div class="fright"><a href="main.php?act=rooms&p=add" class="button-grey-default white transition">New User Group</a></div>
						</th>
					</tr>
					<tr class="subtitle">
						<td>Name</td>
						<td width="1%"></td>
						<td width="1%"></td>
					</tr>
					<?php echo Template::Get() ?>
				</table>
			</form>
		</div>
	</div>