<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_members_ranks.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## ---------------------------------------------------

?>

<h1>Add New Rank</h1>

<div class="block">
	<form action="process.php?do=new_rank" method="post">
		<table class="table">
			<thead>
				<tr>
					<th colspan="5">New Rank</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Rank name</td>
				<td><input type="text" name="title" class="form-control span-3"></td>
			</tr>
			<tr>
				<td class="font-w600">Minimum posts</td>
				<td><input type="text" name="min_posts" class="form-control span-1"> posts</td>
			</tr>
			<tr>
				<td class="font-w600">Number of pips</td>
				<td>
					<select name="pips" class="form-control span-1">
						<?php for($i = 1; $i <= 9; $i++): ?>
							<option value="<?php echo $i ?>"><?php echo $i ?></option>
						<?php endfor; ?>
					</select>
				</td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Settings">
		</div>
	</form>
</div>
