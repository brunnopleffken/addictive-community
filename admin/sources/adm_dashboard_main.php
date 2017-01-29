<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_dashboard_main.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;

// Get board overview

Database::query("SELECT COUNT(*) AS total FROM c_members;");
$registered = Database::fetch();

Database::query("SELECT COUNT(*) AS total FROM c_posts;");
$posts = Database::fetch();

Database::query("SELECT COUNT(*) AS total FROM c_threads;");
$threads = Database::fetch();

$posts['average'] = round($posts['total'] / $threads['total'], 1);

// System environment

$server_software = $_SERVER["SERVER_SOFTWARE"];

Database::query("SELECT VERSION() AS version;");
$mysql_v = Database::fetch();

$mysqlversion = "MySQL " . $mysql_v['version'];

// Abuse reports

$html = "";

$reports = Database::query("SELECT r.*, m.username, t.title FROM c_reports r
		INNER JOIN c_members m ON (r.sender_id = m.m_id)
		LEFT JOIN c_threads t ON (r.thread_id = t.t_id)
		ORDER BY rp_id DESC LIMIT 15;");

if(Database::rows($reports) == 0) {
	$html = "<tr><td colspan='7' class='text-center'>There are no abuse reports at the moment.</td></tr>";
}

while($report = Database::fetch($reports)) {
	$report['date'] = $Core->dateFormat($report['date']);

	if($report['post_id'] == 0) {
		$report['post'] = "-";
	}
	else {
		$report['post'] = "Yes (<a href='" . $Admin->selectConfig("general_community_url") . "thread/{$report['thread_id']}#post-" . $report['post_id'] . "' target='_blank'>view post</a>)";
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

<div class="block">
	<?php
		// Check if installer is locked
		if(!file_exists(__DIR__ . '/../../install/.lock') && is_dir(__DIR__ . '/../../install')) {
			echo Html::notification("The installer is not locked (file \".lock\" is missing) and is publicly accessible. Remove the /install folder immediately.", "failure", true, "DANGER!");
		}

		// Check if /install directory is still on the server
		if(file_exists(__DIR__ . '/../../install') || is_dir(__DIR__ . '/../../install')) {
			echo Html::notification("It's recommended to delete or rename the /install folder.", "warning", true);
		}
	?>
	<div class="row">
		<div class="col-6">
			<table class="table">
				<thead>
					<tr>
						<th colspan="2">Board Overview</th>
					</tr>
				</thead>
				<tr>
					<td class="font-w600">Members</td>
					<td><?php __($registered['total']) ?> registered</td>
				</tr>
				<tr>
					<td class="font-w600">Post Statistics</td>
					<td><?php __($posts['total']) ?> posts in <?php __($threads['total']) ?> threads (avg. <?php echo $posts['average'] ?> posts per thread)</td>
				</tr>
			</table>
		</div>

		<div class="col-6">
			<table class="table">
				<thead>
					<tr>
						<th colspan="2">Server Environment</th>
					</tr>
				</thead>
				<tr>
					<td class="font-w600">Software Version</td>
					<td>Addictive Community <?php echo VERSION . "-" . CHANNEL; ?></td>
				</tr>
				<tr>
					<td class="font-w600">Software Updates</td>
					<td>
						<input type="hidden" id="current-version" value="<?php echo VERSION ?>">
						<div class="loader"><img src="images/loader.gif"> Checking...</div>
						<div class="update-message fail">Unable to connect to GitHub servers.</div>
						<div class="update-message no-updates">There are no updates currently available.</div>
						<div class="update-message done">New update available to <span></span>.</div>
					</td>
				</tr>
				<tr>
					<td class="font-w600">Server Software</td>
					<td><?php __($server_software); ?></td>
				</tr>
				<tr>
					<td class="font-w600">MySQL Version</td>
					<td><?php __($mysqlversion); ?></td>
				</tr>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<table class="table margin-no">
				<thead>
					<tr>
						<th colspan="10">Abuse Reports</th>
					</tr>
					<tr>
						<td class="min">ID</td>
						<td>Reported by</td>
						<td>Date</td>
						<td>Reason</td>
						<td>Thread Name</td>
						<td>Reported a post?</td>
						<td class="min"></td>
					</tr>
				</thead>
				<?php echo $html ?>
			</table>
		</div>
	</div>
</div>
