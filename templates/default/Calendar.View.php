<div class="room-title-bar">
	<div class="title fleft">
		<span><?php __($community_name) ?></span>
		<?php __("C_TITLE") ?>: <?php __($formatted_date) ?>
	</div>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="room/" class="selected"><?php __("C_VIEW") ?></a>
	</div>
	<div class="subnav">
		<a href="room/" class="selected"><?php __("C_VIEW_ALL") ?></a>
		<a href="calendar/view?date=2015-04-28&amp;filter=public"><?php __("C_VIEW_PUBLIC") ?></a>
		<a href="calendar/view?date=2015-04-28&amp;filter=private"><?php __("C_VIEW_PRIVATE") ?></a>
	</div>
</div>

<?php if($count): ?>
	<div class="events-container">
	<?php foreach($events as $event): ?>
		<div class="calendar-events">
			<div class="marker"><span></span></div>
			<div class="details">
				<div class="box">
					<div class="time"><?php __(date("H:i", $event['timestamp'])) ?></div>
					<h3><?php __($event['title']) ?></h3>
					<div class="tiny"><?php __("C_VIEW_BY", $event['username']) ?></div>
					<?php __($event['text']) ?>
				</div>
			</div>
		</div>
		<!--
		<table class="table-list">
			<tr>
				<th><?php __(date("H:i", $event['timestamp'])) ?> - <?php __($event['title']) ?></th>
			</tr>
			<tr>
				<td>
					<div class="tiny"><?php __("C_VIEW_BY", $event['username']) ?></div>
					<?php __($event['text']) ?>
				</td>
			</tr>
		</table>
		-->
	<?php endforeach; ?>
	</div>
<?php else: ?>
	<div class="box center">
		<?php __("C_NO_EVENTS") ?>
	</div>
<?php endif; ?>
