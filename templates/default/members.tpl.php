<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span>Member List</div>
</div>

<div class="classification">
	<?php __($first) ?>
	<?php __($letterList) ?>
</div>

<table class="tableList">
	<tr>
		<th colspan="7">
			<div class="fright">
				<a href="index.php?module=members" class="smallButton grey white transition">Member Name</a>
				<a href="index.php?module=members&amp;order=join" class="smallButton grey white transition">Join Date</a>
				<a href="index.php?module=members&amp;order=post" class="smallButton grey white transition">Post Count</a>
			</div>
		</th>
	</tr>
	<tr class="subtitle">
		<td class="min">&nbsp;</td>
		<td>Member Name</td>
		<td>Joined</td>
		<td>Posts</td>
		<td>Location</td>
		<td>Group</td>
		<td class="min">&nbsp;</td>
	</tr>

	<?php
		if($numResults > 0):
		foreach($_result as $k => $v):
	?>

	<tr>
		<td class="min"><?php __(Html::Crop($_result[$k]['avatar'], 42, 42, "avatar")) ?></td>
		<td style="font-size: 14px;"><a href="index.php?module=profile&amp;id=<?php __($_result[$k]['m_id']) ?>"><b><?php __($_result[$k]['username']) ?></b></a></td>
		<td><?php __($_result[$k]['joined']) ?></td>
		<td><?php __($_result[$k]['posts']) ?></td>
		<td><?php __($_result[$k]['location']) ?></td>
		<td><?php __($_result[$k]['name']) ?></td>
		<td class="min" style="font-size: 16px"><a href="index.php?module=profile&amp;id=1"><i class="fa fa-search"></i></a></td>
	</tr>
	
	<?php
		endforeach;
		else:
	?>

	<tr>
		<td colspan="7" class="center">Your search returned no results.</td>
	</tr>

	<?php
		endif;
	?>

</table>

<div class="box">
	<form action="index.php" class="validate">
		Search by name: <input type="text" name="" class="small required">
		<input type="submit" value="OK">
	</form>
</div>