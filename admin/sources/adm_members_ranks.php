<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_members_ranks.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;

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
Database::Query("SELECT * FROM c_ranks ORDER BY min_posts;");

while($rank = Database::Fetch()) {
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
			<td class='text-center'>
				<a href='process.php?do=delete_rank&id={$rank['id']}'><i class='fa fa-fw fa-remove'></i></a>
			</td>
		</tr>");
}

?>

<div class="header">
	<h1>Ranks</h1>
	<div class="header-buttons">
		<a href="main.php?act=members&p=new_rank" class="btn btn-default font-w600">New Rank</a>
	</div>
</div>

<div class="block">
	<?php echo $message ?>
	<table class="table">
		<thead>
			<tr>
				<th colspan="5">Ranks Overview</th>
			</tr>
			<tr>
				<td>Rank Name</td>
				<td>Min. Posts</td>
				<td>Image or # of Pips</td>
				<td class="min">Delete</td>
			</tr>
		</thead>
		<?php echo Template::Get() ?>
	</table>

	<hr>

	<form action="process.php?do=save" method="post">
		<table class="table">
			<thead>
				<tr>
					<th colspan="5">Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Enable ranks and promotions</td>
				<td><?php echo $Admin->SelectCheckbox("general_member_enable_ranks") ?> Enable ranks for all members</td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Settings">
		</div>
	</form>
</div>
