<?php
	$this->header .= '<script type="text/javascript" src="resources/markdown.parser.js"></script>';
	$this->header .= '<script type="text/javascript">$(document).ready(function(){$(\'#post\').markdownRealTime()});</script>';
?>

<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __("P_TITLE") ?>: <?php echo $threadInfo['title'] ?></div>
</div>

<div class="box">
	<form action="?module=post&amp;act=add&amp;id=<?php echo $threadInfo['t_id'] ?>" method="post" class="validate" enctype="multipart/form-data">
		<div class="inputBox">
			<div class="label"><?php __("P_POST") ?></div>
			<div class="field">
				<?php echo Html::Toolbar() ?>
				<textarea name="post" id="post" class="full required" rows="12"></textarea>
			</div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("P_ATTACHMENTS") ?></div>
			<div class="field"><input type="file" name="attachment"></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("P_PREVIEW") ?></div>
			<div class="field textOnly">
				<div id="markdownPreview"></div>
			</div>
		</div>
		<div class="fleft">
			<div class="errorMessage"><?php __("P_ERROR") ?></div>
		</div>
		<div class="fright">
			<input type="hidden" name="room_id" value="<?php echo $threadInfo['r_id'] ?>">
			<input type="hidden" name="thread_id" value="<?php echo $threadInfo['t_id'] ?>">
			<input type="submit" value="<?php __("P_ADD_REPLY") ?>">
		</div>
	</form>
</div>
