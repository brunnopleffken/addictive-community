<div id="fb-root"></div>
<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php __($obsoleteNotification) ?>

<div class="mainPost">
	<div class="header">
		<div class="author">
			<a href="#"><?php __(Html::Crop($firstPostInfo['avatar'], 42, 42, "avatar")) ?></a>
			<div class="authorInfo">
				<a href="profile/<?php __($firstPostInfo['author_id']) ?>"><?php __($firstPostInfo['username']) ?></a><br>
				<div><?php __($firstPostInfo['member_title']) ?></div>
				<?php __($firstPostInfo['posts']) ?> <?php __("T_POSTS_LOWCASE") ?>
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
			<span><?php __($firstPostInfo['post']) ?></span>
			<div class="attachments">
				<?php if($firstPostInfo['attach_id'] != 0): ?>
					<div class="file">
						<a href="<?php printf("public/attachments/%s/%s", $firstPostInfo['member_id'], $firstPostInfo['filename']) ?>" target="_blank" rel="nofollow">
							<span class="fileIcon <?php __($firstPostInfo['type']) ?>"></span>
							<div class="fileName"><span><?php __($firstPostInfo['filename']) ?></span><?php __(String::FileSizeFormat($firstPostInfo['size'])) ?></div>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="footer">
		<p class="fleft"><i class="fa fa-clock-o"></i> <?php __("T_POSTED_ON", array($firstPostInfo['post_date'])) ?></p>
		<?php if($this->member['m_id'] != 0): ?>
			<p class="fright"><i class="fa fa-warning"></i> <a href="?module=report&amp;t_id=<?php __($threadId) ?>" class="fancybox fancybox.ajax"><?php __("T_REPORT_ABUSE") ?></a></p>
		<?php endif; ?>
	</div>
</div>

<div class="threadButtons">
	<p class="replies fleft"><?php __("T_REPLIES") ?>: <span><?php __($threadInfo['post_count_display']) ?></span></p>
	<div class="fright">
		<?php if($this->IsMember()): ?>
			<?php if($threadInfo['obsolete']): ?>
				<a href="javascript:void()" class="defaultButton disabled transition"><?php __("T_BTN_OBSOLETE") ?></a>
			<?php elseif($threadInfo['locked'] == 0 && $allowToReply): ?>
				<a href="post/<?php __($threadId) ?>/thread" class="defaultButton transition"><?php __("T_BTN_ADD") ?></a>
			<?php else: ?>
				<a href="javascript:void()" class="defaultButton disabled transition"><?php __("T_BTN_LOCKED") ?></a>
			<?php endif; ?>
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
			<a href="profile/<?php __($_replyResult[$k]['author_id']) ?>">
				<?php __(Html::Crop($_replyResult[$k]['avatar'], 96, 96, "avatar")) ?>
			</a>
		</div>
		<p class="name"><a href="profile/<?php __($_replyResult[$k]['author_id']) ?>"><?php __($_replyResult[$k]['username']) ?></a></p>
		<p class="memberTitle"><?php __($_replyResult[$k]['member_title']) ?></p>
		<ul class="userInfo">
			<li><b><?php __("T_POST_POSTS") ?></b> <?php __($_replyResult[$k]['posts']) ?> <?php __("T_POSTS_LOWCASE") ?></li>
			<li><b><?php __("T_POST_REGISTERED") ?></b> <?php __($_replyResult[$k]['joined']) ?></li>
			<li><b><?php __("T_POST_LOCATION") ?></b> <?php __($_replyResult[$k]['location']) ?></li>
		</ul>
	</div>
	<div class="content">
		<div class="date"><?php __("T_POSTED_ON", array($_replyResult[$k]['post_date'])) ?> <?php __($_replyResult[$k]['edited']) ?></div>
		<div class="text">
			<span class="parsing"><?php __(html_entity_decode($_replyResult[$k]['post'])) ?></span>
			<div class="attachments">
				<?php if($_replyResult[$k]['attach_id'] != 0): ?>
					<div class="file">
						<a href="<?php printf("public/attachments/%s/%s", $_replyResult[$k]['member_id'], $_replyResult[$k]['filename']) ?>" target="_blank" rel="nofollow">
							<span class="fileIcon <?php __($_replyResult[$k]['type']) ?>"></span>
							<div class="fileName"><span><?php __($_replyResult[$k]['filename']) ?></span><?php __(String::FileSizeFormat($_replyResult[$k]['size'])) ?></div>
						</a>
					</div>
				<?php endif; ?>
			</div>
			<?php if($_replyResult[$k]['signature']): ?>
				<div class="signature parsing"><?php __($_replyResult[$k]['signature']) ?></div>
			<?php endif; ?>
		</div>
		<div class="footer">
			<div class="fleft">
				<?php if($this->IsMember()): ?>
					<a href="?module=report&amp;t_id=<?php __($threadId) ?>&amp;p_id=<?php __($_replyResult[$k]['p_id']) ?>" class="smallButton grey transition fancybox fancybox.ajax"><?php __("T_REPORT_POST") ?></a>
				<?php endif; ?>
			</div>
			<div class="fright">
				<?php __($_replyResult[$k]['post_controls']) ?>
				<?php __($_replyResult[$k]['thread_controls']) ?>
			</div>
		</div>
	</div>
</div>

<?php endforeach; ?>

<div class="threadButtons">
	<div class="fleft"><?php __($pagination) ?></div>
	<div class="fright">
		<?php if($this->IsMember()): ?>
			<?php if($threadInfo['obsolete']): ?>
				<a href="javascript:void()" class="defaultButton disabled transition">Obsolete Thread</a>
			<?php elseif($threadInfo['locked'] == 0 && $allowToReply): ?>
				<a href="post/<?php __($threadId) ?>/thread" class="defaultButton transition">Add Reply</a>
			<?php else: ?>
				<a href="javascript:void()" class="defaultButton disabled transition">Locked</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>

<?php endif; ?>

<div class="relatedThreads">
	<h2><?php __("T_RELATED") ?></h2>
	<?php if($_relatedThreadList): ?>
		<?php foreach($_relatedThreadList as $k => $v): ?>
			<div class="item">
				<span><?php __($_relatedThreadList[$k]['thread_date']) ?></span>
				<a href="thread/<?php echo $_relatedThreadList[$k]['t_id'] ?>"><?php __($_relatedThreadList[$k]['title']) ?></a>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="item"><span><?php __("T_NO_RELATED") ?></span></div>
	<?php endif; ?>
</div>

<!-- DELETE POST LIGHTBOX -->
<div id="deleteThreadConfirm" style="display: none">
	<form action="?module=thread&amp;act=delete" method="post" class="validate">
		<table class="tableList noBorders" style="width:350px; margin:0">
			<tr>
				<th>
					<div class="fleft"><?php __("T_DELETE_POST") ?></div>
					<div class="fright"><a href="javascript:jQuery.fancybox.close();" class="smallButton grey white transition"><?php __("T_CLOSE") ?></a></div>
				</th>
			</tr>
			<tr>
				<td class="min"><?php __("T_DELETE_NOTICE") ?></td>
			</tr>
			<tr class="footer">
				<td colspan="2" style="text-align:center; padding:10px">
					<input type="hidden" name="pid" id="deletePostId" value="">
					<input type="hidden" name="tid" id="deleteThreadId" value="">
					<input type="hidden" name="mid" id="deleteMemberId" value="">
					<input type="submit" value="<?php __("T_DELETE_POST") ?>">
				</td>
			</tr>
		</table>
	</form>
</div>
