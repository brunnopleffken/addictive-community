<div class="roomTitleBar">
	<div class="title fleft"><span><?php echo $this->Core->config['general_communityname'] ?></span>Calendar</div>
</div>

<div class="box">
	<form action="index.php" method="get">
		<input type="hidden" name="module" value="calendar">
		Go to:
		<?php echo Html::Months("month", false, $this->t, $c_month) ?>
		<?php echo Html::Years("year", 3, 3) ?>
		<input type="submit" value="Go">
	</form>
</div>

<?php echo $calendar ?>

<div class="fright">
	<form>
		<input type="button" onclick="window.location.href='index.php?module=calendar&act=addevent'" value="Add New Event">
		<input type="button" onclick="window.location.href='index.php?module=calendar'" value="Show Current Month">
	</form>
</div>