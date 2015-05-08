<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_general_cookies.php
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

	<h1>Cookies</h1>
	
	<div id="content">
		<div class="grid-row">
			<form action="process.php?do=save" method="post">
			
				<?php echo $message ?>
			
				<table class="table-list">
					<tr>
						<th colspan="2">General Cookies Settings</th>
					</tr>
					<tr>
						<td class="title-fixed">Expiration time (in seconds)</span></td>
						<td><input type="text" name="general_session_expiration" class="nano" value="<?php echo $Admin->SelectConfig("general_session_expiration") ?>"> seconds</td>
					</tr>
				</table>
				
				<div class="box fright"><input type="submit" value="Save Settings"></div>
			</form>
		</div>
	</div>