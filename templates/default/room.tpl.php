<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __($roomInfo['name']) ?></div>
	<?php if($this->IsMember()): ?>
		<div class="buttons fright"><a href="index.php?module=newthread&amp;room=<?php __($roomId) ?>" class="defaultButton transition">New Thread</a></div>
	<?php endif; ?>
</div>

<?php
	if(isset($notification))
		__($notification);
?>

<div class="navigation">
	<div class="navbar">
		<a href="index.php?module=room&amp;id=<?php __($roomId) ?>" class="transition <?php __($menu[0]) ?>">All</a>
		<?php if($this->IsMember()) __($myThreadsMenu) ?>
	</div>
	<div class="subnav">
		<a href="index.php?module=room&amp;id=<?php __($roomId) ?>" class="transition">Last Reply</a>
		<a href="index.php?module=room&amp;id=<?php __($roomId) ?>&amp;act=topreplies" class="transition">Top Threads</a>
		<a href="index.php?module=room&amp;id=<?php __($roomId) ?>&amp;act=noreplies" class="transition">No Replies</a>
		<a href="index.php?module=room&amp;id=<?php __($roomId) ?>&amp;act=bestanswered" class="transition">Answered</a>
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
			<a href="index.php?module=thread&amp;id=<?php __($_thread[$k]['t_id']) ?>" class="title"><?php __($_thread[$k]['title']) ?></a>
			<div class="desc"><?php __($_thread[$k]['description']) ?></div>
			<div class="author">
				<i class="fa fa-user"></i>
				By <?php __($_thread[$k]['author_name']) ?> &nbsp;
				<i class="fa fa-clock-o"></i>
				Started on <?php __($_thread[$k]['start_date']) ?>
			</div>
		</td>
		<td class="info"><i class="fa fa-comments"></i><br><?php __($_thread[$k]['replies']) ?></td>
		<td class="stats">
			<div class="label"><i class="fa fa-fw fa-eye"></i><span class="value"><?php __($_thread[$k]['views']) ?> views</span></div>
			<div class="label"><i class="fa fa-fw fa-user fleft"></i><span class="value"><a href="index.php?module=profile&amp;id=<?php __($_thread[$k]['lastpost_member_id']) ?>" title="Last post by <?php __($_thread[$k]['lastpost_name']) ?>"><?php __($_thread[$k]['lastpost_name']) ?></a></span></div>
			<div class="label"><i class="fa fa-fw fa-clock-o fleft"></i><span class="value"><?php __($_thread[$k]['lastpost_date']) ?></span></div>
		</td>
	</tr>
</table>

<?php
	endforeach;
	else:
	## NO THREADS ##
?>

<div class="threadItem">
	<div class="center">There are no threads to be shown. What about starting a new one?</div>
</div>

<?php
	endif;
?>

<div class="roomTitleBar">
	<?php if($this->IsMember()): ?>
		<div class="buttons fright"><a href="index.php?module=newthread&amp;room=<?php __($roomId) ?>" class="defaultButton transition">New Thread</a></div>
	<?php endif; ?>
</div>