<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_members_usergroups.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;

// Notification

$msg = (Http::Request("msg")) ? Http::Request("msg") : 0;

switch($msg) {
	case 1:
		$message = Html::Notification("The settings has been successfully changed.", "success");
		break;
	default:
		$message = Html::Notification("You cannot remove native user groups (like Administrator, Member or Guest).", "info");
		break;
}

// Get usergroup list

$Db->Query("SELECT * FROM c_usergroups");

while($group = $Db->Fetch()) {
	$delete = ($group['stock'] == 0) ? "<i class='fa fa-remove'></i>" : "-";

	Template::Add("<tr>
			<td><b>{$group['name']}</b></td>
			<td class='min'><a href='main.php?act=members&p=edit_usergroup&id={$group['g_id']}'><i class='fa fa-pencil'></i></a></td>
			<td class='min'>{$delete}</td>
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
						<div class="fright"><a href="main.php?act=members&p=newusergroup" class="button-grey-default white transition">New User Group</a></div>
					</th>
				</tr>
				<tr class="subtitle">
					<td>Name</td>
					<td width="1%">Edit</td>
					<td width="1%">Delete</td>
				</tr>
				<?php echo Template::Get() ?>
			</table>
		</form>
	</div>
</div>
