<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_dashboard_main.php
	#  License: GPLv2
	#  Copyright: (c) 2016 - Addictive Community
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

	$srvsoft = $_SERVER["SERVER_SOFTWARE"];

	$Db->Query("SELECT VERSION() AS version;");
	$mysql_v = $Db->Fetch();

	$mysqlversion = "MySQL " . $mysql_v['version'];

	// Abuse reports

	$html = "";

	$reports = $Db->Query("SELECT r.*, m.username, t.title FROM c_reports r
			INNER JOIN c_members m ON (r.sender_id = m.m_id)
			LEFT JOIN c_threads t ON (r.thread_id = t.t_id)
			ORDER BY rp_id DESC LIMIT 15;");

	if($Db->Rows($reports) == 0) {
		$html = "<tr><td colspan='7' class='center'>There are no abuse reports at the moment.</td></tr>";
	}

	while($report = $Db->Fetch($reports)) {
		$report['date'] = $Core->DateFormat($report['date']);

		if($report['post_id'] == 0) {
			$report['post'] = "-";
		}
		else {
			$report['post'] = "Yes (<a href='" . $Admin->SelectConfig("general_community_url") . "thread/{$report['thread_id']}#post-" . $report['post_id'] . "' target='_blank'>view post</a>)";
		}

		$reason[1] = "Nudity or pornography";
		$reason[2] = "Impersonating me or someone I know";
		$reason[3] = "Racist or hate speech";
		$reason[4] = "Targets me or a friend";
		$reason[5] = "Direct call for violence";
		$reason[6] = "Excessive violent content";
		$reason[7] = "Spam";

		$html .= "<tr>
				<td rowspan='2' style='border-bottom: 2px solid #eee'>{$report['rp_id']}</td>
				<td rowspan='2' style='border-right: 1px solid #eee; border-bottom: 2px solid #eee' nowrap>{$report['username']}</td>
				<td nowrap>{$report['date']}</td>
				<td>{$reason[$report['reason']]}</td>
				<td><a href='../thread/{$report['thread_id']}'>{$report['title']}</a></td>
				<td>{$report['post']}</td>
				<td rowspan='2' style='border-left: 1px solid #eee; border-bottom: 2px solid #eee'>
					<a href='process.php?do=remove_report&id={$report['rp_id']}' onclick='DeleteReport({$report['rp_id']},{$report['thread_id']})'><img src='images/trash.png'></a>
				</td>
			</tr>
			<tr>
				<td colspan='4' style='border-bottom: 2px solid #eee'>{$report['description']}</td>
			</tr>";
	}

?>

	<script>
		$(document).ready(function() {
			checkUpdates();
		});
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
						<td><?php __($posts['total']) ?> posts in <?php __($threads['total']) ?> threads <em>(avg. <?php echo $posts['average'] ?> posts per thread)</em></td>
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
						<td>Addictive Community <?php echo VERSION . "-" . CHANNEL; ?></td>
					</tr>
					<tr>
						<td class="title">Software Updates</td>
						<td>
							<input type="hidden" id="current-version" value="<?php echo VERSION ?>">
							<div class="loader"><img src="images/loader.gif"> Checking...</div>
							<div class="update-message fail">Unable to connect to GitHub servers.</div>
							<div class="update-message no-updates">There is no software update available.</div>
							<div class="update-message done">New update available to <span></span>.</div>
						</td>
					</tr>
					<tr>
						<td class="title">Server Software</td>
						<td><?php __($srvsoft); ?></td>
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
						<td>Reason</td>
						<td>Thread Name</td>
						<td>Reported a post?</td>
						<td class="min"></td>
					</tr>
					<?php echo $html ?>
				</table>
			</div>
		</div>
	</div>
