<div class="title-bar">
	<div class="title fleft">
		<span><?php __($community_name) ?></span>
		<?php __("S_TITLE") ?>
	</div>
</div>


<?php if($num_results): ?>
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
	<?php endforeach; ?>
<?php else: ?>
	<div class="box">
		<form action="search" method="get">
			<div class="input-box">
				<div class="label"><?php __("S_DO") ?></div>
				<div class="field">
					<input type="text" name="q" class="large">
					<div class="fright"><input type="submit" value="<?php __("S_TITLE") ?>"></div>
				</div>
			</div>
		</form>
	</div>
<?php endif; ?>
