<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_rooms_add_mod.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;
use \AC\Kernel\Session;

// Get rooms info

$id = Http::request("id", true);

Database::query("SELECT name FROM c_rooms;");
$room_info = Database::fetch();

if(isset($_POST['username'])) {
	// Must the page show search results?
	$has_result = true;

	$username = $_POST['username'];
	$logged_member = Session::retrieve("admin_m_id");

	$members = Database::query("SELECT m_id, username, email, photo, photo_type FROM c_members
			WHERE username LIKE '%{$username}%' AND m_id <> {$logged_member} LIMIT 10;");

	while($member = Database::fetch()) {
		Template::add("<tr>
				<td class='min'>" . Html::crop($Core->getAvatar($member, 40), 40, 40) . "</td>
				<td><h3>{$member['username']}</h3></td>
				<td class='min' style='padding: 20px 0'>
					<a href='process.php?do=add_moderator&m_id={$member['m_id']}&r_id={$id}' class='btn btn-default btn-sm'>Add as Moderator</a>
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

<div class="block">
		<?php echo Html::notification("The member with moderator privileges will be able to lock/unlock threads, create announcements and to edit/delete others member's post in this room; but moderators still <u>cannot</u> access the Administration Control Panel.", "warning"); ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="3">Find member...</th>
				</tr>
			</thead>
			<tr>
				<form method="post">
					<td class="font-w600">Username</td>
					<td><input type="text" name="username" class="form-control span-4" value="<?php echo $username ?>"> <input type="submit" class="btn btn-default" value="Find"></td>
				</form>
			</tr>
		</table>
		<?php if($has_result): ?>
			<hr>
			<table class="table">
				<thead>
					<tr>
						<th colspan="3">Search Results (limited by 10 results)</th>
					</tr>
					<tr>
						<td colspan="3">Member Information</td>
					</tr>
				</thead>
				<?php echo Template::get(); ?>
			</table>
		<?php endif; ?>
</div>
