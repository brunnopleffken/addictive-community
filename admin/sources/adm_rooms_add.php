<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_rooms_add.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;

// ---------------------------------------------------
// Get list of categories
// ---------------------------------------------------

$categories = "";
Database::query("SELECT * FROM c_categories");

while($_result = Database::fetch()) {
	$categories .= "<option value='{$_result['c_id']}'>{$_result['name']}</option>";
}

// ---------------------------------------------------
// Create permission matrix
// ---------------------------------------------------

// View

function MatrixView()
{
	Database::query("SELECT * FROM c_usergroups;");

	$title = "";
	$checkboxes = "";

	while($view_g = Database::fetch()) {
		$title .= "<td>{$view_g['name']}</td>";

		if($view_g['view_board'] == 1) {
			$checkboxes .= "<td class='center'><input type='checkbox' name='view[]' value='V_{$view_g['g_id']}' checked></td>";
		}
		else {
			$checkboxes .= "<td class='center'><input type='checkbox' name='view[]' value='V_{$view_g['g_id']}'></td>";
		}
	}

	$view = "
	<table cellspacing='0'>
		<tr>{$title}</tr>
		<tr>{$checkboxes}</tr>
	</table>";

	return $view;
}

// Post

function MatrixPost()
{
	Database::query("SELECT * FROM c_usergroups;");

	$title = "";
	$checkboxes = "";

	while($view_g = Database::fetch()) {
		$title .= "<td>{$view_g['name']}</td>";

		if($view_g['post_new_threads'] == 1) {
			$checkboxes .= "<td class='center'><input type='checkbox' name='post[]' value='V_{$view_g['g_id']}' checked></td>";
		}
		else {
			$checkboxes .= "<td class='center'><input type='checkbox' name='post[]' value='V_{$view_g['g_id']}'></td>";
		}
	}

	$post = "
	<table cellspacing='0'><tr>
		{$title}
		</tr><tr>
		{$checkboxes}
	</tr></table>";

	return $post;
}

// Reply

function MatrixReply()
{
	Database::query("SELECT * FROM c_usergroups;");

	$title = "";
	$checkboxes = "";

	while($view_g = Database::fetch()) {
		$title .= "<td>{$view_g['name']}</td>";

		if($view_g['reply_threads'] == 1) {
			$checkboxes .= "<td class='center'><input type='checkbox' name='reply[]' value='V_{$view_g['g_id']}' checked></td>";
		}
		else {
			$checkboxes .= "<td class='center'><input type='checkbox' name='reply[]' value='V_{$view_g['g_id']}'></td>";
		}
	}

	$reply = "
	<table cellspacing='0'><tr>
		{$title}
		</tr><tr>
		{$checkboxes}
	</tr></table>";

	return $reply;
}

?>

<h1>Add New Room</h1>

<div class="block">
	<form action="process.php?do=newroom" method="post">
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Room Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Room name</td>
				<td><input type="text" name="name" class="form-control span-6"></td>
			</tr>
			<tr>
				<td class="font-w600">Room description</td>
				<td><textarea name="description" class="form-control span-6" rows="5"></textarea></td>
			</tr>
			<tr>
				<td class="font-w600">Category</td>
				<td>
					<select name="category_id" class="form-control span-6">
						<?php echo $categories ?>
					</select>
				</td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Redirect Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">URL to redirect<small>If so, the member will be redirected to the specified URL.</small></td>
				<td><input type="url" name="url" class="form-control span-6"></td>
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
				<td><input type="password" name="password" class="form-control span-3"></td>
			</tr>
			<tr>
				<td class="font-w600">Read only</td>
				<td><label><input type="checkbox" name="read_only"> Mark room as Read Only (new posts and replies not allowed for members).</label></td>
			</tr>
			<tr>
				<td class="font-w600">Invisible</td>
				<td><label><input type="checkbox" name="invisible"> Set room as invisible for members, except Administrators.</label></td>
			</tr>
			<tr>
				<td class="font-w600">File uploads</td>
				<td><input type="checkbox" name="upload" checked="checked"> Allow attachments in posts in this room.</td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Permissions</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Read threads</td>
				<td>
					<?php echo MatrixView() ?>
				</td>
			</tr>
			<tr>
				<td class="font-w600">Create new threads</td>
				<td>
					<?php echo MatrixPost() ?>
				</td>
			</tr>
			<tr>
				<td class="font-w600">Reply threads</td>
				<td>
					<?php echo MatrixReply() ?>
				</td>
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
				<td><label><input type="checkbox" id="rules_visible" name="rules_visible" onclick="CustomRulesSelect()"> Enable custom rules to this room.</label></td>
			</tr>
			<tr>
				<td class="font-w600">Rules title</td>
				<td><input type="text" name="rules_title" id="rules_title" class="form-control span-4" disabled></td>
			</tr>
			<tr>
				<td class="font-w600">Rules description</td>
				<td><textarea name="rules_text" id="rules_text" class="form-control span-6" rows="8" disabled></textarea></td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Create Room">
		</div>
	</form>
</div>
