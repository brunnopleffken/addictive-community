<?php foreach($_rooms as $k => $v): ?>
	<table class="roomItem">
		<tr>
			<td class="image"><i class="fa fa-folder-open-o fleft"></i></td>
			<td class="info">
				<a href="index.php?module=room&amp;id=<?php echo $_rooms[$k]['r_id'] ?>" class="title"><?php echo $_rooms[$k]['name'] ?></a>
				<p class="description"><?php echo $_rooms[$k]['description'] ?></p>
				<div class="lastPost">
					<span><i class="fa fa-comment"></i>&nbsp; <?php echo $_rooms[$k]['title'] ?></span>
					<span><i class="fa fa-user"></i>&nbsp; <a href="index.php?module=profile&amp;id=2"><?php echo $_rooms[$k]['username'] ?></a></span>
					<span><i class="fa fa-clock-o"></i>&nbsp; <?php echo $_rooms[$k]['lastpost_date'] ?></span>
				</div>
			</td>
			<td class="roomStats">
				<div class="item"><i class="fa fa-comments fleft"></i><span class="fright"><?php echo $_rooms[$k]['thread_count'] ?></span></div>
				<div class="item"><i class="fa fa-eye fleft"></i><span class="fright">0</span></div>
			</td>
		</tr>
	</table>
<?php endforeach; ?>

<div class="fright"><a href="#">Mark All As Read</a></div>