<!DOCTYPE html>
<html>
<head>
	<title>Teste</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="shortcut icon" href="favicon.ico">
	<!-- CSS -->
	<link rel="stylesheet" href="<?php __($this->p['TPL']) ?>/css/mobile.css">
	<link rel="stylesheet" href="resources/font-awesome/css/font-awesome.min.css">
	<!-- JS -->
	<script src="resources/jquery.min.js" type="text/javascript"></script>
	<script src="resources/mobile.js" type="text/javascript"></script>
	<script src="resources/functions.js" type="text/javascript"></script>
	<?php __($this->header) ?>
</head>
<body>
	<header>
		<i class="fa fa-bars left" id="menu"></i>
		<h1><?php __($this->Core->config['general_communityname']) ?></h1>
	</header>
	<div id="dropdownMenu">
		<ul class="unlogged">
			<li><a href="index.php"><i class="fa fa-home"></i><span>Home</span></a></li>
			<li><a href="index.php?module=login"><i class="fa fa-user"></i><span>Login</span></a></li>
			<li><a href="index.php?module=search"><i class="fa fa-search"></i><span>Search</span></a></li>
		</ul>
	</div>
	
	<section>
		<?php __($this->content) ?>
	</section>
	
	<footer>
		Teste
	</footer>
</body>
</html>