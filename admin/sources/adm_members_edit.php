<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_members_edit.php
	#  License: GPLv2
	#  Copyright: (c) 2016 - Addictive Community
	## ---------------------------------------------------

	// Notification

	$msg = (Http::Request("msg")) ? Http::Request("msg") : 0;

	switch($msg) {
		case 1:
			$message = Html::Notification("The member profile has been successfully updated.", "success");
			break;
		default:
			$message = "";
	}

	// Member ID
	$id = Http::Request("id", true);

	// Member info
	$Db->Query("SELECT * FROM c_members WHERE m_id = {$id};");
	$member = $Db->Fetch();

	// Usergroups
	$Db->Query("SELECT * FROM c_usergroups;");
	while($usergroup = $Db->Fetch()) {
		$usergroup['selected'] = ($usergroup['g_id'] == $member['usergroup']) ? "selected" : "";
		$usergroups[] = $usergroup;
	}

?>

	<h1>Edit Member: Fronteira Final</h1>

	<div id="content">
		<div class="grid-row">
			<?php echo $message ?>
			<!-- LEFT -->
			<form action="process.php?do=update_member&id=<?php echo $id ?>" method="post">
				<table class="table-list">
					<tr>
						<th colspan="2">Basic member information</th>
					</tr>
					<tr>
						<td class="title-fixed">Username</td>
						<td><input type="text" name="username" class="small" value="<?php echo $member['username'] ?>"></td>
					</tr>
					<tr>
						<td class="title-fixed">E-mail Address</td>
						<td><input type="text" name="email" class="medium" value="<?php echo $member['email'] ?>"></td>
					</tr>
					<tr>
						<td class="title-fixed">User Group</td>
						<td>
							<select name="usergroup" id="usergroup">
								<?php foreach($usergroups as $group): ?>
									<option value="<?php echo $group['g_id'] ?>" <?php echo $group['selected'] ?>>
										<?php echo $group['name'] ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="title-fixed">Signature (HTML)</td>
						<td><textarea name="signature" class="large" rows="4"><?php echo $member['signature'] ?></textarea></td>
					</tr>
				</table>
				<table class="table-list">
					<tr>
						<th colspan="2">Profile information</th>
					</tr>
					<tr>
						<td class="title-fixed">Member Title</td>
						<td><input type="text" name="member_title" class="medium" value="<?php echo $member['member_title'] ?>"></td>
					</tr>
					<tr>
						<td class="title-fixed">About</td>
						<td><textarea name="profile" class="large" rows="5"><?php echo $member['profile'] ?></textarea></td>
					</tr>
					<tr>
						<td class="title-fixed">Location</td>
						<td><input type="text" name="location" class="medium" value="<?php echo $member['location'] ?>"></td>
					</tr>
					<tr>
						<td class="title-fixed">Website</td>
						<td><input type="text" name="website" class="medium" value="<?php echo $member['website'] ?>"></td>
					</tr>
				</table>
				<table class="table-list">
					<tr>
						<th colspan="2">Social networks</th>
					</tr>
					<tr>
						<td class="title-fixed">Facebook username</td>
						<td><input type="text" name="im_facebook" class="small" value="<?php echo $member['im_facebook'] ?>"></td>
					</tr>
					<tr>
						<td class="title-fixed">Twitter username</td>
						<td><input type="text" name="im_twitter" class="small" value="<?php echo $member['im_twitter'] ?>"></td>
					</tr>
				</table>
				<div class="box fright"><input type="submit" value="Update Member"></div>
			</form>
		</div>
	</div>
