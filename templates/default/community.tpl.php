<?php foreach($_rooms as $k => $v): ?>
	<table class="roomItem">
		<tr>
			<td class="image"><img src="templates/<?php echo $this->info['template'] ?>/images/room-nonew.png" alt="No New"></td>
			<td class="info">
				<a href="index.php?module=room&amp;id=<?php echo $_rooms[$k]['r_id'] ?>" class="title"><?php echo $_rooms[$k]['name'] ?></a>
				<p class="description"><?php echo $_rooms[$k]['description'] ?></p>
				<div class="lastPost">
					<span><img src="templates/<?php echo $this->info['template'] ?>/images/lastpost-thread.png" title="Thread">&nbsp; <?php echo $_rooms[$k]['title'] ?></span>
					<span><img src="templates/<?php echo $this->info['template'] ?>/images/lastpost-author.png" title="Posted by">&nbsp; <a href="index.php?module=profile&amp;id=2"><?php echo $_rooms[$k]['username'] ?></a></span>
					<span><img src="templates/<?php echo $this->info['template'] ?>/images/lastpost-date.png" title="Posted on">&nbsp; <?php echo $_rooms[$k]['lastpost_date'] ?></span>
				</div>
			</td>
			<td class="roomStats">
				<div class="item"><img src="templates/<?php echo $this->info['template'] ?>/images/room-num-threads.png" alt="Threads" class="fleft"><span class="fright"><?php echo $_rooms[$k]['thread_count'] ?></span></div>
				<div class="item"><img src="templates/<?php echo $this->info['template'] ?>/images/room-users-online.png" alt="Members Online" class="fleft"><span class="fright">0</span></div>
			</td>
		</tr>
	</table>
<?php endforeach; ?>

<div class="fright"><a href="#">Mark All As Read</a></div>