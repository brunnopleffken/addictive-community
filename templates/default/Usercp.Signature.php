<div class="room-title-bar">
	<div class="title fleft">
		<span><?php __($community_name) ?></span><?php __("C_TITLE") ?>
	</div>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="usercp" class="<?php __($menu[0]) ?>"><?php __("C_PROFILE") ?></a>
		<a href="usercp/photo" class="<?php __($menu[1]) ?>"><?php __("C_PHOTO") ?></a>
		<a href="usercp/signature" class="<?php __($menu[2]) ?>"><?php __("C_SIGNATURE") ?></a>
		<a href="usercp/settings" class="<?php __($menu[3]) ?>"><?php __("C_SETTINGS") ?></a>
		<a href="usercp/password" class="<?php __($menu[4]) ?>"><?php __("C_PASSWORD") ?></a>
	</div>
</div>

<div class="box no-shadow">
	<?php __($notification) ?>
	<form action="usercp/save_signature" method="post" class="validate">
		<div class="input-box">
			<div class="label"><?php __("C_SIGNATURE_EDIT") ?></div>
			<div class="field">
				<textarea name="signature" class="signature large" id="signature" rows="10"><?php __($member_info['signature']) ?></textarea>
			</div>
		</div>
		<div class="fright"><input type="submit" value="<?php __("C_SIGNATURE_UPDATE") ?>"></div>
	</form>
</div>
