<div class="roomTitleBar">
	<div class="title fleft"><span><?php echo $this->Core->config['general_communityname'] ?></span><?php echo $roomInfo['name'] ?></div>
	<div class="buttons fright"><a href="index.php?module=newthread&amp;room=<?php echo $roomId ?>" class="defaultButton transition">New Thread</a></div>
</div>

<?php
	if(isset($notification))
		echo $notification;
?>

<div class="navigation">
	<div class="navbar">
		<a href="index.php?module=room&amp;id=<?php echo $roomId ?>" class="transition <?php echo $menu[0] ?>">All</a>
		<?php echo $myThreadsMenu ?>
	</div>
	<div class="subnav">
		<a href="index.php?module=room&amp;id=<?php echo $roomId ?>" class="transition {$newest}">Newest</a>
		<a href="index.php?module=room&amp;id=<?php echo $roomId ?>&amp;act=topreplies" class="transition {$topreplies}">Top Threads</a>
		<a href="index.php?module=room&amp;id=<?php echo $roomId ?>&amp;act=noreplies" class="transition {$noreplies}">No Replies</a>
		<a href="index.php?module=room&amp;id=<?php echo $roomId ?>&amp;act=bestanswered" class="transition {$bestanswered}">Answered</a>
	</div>
</div>

<?php
	## THREAD ROW ##
	if(isset($_thread)):
	foreach($_thread as $k => $v):
?>

<table class="threadItem <?php echo $_thread[$k]['class'] ?>">
	<tr>
		<td class="content">
			<a href="index.php?module=thread&amp;id=<?php echo $_thread[$k]['t_id'] ?>" class="title"><?php echo $_thread[$k]['title'] ?></a>
			<div class="desc"><?php echo $_thread[$k]['description'] ?></div>
			<div class="author">
				<i class="fa fa-user"></i>
				By <?php echo $_thread[$k]['author_name'] ?> &nbsp;
				<i class="fa fa-clock-o"></i>
				Started on <?php echo $_thread[$k]['start_date'] ?>
			</div>
		</td>
		<td class="info"><i class="fa fa-comments"></i><br><?php echo $_thread[$k]['replies'] ?></td>
		<td class="stats {$thread_info['class_status']}">
			<div class="label"><i class="fa fa-eye"></i><span class="value"><?php echo $_thread[$k]['views'] ?> views</span></div>
			<div class="label"><i class="fa fa-user fleft"></i><span class="value"><a href="index.php?module=profile&amp;id={$thread_info['lastpost_member_id']}" title="Last post by <?php echo $_thread[$k]['lastpost_name'] ?>"><?php echo $_thread[$k]['lastpost_name'] ?></a></span></div>
			<div class="label"><i class="fa fa-clock-o fleft"></i><span class="value"><?php echo $_thread[$k]['lastpost_date'] ?></span></div>
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
	<div class="buttons fright"><a href="index.php?module=newthread&amp;room=<?php echo $roomId ?>" class="defaultButton transition">New Thread</a></div>
</div>