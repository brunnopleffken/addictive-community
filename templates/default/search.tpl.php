<div class="roomTitleBar">
	<div class="title fleft"><span><?php echo $this->Core->config['general_communityname'] ?></span>Search</div>
</div>

<div class="box">
	Your search for <b><?php echo $keyword ?></b> returned <b><?php echo $numResults ?></b> results.
</div>

<?php
	if($numResults > 0):
	foreach($_result as $k => $w):
?>

<table class="threadItem search">
	<tr>
		<td class="stats min">
			<img src="<?php echo $this->p['IMG'] ?>/thread-replier.png">
		</td>
		<td class="stats min">
			<div class="label">
				<span class="value">
					<a href="index.php?module=profile&id=<?php echo $_result[$k]['m_id'] ?>" title="{$lang->show['last_post_by']} <?php echo $_result[$k]['username'] ?>"><?php echo $_result[$k]['username'] ?></a>
				</span>
			</div>
		</td>
		<td class="stats min">
			<img src="<?php echo $this->p['IMG'] ?>/thread-date.png">
		</td>
		<td class="stats min">
			<div class="label">
				<span class="value"><a href="#" alt="Post date:"><?php echo $_result[$k]['post_date'] ?></a></span>
			</div>
		</td>
		<td class="stats right">
			<div class="label">
				<span class="value"><a href="#" alt="Relevance:">Relevance: <?php echo $_result[$k]['relevance'] ?></a></span>
			</div>
		</td>
	</tr>
	<tr>
		<td class="content" colspan="5">
			<a href="index.php?module=thread&amp;id=<?php echo $_result[$k]['t_id'] ?>" class="title"><?php echo $_result[$k]['title'] ?></a>
			<?php echo $_result[$k]['post'] ?>
		</td>
	</tr>
</table>

<?php
	endforeach;
	endif;
?>
