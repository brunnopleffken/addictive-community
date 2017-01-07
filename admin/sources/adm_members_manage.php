<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_members_manage.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;

$username = (Http::Request("username")) ? Http::Request("username") : "";

// Get member list

if($username != "") {
	Database::Query("SELECT m.m_id, m.username, m.email, m.photo, m.photo_type, m.joined, m.posts, m.usergroup, u.g_id, u.name
			FROM c_members m INNER JOIN c_usergroups u ON (m.usergroup = u.g_id)
			WHERE username LIKE '%{$username}%' ORDER BY m_id DESC LIMIT 10;");
}
else {
	Database::Query("SELECT m.m_id, m.username, m.email, m.photo, m.photo_type, m.joined, m.posts, m.usergroup, u.g_id, u.name
			FROM c_members m INNER JOIN c_usergroups u ON (m.usergroup = u.g_id)
			ORDER BY m_id DESC LIMIT 10;");
}

while($member = Database::Fetch()) {
	$member['joined'] = $Core->DateFormat($member['joined']);

	if($member['m_id'] != 1) {
		$remove = "<a href='process.php?do=delete_member&amp;id={$member['m_id']}' data-confirm='Do you really want to remove this member? This action CANNOT be undone.'><i class='fa fa-remove'></i></a>";
	}
	else {
		$remove = "";
	}

	Template::Add("<tr>
			<td>" . Html::Crop($Core->GetAvatar($member, 36, "admin"), 36, 36) . "</td>
			<td><b><a href='../profile/{$member['m_id']}' target='_blank'>{$member['username']}</a></b></td>
			<td>{$member['email']}</td>
			<td>{$member['joined']}</td>
			<td>{$member['name']}</td>
			<td>{$member['posts']}</td>
			<td class='min text-center'><a href='main.php?act=members&amp;p=edit&amp;id={$member['m_id']}'><i class='fa fa-pencil'></i></a></td>
			<td class='min text-center'>{$remove}</td>
		</tr>");
}

?>

<h1>Manage Members</h1>

<div class="block">
	<!-- SEARCH -->
	<form action="#" method="post">
		<div class="form-group grid">
			<label for="" class="col-1">Search</label>
			<div class="col-10">
				<input type="text" name="username" class="form-control span-3" value="<?php echo $username ?>">
				<input type="submit" class="btn btn-default" value="Find Member">
			</div>
		</div>
	</form>

	<form action="process.php?do=optimize" method="post">
		<table class="table">
			<thead>
				<tr>
					<?php if($username != ""): ?>
						<th colspan="8">Search Results (output is also always limited by 10 results)</th>
					<?php else: ?>
						<th colspan="8">Last 10 Registered Members</th>
					<?php endif; ?>
				</tr>
				<tr>
					<td class="min"></td>
					<td>Username</td>
					<td>E-mail Address</td>
					<td>Joined (UTC Time)</td>
					<td>Usergroup</td>
					<td>Posts</td>
					<td class="min">Edit</td>
					<td class="min">Delete</td>
				</tr>
			</thead>
			<?php echo Template::Get() ?>
		</table>
	</form>
</div>
