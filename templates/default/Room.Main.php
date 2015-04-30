<div class="title-bar">
	<div class="title fleft"><span><?php __($this->Config['general_communityname']) ?></span><?php __($room_info['name']) ?></div>
	<?php if($this->Session->IsMember()): ?>
		<?php if($room_info['read_only']): ?>
			<div class="buttons fright"><a class="default-button disabled"><?php __("R_READ_ONLY") ?></a></div>
		<?php else: ?>
			<div class="buttons fright"><a href="thread/add/<?php __($room_id) ?>" class="default-button"><?php __("R_NEW_THREAD") ?></a></div>
		<?php endif; ?>
	<?php endif; ?>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="room/<?php __($room_id) ?>" class="<?php __($menu[0]) ?>"><?php __("R_ALL") ?></a>
		<?php if($this->Session->IsMember()): ?>
			<a href="room/<?php __($room_id) ?>?view=mythreads" class="<?php __($menu[1]) ?>">My Threads</a>
		<?php endif; ?>
	</div>
	<div class="subnav">
		<a href="room/<?php __($room_id) ?>" class="selected"><?php __("R_LAST_REPLY") ?></a>
		<a href="room/<?php __($room_id) ?>?view=topreplies"><?php __("R_TOP_THREADS") ?></a>
		<a href="room/<?php __($room_id) ?>?view=noreplies"><?php __("R_NO_REPLIES") ?></a>
		<a href="room/<?php __($room_id) ?>?view=bestanswered"><?php __("R_ANSWERED") ?></a>
	</div>
</div>

<?php if(!empty($threads)): ?>
	<?php foreach($threads as $k => $v): ?>
		<div class="thread-item <?php __($threads[$k]['class']) ?>">
			<div class="avatar">
				<?php __($threads[$k]['author_avatar']) ?>
			</div>
			<div class="middle">
				<a href="thread/<?php __($threads[$k]['t_id']) ?>" class="title"><?php __($threads[$k]['title']) ?></a>
				<div class="description"><?php __($threads[$k]['description']) ?></div>
				<div class="author">
					<i class="fa fa-user"></i>
					<?php __("R_STARTED_BY", array($threads[$k]['author_name'])) ?>
					<i class="fa fa-clock-o"></i>
					<?php __("R_STARTED_ON", array($threads[$k]['start_date'])) ?>
				</div>
			</div>
			<div class="info">
				<i class="fa fa-comments"></i><br><?php __($threads[$k]['replies']) ?>
			</div>
			<div class="stats">
				<div class="label">
					<i class="fa fa-fw fa-eye"></i><span class="value"><?php __("R_VIEWS", array($threads[$k]['views'])) ?></span>
				</div>
				<div class="label">
					<i class="fa fa-fw fa-user"></i><span class="value"><a href="profile/<?php __($threads[$k]['lastpost_member_id']) ?>" title="<?php __("R_LAST_POST_BY", array($threads[$k]['lastpost_name'])) ?>"><?php __($threads[$k]['lastpost_name']) ?></a></span>
				</div>
				<div class="label">
					<i class="fa fa-fw fa-clock-o"></i><span class="value"><?php __($threads[$k]['lastpost_date']) ?></span>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="thread-item">
		<div class="center"><?php __("R_NO_THREADS") ?></div>
	</div>
<?php endif; ?>

<div class="title-bar">
	<?php if($this->Session->IsMember()): ?>
		<?php if($room_info['read_only']): ?>
			<div class="buttons fright"><a class="default-button disabled"><?php __("R_READ_ONLY") ?></a></div>
		<?php else: ?>
			<div class="buttons fright"><a href="thread/add/<?php __($room_id) ?>" class="default-button"><?php __("R_NEW_THREAD") ?></a></div>
		<?php endif; ?>
	<?php endif; ?>
</div>
