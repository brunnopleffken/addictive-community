<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_general_profiles.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Html;
use \AC\Kernel\Http;

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

<div class="block">
	<form action="process.php?do=save" method="post">
		<?php echo $message ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Profiles Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Enable signatures</td>
				<td><label><?php echo $Admin->SelectCheckbox("general_member_enable_signature") ?> Enable signatures in posts and personal messages.</label></td>
			</tr>
			<tr>
				<td class="font-w600">Enable photo upload <small>This will restrict the avatars to 'Gravatar' only. Photos already sent will not be affected.</small></td>
				<td><label><?php echo $Admin->SelectCheckbox("general_member_enable_avatar_upload") ?> Allow members to upload their own photos as avatar.</label></td>
			</tr>
			<tr>
				<td class="font-w600">Enable ranks and promotions</td>
				<td><?php echo $Admin->SelectCheckbox("general_member_enable_ranks") ?> Enable ranks for all members.</td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Settings">
		</div>
	</form>
</div>
