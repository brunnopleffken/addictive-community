<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $html['title'] . $this->Core->config['general_communityname'] ?> (Powered by Addictive Community)</title>
	<!-- META -->
	<meta name="generator" content="Addictive Community <?php VERSION ?>">
	<meta name="description" content="<?php echo $this->Core->config['seo_description'] ?>">
	<meta name="keywords" content="<?php echo $this->Core->config['seo_keywords'] ?>">
	<link rel="shortcut icon" href="favicon.ico">
	<?php echo $pageinfo['canonical_address'] ?>
	<!-- CSS -->
	<link rel="stylesheet" href="<?php echo $this->p['TPL'] ?>/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo $this->p['TPL'] ?>/css/main.css">
	<link rel="stylesheet" href="resources/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="resources/select2/select2.css">
	<link rel="stylesheet" href="resources/fancybox/jquery.fancybox.css" type="text/css" media="screen">
	<!-- JS -->
	<script src="resources/jquery.min.js" type="text/javascript"></script>
	<script src="resources/bootstrap.js" type="text/javascript"></script>
	<script src="resources/fancybox/jquery.fancybox.pack.js" type="text/javascript"></script>
	<script src="resources/select2/select2.js" type="text/javascript"></script>
	<script src="resources/functions.js" type="text/javascript"></script>
	<script src="resources/main.js" type="text/javascript"></script>
	<?php echo $this->header ?>
</head>
<body>

	<nav class="navbar navbar-default">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo $this->Core->config['general_websiteurl'] ?>"><?php echo $this->Core->config['general_websitename'] ?></a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="?module=search" class="transition"><?php __("SEARCH") ?></a></li>
					<li><a href="?module=members" class="transition"><?php __("MEMBERLIST") ?></a></li>
					<li><a href="?module=calendar" class="transition"><?php __("CALENDAR") ?></a></li>
					<li><a href="?module=help" class="transition"><?php __("HELP") ?></a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					<?php if(@$this->member['m_id'] == 0): ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php __("SIDEBAR_WELCOME") ?><span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a class="fancybox fancybox.ajax" href="?module=login"><?php __("SIDEBAR_LOGIN") ?></a></li>
							<li class="divider"></li>
							<li><a href="?module=register" class="highlight"><?php __("SIDEBAR_C_ACCOUNT") ?></a></li>
						</ul>
					</li>
				<?php else: ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php __(Html::Crop($this->member['avatar'], 20, 20, "img")) ?> <?php __($this->member['username']) ?><span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="?module=usercp"><?php __("SIDEBAR_USERCP") ?></a></li>
							<li><a href="?module=messenger"><?php __("SIDEBAR_INBOX", array($unreadMessages['total'])) ?></a></li>
							<?php if($this->member['usergroup'] == 1): ?>
								<li><a href="admin/" target="_blank" class="transition">Admin CP</a></li>
							<?php endif; ?>
							<li class="divider"></li>
							<li><a href="?module=login&amp;act=logout"><?php __("SIDEBAR_LOGOUT") ?></a></li>
						</ul>
					</li>
				<?php endif; ?>

				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container -->
	</nav>

<div class="container">
	<div class="wrapper">
		<div id="breadcrumb"><a href="index.php"><?php echo $this->Core->config['general_communityname'] ?></a> <?php echo $html['breadcrumb'] ?></div>
	</div>
<!---
	<div class="wrapper">
		<div class="mainWrapper">
			<div class="sidebar">
				<div class="sidebarBg">

					<div class="sidebarItem">
						<div class="title"><?php __("SIDEBAR_ROOMS") ?></div>
						<div class="list">
							<?php foreach($_siderooms as $k => $v): ?>
								<div class="item transition">
									<a href="?module=room&amp;id=<?php echo $_siderooms[$k]['r_id'] ?>">
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
							<span class="statsName fleft"><?php __("SIDEBAR_S_LAST") ?></span><b class="fright"><a href="?module=profile&amp;id=<?php echo $_stats['lastmemberid'] ?>"><?php echo $_stats['lastmembername'] ?></a></b>
							<div class="fix"></div>
						</div>
					</div>
				</div>
			</div>
-->

<div class="row row-offcanvas row-offcanvas-right">

	<!-- sidebar -->

	<!-- main area -->
	<div class="col-xs-12 col-sm-9">
		<?php echo $this->content ?>
	</div><!-- /.col-xs-12 main -->

	<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
		<div class="panel panel-default">
			<div class="panel-footer"><?php __("SIDEBAR_STATISTICS") ?></div>

			<div class="panel-body">
				<p><strong><?php __("SIDEBAR_S_THREADS") ?>: </strong> <span class="label label-primary"><?php echo $_stats['threads'] ?></span></p>
				<p><strong><?php __("SIDEBAR_S_REPLIES") ?>: </strong> <span class="label label-primary"><?php echo $_stats['replies'] ?></span></p>
				<p><strong><?php __("SIDEBAR_S_MEMBERS") ?>: </strong> <span class="label label-primary"><?php echo $_stats['members'] ?></span></p>
				<p><strong><?php __("SIDEBAR_S_LAST") ?>: </strong> <span class="label label-success"><a href="?module=profile&amp;id=<?php echo $_stats['lastmemberid'] ?>"><?php echo $_stats['lastmembername'] ?></a></span></p>

			</div>
		</div>

		<ul class="nav">
			<li class="active"><a href="#">Home</a></li>
			<li><a href="#">Link 1</a></li>
			<li><a href="#">Link 2</a></li>
			<li><a href="#">Link 3</a></li>
		</ul>
	</div>
</div><!--/.row-->

		</div>
	</div>
</div><!-- /.container -->
	<div id="footer">
		<div class="wrapper center">
			Powered by Addictive Community <?php echo VERSION ?> &copy; <?php echo date("Y") ?> - All rights reserved.
		</div>
	</div>

</body>
</html>
