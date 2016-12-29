<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_members_add.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

// User group list

$list = "";
$Db->Query("SELECT * FROM c_usergroups ORDER BY g_id");

while($group = $Db->Fetch()) {
	if($group['g_id'] == 3) {
		$list .= "<option value='{$group['g_id']}' selected>{$group['name']}</option>";
	}
	else {
		$list .= "<option value='{$group['g_id']}'>{$group['name']}</option>";
	}
}

?>

<h1>Add New Member</h1>

<div id="content">
	<div class="grid-row">
		<form action="process.php?do=save" method="post">
			<table class="table-list">
				<tr>
					<th colspan="2">New Member Information</th>
				</tr>
				<tr>
					<td class="title-fixed">Username</td>
					<td><input type="text" name="username" class="small"></td>
				</tr>
				<tr>
					<td class="title-fixed">Password</td>
					<td><input type="text" name="password" class="small"></td>
				</tr>
				<tr>
					<td class="title-fixed">E-mail Address</td>
					<td><input type="text" name="email" class="medium"></td>
				</tr>
				<tr>
					<td class="title-fixed">User Group</td>
					<td>
						<select>
							<?php echo $list ?>
						</select>
					</td>
				</tr>
			</table>
			<div class="box fright"><input type="submit" value="Register New Member"></div>
		</form>
	</div>
</div>
