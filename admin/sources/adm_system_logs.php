<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_system_logs.php
	#  License: GPLv2
	#  Copyright: (c) 2016 - Addictive Community
	## ---------------------------------------------------

	// Get administration/moderation logs

	$Db->Query("SELECT l.*, m.username FROM c_logs l
		INNER JOIN c_members m ON (m.m_id = l.member_id)
		ORDER BY log_id DESC LIMIT 50;");

	while($reg = $Db->Fetch()) {
		$reg['time'] = $Core->DateFormat($reg['time']);

		Template::Add("<tr>
				<td>{$reg['username']}</td>
				<td>{$reg['act']}</td>
				<td>{$reg['time']}</td>
				<td>{$reg['ip_address']}</td>
			</tr>");
	}

?>

	<h1>Logs</h1>

	<div id="content">

		<div class="grid-row">
			<table class="table-list">
				<tr>
					<th colspan="4">Administration Logs</th>
				</tr>
				<tr class="subtitle">
					<td>Username</td>
					<td>Action</td>
					<td>Date</td>
					<td>IP</td>
				</tr>

				<?php echo Template::Get() ?>

			</table>
		</div>

	</div>
