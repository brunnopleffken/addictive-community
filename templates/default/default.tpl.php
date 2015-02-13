<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $html['title'] . $this->Core->config['general_communityname'] ?> (Powered by Addictive Community)</title>
	<base href="<?php echo $this->Core->config['general_communityurl'] ?>">
	<!-- META -->
	<meta name="generator" content="Addictive Community <?php VERSION ?>">
	<meta name="description" content="<?php echo $this->Core->config['seo_description'] ?>">
	<meta name="keywords" content="<?php echo $this->Core->config['seo_keywords'] ?>">
	<link rel="shortcut icon" href="favicon.ico">
	<?php echo $pageinfo['canonical_address'] ?>
	<!-- CSS -->
	<link rel="stylesheet" href="<?php echo $this->p['TPL'] ?>/css/main.css">
	<link rel="stylesheet" href="resources/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="resources/select2/select2.css">
	<link rel="stylesheet" href="resources/fancybox/jquery.fancybox.css" type="text/css" media="screen">
	<!-- JS -->
	<script src="resources/jquery.min.js" type="text/javascript"></script>
	<script src="resources/fancybox/jquery.fancybox.pack.js" type="text/javascript"></script>
	<script src="resources/select2/select2.js" type="text/javascript"></script>
	<script src="resources/functions.js" type="text/javascript"></script>
	<script src="resources/main.js" type="text/javascript"></script>
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
				<?php if($this->member['usergroup'] == 1): ?>
					<a href="admin/" target="_blank" class="transition">Admin CP</a>
				<?php endif; ?>
				<a href="search" class="transition"><?php __("SEARCH") ?></a>
				<a href="members" class="transition"><?php __("MEMBERLIST") ?></a>
				<a href="calendar" class="transition"><?php __("CALENDAR") ?></a>
				<a href="help" class="transition"><?php __("HELP") ?></a>
			</div>
			<div class="fix"></div>
		</div>
	</div>

	<div id="logo">
		<div class="wrapper">
			<a href="#"><img src="<?php echo $this->p['IMG'] . "/" . $this->Core->config['general_communitylogo'] ?>" class="logo" alt="<?php echo $this->Core->config['general_communityname'] ?>"></a>
			<div id="search">
				<form action="index.php" method="get" class="validate">
					<input type="text" name="q" size="25" class="required" value="<?php echo Html::Request("q") ?>" placeholder="<?php __("SEARCH_BOX") ?>">
					<input type="hidden" name="module" value="search">
					<input type="submit" class="transition" value="OK">
				</form>
			</div>
		</div>
	</div>

	<div class="wrapper">
		<div id="breadcrumb"><a href="#"><?php echo $this->Core->config['general_communityname'] ?></a> <?php echo $html['breadcrumb'] ?></div>
	</div>

	<div class="wrapper">
		<div class="mainWrapper">
			<div class="sidebar">
				<div class="sidebarBg">

					<?php if(@$this->member['m_id'] == 0): ?>
						<div class="sidebarItem">
							<div class="user">
								<div class="userInfo">
									<b><?php __("SIDEBAR_WELCOME") ?></b><br>
									<a class="fancybox fancybox.ajax" href="login"><?php __("SIDEBAR_LOGIN") ?></a> | <a href="register" class="highlight"><?php __("SIDEBAR_C_ACCOUNT") ?></a>
								</div>
							</div>
						</div>
					<?php else: ?>
						<div class="sidebarItem">
							<div class="user">
								<div class="avatar">
									<?php __(Html::Crop($this->member['avatar'], 30, 30, "img")) ?>
								</div>
								<div class="userInfo">
									<b><a href="profile/<?php echo $this->member['m_id'] ?>" title="<?php __("SIDEBAR_PROFILE", array($this->member['username'])) ?>"><?php __($this->member['username']) ?></a></b><br>
									<a href="usercp"><?php __("SIDEBAR_USERCP") ?></a> | <a href="messenger"><?php __("SIDEBAR_INBOX", array($unreadMessages['total'])) ?></a> | <a href="logout"><?php __("SIDEBAR_LOGOUT") ?></a>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<div class="sidebarItem">
						<div class="title"><?php __("SIDEBAR_ROOMS") ?></div>
						<div class="list">
							<?php foreach($_siderooms as $k => $v): ?>
								<div class="item transition">
									<a href="room/<?php echo $_siderooms[$k]['r_id'] ?>">
										<?php echo $_siderooms[$k]['name'] ?>
									</a>
									<span><?php echo $_siderooms[$k]['threads'] ?></span>
									<?php if($_siderooms[$k]['password']): ?><i class="fa fa-lock"></i><?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="sidebarItem">
						<div class="title"><?php __("SIDEBAR_MEMBERS_ONLINE") ?></div>
						<div class="text">
							<span class="subtitle"><?php __("SIDEBAR_MEMBER_COUNT", array($memberCount)) ?></span>
							<div class="onlineList"><?php echo $memberList ?></div>
							<span class="subtitle" style="margin-top: 10px"><?php __("SIDEBAR_GUEST_COUNT", array($guestsCount)) ?></span>
						</div>
					</div>

					<div class="sidebarItem">
						<div class="title"><?php __("SIDEBAR_STATISTICS") ?></div>
						<div class="text">
							<span class="statsName fleft"><?php __("SIDEBAR_S_THREADS") ?></span><b class="fright"><?php echo $_stats['threads'] ?></b><br>
							<span class="statsName fleft"><?php __("SIDEBAR_S_REPLIES") ?></span><b class="fright"><?php echo $_stats['replies'] ?></b><br>
							<span class="statsName fleft"><?php __("SIDEBAR_S_MEMBERS") ?></span><b class="fright"><?php echo $_stats['members'] ?></b><br>
							<span class="statsName fleft"><?php __("SIDEBAR_S_LAST") ?></span><b class="fright"><a href="profile/<?php echo $_stats['lastmemberid'] ?>"><?php echo $_stats['lastmembername'] ?></a></b>
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
		<div class="wrapper center">
			Powered by Addictive Community <?php echo VERSION ?> &copy; <?php echo date("Y") ?> - All rights reserved.
		</div>
	</div>

</body>
</html>
