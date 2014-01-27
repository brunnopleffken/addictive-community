<div class="roomTitleBar">
	<div class="title fleft"><span><?php echo $this->Core->config['general_communityname'] ?></span><?php echo $breadcrumbTitle ?></div>
</div>

<?php
	if(isset($_topics)):
	foreach($_topics as $k => $v):
?>

<div class="box">
	<div style="float:left">
		<img src="<?php echo $this->p['IMG'] ?>/help.png" style="margin-top: 6px; margin-right: 15px;">
	</div>
	<div>
		<b><a href="index.php?module=help&amp;id=<?php echo $_topics[$k]['h_id'] ?>" style="font-size: 14px"><?php echo $_topics[$k]['title'] ?></a></b>
		<br>
		<em><?php echo $_topics[$k]['short_desc'] ?></em>
	</div>
</div>

<?php
	endforeach;
	else:
?>

<div class="box">
	<p><?php echo $_help['content'] ?></p>
</div>

<?php endif; ?>