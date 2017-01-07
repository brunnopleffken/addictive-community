<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_members_add.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;
use \AC\Kernel\Text;

$username = "";

// User group list

$list = "";
Database::Query("SELECT * FROM c_usergroups ORDER BY g_id");

while($group = Database::Fetch()) {
	if($group['g_id'] == 3) {
		$list .= "<option value='{$group['g_id']}' selected>{$group['name']}</option>";
	}
	else {
		$list .= "<option value='{$group['g_id']}'>{$group['name']}</option>";
	}
}

// Search by username
// Minimum 2 characters to avoid DB overload

if(isset($_POST['username']) && strlen($_POST['username']) >= 2) {
	$username = Text::Sanitize($_POST['username']);

	Database::Query("SELECT m.m_id, m.username, m.email, m.joined, m.photo, m.photo_type, m.posts, m.usergroup, u.g_id, u.name
			FROM c_members m INNER JOIN c_usergroups u ON (m.usergroup = u.g_id)
			WHERE username LIKE '%{$username}%' AND m_id <> 1;");

	while($member = Database::Fetch()) {
		$member['joined'] = $Core->DateFormat($member['joined']);

		if($member['usergroup'] == 4) {
			$member['ban_button'] = "<a href='?act=members&amp;p=ban&amp;do=remove&amp;id={$member['m_id']}' class='btn btn-default btn-sm'>Remove Ban</a>";
		}
		else {
			$member['ban_button'] = "<a href='?act=members&amp;p=ban&amp;do=banishment&amp;id={$member['m_id']}' class='btn btn-default btn-sm'>Ban Member</a>";
		}

		Template::Add("<tr>
				<td>" . Html::Crop($Core->GetAvatar($member, 36, "admin"), 36, 36) . "</td>
				<td style='font-size:15px'><b>{$member['username']}</b></td>
				<td>{$member['email']}</td>
				<td>{$member['joined']}</td>
				<td>{$member['name']}</td>
				<td>{$member['posts']}</td>
				<td class='text-right'>{$member['ban_button']}</td>
			</tr>");
	}
}
else {
	Template::Add("<tr><td colspan='7' class='text-center'>No members to show.</td></tr>");
}

// Execute actions

if(Http::Request("do")) {
	$id = Http::Request("id");

	switch(Http::Request("do")) {
		case "banishment":
			Database::Query("UPDATE c_members SET usergroup = 4 WHERE m_id = '{$id}';");
			break;

		case "remove":
			Database::Query("UPDATE c_members SET usergroup = 3 WHERE m_id = '{$id}';");
			break;
	}
}

?>

<h1>Ban Member</h1>

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

	<!-- MEMBER LIST -->
	<table class="table">
		<thead>
			<tr>
				<th colspan="8">Select member for banishment</th>
			</tr>
			<tr>
				<td width="1%"></td>
				<td>Username</td>
				<td>E-mail Address</td>
				<td>Joined</td>
				<td>Usergroup</td>
				<td>Posts</td>
				<td></td>
			</tr>
		</thead>
		<?php echo Template::Get() ?>
	</table>
</div>
