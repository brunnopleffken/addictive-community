<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_members_usergroups.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;

// Notification

$msg = (Http::request("msg")) ? Http::request("msg") : 0;

switch($msg) {
	case 1:
		$message = Html::notification("The settings has been successfully changed.", "success");
		break;
	default:
		$message = Html::notification("You cannot remove root user groups (like Administrator, Member or Guest).", "info");
		break;
}

// Get usergroup list

Database::query("SELECT * FROM c_usergroups");

while($group = Database::fetch()) {
	$delete = ($group['stock'] == 0) ? "<i class='fa fa-remove'></i>" : "-";

	if($group['stock'] == 0) {
		$delete = "<i class='fa fa-remove'></i>";
		$notice = "";
	}
	else {
		$delete = "-";
		$notice = "<span class='text-muted pull-right'>[ root ]</span>";
	}

	Template::add("<tr>
			<td><b>{$group['name']}</b> {$notice}</td>
			<td class='min text-center'><a href='main.php?act=members&p=edit_usergroup&id={$group['g_id']}'><i class='fa fa-pencil'></i></a></td>
			<td class='min text-center'>{$delete}</td>
		</tr>");
}

?>

<div class="header">
	<h1>User Groups</h1>
	<div class="header-buttons">
		<a href="main.php?act=members&p=add_usergroup" class="btn btn-default font-w600">New User Group</a>
	</div>
</div>

<div class="block">
	<?php echo $message ?>
	<form action="process.php?do=save" method="post">
		<table class="table">
			<thead>
				<tr>
					<th colspan="5">User Groups Overview</th>
				</tr>
				<tr>
					<td>Name</td>
					<td class="min">Edit</td>
					<td class="min">Delete</td>
				</tr>
			</thead>
			<?php echo Template::get() ?>
		</table>
	</form>
</div>
