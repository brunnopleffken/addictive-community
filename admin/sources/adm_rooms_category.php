<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_rooms_manage.php
	#  License: GPLv2
	#  Copyright: (c) 2015 - Addictive Community
	## ---------------------------------------------------

?>

<h1>Add New Category</h1>

<div id="content">
	<div class="grid-row">
		<!-- LEFT -->
		<form action="process.php?do=new_category" method="post">
			<table class="table-list">
				<tr>
					<th colspan="2">Category Settings</th>
				</tr>
				<tr>
					<td class="title-fixed">Category Name</td>
					<td><input type="text" name="name" class="medium"></td>
				</tr>
			</table>
			<div class="box fright"><input type="submit" value="Create Category"></div>
		</form>
	</div>
</div>
