<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_members_edit_usergroup.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Http;

// Get usergroup information
$id = Http::Request("id", true);
Database::Query("SELECT * FROM c_usergroups WHERE g_id = {$id};");
$usergroup = Database::Fetch();

?>

<h1>Edit User Group: <?php echo $usergroup['name'] ?></h1>

<div class="block">
	<form action="process.php?do=update_usergroup&id=<?php echo $usergroup['g_id'] ?>" method="post">
		<table class="table">
			<thead>
				<tr>
					<th colspan="5">
						<div class="fleft">User Group Information</div>
					</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Name</td>
				<td><input type="text" name="name" value="<?php echo $usergroup['name'] ?>" class="form-control span-4"></td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Update User Group">
		</div>
	</form>
</div>
