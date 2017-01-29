<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_general_email.php
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

// "Authentication Method" drop-down element value
$auth = $Admin->selectConfig("general_email_auth_method");

?>

<h1>E-mail</h1>

<div class="block">
	<form action="process.php?do=save" method="post">
		<?php echo $message ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">SMTP Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">SMTP server</td>
				<td><input type="text" name="general_email_smtp" class="form-control span-4" value="<?php echo $Admin->selectConfig("general_email_smtp") ?>"></td>
			</tr>
			<tr>
				<td class="font-w600">Username</td>
				<td><input type="text" name="general_email_username" class="form-control span-3" value="<?php echo $Admin->selectConfig("general_email_username") ?>"></td>
			</tr>
			<tr>
				<td class="font-w600">Password</td>
				<td><input type="password" name="general_email_password" class="form-control span-3" value="<?php echo $Admin->selectConfig("general_email_password") ?>"></td>
			</tr>
			<tr>
				<td class="font-w600">TCP port</td>
				<td><input type="text" name="general_email_port" class="form-control span-1" value="<?php echo $Admin->selectConfig("general_email_port") ?>"></td>
			</tr>
			<tr>
				<td class="font-w600">Requires authentication</td>
				<td><label><?php echo $Admin->selectCheckbox("general_email_authentication") ?> Enable SMTP authentication</label></td>
			</tr>
			<tr>
				<td class="font-w600">Authentication method</td>
				<td>
					<select name="general_email_auth_method" class="form-control span-2">
						<option value="tls" <?php echo ($auth == "tls") ? "selected" : "" ?>>TLS</option>
						<option value="ssl" <?php echo ($auth == "ssl") ? "selected" : "" ?>>SSL</option>
					</select>
				</td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">E-mail Identification</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">"From" e-mail address<small>Usually the same used in authentication.</small></td>
				<td><input type="text" name="general_email_from" class="form-control span-4" value="<?php echo $Admin->selectConfig("general_email_from") ?>"></td>
			</tr>
			<tr>
				<td class="font-w600">"From" name</td>
				<td><input type="text" name="general_email_from_name" class="form-control span-4" value="<?php echo $Admin->selectConfig("general_email_from_name") ?>"></td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Settings">
		</div>
	</form>
</div>
