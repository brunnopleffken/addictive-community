<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_templates_add_help.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## ---------------------------------------------------

?>

<h1>Add New Help Topic</h1>

<div class="block">
	<form action="process.php?do=savehelp" method="post">
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Topic Information</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Topic Title</td>
				<td><input type="text" name="title" class="form-control span-6"></td>
			</tr>
			<tr>
				<td class="font-w600">Short Description<small id="short_desc_stats">255 characters remaining</small></td>
				<td><input type="text" name="short_desc" id="short_desc" class="form-control span-10" maxlength="255" onkeyup="counter(255)"></td>
			</tr>
			<tr>
				<td class="font-w600">Help Content</td>
				<td><textarea name="content" class="form-control span-10" rows="10"></textarea></td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Add Topic">
		</div>
	</form>
</div>
