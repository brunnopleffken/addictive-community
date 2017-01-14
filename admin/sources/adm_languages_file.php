<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_languages_file.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Http;
use \AC\Kernel\Template;

// Get variables

$name = Http::request("id");
$dir  = Http::request("dir");

// Get file content

$file_path = "../languages/" . $dir . "/" . $name . ".php";

require_once($file_path);

// Build table

foreach($t as $k => $v) {
	Template::add("<tr>
			<td class='font-w600'>{$k}<input type='hidden' name='index[]' value='{$k}'></td>
			<td><textarea name='{$k}' rows='2' class='form-control span-8'>{$v}</textarea></td>
		</tr>");
}

?>

<h1>Editing language file: <?php echo ucwords($name) ?></h1>

<div class="block">
	<form action="process.php?do=savelang" method="post">
		<table class="table">
			<thead>
				<tr>
					<th colspan="10">
						<div class="fleft">Language File</div>
					</th>
				</tr>
				<tr>
					<td>Index Name</td>
					<td>Translation</td>
				</tr>
			</thead>
			<?php echo Template::get() ?>
		</table>
		<div class="box fright">
			<input type="hidden" name="file" value="<?php echo $name ?>">
			<input type="hidden" name="dir" value="<?php echo $dir ?>">
			<input type="submit" value="Save File">
		</div>
	</form>
</div>
