<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __($roomInfo['name']) ?></div>
</div>

<?php
	## THREAD ROW ##
	if(isset($_thread)):
	foreach($_thread as $k => $v):
?>

<table class="threadItem <?php __($_thread[$k]['class']) ?>">
	<tr>
		<td class="content">
			<a href="index.php?module=thread&amp;id=<?php __($_thread[$k]['t_id']) ?>" class="title"><?php __($_thread[$k]['title']) ?></a>
			<div class="author">
				<i class="fa fa-user"></i>
				By <?php __($_thread[$k]['author_name']) ?>
				<i class="fa fa-clock-o"></i>
				<?php __($_thread[$k]['mobile_start_date']) ?>
			</div>
		</td>
		<td class="info"><i class="fa fa-comments"></i><br><?php __($_thread[$k]['replies']) ?></td>
	</tr>
</table>

<?php
	endforeach;
	else:
	## NO THREADS ##
?>

<div class="threadItem">
	<div class="center">There are no threads to be shown. What about starting a new one?</div>
</div>

<?php
	endif;
?>