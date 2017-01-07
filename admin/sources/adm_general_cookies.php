<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_general_cookies.php
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

<h1>Cookies</h1>

<div class="block">
	<div class="grid-row">
		<form action="process.php?do=save" method="post">
			<?php echo $message ?>
			<table class="table">
				<thead>
					<tr>
						<th colspan="2">General Cookies Settings</th>
					</tr>
				</thead>
				<tr>
					<td class="font-w600">Expiration time</span></td>
					<td><input type="text" name="general_session_expiration" class="form-control span-1" value="<?php echo $Admin->SelectConfig("general_session_expiration") ?>"> seconds</td>
				</tr>
			</table>
			<div class="text-right">
				<input type="submit" class="btn btn-default" value="Save Settings">
			</div>
		</form>
	</div>
</div>
