<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_general_security.php
	#  License: GPLv2
	#  Copyright: (c) 2015 - Addictive Community
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

	<h1>Security</h1>

	<div id="content">
		<div class="grid-row">
			<!-- LEFT -->
			<form action="process.php?do=save" method="post">
				<?php echo $message ?>
				<table class="table-list">
					<tr>
						<th colspan="2">New Member Registration</th>
					</tr>
					<tr>
						<td class="title-fixed">Require e-mail validation<span class="title-desc">You <strong>must</strong> configure your <a href="main.php?act=general&p=email">E-mail (SMTP) settings</a>, otherwise you will not be able to send e-mails.</span></td>
						<td><label><?php echo $Admin->SelectCheckbox("general_security_validation") ?> Send validation e-mail to new members.</label></td>
					</tr>
					<tr>
						<td class="title-fixed">CAPTCHA validation<span class="title-desc">Your <a href="main.php?act=system&p=server">server environment</a> must have GD2 extension enabled.</span></td>
						<td><label><?php echo $Admin->SelectCheckbox("general_security_captcha") ?> Use CAPTCHA validation.</label></td>
					</tr>
				</table>

				<table class="table-list">
					<tr>
						<th colspan="2">Community Usage</th>
					</tr>
					<tr>
						<td class="title-fixed">Community Offline<span class="title-desc">Note that enabling this feature will also prevent search engines from tracking your community content.</span></td>
						<td><label><?php echo $Admin->SelectCheckbox("general_offline") ?> Community is offline for guests and members (only administrators are allowed to log in).</label></td>
					</tr>
					<tr>
						<td class="title-fixed">Disable new registrations</td>
						<td><label><?php echo $Admin->SelectCheckbox("general_disable_registrations") ?> This community is closed for new registrations.</label></td>
					</tr>
				</table>
				<div class="box fright"><input type="submit" value="Save Settings"></div>
			</form>
		</div>
	</div>
