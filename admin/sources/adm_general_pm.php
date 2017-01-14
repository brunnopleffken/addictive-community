<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_general_pm.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Html;
use \AC\Kernel\Http;

$msg = (Http::request("msg")) ? Http::request("msg") : "";

switch($msg) {
	case 1:
		$message = Html::notification("The settings has been successfully changed.", "success");
		break;
	default:
		$message = "";
		break;
}

?>

<h1>Personal Messages</h1>

<div class="block">
	<form action="process.php?do=save" method="post">
		<?php echo $message ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">PMs Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Enable personal messages</td>
				<td><label><?php echo $Admin->selectCheckbox("member_pm_enable") ?> Enable personal messages to all registered members</label></td>
			</tr>
			<tr>
				<td class="font-w600">Inbox storage size</td>
				<td><input type="text" name="member_pm_storage" class="form-control span-1" value="<?php echo $Admin->selectConfig("member_pm_storage") ?>"> personal messages</td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Settings">
		</div>
	</form>
</div>
