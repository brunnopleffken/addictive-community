<div class="room-title-bar">
	<div class="title fleft">
		<span><?php __($community_name) ?></span>
		<?php __("S_TITLE") ?>
	</div>
</div>

<div class="box">
	<?php __("S_RESULTS", array($keyword, $num_results)) ?>
</div>

<?php __($warning) ?>

<?php foreach($results as $result): ?>
	<table class="table-list search">
		<tr>
			<th>Thread: "<?php __($result['title']) ?>"</th>
		</tr>
		<tr class="subtitle">
			<td>
				<span class="value">
					<i class="fa fa-user"></i>
					<a href="profile/<?php __($result['m_id']) ?>"><?php __($result['username']) ?></a>
				</span>
				<span class="value">
					<i class="fa fa-clock-o"></i>
					<?php __($result['post_date']) ?>
				</span>
				<div class="label fright">
					<span class="value"><?php __("S_RELEVANCE", array($result['relevance'])) ?></span>
				</div>
			</td>
		</tr>
		<tr>
			<td class="content"><?php __($result['post']) ?></td>
		</tr>
	</table>
	<!--
	<table class="thread-item search">
		<tr>
			<td class="stats min">
				<div class="label">
					<span class="value">
						<i class="fa fa-user"></i>
						<a href="profile/<?php __($result['m_id']) ?>"><?php __($result['username']) ?></a>
					</span>
				</div>
			</td>
			<td class="stats min">
				<div class="label">
					<span class="value">
						<i class="fa fa-clock-o"></i>
						<?php __($result['post_date']) ?>
					</span>
				</div>
			</td>
			<td class="stats right">
				<div class="label">
					<span class="value"><?php __("S_RELEVANCE", array($result['relevance'])) ?></span>
				</div>
			</td>
		</tr>
		<tr>
			<td class="middle" colspan="5">
				<a href="thread/<?php __($result['t_id']) ?>" class="title"><?php __($result['title']) ?></a>
				<?php __($result['post']) ?>
			</td>
		</tr>
	</table>
	-->
<?php endforeach; ?>
