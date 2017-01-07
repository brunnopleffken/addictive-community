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

<h1>Warnings</h1>

<div class="block">
	<form action="process.php?do=save" method="post">
		<?php echo $message ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">General Warning Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Maximum warning points<small>Reaching X warnings, the user is automatically banned from the community.</small></td>
				<td><input type="text" name="general_warning_max" class="form-control span-1" value="<?php echo $Admin->SelectConfig("general_warning_max") ?>"> warnings</td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Settings">
		</div>
	</form>
</div>
