<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_general_date.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Html;
use \AC\Kernel\Http;

$msg = (Http::request("msg")) ? Http::request("msg") : "";

switch($msg) {
	case 1:
		$message = Html::notification("The settings has been successfully changed.", "success");
		break;
	default:
		$message = "";
		break;
}

?>

<h1>Date &amp; Time</h1>

<div class="block">
	<form action="process.php?do=save" method="post">
		<?php echo $message ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Date and Time Format</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Short date format<small>Same format as <a href="http://uk1.php.net/manual/en/function.date.php" target="_blank">PHP's date()</a></small></td>
				<td><input type="text" name="date_short_format" value="<?php echo $Admin->selectConfig("date_short_format") ?>" class="form-control span-2"></td>
			</tr>
			<tr>
				<td class="font-w600">Long date format<small>Same format as <a href="http://uk1.php.net/manual/en/function.date.php" target="_blank">PHP's date()</a></small></td>
				<td><input type="text" name="date_long_format" value="<?php echo $Admin->selectConfig("date_long_format") ?>" class="form-control span-2"></td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Timezones</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Default timezone offset<small>Timezone offset, in hours</small></td>
				<td><input type="text" name="date_default_offset" value="<?php echo $Admin->selectConfig("date_default_offset") ?>" class="form-control span-1"> hours</td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Settings">
		</div>
	</form>
</div>
