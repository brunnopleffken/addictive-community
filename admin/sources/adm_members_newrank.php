<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_members_ranks.php
	#  License: GPLv2
	#  Copyright: (c) 2015 - Addictive Community
	## ---------------------------------------------------

?>

	<h1>Add New Rank</h1>

	<div id="content">
		<div class="grid-row">
			<form action="process.php?do=new_rank" method="post">
				<table class="table-list">
					<tr>
						<th colspan="5">
							<div class="fleft">New Rank</div>
						</th>
					</tr>
					<tr>
						<td class="title-fixed">Rank name</td>
						<td><input type="text" name="title" class="small"></td>
					</tr>
					<tr>
						<td class="title-fixed">Minimum posts</td>
						<td><input type="text" name="min_posts" style="width: 40px"> posts</td>
					</tr>
					<tr>
						<td class="title-fixed">Number of pips</td>
						<td>
							<select name="pips">
								<?php for($i = 1; $i <= 9; $i++): ?>
									<option value="<?php echo $i ?>"><?php echo $i ?></option>
								<?php endfor; ?>
							</select>
						</td>
					</tr>
				</table>
				<div class="fright"><input type="submit" value="Save Settings"></div>
			</form>
		</div>
	</div>
