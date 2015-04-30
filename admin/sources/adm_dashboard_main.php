<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_dashboard_main.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// Get board overview

	$Db->Query("SELECT COUNT(*) AS total FROM c_members;");
	$registered = $Db->Fetch();

	$Db->Query("SELECT COUNT(*) AS total FROM c_posts;");
	$posts = $Db->Fetch();

	$Db->Query("SELECT COUNT(*) AS total FROM c_threads;");
	$threads = $Db->Fetch();

	$posts['average'] = round($posts['total'] / $threads['total'], 1);

	// System environment

	$phpversion = "PHP " . phpversion();

	$Db->Query("SELECT VERSION() AS version;");
	$mysql_v = $Db->Fetch();

	$mysqlversion = "MySQL " . $mysql_v['version'];

	// Abuse reports

	$html = "";

	$Db->Query("SELECT r.*, m.username, t.title, p.post FROM c_reports r
		INNER JOIN c_members m ON (m.m_id = r.sender_id)
		INNER JOIN c_threads t ON (t.t_id = r.thread_id)
		INNER JOIN c_posts p ON (p.p_id = r.post_id)
		ORDER BY r.rp_id DESC LIMIT 15;");

	while($report = $Db->Fetch()){
		$report['date'] = $Core->DateFormat($report['date']);

		$html .= "<tr>
				<td rowspan=\"2\" style=\"border-bottom: 2px solid #eee\">{$report['rp_id']}</td>
				<td rowspan=\"2\" style=\"border-right: 1px solid #eee; border-bottom: 2px solid #eee\" nowrap>{$report['username']}</td>
				<td nowrap>{$report['date']}</td>
				<td>{$report['ip_address']}</td>
				<td><a href=\"../index.php?module=thread&amp;id={$report['thread_id']}\">{$report['title']}</a></td>
				<td>{$report['post']}</td>
				<td rowspan=\"2\" style=\"border-left: 1px solid #eee; border-bottom: 2px solid #eee\"><a href=\"#\" onclick=\"DeleteReport({$report['rp_id']},{$report['thread_id']})\"><img src=\"images/trash.png\"></a></td>
			</tr>
			<tr>
				<td colspan=\"4\" style=\"border-bottom: 2px solid #eee\"><em>{$report['description']}</em></td>
			</tr>";
	}

?>

	<script type="text/javascript">

		function DeleteReport(id, thread) {
			if(confirm("Are you sure you want to delete the report ID #" + id + "?\nThis action is permanent and cannot be undone.")) {
				location.href = "process.php?do=deletereport&report=" + id + "&thread=" + thread;
			}
			else {
				return false;
			}
		}

	</script>

	<h1>Dashboard</h1>

	<div id="content">

		<div class="grid-row">
			<!-- LEFT -->
			<div class="grid-half">
				<table class="table-list">
					<tr>
						<th colspan="2">Board Overview</th>
					</tr>
					<tr>
						<td class="title">Members</td>
						<td><?php __($registered['total']) ?> registered</td>
					</tr>
					<tr>
						<td class="title">Post Statistics</td>
						<td><?php __($posts['total']) ?> posts in <?php __($threads['total']) ?> threads <em>(avg. <?php echo $posts['average'] ?> posts per topic)</em></td>
					</tr>
				</table>
			</div>

			<div class="grid-spacing"></div>

			<!-- RIGHT -->
			<div class="grid-half">
				<table class="table-list">
					<tr>
						<th colspan="2">Server Environment</th>
					</tr>
					<tr>
						<td class="title">Software Version</td>
						<td>Addictive Community <?php echo VERSION . "-" . CHANNEL; ?> (<?php echo CODENAME; ?>)</td>
					</tr>
					<tr>
						<td class="title">Server Software</td>
						<td><?php __($_SERVER['SERVER_SOFTWARE']); ?></td>
					</tr>
					<tr>
						<td class="title">PHP Version</td>
						<td><?php __($phpversion); ?></td>
					</tr>
					<tr>
						<td class="title">MySQL Version</td>
						<td><?php __($mysqlversion); ?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="grid-row">
			<!-- LEFT -->
			<div class="grid-full">
				<table class="table-list">
					<tr>
						<th colspan="10">Abuse Reports</th>
					</tr>
					<tr class="subtitle">
						<td class="min">ID</td>
						<td>Reported by</td>
						<td>Date</td>
						<td>IP Address</td>
						<td>Thread</td>
						<td>Post</td>
						<td class="min"></td>
					</tr>

					<?php echo $html ?>

				</table>
			</div>

		</div>

	</div>
