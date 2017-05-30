<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_members_edit.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;

// Notification

$msg = (Http::request("msg")) ? Http::request("msg") : 0;

switch($msg) {
	case 1:
		$message = Html::notification("The member profile has been successfully updated.", "success");
		break;
	default:
		$message = "";
}

// Member ID
$id = Http::request("id", true);

// Member info
Database::query("SELECT * FROM c_members WHERE m_id = {$id};");
$member = Database::fetch();

// Usergroups
Database::query("SELECT * FROM c_usergroups;");
while($usergroup = Database::fetch()) {
	$usergroup['selected'] = ($usergroup['g_id'] == $member['usergroup']) ? "selected" : "";
	$usergroups[] = $usergroup;
}

?>

<h1>Edit Member: <?= $member['username'] ?></h1>

<div class="block">
	<?php echo $message ?>
	<form action="process.php?do=update_member&id=<?php echo $id ?>" method="post">
		<table class="table">
			<thead>
				</tr>
					<th colspan="2">Basic member information</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Username</td>
				<td><input type="text" name="username" class="form-control span-3" value="<?php echo $member['username'] ?>"></td>
			</tr>
			<tr>
				<td class="font-w600">E-mail Address</td>
				<td><input type="text" name="email" class="form-control span-5" value="<?php echo $member['email'] ?>"></td>
			</tr>
			<tr>
				<td class="font-w600">User Group</td>
				<td>
					<select name="usergroup" id="usergroup" class="form-control span-3">
						<?php foreach($usergroups as $group): ?>
							<option value="<?php echo $group['g_id'] ?>" <?php echo $group['selected'] ?>>
								<?php echo $group['name'] ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="font-w600">Signature (HTML)</td>
				<td><textarea name="signature" class="form-control span-6" rows="4"><?php echo $member['signature'] ?></textarea></td>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Profile information</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Member Title</td>
				<td><input type="text" name="member_title" class="form-control span-4" value="<?php echo $member['member_title'] ?>"></td>
			</tr>
			<tr>
				<td class="font-w600">About</td>
				<td><textarea name="profile" class="form-control span-6" rows="5"><?php echo $member['profile'] ?></textarea></td>
			</tr>
			<tr>
				<td class="font-w600">Location</td>
				<td><input type="text" name="location" class="form-control span-6" value="<?php echo $member['location'] ?>"></td>
			</tr>
			<tr>
				<td class="font-w600">Website</td>
				<td><input type="text" name="website" class="form-control span-6" value="<?php echo $member['website'] ?>"></td>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Social networks</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Facebook username</td>
				<td><input type="text" name="im_facebook" class="form-control span-4" value="<?php echo $member['im_facebook'] ?>"></td>
			</tr>
			<tr>
				<td class="font-w600">Twitter username</td>
				<td><input type="text" name="im_twitter" class="form-control span-4" value="<?php echo $member['im_twitter'] ?>"></td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Update Member">
		</div>
	</form>
</div>
