<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_system_optimization.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Execute queries, if defined
	// ---------------------------------------------------

	$execute = (Html::Request("execute")) ? Html::Request("execute") : false;

	if($execute) {
		switch($execute) {
			// Recount members

			case "members":

				// Update member count

				$Db->Query("SELECT COUNT(*) AS count FROM c_members;");
				$total = $Db->Fetch();
				$total = $total['count'];

				$Db->Query("UPDATE c_stats SET member_count = '{$total}';");

				// Exit

				$Admin->RegisterLog("Executed system optimization: member counting.");
				echo $notification->ShowNotif("Registered members have been recounted successfully.", "success");

				break;

			// Recount member's threads and posts

			case "threads":

				// Update posts

				$Db->Query("SELECT COUNT(*) AS count FROM c_posts;");
				$total = $Db->Fetch();
				$total = $total['count'];

				$Db->Query("UPDATE c_stats SET total_posts = '{$total}';");

				// Update threads

				$Db->Query("SELECT COUNT(*) AS count FROM c_threads;");
				$total = $Db->Fetch();
				$total = $total['count'];

				$Db->Query("UPDATE c_stats SET total_threads = '{$total}';");

				// Update members thread count

				$Db2 = clone($Db);
				$Db3 = clone($Db);

				$Db->Query("SELECT m_id FROM c_members;");

				while($members = $Db->Fetch()) {
					$Db2->Query("SELECT COUNT(*) AS total FROM c_posts WHERE author_id = '{$members['m_id']}';");

					while($post_count = $Db2->Fetch()) {
						$Db3->Query("UPDATE c_members SET posts = '{$post_count['total']}' WHERE m_id = '{$members['m_id']}';");
					}
				}

				// Exit

				$Admin->RegisterLog("Executed system optimization: threads and posts counting.");
				echo Html::Notification("Threads and posts have been recounted successfully.", "success");

				break;

			// Recount replies

			case "replies":

				// List threads (global)

				$Db2 = clone($Db);
				$Db3 = clone($Db);
				$Db->Query("SELECT t_id FROM c_threads;");

				// Replies counting

				while($threads = $Db->Fetch()) {
					$Db2->Query("SELECT COUNT(p_id) AS post_count FROM c_posts WHERE thread_id = '{$threads['t_id']}';");

					while($posts = $Db2->Fetch()) {
						$Db3->Query("UPDATE c_threads SET replies = '{$posts['post_count']}' WHERE t_id = '{$threads['t_id']}';");
					}
				}

				// Exit

				$Admin->RegisterLog("Executed system optimization: replies counting.");
				echo Html::Notification("Replies have been recounted successfully.", "success");

				break;
		}
	}

?>

	<h1>System Optimization</h1>

	<div id="content">

		<div class="grid-row">
			<!-- LEFT -->
			<form action="" method="post">

				<table class="table-list">
					<tr>
						<th colspan="4">Predefined Tasks</th>
					</tr>

					<tr class="subtitle">
						<td>Task Description</td>
						<td width="1%">Execute</td>
					</tr>
					<tr>
						<td>
							<b>Recount members</b><br>
							This will recount registered members based on current number of members from the database and reset last registered member. This should not be used if you wish to retain your registered member counts.
						</td>
						<td style="text-align:center"><a href="main.php?act=system&p=optimization&execute=members"><i class="fa fa-play-circle-o"></i></a></td>
					</tr>
					<tr>
						<td>
							<b>Recount member's threads and posts</b><br>
							This will recount members threads and posts based on current posts from the database. This will almost certainly REDUCE the post counts for your members as deleted and pruned posts will no longer be counted.
						</td>
						<td style="text-align:center"><a href="main.php?act=system&p=optimization&execute=threads"><i class="fa fa-play-circle-o"></i></a></td>
					</tr>
					<tr>
						<td>
							<b>Recount replies</b><br>
							This will recount replies, attachments and last poster for all your topics. This may take a while to complete!
						</td>
						<td style="text-align:center"><a href="main.php?act=system&p=optimization&execute=replies"><i class="fa fa-play-circle-o"></i></a></td>
					</tr>

				</table>

			</form>
		</div>

	</div>
