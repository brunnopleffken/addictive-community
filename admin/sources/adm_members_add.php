<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_members_add.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;

// User group list

$list = "";
Database::query("SELECT * FROM c_usergroups ORDER BY g_id");

while($group = Database::fetch()) {
	if($group['g_id'] == 3) {
		$list .= "<option value='{$group['g_id']}' selected>{$group['name']}</option>";
	}
	else {
		$list .= "<option value='{$group['g_id']}'>{$group['name']}</option>";
	}
}

?>

<h1>Add New Member</h1>

<div class="block">
	<form action="process.php?do=save" method="post">
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">New Member Information</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Username</td>
				<td><input type="text" name="username" class="form-control span-3"></td>
			</tr>
			<tr>
				<td class="font-w600">Password <small>Password field is visible</small></td>
				<td><input type="text" name="password" class="form-control span-3"></td>
			</tr>
			<tr>
				<td class="font-w600">E-mail Address</td>
				<td><input type="email" name="email" class="form-control span-5"></td>
			</tr>
			<tr>
				<td class="font-w600">User Group</td>
				<td>
					<select class="form-control span-3">
						<?php echo $list ?>
					</select>
				</td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Register New Member">
		</div>
	</form>
</div>
