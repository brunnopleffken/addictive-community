<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span>Search</div>
</div>

<div class="box">
	Your search for <b><?php __($keyword) ?></b> returned <b><?php __($numResults) ?></b> results.
</div>

<?php
	if($numResults > 0):
	foreach($_result as $k => $w):
?>

<table class="threadItem search">
	<tr>
		<td class="stats min">
			<img src="<?php __($this->p['IMG']) ?>/thread-replier.png">
		</td>
		<td class="stats min">
			<div class="label">
				<span class="value">
					<a href="index.php?module=profile&id=<?php __($_result[$k]['m_id']) ?>" title="{$lang->show['last_post_by']} <?php __($_result[$k]['username']) ?>"><?php __($_result[$k]['username']) ?></a>
				</span>
			</div>
		</td>
		<td class="stats min">
			<img src="<?php __($this->p['IMG']) ?>/thread-date.png">
		</td>
		<td class="stats min">
			<div class="label">
				<span class="value"><a href="#" alt="Post date:"><?php __($_result[$k]['post_date']) ?></a></span>
			</div>
		</td>
		<td class="stats right">
			<div class="label">
				<span class="value"><a href="#" alt="Relevance:">Relevance: <?php __($_result[$k]['relevance']) ?></a></span>
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
