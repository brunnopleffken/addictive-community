<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_rooms_add_mod.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;

// Build notification messages
$msg = (Http::request("msg")) ? Http::request("msg") : "";

switch($msg) {
	case 1:
		$member_id = Http::request("m_id");
		Database::query("SELECT username FROM c_members WHERE m_id = {$member_id}");
		$member_info = Database::fetch();

		$message = Html::notification("You have successfully revoked the moderator permissions of {$member_info['username']} in this room.", "success");
		break;
	default:
		$message = "";
		break;
}

// Get rooms info

$id = Http::request("id", true);

$_room_info = Database::query("SELECT name, moderators FROM c_rooms WHERE r_id = {$id};");
$room_info = Database::fetch($_room_info);

if($room_info['moderators'] != "") {
	$moderators = unserialize($room_info['moderators']);

	foreach($moderators as $member_id) {
		$_member = Database::query("SELECT m_id, username, email, photo, photo_type FROM c_members WHERE m_id = {$member_id};");
		$member = Database::fetch($_member);

		Template::add("<tr>
				<td class='min'>" . Html::crop($Core->getAvatar($member, 30, "admin"), 30, 30) . "</td>
				<td><h3>{$member['username']}</h3></td>
				<td class='min'>
					<a href='process.php?do=remove_moderator&m_id={$member['m_id']}&r_id={$id}' class='btn btn-default btn-sm'>Revoke Moderator Permission</a>
				</td>
			</tr>");
	}
}

?>

<h1>Remove Moderator: "<?php echo $room_info['name'] ?>"</h1>

<div class="block">
	<?php echo $message ?>
	<table class="table">
		<thead>
			<tr>
				<th colspan="3">Moderators from <?php echo $room_info['name'] ?></th>
			</tr>
			<tr>
				<td colspan="2">Member Information</td>
				<td></td>
			</tr>
		</thead>
		<?php echo Template::get(); ?>
	</table>
</div>
