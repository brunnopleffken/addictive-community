<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __("C_TITLE") ?></div>
</div>

<?php __($notification) ?>

<?php
	switch($view):
	case "month":
?>

<div class="box">
	<form action="index.php" method="get">
		<input type="hidden" name="module" value="calendar">
		<?php __("C_GOTO") ?>:
		<?php __(Html::Months("month", false, $this->t, $c_month)) ?>
		<?php __(Html::Years("year", 3, 3)) ?>
		<input type="submit" value="<?php __("C_GO") ?>">
	</form>
</div>

<?php __($calendar) ?>

<div class="fright">
	<form>
		<input type="button" onclick="window.location.href='?module=calendar&view=addevent'" value="<?php __("C_ADD") ?>">
		<input type="button" onclick="window.location.href='?module=calendar'" value="<?php __("C_SHOW") ?>">
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
			<div class="label"><?php __("C_NEW_TITLE") ?></div>
			<div class="field"><input type="text" name="title" class="large required"></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_NEW_DATE") ?></div>
			<div class="field">
				<?php
					__(Html::Days("day", date("d")) . " ");
					__(Html::Months("month", false, $this->t, date("m")) . " ");
					__(Html::Years("year", 0, 3) . __("C_NEW_AT"));
					__(Html::Hours("hour") . ":");
					__(Html::Minutes("minute"));
				?>
			</div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_NEW_TYPE") ?></div>
			<div class="field">
				<select name="type" class="select2">
					<option value="public"><?php __("C_NEW_PUBLIC") ?></option>
					<option value="private"><?php __("C_NEW_PRIVATE") ?></option>
				</select>
			</div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_NEW_DESC") ?></div>
			<div class="field">
				<textarea name="text" id="descTextarea" rows="8" class="large required"></textarea>
				<div id="markdownPreview"></div>
			</div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="addevent"><input type="submit" value="<?php __("C_ADD") ?>"></div>
	</form>
</div>

<?php
	endswitch;
?>
