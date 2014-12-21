<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __("S_TITLE") ?></div>
</div>

<div class="box">
	<?php __("S_RESULTS", array($keyword, $numResults)) ?>
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
					<a href="?module=profile&id=<?php __($_result[$k]['m_id']) ?>"><?php __($_result[$k]['username']) ?></a>
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
				<span class="value"><?php __("S_RELEVANCE", array($_result[$k]['relevance'])) ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td class="content" colspan="5">
			<a href="?module=thread&amp;id=<?php __($_result[$k]['t_id']) ?>" class="title"><?php __($_result[$k]['title']) ?></a>
			<?php __($_result[$k]['post']) ?>
		</td>
	</tr>
</table>

<?php
	endforeach;
	endif;
?>
