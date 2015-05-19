<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_languages_manager.php
	#  License: GPLv2
	#  Copyright: (c) 2015 - Addictive Community
	## ---------------------------------------------------

	// List of emoticons

	$Db->Query("SELECT * FROM c_emoticons ORDER BY filename;");

	while($emoticon = $Db->Fetch()) {
		if($emoticon['display']) {
			$disable = "<a href='process.php?do=disable_emoticon&id={$emoticon['id']}'><i class='fa fa-ban'></i></a>";
		}
		else {
			$disable = "<a href='process.php?do=enable_emoticon&id={$emoticon['id']}'><i class='fa fa-check'></i></a>";
		}

		Template::Add("<tr>
				<td>{$emoticon['filename']}</td>
				<td>{$emoticon['shortcut']}</td>
				<td><img src='../public/emoticons/default/{$emoticon['filename']}' width='16'></td>
				<td>" . $Admin->FriendlyBool($emoticon['display']) . "</td>
				<td style='text-align:center'>{$disable}</td>
			</tr>");
	}

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
					<td>Shortcut</td>
					<td>Image</td>
					<td>Active</td>
					<td class="min">Enable/Disable</td>
				</tr>
				<?php echo Template::Get() ?>
			</table>
		</div>
		<!--
		<div class="grid-row">
			<table class="table-list">
				<tr>
					<th colspan="10">
						<div class="fleft">Upload new emoticon...</div>
					</th>
				</tr>
				<tr>
					<td class="title-fixed">Select image file<span class="title-desc">You might upload a 32x32 pixels image for Retina-optimized emoticons.</span></td>
					<td><input type="file" name=""></td>
				</tr>
				<tr>
					<td class="title-fixed">Shortcut <span class="title-desc">Be careful! All occourences of the word will be replaced by an image.</span></td>
					<td><input type="text" name="" class="medium"></td>
				</tr>
				<tr>
					<td class="title-fixed">Active</td>
					<td><input type="checkbox" name="" checked> This emoticon is active</td>
				</tr>
			</table>
		</div>
		-->
	</div>
