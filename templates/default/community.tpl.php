<?php echo $notification ?>

<?php foreach($_rooms as $k => $v): ?>
	<table class="roomItem">
		<tr>
			<td class="image"><?php __($_rooms[$k]['icon']) ?></td>
			<td class="info">
				<a href="index.php?module=room&amp;id=<?php __($_rooms[$k]['r_id']) ?>" class="title"><?php __($_rooms[$k]['name']) ?></a>
				<p class="description"><?php __($_rooms[$k]['description']) ?></p>
				<div class="lastPost">
					<span><i class="fa fa-comment"></i>&nbsp; <?php __($_rooms[$k]['title']) ?></span>
					<span><i class="fa fa-user"></i>&nbsp; <a href="index.php?module=profile&amp;id=<?php __($_rooms[$k]['lastpost_member']) ?>"><?php __($_rooms[$k]['username']) ?></a></span>
					<span><i class="fa fa-clock-o"></i>&nbsp; <?php __($_rooms[$k]['lastpost_date']) ?></span>
				</div>
			</td>
			<td class="roomStats">
				<div class="item"><i class="fa fa-comments fleft"></i><span class="fright"><?php __($_rooms[$k]['thread_count']) ?></span></div>
				<div class="item"><i class="fa fa-eye fleft"></i><span class="fright">0</span></div>
			</td>
		</tr>
	</table>
<?php endforeach; ?>

<div class="fright"><a href="#">Mark All As Read</a></div>