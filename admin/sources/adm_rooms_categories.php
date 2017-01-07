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
			<td class='min'><input type='number' name='category[{$category['c_id']}][order_n]' value='{$category['order_n']}' class='form-control' style='width: 50px'></td>
			<td><b>{$category['name']}</b></td>
			<td class='min text-center'>
				<input type='hidden' name='category[{$category['c_id']}][visible]' value='0'>
				<input type='checkbox' name='category[{$category['c_id']}][visible]' value='1' {$checked}>
			</td>
			<td class='min text-center'>{$remove}</td>
		</tr>");
}

?>

<h1>Categories</h1>

<div class="block">
		<?php echo $message ?>
		<form action="process.php?do=update_categories" method="post" style="overflow: hidden">
			<table class="table">
				<thead>
					<tr>
						<th colspan="4">Current categories</th>
					</tr>
					<tr>
						<td>Order</td>
						<td>Category</td>
						<td class="min">Visible</td>
						<td class="min">Delete</td>
					</tr>
				</thead>
				<?php echo Template::Get(); ?>
			</table>
			<div class="text-right">
				<input type="submit" class="btn btn-default" value="Update Categories">
			</div>
		</form>

		<hr>

		<form action="process.php?do=new_category" method="post">
			<table class="table">
				<thead>
					<tr>
						<th colspan="2">Add new category</th>
					</tr>
				</thead>
				<tr>
					<td class="font-w600">Category name</td>
					<td><input type="text" name="name" class="form-control span-4"></td>
				</tr>
			</table>
			<div class="text-right">
				<input type="submit" class="btn btn-default" value="Create Category">
			</div>
		</form>
</div>
