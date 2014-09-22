<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_general_profiles.php
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

	<h1>Profiles</h1>
	
	<div id="content">
		<div class="grid-row">
			<form action="process.php?do=save" method="post">
			
				<?php echo $message ?>
			
				<table class="table-list">
					<tr>
						<th colspan="2">Profiles Settings</th>
					</tr>
					<tr>
						<td class="title-fixed"></td>
						<td></td>
					</tr>
				</table>
				
				<div class="box fright"><input type="submit" value="Save Settings"></div>
			</form>
		</div>
	</div>