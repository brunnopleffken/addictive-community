<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_rooms_edit.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Http;

// Room editing

$id = Http::Request("id");

Database::Query("SELECT * FROM c_rooms WHERE r_id = '{$id}';");
$room_info = Database::Fetch();

?>

<h1>Edit Room: <?php echo $room_info['name'] ?></h1>

<div id="content">
	<div class="grid-row">
		<form action="process.php?do=editroom" method="post">
			<input type="hidden" name="room_id" value="<?php echo $id ?>">
			<table class="table-list">
				<tr>
					<th colspan="2">Room Information</th>
				</tr>
				<tr>
					<td class="title-fixed">Room name</td>
					<td><input type="text" name="room_name" value="<?php echo $room_info['name'] ?>" class="medium"></td>
				</tr>
				<tr>
					<td class="title-fixed">Room description</td>
					<td><textarea name="room_description" class="large" rows="4"><?php echo $room_info['description'] ?></textarea></td>
				</tr>
			</table>

			<table class="table-list">
				<tr>
					<th colspan="2">Security</th>
				</tr>
				<tr>
					<td class="title-fixed">Password Protection<span class="title-desc">Leave empty if not required</span></td>
					<td><input type="password" name="password" value="<?php echo $room_info['password'] ?>" class="small"></td>
				</tr>
				<tr>
					<td class="title-fixed">Read Only</td>
					<td><label><?php echo $Admin->BooleanCheckbox("read_only", $room_info['read_only']) ?> Mark room as Read Only (post and reply not allowed).</label></td>
				</tr>
				<tr>
					<td class="title-fixed">Invisible</td>
					<td><label><?php echo $Admin->BooleanCheckbox("invisible", $room_info['invisible']) ?> Set room as invisible for members, except Administrators.</label></td>
				</tr>
				<tr>
					<td class="title-fixed">Uploads</td>
					<td><label><?php echo $Admin->BooleanCheckbox("upload", $room_info['upload']) ?> Allow file uploads in this room.</label></td>
				</tr>
			</table>

			<table class="table-list">
				<tr>
					<th colspan="2">Rules</th>
				</tr>
				<tr>
					<td class="title-fixed">Enable custom rules<span class="title-desc">Add specific rules to this room?</span></td>
					<td><label><?php echo $Admin->BooleanCheckbox("rules_visible", $room_info['rules_visible']) ?> Enable custom rules to this room.</label></td>
				</tr>
				<tr>
					<td class="title-fixed">Rules Title (if enabled)</td>
					<td><input type="text" name="rules_title" value="<?php echo $room_info['rules_title'] ?>" class="medium"></td>
				</tr>
				<tr>
					<td class="title-fixed">Rules Description (if enabled)</td>
					<td><textarea name="rules_text" class="large" rows="8"><?php echo $room_info['rules_text'] ?></textarea></td>
				</tr>
			</table>

			<div class="box fright"><input type="submit" value="Save Edit"></div>
		</form>
	</div>
</div>
