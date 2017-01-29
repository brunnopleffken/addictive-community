<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_general_topics_posts.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Html;
use \AC\Kernel\Http;

$msg = (Http::request("msg")) ? Http::request("msg") : "";

switch($msg) {
	case 1:
		$message = Html::notification("The settings has been successfully changed.", "success");
		break;
	default:
		$message = "";
		break;
}

?>

<h1>Threads and Posts</h1>

<div class="block">
	<form action="process.php?do=save" method="post">
		<?php echo $message ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Room Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Threads per page</span></td>
				<td><input type="text" name="threads_per_page" class="form-control span-1" value="<?php echo $Admin->selectConfig("threads_per_page") ?>"> threads</td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Thread Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Replies per page</span></td>
				<td><input type="text" name="thread_posts_per_page" class="form-control span-1" value="<?php echo $Admin->selectConfig("thread_posts_per_page") ?>"> replies</td>
			</tr>
			<tr>
				<td class="font-w600">Hot Thread (min. posts)</td>
				<td><input type="text" name="thread_posts_hot" class="form-control span-1" value="<?php echo $Admin->selectConfig("thread_posts_hot") ?>"> minumum replies for a thread to become a Hot Thread.</td>
			</tr>
			<tr>
				<td class="font-w600">Allow emoticons</td>
				<td><label><?php echo $Admin->selectCheckbox("thread_allow_emoticons") ?> Emoticons are allowed in posts, replies and personal messages.</label></td>
			</tr>
			<tr>
				<td class="font-w600">Allow guests to reply</td>
				<td><label><?php echo $Admin->selectCheckbox("thread_allow_guest_post") ?> Guests are allowed to reply any thread (not recommended).</label></td>
			</tr>
			<tr>
				<td class="font-w600">Attachment: max. file size</span></td>
				<td><input type="text" name="general_max_attachment_size" class="form-control span-1" value="<?php echo $Admin->selectConfig("general_max_attachment_size") ?>"> MB</td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Best Reply Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Best reply in all pages</td>
				<td><label><?php echo $Admin->selectCheckbox("thread_best_answer_all_pages") ?> Show the best reply on top of all pages of a thread (not only the first page).</label></td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Obsolete Thread Feature</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Enable "Obsolete Thread"<small>Prevent old threads in the community rooms from being bumped.</small></td>
				<td><label><?php echo $Admin->selectCheckbox("thread_obsolete") ?> Allows a thread to become obsolete after X days (replies are not allowed).</label></td>
			</tr>
			<tr>
				<td class="font-w600">Days to become obsolete</td>
				<td><input type="text" name="thread_obsolete_value" class="form-control span-1" value="<?php echo $Admin->selectConfig("thread_obsolete_value") ?>"> days without answers for a thread to become obsolete.</td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Settings">
		</div>
	</form>
</div>
