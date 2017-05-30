<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_rooms_edit.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Http;

// Room editing

$id = Http::request("id");

Database::query("SELECT * FROM c_rooms WHERE r_id = '{$id}';");
$room_info = Database::fetch();

?>

<h1>Edit Room: <?php echo $room_info['name'] ?></h1>

<div class="block">
	<form action="process.php?do=editroom" method="post">
		<input type="hidden" name="room_id" value="<?php echo $id ?>">
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Room Information</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Room name</td>
				<td><input type="text" name="room_name" value="<?php echo $room_info['name'] ?>" class="form-control span-6"></td>
			</tr>
			<tr>
				<td class="font-w600">Room description</td>
				<td><textarea name="room_description" class="form-control span-6" rows="4"><?php echo $room_info['description'] ?></textarea></td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Security</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Password protection<small>Leave empty if not required</small></td>
				<td><input type="password" name="password" value="<?php echo $room_info['password'] ?>" class="form-control span-3"></td>
			</tr>
			<tr>
				<td class="font-w600">Read only</td>
				<td><label><?php echo $Admin->booleanCheckbox("read_only", $room_info['read_only']) ?> Mark room as Read Only (post and reply not allowed).</label></td>
			</tr>
			<tr>
				<td class="font-w600">Invisible</td>
				<td><label><?php echo $Admin->booleanCheckbox("invisible", $room_info['invisible']) ?> Set room as invisible for members, except Administrators.</label></td>
			</tr>
			<tr>
				<td class="font-w600">File uploads</td>
				<td><label><?php echo $Admin->booleanCheckbox("upload", $room_info['upload']) ?> Allow attachments in posts in this room.</label></td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Rules</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Enable custom rules<small>Add an alert board at the top of this room?</small></td>
				<td><label><?php echo $Admin->booleanCheckbox("rules_visible", $room_info['rules_visible']) ?> Enable custom rules to this room.</label></td>
			</tr>
			<tr>
				<td class="font-w600">Rules title <small>If enabled</small></td>
				<td><input type="text" name="rules_title" value="<?php echo $room_info['rules_title'] ?>" class="form-control span-4"></td>
			</tr>
			<tr>
				<td class="font-w600">Rules title <small>If enabled</small></td>
				<td><textarea name="rules_text" class="form-control span-6" rows="8"><?php echo $room_info['rules_text'] ?></textarea></td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Edit">
		</div>
	</form>
</div>
