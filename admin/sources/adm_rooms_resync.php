<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_rooms_resync.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;

// ---------------------------------------------------
// Get information
// ---------------------------------------------------

$id = $_REQUEST['id'];

Database::query("SELECT * FROM c_rooms WHERE r_id = '{$id}';");
$room_info = Database::fetch();

?>

<h1>Resynchronize Room</h1>

<div class="block">
		<form action="process.php?do=resync_room" method="post">
			<table class="table">
				<thead>
					<tr>
						<th colspan="2">Confirmation</th>
					</tr>
				</thead>
				<tr>
					<td>
						<p>If you delete a lot of threads and/or posts in a room in a high-traffic community, some statistics (like last post date or thread count) may be erroneous. If so, resynchronize the room to recount threads and replies, get the correct last post date and save the new information. Remember: it will <u>not</u> recount the number of posts per member (for this,
							<a href="main.php?act=system&p=optimization">go here</a>).</p><br>
						<p>Be aware that this task uses a lot of CPU processing power, especially if your room contains many threads.</p><br>
						<p>Are you sure you want to resyncronize the room <b><?php echo $room_info['name'] ?></b>?</p>
					</td>
				</tr>
				<tr>
					<td style="text-align: center">
						<input type="hidden" name="r_id" value="<?php echo $id ?>">
						<input type="submit" class="btn btn-default" value="Yes, resynchronize!">
						<input type="button" onclick="history.back()" class="btn btn-cancel" value="No, go back.">
					</td>
				</tr>
			</table>
		</form>
</div>
