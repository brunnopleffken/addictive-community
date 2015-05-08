<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_templates_helpadd.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
?>

	<script type="text/javascript">
	
		function counter(limit) {
			var field = document.getElementById('short_desc');
			var counter = document.getElementById('short_desc_stats');
			
			var char_number = limit - field.value.length;
			
			counter.innerHTML = char_number + " characters remaining";
		}
	
	</script>

	<h1>Add New Help Topic</h1>
	
	<div id="content">
	
		<div class="grid-row">
			<!-- LEFT -->
			<form action="process.php?do=savehelp" method="post">
			
				<table class="table-list">
					<tr>
						<th colspan="2">Topic Information</th>
					</tr>
					
					<tr>
						<td class="title-fixed">Topic Title</td>
						<td><input type="text" name="title" class="medium"></td>
					</tr>
					<tr>
						<td class="title-fixed">Short Description<div id="short_desc_stats" class="title-desc">255 characters remaining</div></td>
						<td><input type="text" name="short_desc" id="short_desc" class="full" maxlength="255" onkeyup="counter(255)"></td>
					</tr>
					<tr>
						<td class="title-fixed">Help Content</td>
						<td><textarea name="content" class="full" rows="10"></textarea></td>
					</tr>
				</table>
				
				<div class="box fright"><input type="submit" value="Add Topic"></div>
				
			</form>
		</div>

	</div>