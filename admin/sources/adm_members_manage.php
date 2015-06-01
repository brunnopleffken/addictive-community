<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_members_manage.php
	#  License: GPLv2
	#  Copyright: (c) 2015 - Addictive Community
	## ---------------------------------------------------


	$username = (Html::Request("username")) ? Html::Request("username") : "";

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

	if($username != "") {
		$Db->Query("SELECT m.m_id, m.username, m.email, m.photo, m.photo_type, m.joined, m.posts, m.usergroup, u.g_id, u.name
				FROM c_members m INNER JOIN c_usergroups u ON (m.usergroup = u.g_id)
				WHERE username LIKE '%{$username}%' ORDER BY m_id DESC LIMIT 10;");
	}
	else {
		$Db->Query("SELECT m.m_id, m.username, m.email, m.photo, m.photo_type, m.joined, m.posts, m.usergroup, u.g_id, u.name
				FROM c_members m INNER JOIN c_usergroups u ON (m.usergroup = u.g_id)
				ORDER BY m_id DESC LIMIT 10;");
	}

	while($member = $Db->Fetch()) {
		$member['joined'] = $Core->DateFormat($member['joined']);

		if($member['m_id'] != 1) {
			$remove = "<a href='?act=members&amp;p=manage&amp;do=delete&amp;id={$member['m_id']}'><i class='fa fa-remove'></i></a>";
		}
		else {
			$remove = "";
		}

		Template::Add("<tr>
				<td>" . Html::Crop($Core->GetAvatar($member, 36, "admin"), 36, 36) . "</td>
				<td><b>{$member['username']}</b></td>
				<td>{$member['email']}</td>
				<td>{$member['joined']}</td>
				<td>{$member['name']}</td>
				<td>{$member['posts']}</td>
				<td class='min'><a href='?act=members&amp;p=manage&amp;do=edit&amp;id={$member['m_id']}'><i class='fa fa-pencil'></i></a></td>
				<td class='min'>{$remove}</td>
			</tr>");
	}

?>

	<h1>Manage Members</h1>

	<div id="content">
		<div class="grid-row">
			<!-- SEARCH -->
			<form action="#" method="post">
				<div class="input-box">
					<div class="input-box-label">Search</div>
					<div class="input-box-field">
						<input type="text" name="username" class="small" value="<?php echo $username ?>">
						<div class="fright"><input type="submit" value="Find Member"></div>
					</div>
				</div>
			</form>

			<br>

			<!-- LEFT -->
			<form action="process.php?do=optimize" method="post">
				<table class="table-list">
					<tr>
						<?php if($username != ""): ?>
						<th colspan="8">Search Results (output is also always limited by 10 results)</th>
						<?php else: ?>
						<th colspan="8">Last 10 Registered Members</th>
						<?php endif; ?>
					</tr>
					<tr class="subtitle">
						<td width="1%"></td>
						<td>Username</td>
						<td>E-mail Address</td>
						<td>Joined</td>
						<td>Usergroup</td>
						<td>Posts</td>
						<td class="min">Edit</td>
						<td class="min">Delete</td>
					</tr>
					<?php echo Template::Get() ?>
				</table>
			</form>
		</div>
	</div>
