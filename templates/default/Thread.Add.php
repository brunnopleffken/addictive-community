<div class="room-title-bar">
	<div class="title fleft">
		<span><?php __($community_name) ?></span>
		<?php __("T_NEW_TITLE") ?>: <?php echo $room_info['name'] ?>
	</div>
</div>

<div class="box">
	<form action="thread/savethread/<?php echo $room_info['r_id'] ?>" method="post" class="validate" enctype="multipart/form-data">
		<div class="input-box">
			<div class="label"><?php __("T_NEW_THREAD_TITLE") ?></div>
			<div class="field"><input type="text" name="title" class="large required"></div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("T_NEW_POST") ?></div>
			<div class="field">
				<textarea name="post" id="post" class="full" rows="12"></textarea>
			</div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("T_NEW_ATTACHMENTS") ?></div>
			<div class="field"><input type="file" name="attachment"></div>
		</div>
		<?php if($member_info['usergroup'] == 1 || $member_info['usergroup'] == 2): ?>
			<div class="input-box">
				<div class="label"><?php __("T_NEW_OPTIONS") ?></div>
				<div class="field textOnly">
					<label><input type="hidden" name="announcement" value="0"><input type="checkbox" value="1" name="announcement"> <?php __("T_NEW_SET_ANNOUNCEMENT") ?></label><br>
					<label><input type="hidden" name="locked" value="0"><input type="checkbox" value="1" name="locked"> <?php __("T_NEW_LOCK") ?></label>
				</div>
			</div>
		<?php endif; ?>
		<div class="fleft">
			<div class="error-message"><?php __("T_NEW_ERROR") ?></div>
		</div>
		<div class="fright">
			<input type="hidden" name="room_id" value="<?php echo $room_info['r_id'] ?>">
			<input type="submit" value="<?php __("T_NEW_SUBMIT") ?>">
		</div>
	</form>
</div>
