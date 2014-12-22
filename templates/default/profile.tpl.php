<?php
	$this->header .= '<script type="text/javascript" src="resources/markdown.parser.js"></script>';
	$this->header .= '<script type="text/javascript">$(document).ready(function(){$(\'.parsing\').markdownParser()});</script>';
?>

<div class="profileHeader">
	<div class="background" style="background-image: url('<?php __($info['cover']) ?>')"><div class="cover"></div></div>
	<?php __(Html::Crop($info['avatar'], 160, 160, "avatar")) ?>
	<div class="memberTitle"><?php __($info['member_title']) ?></div>
	<div class="memberInfo">
		<span class="username"><?php __($info['username']) ?></span>
		<div class="item"><span><?php __("P_POSTS") ?></span><?php __($info['posts']) ?></div>
		<div class="item"><span><?php __("P_REGISTERED") ?></span><?php __($info['joined']) ?></div>
		<div class="item"><span><?php __("P_USERGROUP") ?></span><?php __($info['name']) ?></div>
	</div>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="?module=profile&amp;id=<?php __($id) ?>" class="transition selected"><?php __("P_PROFILE") ?></a>
	</div>
	<div class="subnav">
		<a href="?module=profile&amp;id=<?php __($id) ?>" class="transition"><?php __("P_MY_PROFILE") ?></a>
		<a href="?module=profile&amp;id=<?php __($id) ?>&amp;act=posts" class="transition"><?php __("P_LAST_POSTS") ?></a>
		<a href="?module=profile&amp;id=<?php __($id) ?>&amp;act=attachments" class="transition"><?php __("P_ATTACHMENTS") ?></a>
	</div>
</div>

<div class="tableLayer profileContainer">
	<div class="box tCell profileContent">

	<?php
		switch($act):
		case "profile":
	?>

		<!-- MEMBER PROFILE -->
		<table class="tableList noShadow noBorders">
			<tr>
				<th colspan="2"><?php __("P_MY_PROFILE") ?></th>
			</tr>
			<tr>
				<td class="tLabel"><?php __("P_ABOUT_ME") ?></td>
				<td><?php __($info['profile']) ?></td>
			</tr>
			<tr>
				<td class="tLabel"><?php __("P_BIRTHDATE") ?></td>
				<td><?php if($has_birthday) __("P_AGE", array($info['birthday'], $info['age'])) ?></td>
			</tr>
			<tr>
				<td class="tLabel"><?php __("P_GENDER") ?></td>
				<td><?php __($info['gender']) ?></td>
			</tr>
			<tr>
				<td class="tLabel"><?php __("P_LOCATION") ?></td>
				<td><?php __($info['location']) ?></td>
			</tr>
			<tr>
				<td class="tLabel"><?php __("P_WEBSITE") ?></td>
				<td><?php __($info['website']) ?></td>
			</tr>
			<tr>
				<td class="tLabel"><?php __("P_SIGNATURE") ?></td>
				<td class="parsing"><?php __($info['signature']) ?></td>
			</tr>
		</table>
		<table class="tableList noShadow noShadow" style="margin-top: 15px">
			<tr>
				<th colspan="2"><?php __("P_STATS") ?></th>
			</tr>
			<tr>
				<td class="tLabel"><?php __("P_BEST_ANSWERS") ?></td>
				<td><?php __("P_BEST_POSTS", array($info['bestanswers'])) ?></td>
			</tr>
		</table>
		<table class="tableList noShadow noShadow" style="margin-top: 15px">
			<tr>
				<th colspan="2"><?php __("P_CONTACT_INFO") ?></th>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-fw fa-envelope-o"></i></td>
				<td><?php __($info['email']) ?></td>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-fw fa-facebook-square"></i></td>
				<td><a href="https://www.facebook.com/<?php __($info['im_facebook']) ?>" target="_blank" rel="nofollow">fb.com/<?php __($info['im_facebook']) ?></a></td>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-fw fa-twitter"></i></td>
				<td><a href="https://twitter.com/<?php __($info['im_twitter']) ?>" target="_blank" rel="nofollow">@<?php __($info['im_twitter']) ?></a></td>
			</tr>
		</table>

	<?php
		break;
		case "posts":
	?>

	<!-- POSTS & THREADS -->

	<table class="tableList noBorders noShadow">
		<tr>
			<th colspan="2"><?php __("P_LAST_THREADS_BY", array($info['username'])) ?></th>
		</tr>
		<?php __($threadList) ?>
	</table>
	<table class="tableList noBorders noShadow">
		<tr>
			<th colspan="2"><?php __("P_LAST_POSTS_BY", array($info['username'])) ?></th>
		</tr>
		<?php __($postList) ?>
	</table>

	<?php
		break;
		case "attachments":
	?>

	<table class="tableList noShadow">
		<tr>
			<th colspan="4"><?php __("P_ATTACHMENTS") ?></th>
		</tr>
		<tr class="subtitle">
			<td class="min"></td>
			<td><?php __("P_NAME") ?></td>
			<td><?php __("P_FILETYPE") ?></td>
			<td><?php __("P_FILESIZE") ?></td>
		</tr>
		<?php if(isset($attachments)): ?>
			<?php foreach($attachments as $file): ?>
				<tr>
					<td><?php __($file['icon']) ?></td>
					<td><?php __($file['filename']) ?></td>
					<td><?php __($file['type']) ?></td>
					<td><?php __($file['size']) ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="4" class="center"><?php __("P_NO_ATTACHMENTS") ?></td>
			</tr>
		<?php endif; ?>
	</table>

	<?php
		break;
		endswitch;
	?>

	</div>
</div>
