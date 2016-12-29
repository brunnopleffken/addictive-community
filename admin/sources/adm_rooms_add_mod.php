<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_rooms_add_mod.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;

// Get rooms info

$id = Http::Request("id", true);

$Db->Query("SELECT name FROM c_rooms;");
$room_info = $Db->Fetch();

if(isset($_POST['username'])) {
	// Must the page show search results?
	$has_result = true;

	$username = $_POST['username'];
	$logged_member = Http::Request("member_id", true);

	$Db->Query("SELECT m_id, username, email, photo, photo_type FROM c_members
			WHERE username LIKE '%{$username}%' AND m_id <> {$logged_member}
			LIMIT 10;");

	while($member = $Db->Fetch()) {
		Template::Add("<tr>
				<td class='min'>" . Html::Crop($Core->GetAvatar($member, 40, "admin"), 40, 40) . "</td>
				<td><h3>{$member['username']}</h3></td>
				<td class='min' style='padding: 20px 0'>
					<a href='process.php?do=add_moderator&m_id={$member['m_id']}&r_id={$id}' class='default-button'>Add as Moderator</a>
				</td>
			</tr>");
	}
}
else {
	// Must the page show search results?
	$has_result = false;
	$username = "";
}

?>

<h1>Add New Moderator: <?php echo $room_info['name'] ?></h1>

<div id="content">
	<div class="grid-row">
		<?php echo Html::Notification("The member with moderator privileges will be able to lock/unlock threads, create announcements and to edit/delete others member's post in this room. Moderators still cannot access the Administration Control Panel.", "warning"); ?>
		<table class="table-list">
			<tr>
				<th colspan="3">Find member...</th>
			</tr>
			<tr>
				<form method="post">
					<td class="title-fixed">Username</td>
					<td><input type="text" name="username" class="medium" value="<?php echo $username ?>"> <input type="submit" value="Find"></td>
				</form>
			</tr>
		</table>
		<?php if($has_result): ?>
			<table class="table-list">
				<tr>
					<th colspan="3">Search Results (limited by 10 results)</th>
				</tr>
				<tr class="subtitle">
					<td colspan="3">Member Information</td>
				</tr>
				<?php echo Template::Get(); ?>
			</table>
		<?php endif; ?>
	</div>
</div>
