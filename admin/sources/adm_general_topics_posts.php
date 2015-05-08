<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_general_topics_posts.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------
	
	$msg = (Html::Request("msg")) ? Html::Request("msg") : "";
	
	switch($msg) {
		case 1:
			$message = Html::Notification("The settings has been changed successfully.", "success");
			break;
		default:
			$message = "";
			break;
	}
	
?>

	<h1>Threads and Posts</h1>
	
	<div id="content">
	
		<div class="grid-row">
			<!-- LEFT -->
			<form action="process.php?do=save" method="post">
			
				<?php echo $message ?>
			
				<table class="table-list">
					<tr>
						<th colspan="2">Thread Settings</th>
					</tr>
					
					<tr>
						<td class="title-fixed">Replies per page</span></td>
						<td><input type="text" name="thread_posts_per_page" class="nano" value="<?php echo $Admin->SelectConfig("thread_posts_per_page") ?>"> replies</td>
					</tr>
					<tr>
						<td class="title-fixed">Hot Thread (min. posts)</td>
						<td><input type="text" name="thread_posts_hot" class="nano" value="<?php echo $Admin->SelectConfig("thread_posts_hot") ?>"> minumum replies for a thread to become a Hot Thread.</td>
					</tr>
					<tr>
						<td class="title-fixed">Allow emoticons</td>
						<td><label><?php echo $Admin->SelectCheckbox("thread_allow_emoticons") ?> Emoticons are allowed in posts, replies and personal messages.</label></td>
					</tr>
					<tr>
						<td class="title-fixed">Allow guests to reply</td>
						<td><label><?php echo $Admin->SelectCheckbox("thread_allow_guest_post") ?> Guests are allowed to reply any thread (not recommended).</label></td>
					</tr>
				</table>
				
				<table class="table-list">
					<tr>
						<th colspan="2">Best Answer Settings</th>
					</tr>
					
					<tr>
						<td class="title-fixed">Show Best Answer in all pages</td>
						<td><label><?php echo $Admin->SelectCheckbox("thread_best_answer_all_pages") ?> Show the best answer on top of all pages of a thread (not only the first page).</label></td>
					</tr>
				</table>
				
				<table class="table-list">
					<tr>
						<th colspan="2">Obsolete Thread Feature</th>
					</tr>
					
					<tr>
						<td class="title-fixed">Enable "Obsolete Thread"<span class="title-desc">Prevent old threads in the community rooms from being bumped.</span></td>
						<td><label><?php echo $Admin->SelectCheckbox("thread_obsolete") ?> Allows a thread to become obsolete after X days (replies are not allowed).</label></td>
					</tr>
					<tr>
						<td class="title-fixed">Days to become obsolete</td>
						<td><input type="text" name="thread_obsolete_value" class="nano" value="<?php echo $Admin->SelectConfig("thread_obsolete_value") ?>"> days without answers for a thread to become obsolete.</td>
					</tr>
				</table>
				
				<div class="box fright"><input type="submit" value="Save Settings"></div>
				
			</form>
		</div>

	</div>