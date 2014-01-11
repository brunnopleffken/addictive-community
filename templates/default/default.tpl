<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title><?php echo $html['title'] . $this->Core->config['general_communityname'] ?></title>
	<meta name="generator" content="Addictive Community <?php echo VERSION ?>">
	<meta name="description" content="<?php echo $this->Core->config['seo_description'] ?>">
	<meta name="keywords" content="<?php echo $this->Core->config['seo_keywords'] ?>">
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" href="templates/<?php echo $this->info['template'] ?>/css/main.css">
	<script src="resources/jquery-1.10.2.min.js" type="text/javascript"></script>
	<?php echo $this->header ?>
</head>
<body>

	<div id="topbar">
		<div class="wrapper">
			<div class="fleft"><a href="<?php echo $this->Core->config['general_websiteurl'] ?>" target="_blank" class="transition"><?php echo $this->Core->config['general_websitename'] ?></a></div>
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
			<a href="index.php"><img src="templates/<?php echo $this->info['template'] ?>/images/<?php echo $this->Core->config['general_communitylogo'] ?>" class="logo-image"></a>
			<div id="search">
				<form action="index.php" method="get">
					<input type="hidden" name="module" value="search">
					<input type="text" name="q" size="25" placeholder="Search...">
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
					<div class="sidebarItem">
						<div class="user">
							<div class="userInfo">
								<b>Welcome, guest!</b><br>
								<a class="fancybox fancybox.ajax" href="index.php?module=login">Login</a> | <a href="index.php?module=register" class="highlight">Create Account</a>
							</div>
						</div>
					</div>
					
					<div class="sidebarItem">
						<div class="title">Rooms</div>
						<div class="list">
							<div class="item"><a href="index.php?module=room&amp;id=1">General Web Design and Coding</a> <span>8</span></div>
							<div class="item"><a href="index.php?module=room&id=3">Pre-Sales Questions</a> <span>0</span></div>
						</div>
					</div>
					
					<div class="sidebarItem">
						<div class="title">Members Online</div>
						<div class="text">
							<span class="subtitle">Members (1)</span>
							<div class="onlineList"><a href="">Brunno Pleffken</a></div>
							<span class="subtitle" style="margin-top: 10px">Guests (0)</span>
						</div>
					</div>
					
					<div class="sidebarItem">
						<div class="title">Statistics</div>
						<div class="text">
							<span class="statsName fleft">Threads</span><b class="fright">8</b><br>
							<span class="statsName fleft">Replies</span><b class="fright">30</b><br>
							<span class="statsName fleft">Members</span><b class="fright">2</b><br>
							<span class="statsName fleft">Last Member</span><b class="fright"><a href="index.php?module=profile&amp;id=2">Brunno Pleffken</a></b>
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