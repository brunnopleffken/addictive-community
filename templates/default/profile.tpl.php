<?php
	$this->header .= '<script type="text/javascript" src="resources/markdown.parser.js"></script>';
	$this->header .= '<script type="text/javascript">$(document).ready(function(){$(\'.parsing\').markdownParser()});</script>';
?>

<div class="roomTitleBar">
	<div class="title fleft"><span><?php echo $this->Core->config['general_communityname'] ?></span>View Profile</div>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="index.php?module=profile&amp;id=<?php echo $id ?>" class="transition selected">Profile</a>
		<!-- <a href="index.php?module=profile&amp;id=<?php echo $id ?>&amp;act=updates" class="transition">Status Updates</a> -->
	</div>
	<div class="subnav">
		<a href="index.php?module=profile&amp;id=<?php echo $id ?>" class="transition">My Profile</a>
		<a href="index.php?module=profile&amp;id=<?php echo $id ?>&amp;act=posts" class="transition">Posts &amp; Threads</a>
	</div>
</div>

<div class="tableLayer profileContainer">
	<div class="box tCell profileSidebar">
		<?php echo Html::Crop($info['avatar'], 160, 160, "avatar") ?>
		<ul class="userInfoList">
			<li><b>Group</b> <?php echo $info['name'] ?></li>
			<li><b>Registered</b> <?php echo $info['joined'] ?></li>
			<li><b>Posts</b> <?php echo $info['posts'] ?> posts</li>
		</ul>
	</div>
	<div class="tSpacer"></div>
	<div class="box tCell profileContent">

	<?php
		switch($act):
		case "profile":
	?>

	<!-- MEMBER PROFILE -->

		<h1><?php echo $info['username'] ?></h1>
		<span><em><?php echo $info['member_title'] ?></em></span>
		<table class="tableList noShadow noBorders" style="margin-top: 15px">
			<tr>
				<th colspan="2">My Profile</th>
			</tr>
			<tr>
				<td class="tLabel">About Me</td>
				<td><?php echo $info['profile'] ?></td>
			</tr>
			<tr>
				<td class="tLabel">Birthday</td>
				<td><?php echo $info['birthday'] ?> (<?php echo $info['age'] ?> years old)</td>
			</tr>
			<tr>
				<td class="tLabel">Gender</td>
				<td><?php echo $info['gender'] ?></td>
			</tr>
			<tr>
				<td class="tLabel">Location</td>
				<td><?php echo $info['location'] ?></td>
			</tr>
			<tr>
				<td class="tLabel">Website</td>
				<td><?php echo $info['website'] ?></td>
			</tr>
			<tr>
				<td class="tLabel">Signature</td>
				<td class="parsing"><?php echo $info['signature'] ?></td>
			</tr>
		</table>
		<table class="tableList noShadow noShadow" style="margin-top: 15px">
			<tr>
				<th colspan="2">Community Statistics</th>
			</tr>
			<tr>
				<td class="tLabel">Best Answers</td>
				<td><?php echo $info['bestanswers'] ?> post(s)</td>
			</tr>
		</table>
		<table class="tableList noShadow noShadow" style="margin-top: 15px">
			<tr>
				<th colspan="2">Contact Information</th>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-envelope-o"></i></td>
				<td><a href="mailto:<?php echo $info['email'] ?>"><?php echo $info['email'] ?></a></td>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-facebook-square"></i></td>
				<td><a href="http://www.facebook.com/<?php echo $info['im_facebook'] ?>" target="_blank" rel="nofollow">fb.com/<?php echo $info['im_facebook'] ?></a></td>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-twitter"></i></td>
				<td><a href="http://twitter.com/<?php echo $info['im_twitter'] ?>" target="_blank" rel="nofollow">@<?php echo $info['im_twitter'] ?></a></td>
			</tr>
		</table>

	<?php
		break;
		case "posts":
	?>

	<!-- POSTS & THREADS -->

	<h1><?php echo $info['username'] ?></h1>
	<span><?php echo $info['member_title'] ?></span>
	<table class="tableList noBorders noShadow" style="margin-top: 15px">
		<tr>
			<th colspan="2">Last 5 threads by <?php echo $info['username'] ?></th>
		</tr>
		<?php echo $threadList ?>
	</table>
	<table class="tableList noBorders noShadow">
		<tr>
			<th colspan="2">Last 5 posts by <?php echo $info['username'] ?></th>
		</tr>
		<?php echo $postList ?>
	</table>

	<?php
		break;
		case "updates":
	?>

	<!-- STATUS UPDATES -->

	<h1><?php echo $info['username'] ?></h1>
	<span><?php echo $info['member_title'] ?></span>
	

	<?php
		break;
		endswitch;
	?>

	</div>
</div>