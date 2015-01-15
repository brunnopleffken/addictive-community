<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_members_manage.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// Execute queries, if defined

	$do = (Html::Request("do")) ? Html::Request("do") : false;

	if($do) {
		switch($do) {
			// Edit member info
			case "edit":

				break;

			// Delete member
			case "delete":

				break;
		}
	}

	// Get member list

	$Db->Query("SELECT m.m_id, m.username, m.email, m.joined, m.posts, m.usergroup, u.g_id, u.name
			FROM c_members m INNER JOIN c_usergroups u ON (m.usergroup = u.g_id);");

	while($member = $Db->Fetch()) {
		$member['joined'] = $Core->DateFormat($member['joined']);

		Template::Add("<tr>
				<td>" . Html::Crop($Core->GetGravatar($member['email'], $member['m_id'], 36), 36, 36) . "</td>
				<td><b>{$member['username']}</b></td>
				<td>{$member['email']}</td>
				<td>{$member['joined']}</td>
				<td>{$member['name']}</td>
				<td>{$member['posts']}</td>
				<td><a href=\"?act=members&amp;p=manage&amp;do=edit&amp;id={$member['m_id']}\"><img src='images/edit.png'></a></td>
				<td><a href=\"?act=members&amp;p=manage&amp;do=delete&amp;id={$member['m_id']}\"><img src='images/delete.png'></a></td>
			</tr>");
	}

?>

	<h1>Manage Members</h1>

	<div id="content">
		<div class="grid-row">
			<!-- LEFT -->
			<form action="process.php?do=optimize" method="post">
				<table class="table-list">
					<tr>
						<th colspan="8">Registered Members</th>
					</tr>
					<tr class="subtitle">
						<td width="1%"></td>
						<td>Username</td>
						<td>E-mail Address</td>
						<td>Joined</td>
						<td>Usergroup</td>
						<td>Posts</td>
						<td width="1%"></td>
						<td width="1%"></td>
					</tr>
					<?php echo Template::Get() ?>
				</table>
			</form>
		</div>
	</div>