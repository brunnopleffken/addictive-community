<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span>Add Reply: Welcome</div>
</div>

<div class="box">
	<form action="index.php?module=post&amp;act=add&amp;id=1" method="post" class="validate">
		<div class="inputBox">
			<div class="label">Message</div>
			<div class="field">
				<?php echo Html::Toolbar() ?>
				<textarea class="full required" rows="12"></textarea>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Post Preview</div>
			<div class="field">
				<div class="parser"></div>
			</div>
		</div>
		<div class="fleft">
			<div class="errorMessage">Your message is empty.</div>
		</div>
		<div class="fright">
			<input type="hidden" name="thread_id" value="<?php echo $threadInfo['t_id'] ?>">
			<input type="submit" value="Add Reply">
		</div>
	</form>
</div>