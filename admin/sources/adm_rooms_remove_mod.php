<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_rooms_add_mod.php
	#  License: GPLv2
	#  Copyright: (c) 2016 - Addictive Community
	## ---------------------------------------------------

	// Build notification messages
	$msg = (Http::Request("msg")) ? Http::Request("msg") : "";

	switch($msg) {
		case 1:
			$member_id = Http::Request("m_id");
			$Db->Query("SELECT username FROM c_members WHERE m_id = {$member_id}");
			$member_info = $Db->Fetch();

			$message = Html::Notification("You have successfully revoked the moderator permissions of {$member_info['username']} in this room.", "success");
			break;
		default:
			$message = "";
			break;
	}

	// Get rooms info

	$id = Http::Request("id", true);

	$_room_info = $Db->Query("SELECT name, moderators FROM c_rooms WHERE r_id = {$id};");
	$room_info = $Db->Fetch($_room_info);

	if($room_info['moderators'] != "") {
		$moderators = unserialize($room_info['moderators']);

		foreach($moderators as $member_id) {
			$_member = $Db->Query("SELECT m_id, username, email, photo, photo_type FROM c_members WHERE m_id = {$member_id};");
			$member = $Db->Fetch($_member);

			Template::Add("<tr>
					<td class='min'>" . Html::Crop($Core->GetAvatar($member, 30, "admin"), 30, 30) . "</td>
					<td><h3>{$member['username']}</h3></td>
					<td class='min'>
						<a href='process.php?do=remove_moderator&m_id={$member['m_id']}&r_id={$id}' class='button-grey-default transition'>Revoke Moderator Permission</a>
					</td>
				</tr>");
		}
	}

?>

	<h1>Remove Moderator: <?php echo $room_info['name'] ?></h1>

	<div id="content">
		<div class="grid-row">
			<?php echo $message ?>
			<table class="table-list">
				<tr>
					<th colspan="3">Moderators</th>
				</tr>
				<tr class="subtitle">
					<td colspan="2">Member Name</td>
					<td></td>
				</tr>
				<?php echo Template::Get(); ?>
			</table>
		</div>
	</div>
