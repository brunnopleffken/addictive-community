<div class="room-title-bar">
	<div class="title fleft">
		<span><?php __($community_name) ?></span><?php __("M_TITLE") ?>
	</div>
	<div class="buttons fright">
		<a href="messenger/compose" class="default-button"><?php __("M_NEW_MESSAGE") ?></a>
	</div>
</div>

<?php __($notification) ?>

<div class="navigation">
	<div class="navbar">
		<a href="messenger" class="selected"><?php __("M_FOLDERS") ?></a>
	</div>
	<div class="subnav">
		<a href="messenger" class="selected"><?php __("M_FOLDER_INBOX") ?></a>
		<a href="messenger?folder=sent"><?php __("M_FOLDER_SENT") ?></a>
		<div class="progress-bar" style="width: 204px; float: right; margin-top: 9px"><div class="fill" style="width: <?php __($percentage_width) ?>"></div><span><?php __("M_STORAGE", array($num_results, $max_storage_size)) ?></span></div>
	</div>
</div>

<form action="messenger/delete" method="post" class="personal-messenger">
	<table class="table-list">
		<tr>
			<th colspan="7">
				<div class="fleft"><?php __("M_FOLDER_INBOX") ?></div>
				<div class="fright">
					<a class="small-button grey white" id="delete-messages"><?php __("M_DELETE_SELECTED") ?></a>
					<a class="small-button grey white" data-check="check-messages"><?php __("M_SELECT_ALL") ?></a>
				</div>
			</th>
		</tr>
		<tr class="subtitle">
			<td class="min">&nbsp;</td>
			<td><?php __("M_SUBJECT") ?></td>
			<td width="20%"><?php __("M_FROM") ?></td>
			<td width="22%"><?php __("M_DATE") ?></td>
			<td class="min center"><i class="fa fa-check-square-o"></i></td>
		</tr>
		<?php if($num_results): ?>
			<?php foreach($results as $pm): ?>
				<tr>
					<td class="min" style="font-size: 17px"><i class="fa <?php __($pm['icon_class']) ?>"></i></td>
					<td style="font-size: 14px;"><a href="messenger/read/<?php __($pm['pm_id']) ?>"><?php __($pm['subject']) ?></a></td>
					<td><a href="profile/<?php __($pm['from_id']) ?>"><?php __($pm['username']) ?></a></td>
					<td><?php __($pm['sent_date']) ?></td>
					<td class="min"><input type="checkbox" name="pm[]" value="<?php __($pm['pm_id']) ?>" class="checkDeleteMessage"></td>
				</tr>
			<?php endforeach; ?>
			<?php else: ?>
			<tr>
				<td colspan="5" class="min center"><?php __("M_NO_PM_INBOX") ?></td>
			</tr>
		<?php endif; ?>
	</table>
</form>
