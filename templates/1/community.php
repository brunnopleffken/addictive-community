<?php while($result = $this->Db->Fetch($rooms_result)): ?>
	<table class="roomItem">
		<tr>
			<td class="image"><img src="templates/1/images/room-nonew.png" alt="No New"></td>
			<td class="info">
				<a href="index.php?module=room&amp;id=<?php echo $result['r_id'] ?>" class="title"><?php echo $result['name'] ?></a>
				<p class="description"><?php echo $result['description'] ?></p>
				<div class="lastPost">
					<span><img src="templates/1/images/lastpost-thread.png" title="Thread">&nbsp; <a href="index.php?module=thread&amp;id=10"><?php echo $result['title'] ?></a></span>
					<span><img src="templates/1/images/lastpost-author.png" title="Posted by">&nbsp; <a href="index.php?module=profile&amp;id=2"><?php echo $result['username'] ?></a></span>
					<span><img src="templates/1/images/lastpost-date.png" title="Posted on">&nbsp; <?php echo $this->Core->DateFormat($result['lastpost_date']) ?></span>
				</div>
			</td>
			<td class="roomStats">
				<div class="item"><img src="templates/1/images/room-num-threads.png" alt="Threads" class="fleft"><span class="fright"><?php echo $result['thread_count'] ?></span></div>
				<div class="item"><img src="templates/1/images/room-users-online.png" alt="Members Online" class="fleft"><span class="fright">0</span></div>
			</td>
		</tr>
	</table>
<?php endwhile; ?>

<div class="fright"><a href="#">Mark All As Read</a></div>