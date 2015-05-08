<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_templates_help.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	// Messages
	
	$msg = (Html::Request("msg")) ? Html::Request("msg") : "";

	switch($msg) {
		case 1:
			$message = Html::Notification("The new help topic has been added successfully.", "success");
			break;
		default:
			$message = "";
			break;
	}
	
	// Room list
	
	$Db->Query("SELECT * FROM c_help ORDER BY title;");
	
	while($topic = $Db->Fetch()) {
		Template::Add("
			<tr>
			<td>
				<b>{$topic['title']}</b><br>
				{$topic['short_desc']}
			</td>
			<td class=\"min\"><a href=\"main.php?act=rooms&p=edit&id={$topic['h_id']}\"><img src=\"images/edit.png\" title=\"Edit\"></a></td>
			<td class=\"min\"><a href=\"main.php?act=rooms&p=delete&id={$topic['h_id']}\"><img src=\"images/delete.png\" title=\"Delete\"></a></td>
		</tr>
		");
	}
	
?>

	<h1>Help Topics</h1>
	
	<div id="content">
	
		<div class="grid-row">
			<!-- LEFT -->
			<form action="process.php?do=newroom" method="post">
			
				<?php echo $message; ?>
			
				<table class="table-list">
					<tr>
						<th colspan="5">
							<div class="fleft">Help Topic List</div>
							<div class="fright"><a href="main.php?act=templates&p=helpadd" class="button-grey-default white transition">Add New Topic</a></div>
</th>
					</tr>
					
					<tr class="subtitle">
						<td>Topic</td>
						<td colspan="3" class="min">Options</td>
					</tr>
					
					<?php echo Template::Get(); ?>
				</table>
				
			</form>
		</div>

	</div>