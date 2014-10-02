<?php foreach($_rooms as $k => $v): ?>
	<table class="roomItem">
		<tr>
			<td class="image"><?php __($_rooms[$k]['icon']) ?></td>
			<td class="info">
				<a href="index.php?module=room&amp;id=<?php __($_rooms[$k]['r_id']) ?>" class="title"><?php __($_rooms[$k]['name']) ?></a>
				<p class="description"><?php __($_rooms[$k]['description']) ?></p>
				<div class="lastPost">
					<span><i class="fa fa-fw fa-comment"></i>&nbsp; <?php __($_rooms[$k]['title']) ?></span>
					<span><i class="fa fa-fw fa-clock-o"></i>&nbsp; <?php __($_rooms[$k]['lastpost_date']) ?></span>
				</div>
			</td>
			<td class="roomStats">
				<div class="item"><i class="fa fa-comments"></i><?php __($_rooms[$k]['thread_count']) ?></div>
				<div class="item"><i class="fa fa-eye"></i>0</div>
			</td>
		</tr>
	</table>
<?php endforeach; ?>