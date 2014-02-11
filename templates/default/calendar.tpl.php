<div class="roomTitleBar">
	<div class="title fleft"><span><?php echo $this->Core->config['general_communityname'] ?></span>Calendar</div>
</div>

<?php echo $notification ?>

<?php
	switch($view):
	case "month":
?>

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
		<input type="button" onclick="window.location.href='index.php?module=calendar&view=addevent'" value="Add New Event">
		<input type="button" onclick="window.location.href='index.php?module=calendar'" value="Show Current Month">
	</form>
</div>

<?php
	break;
	case "addevent":
?>

<?php
	$this->header .= '<script type="text/javascript" src="resources/markdown.parser.js"></script>';
	$this->header .= '<script type="text/javascript">$(document).ready(function(){$(\'#descTextarea\').markdownRealTime()});</script>';
?>

<div class="box">
	<form action="" method="post" class="validate">
		<div class="inputBox">
			<div class="label">Event Title</div>
			<div class="field"><input type="text" name="title" class="large required"></div>
		</div>
		<div class="inputBox">
			<div class="label">Event Date</div>
			<div class="field">
				<?php
					echo Html::Days("day", date("d")) . " ";
					echo Html::Months("month", false, $this->t, date("m")) . " ";
					echo Html::Years("year", 0, 3) . " at ";
					echo Html::Hours("hour") . ":";
					echo Html::Minutes("minute");
				?>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Type</div>
			<div class="field">
				<select name="type">
					<option value="public">Public (viewable by all)</option>
					<option value="private">Private (viewable only by you)</option>
				</select>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Description</div>
			<div class="field">
				<textarea name="text" id="descTextarea" rows="8" class="large required"></textarea>
				<div id="markdownPreview"></div>
			</div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="addevent"><input type="submit" value="Add New Event"></div>
	</form>
</div>

<?php
	endswitch;
?>