<?php
	$this->header .= '<script type="text/javascript" src="resources/markdown.parser.js"></script>';
	$this->header .= '<script type="text/javascript">$(document).ready(function(){$(\'.parsing\').markdownParser()});</script>';
?>

<!--
<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span>View Profile</div>
</div>
-->

<div class="profileHeader">
	<div class="background" style="background-image: url('<?php __($info['cover']) ?>')"><div class="cover"></div></div>
	<?php __(Html::Crop($info['avatar'], 160, 160, "avatar")) ?>
	<div class="memberInfo">
		<span class="username"><?php __($info['username']) ?></span>
		<div class="item"><span>Posts</span><?php __($info['posts']) ?></div>
		<div class="item"><span>Registered</span><?php __($info['joined']) ?></div>
		<div class="item"><span>Group</span><?php __($info['name']) ?></div>
	</div>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="index.php?module=profile&amp;id=<?php __($id) ?>" class="transition selected">Profile</a>
		<!-- <a href="index.php?module=profile&amp;id=<?php __($id) ?>&amp;act=updates" class="transition">Status Updates</a> -->
	</div>
	<div class="subnav">
		<a href="index.php?module=profile&amp;id=<?php __($id) ?>" class="transition">My Profile</a>
		<a href="index.php?module=profile&amp;id=<?php __($id) ?>&amp;act=posts" class="transition">Posts &amp; Threads</a>
	</div>
</div>

<div class="tableLayer profileContainer">
	<div class="box tCell profileSidebar">
		<div class="reputation">
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star"></i>
			<i class="fa fa-star-o"></i>
		</div>
		<ul class="userInfoList">
			<li><b>Group</b> <?php __($info['name']) ?></li>
			<li><b>Registered</b> <?php __($info['joined']) ?></li>
			<li><b>Posts</b> <?php __($info['posts']) ?> posts</li>
		</ul>
	</div>
	<div class="tSpacer"></div>
	<div class="box tCell profileContent">

	<?php
		switch($act):
		case "profile":
	?>

		<!-- MEMBER PROFILE -->
		<span><em><?php __($info['member_title']) ?></em></span>
		<table class="tableList noShadow noBorders" style="margin-top: 15px">
			<tr>
				<th colspan="2">My Profile</th>
			</tr>
			<tr>
				<td class="tLabel">About Me</td>
				<td><?php __($info['profile']) ?></td>
			</tr>
			<tr>
				<td class="tLabel">Birthday</td>
				<td><?php __($info['birthday']) ?> (<?php __($info['age']) ?> years old)</td>
			</tr>
			<tr>
				<td class="tLabel">Gender</td>
				<td><?php __($info['gender']) ?></td>
			</tr>
			<tr>
				<td class="tLabel">Location</td>
				<td><?php __($info['location']) ?></td>
			</tr>
			<tr>
				<td class="tLabel">Website</td>
				<td><?php __($info['website']) ?></td>
			</tr>
			<tr>
				<td class="tLabel">Signature</td>
				<td class="parsing"><?php __($info['signature']) ?></td>
			</tr>
		</table>
		<table class="tableList noShadow noShadow" style="margin-top: 15px">
			<tr>
				<th colspan="2">Community Statistics</th>
			</tr>
			<tr>
				<td class="tLabel">Best Answers</td>
				<td><?php __($info['bestanswers']) ?> post(s)</td>
			</tr>
		</table>
		<table class="tableList noShadow noShadow" style="margin-top: 15px">
			<tr>
				<th colspan="2">Contact Information</th>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-envelope-o"></i></td>
				<td><?php __($info['email']) ?></td>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-facebook-square"></i></td>
				<td><a href="https://www.facebook.com/<?php __($info['im_facebook']) ?>" target="_blank" rel="nofollow">fb.com/<?php __($info['im_facebook']) ?></a></td>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-twitter"></i></td>
				<td><a href="https://twitter.com/<?php __($info['im_twitter']) ?>" target="_blank" rel="nofollow">@<?php __($info['im_twitter']) ?></a></td>
			</tr>
		</table>

	<?php
		break;
		case "posts":
	?>

	<!-- POSTS & THREADS -->

	<h1><?php __($info['username']) ?></h1>
	<span><?php __($info['member_title']) ?></span>
	<table class="tableList noBorders noShadow" style="margin-top: 15px">
		<tr>
			<th colspan="2">Last 5 threads by <?php __($info['username']) ?></th>
		</tr>
		<?php __($threadList) ?>
	</table>
	<table class="tableList noBorders noShadow">
		<tr>
			<th colspan="2">Last 5 posts by <?php __($info['username']) ?></th>
		</tr>
		<?php __($postList) ?>
	</table>

	<?php
		break;
		case "updates":
	?>

	<!-- STATUS UPDATES -->

	<h1><?php __($info['username']) ?></h1>
	<span><?php __($info['member_title']) ?></span>
	

	<?php
		break;
		endswitch;
	?>

	</div>
</div>