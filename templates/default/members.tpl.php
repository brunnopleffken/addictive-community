<div class="roomTitleBar">
	<div class="title fleft"><span><?php echo $this->Core->config['general_communityname'] ?></span>Member List</div>
</div>

<div class="classification">
	<?php echo $first ?>
	<?php echo $letterList ?>
</div>

<table class="tableList">
	<tr>
		<th colspan="7">
			<div class="fright">
				<a href="index.php?module=members" class="smallButton grey white transition">Member Name</a>
				<a href="index.php?module=members&amp;order=join" class="smallButton grey white transition">Join Date</a>
				<a href="index.php?module=members&amp;order=post" class="smallButton grey white transition">Post Count</a>
			</div>
		</th>
	</tr>
	<tr class="subtitle">
		<td class="min">&nbsp;</td>
		<td>Member Name</td>
		<td>Joined</td>
		<td>Posts</td>
		<td>Location</td>
		<td>Group</td>
		<td class="min">&nbsp;</td>
	</tr>

	<?php
		if($numResults > 0):
		foreach($_result as $k => $v):
	?>

	<tr>
		<td class="min"><img src="<?php echo $_result[$k]['avatar'] ?>" class="avatar"></td>
		<td style="font-size: 14px;"><a href="index.php?module=profile&amp;id=<?php echo $_result[$k]['m_id'] ?>"><b><?php echo $_result[$k]['username'] ?></b></a></td>
		<td><?php echo $_result[$k]['joined'] ?></td>
		<td><?php echo $_result[$k]['posts'] ?></td>
		<td><?php echo $_result[$k]['location'] ?></td>
		<td><?php echo $_result[$k]['name'] ?></td>
		<td class="min"><a href="index.php?module=profile&amp;id=1"><img src="templates/<?php echo $this->info['template'] ?>/images/view_profile.png"></a></td>
	</tr>
	
	<?php
		endforeach;
		else:
	?>

	<tr>
		<td colspan="7" class="center">Your search returned no results.</td>
	</tr>

	<?php
		endif;
	?>

</table>

<div class="box">
	<form action="index.php" class="validate">
		Search by name: <input type="text" name="" class="small required">
		<input type="submit" value="OK">
	</form>
</div>