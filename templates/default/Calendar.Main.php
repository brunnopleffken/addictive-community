<div class="room-title-bar">
	<div class="title fleft">
		<span><?php __($community_name) ?></span>
		<?php __("C_TITLE") ?>
	</div>
</div>

<?php __($notification) ?>

<div class="box">
	<form action="" method="post" id="calendar-set-date">
		<?php __("C_GOTO") ?>:
		<?php __(Html::Months("month", false, $c_month)) ?>
		<?php __(Html::Years("year", 3, 3, $c_year)) ?>
		<input type="submit" value="<?php __("C_GO") ?>">
	</form>
</div>

<?php __($calendar) ?>

<div class="fright">
	<form>
		<input type="button" onclick="window.location.href='calendar/add'" value="<?php __("C_ADD") ?>">
		<input type="button" onclick="window.location.href='calendar'" value="<?php __("C_SHOW") ?>">
	</form>
</div>
