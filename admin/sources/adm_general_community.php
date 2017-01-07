<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_general_community.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;

// Notifications

$msg = (Http::Request("msg")) ? Http::Request("msg") : "";

switch($msg) {
	case 1:
		$message = Html::Notification("The settings has been successfully changed.", "success");
		break;
	default:
		$message = "";
		break;
}


// Languages

Database::Query("SELECT * FROM c_languages;");
while($result = Database::Fetch()) {
	$languages[$result['directory']] = $result['name'];
}

?>

<h1>Community</h1>

<div class="block">
	<form action="process.php?do=save" method="post">
		<?php echo $message ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">General Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Community name</td>
				<td><input type="text" name="general_community_name" value="<?php echo $Admin->SelectConfig("general_community_name") ?>" class="form-control span-4"></td>
			</tr>
			<tr>
				<td class="font-w600">Root path (URL)<small>Absolute URL of your community</small></td>
				<td><input type="text" name="general_community_url" value="<?php echo $Admin->SelectConfig("general_community_url") ?>" class="form-control span-6"></td>
			</tr>
			<tr>
				<td class="font-w600">Default language<small>For guests and new members</small></td>
				<td><?php echo $Admin->Dropdown("language_default_set", $languages, $Admin->SelectConfig("language_default_set"), "span-3") ?></td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Host Website Information</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Website name</td>
				<td><input type="text" name="general_website_name" value="<?php echo $Admin->SelectConfig("general_website_name") ?>" class="form-control span-5"></td>
			</tr>
			<tr>
				<td class="font-w600">Website URL</td>
				<td><input type="text" name="general_website_url" value="<?php echo $Admin->SelectConfig("general_website_url") ?>" class="form-control span-5"></td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Interface Elements</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Logo image name</td>
				<td><input type="text" name="general_community_logo" value="<?php echo $Admin->SelectConfig("general_community_logo") ?>" class="form-control span-3"></td>
			</tr>
			<tr>
				<td class="font-w600">Breadcrumb separator</td>
				<td><input type="text" name="general_bread_separator" value="<?php echo $Admin->SelectConfig("general_bread_separator") ?>" class="form-control span-1"></td>
			</tr>
			<tr>
				<td class="font-w600">Show members online</td>
				<td><label><?php echo $Admin->SelectCheckbox("general_sidebar_online") ?> Show members online in sidebar.</label></td>
			</tr>
			<tr>
				<td class="font-w600">Show statistics</td>
				<td><label><?php echo $Admin->SelectCheckbox("general_sidebar_stats") ?> Show community statistics in sidebar.</label></td>
			</tr>
		</table>

		<table class="table">
			<thead>
				<tr>
					<th colspan="2">SEO (Search Engine Optimization) Settings</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">META Description</td>
				<td><textarea name="seo_description" class="form-control span-6" rows="3"><?php echo $Admin->SelectConfig("seo_description") ?></textarea></td>
			</tr>
			<tr>
				<td class="font-w600">META Keywords</td>
				<td><textarea name="seo_keywords" class="form-control span-6" rows="3"><?php echo $Admin->SelectConfig("seo_keywords") ?></textarea></td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Settings">
		</div>
	</form>
</div>
