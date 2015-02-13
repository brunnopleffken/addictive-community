<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __("M_TITLE") ?></div>
</div>

<div class="classification">
	<?php __($first) ?>
	<?php __($letterList) ?>
</div>

<table class="tableList">
	<tr>
		<th colspan="7">
			<div class="fright">
				<a href="?module=members" class="smallButton grey white transition"><?php __("M_MEMBER_NAME") ?></a>
				<a href="?module=members&amp;order=join" class="smallButton grey white transition"><?php __("M_JOIN_DATE") ?></a>
				<a href="?module=members&amp;order=post" class="smallButton grey white transition"><?php __("M_POST_COUNT") ?></a>
			</div>
		</th>
	</tr>
	<tr class="subtitle">
		<td class="min">&nbsp;</td>
		<td><?php __("M_MEMBER_NAME") ?></td>
		<td><?php __("M_JOINED") ?></td>
		<td><?php __("M_POSTS") ?></td>
		<td><?php __("M_LOCATION") ?></td>
		<td><?php __("M_GROUP") ?></td>
		<td class="min">&nbsp;</td>
	</tr>

	<?php
		if($numResults > 0):
		foreach($_result as $k => $v):
	?>

	<tr>
		<td class="min"><?php __(Html::Crop($_result[$k]['avatar'], 42, 42, "avatar")) ?></td>
		<td style="font-size: 14px;"><a href="profile/<?php __($_result[$k]['m_id']) ?>"><b><?php __($_result[$k]['username']) ?></b></a></td>
		<td><?php __($_result[$k]['joined']) ?></td>
		<td><?php __($_result[$k]['posts']) ?></td>
		<td><?php __($_result[$k]['location']) ?></td>
		<td><?php __($_result[$k]['name']) ?></td>
		<td class="min" style="font-size: 16px"><a href="profile/<?php __($_result[$k]['m_id']) ?>"><i class="fa fa-search"></i></a></td>
	</tr>

	<?php
		endforeach;
		else:
	?>

	<tr>
		<td colspan="7" class="center"><?php __("M_NO_RESULTS") ?></td>
	</tr>

	<?php
		endif;
	?>

</table>

<div class="box">
	<form action="?module=members" method="get" class="validate">
		<input type="hidden" name="module" value="members">
		<?php __("M_SEARCH") ?>: <input type="text" name="username" class="small required">
		<input type="submit" value="OK">
	</form>
</div>
