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
		<!-- MEMBER PROFILE -->
		<table class="table-list no-shadow no-borders">
			<tr>
				<th colspan="2"><?php __("P_MY_PROFILE") ?></th>
			</tr>
			<tr>
				<td class="table-label"><?php __("P_ABOUT_ME") ?></td>
				<td><?php __($info['profile']) ?></td>
			</tr>
			<tr>
				<td class="table-label"><?php __("P_BIRTHDATE") ?></td>
				<td><?php if($has_birthday) __("P_AGE", array($info['birthday'], $info['age'])) ?></td>
			</tr>
			<tr>
				<td class="table-label"><?php __("P_GENDER") ?></td>
				<td><?php __($info['gender']) ?></td>
			</tr>
			<tr>
				<td class="table-label"><?php __("P_LOCATION") ?></td>
				<td><?php __($info['location']) ?></td>
			</tr>
			<tr>
				<td class="table-label"><?php __("P_WEBSITE") ?></td>
				<td><?php __($info['website']) ?></td>
			</tr>
			<tr>
				<td class="table-label"><?php __("P_SIGNATURE") ?></td>
				<td class="parsing"><?php __($info['signature']) ?></td>
			</tr>
		</table>
		<table class="table-list no-shadow no-borders" style="margin-top: 15px">
			<tr>
				<th colspan="2"><?php __("P_STATS") ?></th>
			</tr>
			<tr>
				<td class="table-label"><?php __("P_BEST_ANSWERS") ?></td>
				<td><?php __("P_BEST_POSTS", array($info['bestanswers'])) ?></td>
			</tr>
		</table>
		<table class="table-list no-shadow no-borders" style="margin-top: 15px">
			<tr>
				<th colspan="2"><?php __("P_CONTACT_INFO") ?></th>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-fw fa-envelope-o"></i></td>
				<td><?php __($info['email']) ?></td>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-fw fa-facebook-official"></i></td>
				<td><a href="https://www.facebook.com/<?php __($info['im_facebook']) ?>" target="_blank" rel="nofollow">fb.com/<?php __($info['im_facebook']) ?></a></td>
			</tr>
			<tr>
				<td class="min" style="font-size: 16px"><i class="fa fa-fw fa-twitter"></i></td>
				<td><a href="https://twitter.com/<?php __($info['im_twitter']) ?>" target="_blank" rel="nofollow">@<?php __($info['im_twitter']) ?></a></td>
			</tr>
		</table>
	</div>
</div>
