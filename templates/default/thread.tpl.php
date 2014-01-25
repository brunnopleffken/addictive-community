<div id="fb-root" style="display: none"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="mainPost">
	<div class="header">
		<div class="author">
			<a href="#"><img src="<?php echo $firstPostInfo['avatar'] ?>"></a>
			<div class="authorInfo">
				<div>
					<a href="index.php?module=profile&amp;id=<?php echo $firstPostInfo['author_id'] ?>"><?php echo $firstPostInfo['username'] ?></a><br>
					<?php echo $firstPostInfo['member_title'] ?><br>
					<?php echo $firstPostInfo['posts'] ?> posts
				</div>
			</div>
			<div class="fright" style="margin-top: 12px">	 
				<div class="fb-share-button" data-href="http://www.underthelight.com.br" data-type="button_count"></div>
			</div>
		</div>
	</div>
	<div>
		<p class="title"><?php echo $firstPostInfo['title'] ?></p>
		<div class="text">
			<?php echo $firstPostInfo['post_html'] ?>
		</div>
	</div>
	<!-- <div class="footer">
		<p class="fleft" title="Tags"><img src="<?php echo $this->p['IMG'] ?>/post-tags.png"> {$first_post_info['tags']}</p>
	</div> -->
	<div class="footer">
		<p class="fleft"><img src="<?php echo $this->p['IMG'] ?>/post-clock.png"> Posted on <?php echo $firstPostInfo['post_date'] ?></p>
		<p class="fright"><img src="<?php echo $this->p['IMG'] ?>/report.png"> <a href="index.php?module=report&amp;t_id=<?php echo $threadId ?>" class="fancybox fancybox.ajax">Report abuse</a></p>
	</div>
</div>

<div class="threadButtons">
	<p class="replies fleft">Replies: <span><?php echo $threadInfo['post_count']-1 ?></span></p>
	<div class="fright">
		<a href="index.php?module=post&amp;act=thread&amp;id=<?php echo $threadId ?>" class="defaultButton transition">Add Reply</a>
	</div>
</div>

<?php
	if(isset($_replyResult)):
	foreach($_replyResult as $k => $v):
?>

<div class="postReply <?php echo $_replyResult[$k]['bestanswer_class'] ?>">
	<div class="author">
		<div class="photostack"><a href="index.php?module=profile&amp;id=<?php echo $_replyResult[$k]['author_id'] ?>"><img src="<?php echo $_replyResult[$k]['avatar'] ?>"></a></div>
		<p class="name"><a href="index.php?module=profile&amp;id=<?php echo $_replyResult[$k]['author_id'] ?>"><?php echo $_replyResult[$k]['username'] ?></a></p>
		<p class="memberTitle"><?php echo $_replyResult[$k]['member_title'] ?></p>
		<ul class="userInfo">
			<li><b>Posts</b> <?php echo $_replyResult[$k]['posts'] ?> posts</li>
			<li><b>Registered</b> <?php echo $_replyResult[$k]['joined'] ?></li>
			<li><b>Location</b> <?php echo $_replyResult[$k]['location'] ?></li>
		</ul>
	</div>
	<div class="content">
		<div class="date">Posted on <?php echo $_replyResult[$k]['post_date'] ?> <?php echo $_replyResult[$k]['edited'] ?></div>
		<div class="text">
			<?php echo $_replyResult[$k]['post_html'] ?>
			<div class="signature"><?php echo $_replyResult[$k]['signature'] ?></div>
		</div>
		<div class="footer">
			<div class="fleft">
				<a href="index.php?module=report&amp;t_id=<?php echo $threadId ?>&amp;p_id=<?php echo $_replyResult[$k]['p_id'] ?>" class="smallButton grey transition fancybox fancybox.ajax">Report this post</a>
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
	<div class="fleft"><?php echo $pagination ?></div>
	<div class="fright">
		<a href="index.php?module=post&amp;act=thread&amp;id=<?php echo $threadId ?>" class="defaultButton transition">Add Reply</a>
	</div>
</div>

<?php endif; ?>

<div class="relatedThreads">
		<h2>Related Threads</h2>
		<?php
			if($_relatedThreadList):
			foreach($_relatedThreadList as $k => $v):
		?>
			<div class="item"><span><?php echo $_relatedThreadList[$k]['thread_date'] ?></span><a href="index.php?module=thread&amp;id={$id}"><?php echo $_relatedThreadList[$k]['title'] ?></a></div>
		<?php
			endforeach;
			endif;
		?>
	</div>