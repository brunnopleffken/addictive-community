<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __($roomInfo['name']) ?></div>
	<?php if($this->IsMember()): ?>
		<div class="buttons fright"><a href="?module=newthread&amp;room=<?php __($roomId) ?>" class="defaultButton transition"><?php __("R_NEW_THREAD") ?></a></div>
	<?php endif; ?>
</div>

<?php
	if(isset($notification))
		__($notification);
?>

<div class="navigation">
	<div class="navbar">
		<a href="?module=room&amp;id=<?php __($roomId) ?>" class="transition <?php __($menu[0]) ?>"><?php __("R_ALL") ?></a>
		<?php if($this->IsMember()) __($myThreadsMenu) ?>
	</div>
	<div class="subnav">
		<a href="?module=room&amp;id=<?php __($roomId) ?>" class="transition"><?php __("R_LAST_REPLY") ?></a>
		<a href="?module=room&amp;id=<?php __($roomId) ?>&amp;act=topreplies" class="transition"><?php __("R_TOP_THREADS") ?></a>
		<a href="?module=room&amp;id=<?php __($roomId) ?>&amp;act=noreplies" class="transition"><?php __("R_NO_REPLIES") ?></a>
		<a href="?module=room&amp;id=<?php __($roomId) ?>&amp;act=bestanswered" class="transition"><?php __("R_ANSWERED") ?></a>
	</div>
</div>

<?php
	## THREAD ROW ##
	if(isset($_thread)):
	foreach($_thread as $k => $v):
?>

<table class="threadItem <?php __($_thread[$k]['class']) ?>">
	<tr>
		<td class="min avatar">
			<?php __($_thread[$k]['author_avatar']) ?>
		</td>
		<td class="middle">
			<a href="?module=thread&amp;id=<?php __($_thread[$k]['t_id']) ?>" class="title"><?php __($_thread[$k]['title']) ?></a>
			<div class="desc"><?php __($_thread[$k]['description']) ?></div>
			<div class="author">
				<i class="fa fa-user"></i>
				<?php __("R_STARTED_BY", array($_thread[$k]['author_name'])) ?> &nbsp;
				<i class="fa fa-clock-o"></i>
				<?php __("R_STARTED_ON", array($_thread[$k]['start_date'])) ?>
			</div>
		</td>
		<td class="info"><i class="fa fa-comments"></i><br><?php __($_thread[$k]['replies']) ?></td>
		<td class="stats">
			<div class="label">
				<i class="fa fa-fw fa-eye"></i><span class="value"><?php __("R_VIEWS", array($_thread[$k]['views'])) ?></span>
			</div>
			<div class="label">
				<i class="fa fa-fw fa-user"></i><span class="value"><a href="?module=profile&amp;id=<?php __($_thread[$k]['lastpost_member_id']) ?>" title="<?php __("R_LAST_POST_BY", array($_thread[$k]['lastpost_name'])) ?>"><?php __($_thread[$k]['lastpost_name']) ?></a></span>
			</div>
			<div class="label">
				<i class="fa fa-fw fa-clock-o"></i><span class="value"><?php __($_thread[$k]['lastpost_date']) ?></span>
			</div>
		</td>
	</tr>
</table>

<?php
	endforeach;
	else:
	## NO THREADS ##
?>

<div class="threadItem">
	<div class="center"><?php __("R_NO_THREADS") ?></div>
</div>

<?php
	endif;
?>

<div class="roomTitleBar">
	<?php if($this->IsMember()): ?>
		<div class="buttons fright"><a href="?module=newthread&amp;room=<?php __($roomId) ?>" class="defaultButton transition"><?php __("R_NEW_THREAD") ?></a></div>
	<?php endif; ?>
</div>
