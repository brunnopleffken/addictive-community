<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span>Search</div>
</div>

<div class="box">
	Your search for <b><?php __($keyword) ?></b> returned <b><?php __($numResults) ?></b> results.
</div>

<?php __($warning) ?>

<?php
	if($numResults > 0):
	foreach($_result as $k => $w):
?>

<table class="threadItem search">
	<tr>
		<td class="stats min">
			<div class="label">
				<span class="value">
					<i class="fa fa-user"></i>
					<a href="index.php?module=profile&id=<?php __($_result[$k]['m_id']) ?>" title="Last post by <?php __($_result[$k]['username']) ?>"><?php __($_result[$k]['username']) ?></a>
				</span>
			</div>
		</td>
		<td class="stats min">
			<div class="label">
				<span class="value">
					<i class="fa fa-clock-o"></i>
					<?php __($_result[$k]['post_date']) ?>
				</span>
			</div>
		</td>
		<td class="stats right">
			<div class="label">
				<span class="value">Relevance: <?php __($_result[$k]['relevance']) ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td class="content" colspan="5">
			<a href="index.php?module=thread&amp;id=<?php __($_result[$k]['t_id']) ?>" class="title"><?php __($_result[$k]['title']) ?></a>
			<?php __($_result[$k]['post']) ?>
		</td>
	</tr>
</table>

<?php
	endforeach;
	endif;
?>
