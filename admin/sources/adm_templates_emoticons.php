<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_templates_emoticons.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Template;

// List of emoticon in database
$emoticon_list_from_db = array();
$emoticon_list_from_dir = array();

// Get list of emoticons from database
$emoticon_query = Database::Query("SELECT * FROM c_emoticons ORDER BY filename;");

while($emoticon = Database::Fetch($emoticon_query)) {
	$emoticon_list_from_db[] = $emoticon['filename'];

	if($emoticon['display']) {
		$disable = "<a href='process.php?do=disable_emoticon&id={$emoticon['id']}'><i class='fa fa-ban'></i></a>";
	}
	else {
		$disable = "<a href='process.php?do=enable_emoticon&id={$emoticon['id']}'><i class='fa fa-check'></i></a>";
	}

	Template::Add("<tr>
			<td>{$emoticon['filename']}</td>
			<td>{$emoticon['shortcut']}</td>
			<td><img src='../public/emoticons/{$emoticon['emoticon_set']}/{$emoticon['filename']}' width='16'></td>
			<td>" . $Admin->FriendlyBool($emoticon['display']) . "</td>
			<td style='text-align:center'>{$disable}</td>
		</tr>");
}

// Select default emoticon directory
$dir = scandir("../public/emoticons/default");

foreach($dir as $filename) {
	if(!in_array($filename, array(".", ".."))) {
		$emoticon_list_from_dir[] = $filename;
	}
}

// Analyse which emoticons are not stored in database
$not_in_database = array_diff($emoticon_list_from_dir, $emoticon_list_from_db);

?>

<h1>Emoticons</h1>

<div class="block">
	<table class="table">
		<thead>
			<tr>
				<th colspan="10">
					<div class="fleft">List of Emoticons</div>
				</th>
			</tr>
			<tr>
				<td>Filename</td>
				<td>Trigger</td>
				<td>Image</td>
				<td>Active</td>
				<td class="min">Enable/Disable</td>
			</tr>
		</thead>
		<?php echo Template::Get() ?>
	</table>

	<hr>

	<table class="table">
		<thead>
			<tr>
				<th colspan="10">
					<div class="fleft">Upload new emoticon</div>
				</th>
			</tr>
		</thead>
		<tr>
			<td class="font-w600">Select image file<small>You might upload a 32x32 pixels image for Retina-optimized emoticons.</small></td>
			<td><input type="file" name=""></td>
		</tr>
		<tr>
			<td class="font-w600">Trigger <small>Be careful! All occourences of the word will be replaced by an image.</small></td>
			<td><input type="text" name="" class="form-control span-1" placeholder=":("></td>
		</tr>
		<tr>
			<td class="font-w600">Active</td>
			<td><input type="checkbox" name="" checked> This emoticon sould be activated immediately.</td>
		</tr>
	</table>
	<div class="text-right">
		<input type="submit" class="btn btn-default" value="Upload Emoticon">
	</div>

	<hr>

	<table class="table">
		<thead>
			<tr>
				<th colspan="10">
					<div class="fleft">Add files to database</div>
				</th>
			</tr>
		</thead>
		<tr>
			<td class="font-w600">Image missing</td>
			<td>
				<?php if(count($not_in_database) > 0): ?>
					<select name="" id="" class="form-control span-3">
						<?php foreach($not_in_database as $image_name): ?>
							<option value="<?php echo $image_name ?>"><?php echo $image_name ?></option>
						<?php endforeach; ?>
					</select>
				<?php else: ?>
					<span class="done">The database isn't missing any file! :)</span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td class="font-w600">Trigger <small>Be careful! All occourences of the word will be replaced by an image.</small></td>
			<td><input type="text" name="" class="form-control span-1" placeholder=":("></td>
		</tr>
		<tr>
			<td class="font-w600">Active</td>
			<td><input type="checkbox" name="" checked> This emoticon sould be activated immediately.</td>
		</tr>
	</table>
	<div class="text-right">
		<input type="submit" class="btn btn-default" value="Add Missing Files">
	</div>
</div>
