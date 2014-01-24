<div class="roomTitleBar">
	<div class="title fleft"><span><?php echo $this->Core->config['general_communityname'] ?></span>View Profile</div>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="index.php?module=profile&amp;id=<?php echo $id ?>" class="transition selected">Profile</a>
		<a href="index.php?module=profile&amp;id=<?php echo $id ?>&amp;act=updates" class="transition">Status Updates</a>
	</div>
	<div class="subnav">
		<a href="index.php?module=profile&amp;id=<?php echo $id ?>" class="transition">My Profile</a>
		<a href="index.php?module=profile&amp;id=<?php echo $id ?>&amp;act=posts" class="transition">Posts &amp; Threads</a>
	</div>
</div>

<div class="tableLayer profileContainer">
	<div class="box tCell profileSidebar">
		<img src="<?php echo $info['avatar'] ?>" alt="<?php echo $info['username'] ?>">
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
		<span><?php echo $info['member_title'] ?></span>
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
				<td><?php echo $info['signature'] ?></td>
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
				<td class="min"><img src="<?php echo $this->p['IMG'] ?>/profile_icons/email_16.png"></td>
				<td><a href="mailto:<?php echo $info['email'] ?>"><?php echo $info['email'] ?></a></td>
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
	<table class="tableList noBorders noShadow" style="margin-top: 15px" id="facebookNewsFeed">
		<tr>
			<th colspan="2">Last 10 Facebook posts</th>
		</tr>
		
	</table>

	<?php
		break;
		endswitch;
	?>

	</div>
</div>