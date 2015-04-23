<div class="room-title-bar">
	<div class="title fleft">
		<span><?php __($community_name) ?></span>
		<?php __("C_TITLE") ?>: <?php __("C_ADD") ?>
	</div>
</div>

<div class="box">
	<form action="calendar/save" method="post" class="validate">
		<div class="input-box">
			<div class="label"><?php __("C_NEW_TITLE") ?></div>
			<div class="field"><input type="text" name="title" class="large required"></div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_NEW_DATE") ?></div>
			<div class="field">
				<?php
					__(Html::Days("day", date("d")) . " ");
					__(Html::Months("month", false, date("m")) . " ");
					__(Html::Years("year", 0, 3)) . __("C_NEW_AT");
					__(Html::Hours("hour") . ":");
					__(Html::Minutes("minute"));
				?>
			</div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_NEW_TYPE") ?></div>
			<div class="field">
				<select name="type" class="select2 medium">
					<option value="public"><?php __("C_NEW_PUBLIC") ?></option>
					<option value="private"><?php __("C_NEW_PRIVATE") ?></option>
				</select>
			</div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_NEW_DESC") ?></div>
			<div class="field">
				<textarea name="text" id="description" rows="8" class="large required"></textarea>
			</div>
		</div>
		<div class="fleft">
			<div class="error-message"><?php __("C_NEW_ERROR") ?></div>
		</div>
		<div class="fright"><input type="submit" value="<?php __("C_ADD") ?>"></div>
	</form>
</div>
