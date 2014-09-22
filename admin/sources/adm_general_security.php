<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_general_security.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	$msg = (Html::Request("msg")) ? Html::Request("msg") : "";
	
	switch($msg) {
		case 1:
			$message = Html::Notification("The settings has been changed successfully.", "success");
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
						<th colspan="2">Community Usage</th>
					</tr>
					
					<tr>
						<td class="title-fixed">Community Offline</span></td>
						<td><label><?php echo $Admin->SelectCheckbox("general_offline") ?> Community is offline for guests and members. Only administrators are allowed to log in.<br><em>Note that enabling this feature will also prevent search engines from tracking your community content.</em></label></td>
					</tr>
					<tr>
						<td class="title-fixed">Disable New Registrations</span></td>
						<td><label><?php echo $Admin->SelectCheckbox("general_disable_registrations") ?> This community is closed for new registrations.</label></td>
					</tr>
				</table>
				
				<div class="box fright"><input type="submit" value="Save Settings"></div>
				
			</form>
		</div>

	</div>