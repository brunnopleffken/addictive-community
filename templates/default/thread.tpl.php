<?php
	$this->header .= '<script type="text/javascript" src="resources/markdown.parser.js"></script>';
	$this->header .= '<script type="text/javascript">$(document).ready(function(){$(\'.parsing\').markdownParser()});</script>';
?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="mainPost">
	<div class="header">
		<div class="author">
			<a href="#"><?php __(Html::Crop($firstPostInfo['avatar'], 42, 42, "avatar")) ?></a>
			<div class="authorInfo">
				<a href="index.php?module=profile&amp;id=<?php __($firstPostInfo['author_id']) ?>"><?php __($firstPostInfo['username']) ?></a><br>
				<?php __($firstPostInfo['member_title']) ?><br>
				<?php __($firstPostInfo['posts']) ?> posts
			</div>
			<div class="fright" style="margin-top: 12px">
				<div class="fb-share-button" data-href="<?php echo Html::CurrentUrl() ?>" data-type="button_count"></div>
			</div>
			<div class="fright" style="margin-top: 12px">
				<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
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
		<?php if($this->Session->sInfo['member_id'] != 0): ?>
			<p class="fright"><i class="fa fa-warning"></i> <a href="index.php?module=report&amp;t_id=<?php __($threadId) ?>" class="fancybox fancybox.ajax">Report abuse</a></p>
		<?php endif; ?>
	</div>
</div>

<div class="threadButtons">
	<p class="replies fleft">Replies: <span><?php __($threadInfo['post_count_display']) ?></span></p>
	<div class="fright">
		<?php if($threadInfo['locked'] == 0 && $allowToReply): ?>
			<a href="index.php?module=post&amp;act=thread&amp;id=<?php __($threadId) ?>" class="defaultButton transition">Add Reply</a>
		<?php else: ?>
			<a href="#" class="defaultButton disabled transition">Locked</a>
		<?php endif; ?>
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
				<?php __(Html::Crop($_replyResult[$k]['avatar'], 96, 96, "avatar")) ?>
			</a>
		</div>
		<p class="name"><a href="index.php?module=profile&amp;id=<?php __($_replyResult[$k]['author_id']) ?>"><?php __($_replyResult[$k]['username']) ?></a></p>
		<p class="memberTitle"><?php __($_replyResult[$k]['member_title']) ?></p>
		<ul class="userInfo">
			<li><b>Posts</b> <?php __($_replyResult[$k]['posts']) ?> posts</li>
			<li><b>Registered</b> <?php __($_replyResult[$k]['joined']) ?></li>
			<li><b>Location</b> <?php __($_replyResult[$k]['location']) ?></li>
		</ul>
	</div>
	<div class="content">
		<div class="date">Posted on <?php __($_replyResult[$k]['post_date']) ?> <?php __($_replyResult[$k]['edited']) ?></div>
		<div class="text">
			<span class="parsing"><?php __($_replyResult[$k]['post']) ?></span>
			<div class="signature parsing"><?php __($_replyResult[$k]['signature']) ?></div>
		</div>
		<div class="footer">
			<div class="fleft">
				<a href="index.php?module=report&amp;t_id=<?php __($threadId) ?>&amp;p_id=<?php __($_replyResult[$k]['p_id']) ?>" class="smallButton grey transition fancybox fancybox.ajax">Report this post</a>
			</div>
			<div class="fright">
				<a href="" class="smallButton grey transition">Edit</a>
				<a href="" class="smallButton grey transition">Set as Best Answer</a>
			</div>
		</div>
	</div>
</div>

<?php endforeach; ?>

<div class="threadButtons">
	<div class="fleft"><?php __($pagination) ?></div>
	<div class="fright">
		<a href="index.php?module=post&amp;act=thread&amp;id=<?php __($threadId) ?>" class="defaultButton transition">Add Reply</a>
	</div>
</div>

<?php endif; ?>

<div class="relatedThreads">
	<h2>Related Threads</h2>
	<?php
		if($_relatedThreadList):
		foreach($_relatedThreadList as $k => $v):
	?>
		<div class="item"><span><?php __($_relatedThreadList[$k]['thread_date']) ?></span><a href="index.php?module=thread&amp;id=<?php echo $_relatedThreadList[$k]['t_id'] ?>"><?php __($_relatedThreadList[$k]['title']) ?></a></div>
	<?php
		endforeach;
		else:
	?>
		<div class="item"><span>There are no related threads to show.</span></div>
	<?php endif; ?>
</div>