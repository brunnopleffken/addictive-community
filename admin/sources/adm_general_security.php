<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_general_security.php
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

<h1>Security</h1>

<div class="block">
	<form action="process.php?do=save" method="post">
		<?php echo $message ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">New Member Registration</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Require e-mail validation<small>You <strong>must</strong> configure your <a href="main.php?act=general&p=email">E-mail (SMTP) settings</a>, otherwise you will not be able to send e-mails.</small></td>
				<td><label><?php echo $Admin->selectCheckbox("general_security_validation") ?> Send validation e-mail to new members.</label></td>
			</tr>
			<tr>
				<td class="font-w600">CAPTCHA validation<small>Your <a href="main.php?act=system&p=server">server environment</a> must have GD2 extension enabled.</small></td>
				<td><label><?php echo $Admin->selectCheckbox("general_security_captcha") ?> Use CAPTCHA validation.</label></td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Community Usage</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Offline Mode<small>Note that enabling this feature will also prevent search engines from tracking your community content.</small></td>
				<td><label><?php echo $Admin->selectCheckbox("general_offline") ?> Community is offline for guests and members (only administrators are allowed to log in).</label></td>
			</tr>
			<tr>
				<td class="font-w600">Disable new registrations</td>
				<td><label><?php echo $Admin->selectCheckbox("general_disable_registrations") ?> This community is closed for new registrations.</label></td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Settings">
		</div>
	</form>
</div>
