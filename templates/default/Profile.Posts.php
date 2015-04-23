<div class="profile-header">
	<div class="background" style="background-image: url('<?php __($info['cover']) ?>')"><div class="cover"></div></div>
	<?php __(Html::Crop($info['avatar'], 160, 160, "avatar")) ?>
	<div class="member-title"><?php __($info['member_title']) ?></div>
	<div class="member-info">
		<span class="username"><?php __($info['username']) ?></span>
		<div class="item"><span><?php __("P_POSTS") ?></span><?php __($info['posts']) ?></div>
		<div class="item"><span><?php __("P_REGISTERED") ?></span><?php __($info['joined']) ?></div>
		<div class="item"><span><?php __("P_USERGROUP") ?></span><?php __($info['name']) ?></div>
	</div>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="profile/<?php __($member_id) ?>" class="transition selected"><?php __("P_PROFILE") ?></a>
	</div>
	<div class="subnav">
		<a href="profile/<?php __($member_id) ?>"><?php __("P_MY_PROFILE") ?></a>
		<a href="profile/posts/<?php __($member_id) ?>"><?php __("P_LAST_POSTS") ?></a>
		<a href="profile/attachments/<?php __($member_id) ?>"><?php __("P_ATTACHMENTS") ?></a>
	</div>
</div>

<div class="profile">
	<div class="box">
		<!-- POSTS & THREADS -->
		<table class="table-list no-borders no-shadow">
			<tr>
				<th colspan="2"><?php __("P_LAST_THREADS_BY", array($info['username'])) ?></th>
			</tr>
			<?php __($thread_list) ?>
		</table>
		<table class="table-list no-borders no-shadow">
			<tr>
				<th colspan="2"><?php __("P_LAST_POSTS_BY", array($info['username'])) ?></th>
			</tr>
			<?php __($post_list) ?>
		</table>
	</div>
</div>
