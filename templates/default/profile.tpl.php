<div class="roomTitleBar">
	<div class="title fleft"><span><?php echo $this->Core->config['general_communityname'] ?></span>View Profile</div>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="index.php?module=room&amp;id=<?php echo $id ?>" class="transition selected">Profile</a>
		<a href="index.php?module=room&amp;id=<?php echo $id ?>&amp;act=updates" class="transition">Status Updates</a>
	</div>
	<div class="subnav">
		<a href="index.php?module=room&amp;id=<?php echo $id ?>" class="transition">My Profile</a>
		<a href="index.php?module=room&amp;id=<?php echo $id ?>&amp;act=posts" class="transition">Posts &amp; Threads</a>
	</div>
</div>