<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span>Messenger</div>
</div>

<div class="box">
	<span class="fleft">
		<div class="progressBar" style="width: 204px"><div class="fill" style="width: <?php __($percentageWidth) ?>"></div><span>Storage:  <?php __($numResults . " of " . $maxStorageSize) ?></span></div>
	</span>
	<form class="fright">
		<input type="button" id="messengerDeleteMessages" class="cancel" value="Delete Selected Messages">
		<input type="button" onclick="window.location.href='index.php?module=messenger&view=compose'" value="New Message">
	</form>
</div>

<form action="index.php?module=messenger&act=delete" method="post" class="personalMessenger">
	<table class="tableList">
		<tr>
			<th colspan="7">
				<div class="fright">
					<a href="index.php?module=members&amp;order=post" class="smallButton grey white transition">Select All</a>
				</div>
			</th>
		</tr>

		<tr class="subtitle">
			<td class="min">&nbsp;</td>
			<td>Subject</td>
			<td width="20%">From</td>
			<td width="22%">Last Message</td>
			<td class="min"></td>
		</tr>

		<?php
			if($numResults):
				foreach($results as $pm):
		?>
		<tr>
			<td class="min" style="font-size: 17px"><i class="fa <?php __($pm['icon_class']) ?>"></i></td>
			<td style="font-size: 14px;"><a href=""><?php __($pm['subject']) ?></a></td>
			<td><a href="index.php?module=profile&id=<?php __($pm['from_id']) ?>"><?php __($pm['username']) ?></a></td>
			<td><?php __($pm['sent_date']) ?></td>
			<td class="min"><input type="checkbox" name="pm[]" value="<?php __($pm['pm_id']) ?>" class="checkDeleteMessage"></td>
		</tr>
		<?php
				endforeach;
			else:
		?>
		<tr>
			<td colspan="5" class="min center">There are no private messages to show.</td>
		</tr>
		<?php
			endif;
		?>
	</table>
</form>