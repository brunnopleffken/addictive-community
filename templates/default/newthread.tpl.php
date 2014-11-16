<?php
	$this->header .= '<script type="text/javascript" src="resources/markdown.parser.js"></script>';
	$this->header .= '<script type="text/javascript">$(document).ready(function(){$(\'#post\').markdownRealTime()});</script>';
?>

<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span>New Thread: <?php echo $roomInfo['name'] ?></div>
</div>

<div class="box">
	<form action="index.php?module=newthread&amp;act=add&amp;room=<?php echo $roomInfo['r_id'] ?>" method="post" class="validate" enctype="multipart/form-data">
		<div class="inputBox">
			<div class="label">Thread Title</div>
			<div class="field"><input type="text" name="title" class="large required"></div>
		</div>
		<div class="inputBox">
			<div class="label">Post</div>
			<div class="field">
				<?php echo Html::Toolbar() ?>
				<textarea name="post" id="post" class="full required" rows="12"></textarea>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Attachments</div>
			<div class="field"><input type="file" name="attachment"></div>
		</div>
		<div class="inputBox">
			<div class="label">Post Preview</div>
			<div class="field textOnly">
				<div id="markdownPreview"></div>
			</div>
		</div>
		<?php if($this->member['usergroup'] == 1 || $this->member['usergroup'] == 2): ?>
			<div class="inputBox">
				<div class="label">Thread Options</div>
				<div class="field textOnly">
					<label><input type="hidden" name="announcement" value="0"><input type="checkbox" value="1" name="announcement"> Set thread as Announcement</label><br>
					<label><input type="hidden" name="locked" value="0"><input type="checkbox" value="1" name="locked"> Lock thread</label>
				</div>
			</div>
		<?php endif; ?>
		<div class="fleft">
			<div class="errorMessage">Name and post are required fields.</div>
		</div>
		<div class="fright">
			<input type="hidden" name="room_id" value="<?php echo $roomInfo['r_id'] ?>">
			<input type="submit" value="Add New Thread">
		</div>
	</form>
</div>