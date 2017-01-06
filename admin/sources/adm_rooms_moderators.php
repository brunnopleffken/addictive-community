<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_rooms_moderators.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;
use \AC\Kernel\Text;

// Build notification messages
$msg = (Http::Request("msg")) ? Http::Request("msg") : "";

switch($msg) {
	case 1:
		$message = Html::Notification("You have successfully added a new moderator.", "success");
		break;
	case 2:
		$message = Html::Notification("This member is already defined as moderator in this room.", "failure");
		break;
	default:
		$message = "";
		break;
}

// Get rooms and its moderators
$list = Database::Query("SELECT r_id, name, url, moderators FROM c_rooms WHERE CHAR_LENGTH(url) = 0 OR url IS NULL;");

while($result = Database::Fetch($list)) {
	// Show list of moderators
	if($result['moderators'] == "") {
		$result['moderators_list'] = "---";
	}
	else {
		$moderators = unserialize($result['moderators']);
		$moderator_list = array();

		foreach($moderators as $member_id) {
			$mod_info = Database::Query("SELECT username FROM c_members WHERE m_id = {$member_id};");
			$member = Database::Fetch($mod_info);

			$moderator_list[] = $member['username'];
		}

		$result['moderators_list'] = Text::ToList($moderator_list);
	}

	// Cannot add moderators in Redirect Rooms
	if($result['url'] != "") {
		$result['add_link'] = "";
		$result['remove_link'] = "";
	}
	else {
		$result['add_link'] = "<a href='main.php?act=rooms&p=add_mod&id={$result['r_id']}' title='Add'><i class='fa fa-user-plus'></i></a>";
		$result['remove_link'] = "<a href='main.php?act=rooms&p=remove_mod&id={$result['r_id']}' title='Remove'><i class='fa fa-remove'></i></a>";
	}

	// Build table
	Template::Add("<tr>
			<td><strong>{$result['name']}</strong></td>
			<td>{$result['moderators_list']}</td>
			<td class='min'>{$result['add_link']}</td>
			<td class='min'>{$result['remove_link']}</td>
		</tr>");
}

?>

<h1>Room Moderators</h1>

<div id="content">
	<div class="grid-row">
		<?php echo $message ?>
		<table class="table-list">
			<tr>
				<th colspan="4">List of Moderators per Room</th>
			</tr>
			<tr class="subtitle">
				<td style="width: 250px">Room</td>
				<td>Moderators</td>
				<td class="min">Add New</td>
				<td class="min">Remove</td>
			</tr>
			<?php echo Template::Get(); ?>
		</table>
	</div>
</div>
