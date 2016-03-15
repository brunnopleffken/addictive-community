<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_members_ranks.php
	#  License: GPLv2
	#  Copyright: (c) 2016 - Addictive Community
	## ---------------------------------------------------

	// Notification

	$msg = (Http::Request("msg")) ? Http::Request("msg") : 0;

	switch($msg) {
		case 1:
			$message = Html::Notification("The settings has been successfully changed.", "success");
			break;
		case 2:
			$message = Html::Notification("The new rank has been successfully added.", "success");
			break;
		case 3:
			$message = Html::Notification("The rank has been successfully removed.", "success");
			break;
		default:
			$message = "";
	}

	// Get usergroup list
	$Db->Query("SELECT * FROM c_ranks ORDER BY min_posts;");

	while($rank = $Db->Fetch()) {
		// Image has a higher priority than pip number
		$symbol = "";
		if($rank['pips'] != "") {
			$symbol = $rank['pips'];
		}
		if($rank['image'] != "") {
			$symbol = $rank['pips'];
		}

		Template::Add("<tr>
				<td><b>{$rank['title']}</b></td>
				<td>{$rank['min_posts']}</td>
				<td>{$symbol}</td>
				<td class='min'><a href='process.php?do=delete_rank&id={$rank['id']}'><i class='fa fa-fw fa-remove'></i></a></td>
			</tr>");
	}

?>

	<h1>Ranks</h1>

	<div id="content">
		<div class="grid-row">
			<?php echo $message ?>
			<form action="process.php?do=save" method="post">
				<table class="table-list">
					<tr>
						<th colspan="5">
							<div class="fleft">Ranks Overview</div>
							<div class="fright"><a href="main.php?act=members&p=newrank" class="button-grey-default white transition">New Rank</a></div>
						</th>
					</tr>
					<tr class="subtitle">
						<td>Rank Name</td>
						<td>Min. Posts</td>
						<td>Image or # of Pips</td>
						<td width="1%">Delete</td>
					</tr>
					<?php echo Template::Get() ?>
				</table>

				<form action="process.php?do=save" method="post">
					<table class="table-list">
						<tr>
							<th colspan="5">
								<div class="fleft">Settings</div>
							</th>
						</tr>
						<tr>
							<td class="title-fixed">Enable ranks and promotions</td>
							<td><?php echo $Admin->SelectCheckbox("general_member_enable_ranks") ?> Enable ranks for all members.</td>
						</tr>
					</table>
					<div class="fright"><input type="submit" value="Save Settings"></div>
				</form>
			</form>
		</div>
	</div>
