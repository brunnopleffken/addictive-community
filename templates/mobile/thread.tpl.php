<?php
	$this->header .= '<script type="text/javascript" src="resources/markdown.parser.js"></script>';
	$this->header .= '<script type="text/javascript">$(document).ready(function(){$(\'.parsing\').markdownParser()});</script>';
?>

<div class="mainPost">
	<div class="header">
		<div class="author">
			<a href="#"><?php __(Html::Crop($firstPostInfo['avatar'], 42, 42, "avatar")) ?></a>
			<div class="authorInfo">
				<a href="index.php?module=profile&amp;id=<?php __($firstPostInfo['author_id']) ?>"><?php __($firstPostInfo['username']) ?></a><br>
				<?php __($firstPostInfo['member_title']) ?><br>
				<?php __($firstPostInfo['posts']) ?> posts
			</div>
		</div>
	</div>
	<div>
		<p class="title"><?php __($firstPostInfo['title']) ?></p>
		<div class="text">
			<span class="parsing"><?php __($firstPostInfo['post']) ?></span>
		</div>
	</div>
	<div class="footer">
		<p class="fleft"><i class="fa fa-clock-o"></i> Posted on <?php __($firstPostInfo['post_date']) ?></p>
	</div>
</div>

<?php
	if(isset($_replyResult)):
	foreach($_replyResult as $k => $v):
?>

<div class="postReply <?php __($_replyResult[$k]['bestanswer_class']) ?>">
	<div class="author">
		<div class="photostack">
			<a href="index.php?module=profile&amp;id=<?php __($_replyResult[$k]['author_id']) ?>">
				<?php __(Html::Crop($_replyResult[$k]['avatar'], 40, 40, "avatar")) ?>
			</a>
		</div>
		<p class="name"><a href="index.php?module=profile&amp;id=<?php __($_replyResult[$k]['author_id']) ?>"><?php __($_replyResult[$k]['username']) ?></a></p>
		<ul class="userInfo">
			<li><b>Posts</b> <?php __($_replyResult[$k]['posts']) ?> posts</li>
			<li><b>Location</b> <?php __($_replyResult[$k]['location']) ?></li>
		</ul>
	</div>
	<div class="content">
		<div class="text">
			<span class="parsing"><?php __($_replyResult[$k]['post']) ?></span>
		</div>
		<div class="footer">
			<div class="date">Posted on <?php __($_replyResult[$k]['post_date']) ?> <?php __($_replyResult[$k]['edited']) ?></div>
		</div>
	</div>
</div>

<?php endforeach; ?>

<div class="threadButtons">
	<div class="fleft"><?php __($pagination) ?></div>
</div>

<?php endif; ?>	