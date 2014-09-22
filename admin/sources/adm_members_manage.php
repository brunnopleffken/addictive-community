<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_members_manage.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	// Execute queries, if defined
	
	$execute = (Html::Request("execute")) ? Html::Request("execute") : false;
	
	if($execute) {
		switch($execute) {
			case "optimize":
				
				break;
		}
	}
	
	// Get member list
	
	$Db->Query("SELECT * FROM c_members
		INNER JOIN c_usergroups ON (c_members.usergroup = c_usergroups.g_id);");
	
	while($member = $Db->Fetch()) {
		$member['joined'] = $Core->DateFormat($member['joined']);
		
		Template::Add("<tr>
				<td>" . Html::Crop($Core->GetGravatar($member['email'], $member['m_id'], 36), 36, 36) . "</td>
				<td><b>{$member['username']}</b></td>
				<td>{$member['email']}</td>
				<td>{$member['joined']}</td>
				<td>{$member['name']}</td>
				<td>{$member['ip_address']}</td>
				<td><img src=\"images/edit.png\"></td>
				<td><img src=\"images/delete.png\"></td>
			</tr>");
	}
	
?>

	<h1>Manage Members</h1>
	
	<div id="content">
	
		<div class="grid-row">
			<!-- LEFT -->
			<form action="process.php?do=optimize" method="post">
				
				<table class="table-list">
					<tr>
						<th colspan="8">Registered Members</th>
					</tr>
					
					<tr class="subtitle">
						<td width="1%"></td>
						<td>Username</td>
						<td>E-mail Address</td>
						<td>Joined</td>
						<td>Usergroup</td>
						<td>IP Address</td>
						<td width="1%"></td>
						<td width="1%"></td>
					</tr>
					<?php echo Template::Get() ?>
					
				</table>
				
			</form>
		</div>

	</div>