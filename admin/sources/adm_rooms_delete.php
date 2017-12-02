<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_rooms_delete.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Http;

// ---------------------------------------------------
// Get information
// ---------------------------------------------------

$id = Http::request("id");

Database::query("SELECT * FROM c_rooms WHERE r_id = '{$id}';");
$room_info = Database::fetch();

?>

<h1>Delete Room: <?php echo $room_info['name'] ?></h1>

<div class="block">
	<div class="grid-row">
		<form action="process.php?do=deleteroom" method="post">
			<table class="table">
				<thead>
					<tr>
						<th colspan="2">Please, confirm what you're about to do!</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Are you sure you want to delete the room <b><?php echo $room_info['name'] ?></b>? All threads and replies in it will be lost.</td>
					</tr>
					<tr>
						<td style="text-align: center">
							<input type="hidden" name="r_id" value="<?php echo $id ?>">
							<input type="submit" class="btn btn-default" value="Yes, delete!">
							<input type="button" class="btn btn-cancel" onclick="javascript:history.back()" class="cancel" value="No, go back.">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
