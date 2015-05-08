<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: adm_languages_manager.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	// List of emoticons

	$Db->Query("SELECT * FROM c_emoticons ORDER BY filename;");

	while($emoticon = $Db->Fetch()) {
		Template::Add("<tr>
				<td>{$emoticon['filename']}</td>
				<td>{$emoticon['shortcut']}</td>
				<td><img src='../public/emoticons/default/{$emoticon['filename']}' width='16'></td>
				<td>" . $Admin->FriendlyBool($emoticon['display']) . "</td>
				<td><a href=''><i class='fa fa-pencil'></i></a></td>
				<td><a href=''><i class='fa fa-close'></i></a></td>
			</tr>");
	}

?>

	<h1>Emoticons</h1>

	<div id="content">
		<div class="grid-row">
			<table class="table-list">
				<tr>
					<th colspan="10">
						<div class="fleft">List of bad words</div>
					</th>
				</tr>
				<tr class="subtitle">
					<td>Filename</td>
					<td>Shortcut</td>
					<td>Image</td>
					<td>Active</td>
					<td class="min">Edit</td>
					<td class="min">Delete</td>
				</tr>
				<?php echo Template::Get() ?>
			</table>
		</div>
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
					<td class="title-fixed">Shortcut <span class="title-desc">Be careful! All occourences of the word will be replaced by ":)".</span></td>
					<td><input type="text" name="" class="medium"></td>
				</tr>
				<tr>
					<td class="title-fixed">Active</td>
					<td><input type="checkbox" name="" checked> This emoticon is active</td>
				</tr>
			</table>
		</div>
	</div>
