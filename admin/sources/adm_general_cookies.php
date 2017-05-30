<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_general_cookies.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
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
					<td><input type="text" name="general_session_expiration" class="form-control span-1" value="<?php echo $Admin->selectConfig("general_session_expiration") ?>"> seconds</td>
				</tr>
			</table>
			<div class="text-right">
				<input type="submit" class="btn btn-default" value="Save Settings">
			</div>
		</form>
	</div>
</div>
