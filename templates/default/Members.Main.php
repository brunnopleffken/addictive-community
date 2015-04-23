<div class="room-title-bar">
	<div class="title fleft">
		<span><?php __($community_name) ?></span>
		<?php __("M_TITLE") ?>
	</div>
</div>

<div class="classification">
	<?php __($letter_first) ?>
	<?php __($letter_list) ?>
</div>

<table class="table-list">
	<tr>
		<th colspan="7">
			<div class="fright">
				<a href="members" class="small-button grey white"><?php __("M_MEMBER_NAME") ?></a>
				<a href="members?order=join" class="small-button grey white"><?php __("M_JOIN_DATE") ?></a>
				<a href="members?order=post" class="small-button grey white"><?php __("M_POST_COUNT") ?></a>
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
	<?php if(!empty($results)): ?>
		<?php foreach($results as $k => $v): ?>
			<tr>
				<td class="min"><?php __(Html::Crop($results[$k]['avatar'], 42, 42, "avatar")) ?></td>
				<td style="font-size: 14px;">
					<a href="profile/<?php __($results[$k]['m_id']) ?>"><b><?php __($results[$k]['username']) ?></b></a>
				</td>
				<td><?php __($results[$k]['joined']) ?></td>
				<td><?php __($results[$k]['posts']) ?></td>
				<td><?php __($results[$k]['location']) ?></td>
				<td><?php __($results[$k]['name']) ?></td>
				<td class="min" style="font-size: 16px"><a href="profile/<?php __($results[$k]['m_id']) ?>"><i class="fa fa-search"></i></a></td>
			</tr>
		<?php endforeach; ?>
	<?php else: ?>
		<tr>
			<td colspan="7" class="center"><?php __("M_NO_RESULTS") ?></td>
		</tr>
	<?php endif; ?>
</table>

<div class="box">
	<form action="?module=members" method="get" class="validate">
		<input type="hidden" name="module" value="members">
		<?php __("M_SEARCH") ?>: <input type="text" name="username" class="small required">
		<input type="submit" value="OK">
	</form>
</div>
