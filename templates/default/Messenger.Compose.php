<div class="room-title-bar">
	<div class="title fleft">
		<span><?php __($community_name) ?></span><?php __("M_TITLE") ?>: <?php __("M_COMPOSE") ?>
	</div>
</div>

<div class="box">
	<form action="messenger/send" method="post" class="validate">
		<div class="input-box">
			<div class="label"><?php __("M_TO") ?></div>
			<div class="field">
				<input type="hidden" name="to" id="pmTo" class="medium required">
			</div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("M_SUBJECT") ?></div>
			<div class="field"><input type="text" name="subject" class="large required"></div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("M_MESSAGE") ?></div>
			<div class="field">
				<textarea name="post" id="post" class="full required" rows="12"></textarea>
			</div>
		</div>
		<div class="fleft">
			<div class="error-message"><?php __("M_PM_ERROR") ?></div>
		</div>
		<div class="fright">
			<input type="submit" value="<?php __("M_SEND") ?>">
		</div>
	</form>
</div>
