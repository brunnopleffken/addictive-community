<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_general_profiles.php
	#  License: GPLv2
	#  Copyright: (c) 2016 - Addictive Community
	## ---------------------------------------------------

	$msg = (Http::Request("msg")) ? Http::Request("msg") : "";

	switch($msg) {
		case 1:
			$message = Html::Notification("The settings has been successfully changed.", "success");
			break;
		default:
			$message = "";
			break;
	}

?>

	<h1>Profiles</h1>

	<div id="content">
		<div class="grid-row">
			<form action="process.php?do=save" method="post">
				<?php echo $message ?>
				<table class="table-list">
					<tr>
						<th colspan="2">Profiles Settings</th>
					</tr>
					<tr>
						<td class="title-fixed">Enable signatures</td>
						<td><label><?php echo $Admin->SelectCheckbox("general_member_enable_signature") ?> Enable signatures in posts and personal messages.</label></td>
					</tr>
					<tr>
						<td class="title-fixed">Enable photo upload <span class="title-desc">This will restrict the avatars to 'Gravatar' only. Photos already sent will not be affected.</span></td>
						<td><label><?php echo $Admin->SelectCheckbox("general_member_enable_avatar_upload") ?> Allow members to send their own photos as avatar.</label></td>
					</tr>
					<tr>
						<td class="title-fixed">Enable ranks and promotions</td>
						<td><?php echo $Admin->SelectCheckbox("general_member_enable_ranks") ?> Enable ranks for all members.</td>
					</tr>
				</table>
				<div class="box fright"><input type="submit" value="Save Settings"></div>
			</form>
		</div>
	</div>
