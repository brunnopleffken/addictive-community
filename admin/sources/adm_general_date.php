<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_general_date.php
	#  License: GPLv2
	#  Copyright: (c) 2016 - Addictive Community
	## ---------------------------------------------------

	$msg = (Http::Request("msg")) ? Http::Request("msg") : "";

	switch($msg) {
		case 1:
			$message = Html::Notification("The settings has been successfully changed.", "success");
			break;
		default:
			$message = "";
			break;
	}

?>

	<h1>Date &amp; Time</h1>

	<div id="content">
		<div class="grid-row">
			<form action="process.php?do=save" method="post">

				<?php echo $message ?>

				<table class="table-list">
					<tr>
						<th colspan="2">Date and Time Format</th>
					</tr>
					<tr>
						<td class="title-fixed">Short date format<span class="title-desc">Same format as <a href="http://uk1.php.net/manual/en/function.date.php" target="_blank">PHP's date()</a></span></td>
						<td><input type="text" name="date_short_format" value="<?php echo $Admin->SelectConfig("date_short_format") ?>" class="tiny"></td>
					</tr>
					<tr>
						<td class="title-fixed">Long date format<span class="title-desc">Same format as <a href="http://uk1.php.net/manual/en/function.date.php" target="_blank">PHP's date()</a></span></td>
						<td><input type="text" name="date_long_format" value="<?php echo $Admin->SelectConfig("date_long_format") ?>" class="tiny"></td>
					</tr>
				</table>

				<table class="table-list">
					<tr>
						<th colspan="2">Timezones</th>
					</tr>
					<tr>
						<td class="title-fixed">Default Timezone Offset<span class="title-desc">Timezone offset, in hours</span></td>
						<td><input type="text" name="date_default_offset" value="<?php echo $Admin->SelectConfig("date_default_offset") ?>" class="tiny"></td>
					</tr>
				</table>

				<div class="box fright"><input type="submit" value="Save Settings"></div>
			</form>
		</div>
	</div>
