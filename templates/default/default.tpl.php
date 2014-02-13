<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title><?php echo $html['title'] . $this->Core->config['general_communityname'] ?> (Powered by Addictive Community)</title>
	<meta name="generator" content="Addictive Community <?php echo VERSION ?>">
	<meta name="description" content="<?php echo $this->Core->config['seo_description'] ?>">
	<meta name="keywords" content="<?php echo $this->Core->config['seo_keywords'] ?>">
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" href="<?php echo $this->p['TPL'] ?>/css/main.css">
	<link rel="stylesheet" href="resources/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="resources/select2/select2.css">
	<link rel="stylesheet" href="resources/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script src="resources/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="resources/main.js" type="text/javascript"></script>
	<script src="resources/functions.js" type="text/javascript"></script>
	<script src="resources/fancybox/jquery.fancybox.pack.js?v=2.1.5" type="text/javascript"></script>
	<script src="resources/select2/select2.js" type="text/javascript"></script>
	<?php echo $this->header ?>
</head>
<body>

	<div id="topbar">
		<div class="wrapper">
			<div class="fleft">
				<a href="<?php echo $this->Core->config['general_websiteurl'] ?>" target="_blank" class="transition">
					<?php echo $this->Core->config['general_websitename'] ?>
				</a>
			</div>
			<div class="fright">
				<a href="index.php?module=search" class="transition">Search</a>
				<a href="index.php?module=members" class="transition">Member List</a>
				<a href="index.php?module=calendar" class="transition">Calendar</a>
				<a href="index.php?module=help" class="transition">Help</a>
			</div>
			<div class="fix"></div>
		</div>
	</div>

	<div id="logo">
		<div class="wrapper">
			<a href="index.php"><img src="<?php echo $this->p['IMG'] . "/" . $this->Core->config['general_communitylogo'] ?>" class="logo"></a>
			<div id="search">
				<form action="index.php" method="get">
					<input type="text" name="q" size="25" placeholder="Search...">
					<input type="hidden" name="module" value="search">
					<input type="submit" value="OK">
				</form>
			</div>
		</div>
	</div>

	<div class="wrapper">
		<div id="breadcrumb"><a href="index.php">Addictive Community</a> <?php echo $html['breadcrumb'] ?></div>
	</div>

	<div class="wrapper">
		<div class="mainWrapper">
			<div class="sidebar">
				<div class="sidebarBg">
					
					<?php if(@$this->member['m_id'] == 0): ?>

					<div class="sidebarItem">
						<div class="user">
							<div class="userInfo">
								<b>Welcome, guest!</b><br>
								<a class="fancybox fancybox.ajax" href="index.php?module=login">Login</a> | <a href="index.php?module=register" class="highlight">Create Account</a>
							</div>
						</div>
					</div>
					
					<?php else: ?>
					
					<div class="sidebarItem">
						<div class="user">
							<div class="avatar">
								<?php echo Html::Crop($this->member['avatar'], 30, 30, "img") ?>
							</div>
							<div class="userInfo">
								<b><a href="index.php?module=profile&id=<?php echo $this->member['m_id'] ?>" title="Show <?php echo $this->member['username'] ?>'s profile"><?php echo $this->member['username'] ?></a></b><br>
								<a href="index.php?module=usercp">Control Panel</a> | <a href="index.php?module=messenger">Inbox (0)</a> | <a href="index.php?module=login&amp;act=logout">Logout</a>
							</div>
						</div>
					</div>
					
					<?php endif; ?>

					<div class="sidebarItem">
						<div class="title">Rooms</div>
						<div class="list">
							<?php foreach($_siderooms as $k => $v): ?>
								<div class="item">
									<a href="index.php?module=room&amp;id=<?php echo $_siderooms[$k]['r_id'] ?>">
										<?php echo $_siderooms[$k]['name'] ?>
									</a>
									<span><?php echo $_siderooms[$k]['threads'] ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					
					<div class="sidebarItem">
						<div class="title">Members Online</div>
						<div class="text">
							<span class="subtitle">Members (<?php echo $memberCount ?>)</span>
							<div class="onlineList"><?php echo $memberList ?></div>
							<span class="subtitle" style="margin-top: 10px">Guests (<?php echo $guestsCount ?>)</span>
						</div>
					</div>
					
					<div class="sidebarItem">
						<div class="title">Statistics</div>
						<div class="text">
							<span class="statsName fleft">Threads</span><b class="fright"><?php echo $_stats['threads'] ?></b><br>
							<span class="statsName fleft">Replies</span><b class="fright"><?php echo $_stats['replies'] ?></b><br>
							<span class="statsName fleft">Members</span><b class="fright"><?php echo $_stats['members'] ?></b><br>
							<span class="statsName fleft">Last Member</span><b class="fright"><a href="index.php?module=profile&amp;id=<?php echo $_stats['lastmemberid'] ?>"><?php echo $_stats['lastmembername'] ?></a></b>
							<div class="fix"></div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="content">
				<?php echo $this->content ?>
			</div>
		</div>
	</div>

	<div id="footer">
		<div class="wrapper">
			<span class="fleft"><a href="#" class="transition">Delete All Cookies</a> | <a href="#" class="transition">Mark All As Read</a></span>
			<span class="fright">Powered by Addictive Community <?php echo VERSION ?> &copy; 2012 - All rights reserved.</span>
		</div>
	</div>

</body>
</html>