<?php foreach($rooms as $k => $v): ?>
	<table class="room-item">
		<tr>
			<td class="image"><?php __($rooms[$k]['icon']) ?></td>
			<td class="info">
				<a href="room/<?php __($rooms[$k]['r_id']) ?>" class="title"><?php __($rooms[$k]['name']) ?></a>
				<p class="description"><?php __($rooms[$k]['description']) ?></p>
				<div class="last-post">
					<span><i class="fa fa-comment"></i>&nbsp; <?php __($rooms[$k]['title']) ?></span>
					<span><i class="fa fa-user"></i>&nbsp; <a href="profile/<?php __($rooms[$k]['lastpost_member']) ?>"><?php __($rooms[$k]['username']) ?></a></span>
					<span><i class="fa fa-clock-o"></i>&nbsp; <?php __($rooms[$k]['lastpost_date']) ?></span>
				</div>
			</td>
			<td class="room-stats">
				<div class="item"><i class="fa fa-comments fleft"></i><span class="fright"><?php __($rooms[$k]['thread_count']) ?></span></div>
				<div class="item"><i class="fa fa-eye fleft"></i><span class="fright">0</span></div>
			</td>
		</tr>
	</table>
<?php endforeach; ?>