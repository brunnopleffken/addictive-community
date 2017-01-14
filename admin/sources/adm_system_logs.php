<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_system_logs.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Template;

// Get administration/moderation logs

Database::query("SELECT l.*, m.username FROM c_logs l
	INNER JOIN c_members m ON (m.m_id = l.member_id)
	ORDER BY log_id DESC LIMIT 50;");

while($reg = Database::fetch()) {
	$reg['time'] = $Core->dateFormat($reg['time']);

	Template::add("<tr>
			<td>{$reg['username']}</td>
			<td>{$reg['act']}</td>
			<td>{$reg['time']}</td>
			<td>{$reg['ip_address']}</td>
		</tr>");
}

?>

<h1>Logs</h1>

<div class="block">
	<table class="table">
		<thead>
			<tr>
				<th colspan="4">Administration Logs</th>
			</tr>
			<tr>
				<td>Username</td>
				<td>Action</td>
				<td>Date</td>
				<td>IP</td>
			</tr>
		</thead>
		<?php echo Template::get() ?>
	</table>
</div>
