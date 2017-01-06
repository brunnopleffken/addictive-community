<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_rooms_categories.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\Template;

// Messages

$msg = (Http::Request("msg")) ? Http::Request("msg") : "";

switch($msg) {
	case 1:
		$message = Html::Notification("The categories has been successfully updated.", "success");
		break;
	case 2:
		$message = Html::Notification("The room has been successfully removed and all its rooms were moved to the first category.", "success");
		break;
	default:
		$message = "";
		break;
}


// Get list of categories

$categories = Database::Query("SELECT * FROM c_categories ORDER BY c_id;");

while($category = Database::Fetch($categories)) {
	$checked = ($category['visible'] == 1) ? "checked='checked'" : "";

	if($categories->num_rows > 1) {
		$remove = "<a href='process.php?do=remove_category&id={$category['c_id']}'><i class='fa fa-fw fa-remove'></i></a>";
	}
	else {
		$remove = "";
	}

	Template::Add("<tr>
			<td class='min'><input type='text' name='category[{$category['c_id']}][order_n]' value='{$category['order_n']}' style='width: 25px'></td>
			<td><b>{$category['name']}</b></td>
			<td class='min'>
				<input type='hidden' name='category[{$category['c_id']}][visible]' value='0'>
				<input type='checkbox' name='category[{$category['c_id']}][visible]' value='1' {$checked}>
			</td>
			<td class='min'>{$remove}</td>
		</tr>");
}

?>

<h1>Categories</h1>

<div id="content">
	<div class="grid-row">
		<?php echo $message ?>
		<!-- LIST -->
		<form action="process.php?do=update_categories" method="post" style="overflow: hidden">
			<table class="table-list">
				<tr>
					<th colspan="4">Current categories</th>
				</tr>
				<tr class="subtitle">
					<td>Order</td>
					<td>Category</td>
					<td class="min">Visible</td>
					<td class="min">Delete</td>
				</tr>
				<?php echo Template::Get(); ?>
			</table>
			<div class="fright"><input type="submit" value="Update Categories"></div>
		</form>

		<br><br>

		<!-- ADD -->
		<form action="process.php?do=new_category" method="post">
			<table class="table-list">
				<tr>
					<th colspan="2">Add New Category</th>
				</tr>
				<tr>
					<td class="title-fixed">Category Name</td>
					<td><input type="text" name="name" class="medium"></td>
				</tr>
			</table>
			<div class="fright"><input type="submit" value="Create Category"></div>
		</form>
	</div>
</div>
