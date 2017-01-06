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

<div id="content">
	<div class="grid-row">
		<table class="table-list">
			<tr>
				<th colspan="10">
					<div class="fleft">List of Emoticons</div>
				</th>
			</tr>
			<tr class="subtitle">
				<td>Filename</td>
				<td>Trigger</td>
				<td>Image</td>
				<td>Active</td>
				<td class="min">Enable/Disable</td>
			</tr>
			<?php echo Template::Get() ?>
		</table>
	</div>
	<div class="grid-row">
		<table class="table-list">
			<tr>
				<th colspan="10">
					<div class="fleft">Upload new emoticon</div>
				</th>
			</tr>
			<tr>
				<td class="title-fixed">Select image file<span class="title-desc">You might upload a 32x32 pixels image for Retina-optimized emoticons.</span></td>
				<td><input type="file" name=""></td>
			</tr>
			<tr>
				<td class="title-fixed">Trigger <span class="title-desc">Be careful! All occourences of the word will be replaced by an image.</span></td>
				<td><input type="text" name="" class="medium" placeholder=":("></td>
			</tr>
			<tr>
				<td class="title-fixed">Active</td>
				<td><input type="checkbox" name="" checked> This emoticon sould be activated immediately.</td>
			</tr>
		</table>
		<div class="fright">
			<input type="submit" value="Upload new emoticon">
		</div>
	</div>
	<br><br>
	<div class="grid-row">
		<table class="table-list">
			<tr>
				<th colspan="10">
					<div class="fleft">Add files to database</div>
				</th>
			</tr>
			<tr>
				<td class="title-fixed">Image missing</td>
				<td>
					<?php if(count($not_in_database) > 0): ?>
						<select name="" id="">
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
				<td class="title-fixed">Trigger <span class="title-desc">Be careful! All occourences of the word will be replaced by an image.</span></td>
				<td><input type="text" name="" class="medium" placeholder=":("></td>
			</tr>
			<tr>
				<td class="title-fixed">Active</td>
				<td><input type="checkbox" name="" checked> This emoticon sould be activated immediately.</td>
			</tr>
		</table>
		<div class="fright">
			<input type="submit" value="Insert missing file">
		</div>
	</div>
</div>
