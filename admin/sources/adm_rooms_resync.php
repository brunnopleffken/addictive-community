<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_rooms_resync.php
	#  License: GPLv2
	#  Copyright: (c) 2015 - Addictive Community
	## ---------------------------------------------------

	// ---------------------------------------------------
	// Get information
	// ---------------------------------------------------

	$id = $_REQUEST['id'];

	$Db->Query("SELECT * FROM c_rooms WHERE r_id = '{$id}';");
	$room_info = $Db->Fetch();

?>

	<h1>Resynchronize Room</h1>

	<div id="content">
		<div class="grid-row">
			<!-- LEFT -->
			<form action="process.php?do=resync_room" method="post">
				<table class="table-list">
					<tr>
						<th colspan="2">Confirmation</th>
					</tr>
					<tr>
						<td>
							If you delete a lot of threads and/or posts in a room in a high-traffic community, some statistics (like last post date or thread count) may be erroneous. If so, resynchronize the room to recount threads and posts, get the correct last post date and save the new information. Remember: it will not recount the number of posts per member.<br><br>
							This task uses a lot of CPU processing power, especially if your rooms contains a lot of threads.<br><br>
							Are you sure you want to resyncronize the room <b><?php echo $room_info['name'] ?></b>?
						</td>
					</tr>
					<tr>
						<td style="text-align: center">
							<input type="hidden" name="r_id" value="<?php echo $id ?>">
							<input type="submit" value="Yes, resynchronize!">
							<input type="button" onclick="javascript:history.back()" class="cancel" value="No, go back.">
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
