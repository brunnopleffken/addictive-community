<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __($breadcrumbTitle) ?></div>
</div>

<?php
	if(isset($_topics)):
		foreach($_topics as $k => $v):
?>

<div class="box">
	<div style="float:left">
		<img src="<?php __($this->p['IMG']) ?>/help.png" style="margin-top: 6px; margin-right: 15px;">
	</div>
	<div>
		<b><a href="index.php?module=help&amp;id=<?php __($_topics[$k]['h_id']) ?>" style="font-size: 14px"><?php __($_topics[$k]['title']) ?></a></b>
		<br>
		<em><?php __($_topics[$k]['short_desc']) ?></em>
	</div>
</div>

<?php
	endforeach;
	else:
?>

<div class="box">
	<p><?php __($_help['content']) ?></p>
</div>

<?php endif; ?>