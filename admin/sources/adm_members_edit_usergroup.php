<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_members_edit_usergroup.php
	#  License: GPLv2
	#  Copyright: (c) 2015 - Addictive Community
	## ---------------------------------------------------

	// Get usergroup information
	$id = Http::Request("id", true);
	$Db->Query("SELECT * FROM c_usergroups WHERE g_id = {$id};");
	$usergroup = $Db->Fetch();

?>

	<h1>Edit User Group: <?php echo $usergroup['name'] ?></h1>

	<div id="content">
		<div class="grid-row">
			<form action="process.php?do=update_usergroup&id=<?php echo $usergroup['g_id'] ?>" method="post">
				<table class="table-list">
					<tr>
						<th colspan="5">
							<div class="fleft">User Group Information</div>
						</th>
					</tr>
					<tr>
						<td class="title-fixed">Name</td>
						<td><input type="text" name="name" value="<?php echo $usergroup['name'] ?>" class="small"></td>
					</tr>
				</table>
				<div class="fright"><input type="submit" value="Update User Group"></div>
			</form>
		</div>
	</div>
