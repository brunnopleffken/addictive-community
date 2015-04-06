<div class="room-title-bar">
	<div class="title fleft">
		<span><?php __($community_name) ?></span>Control Panel
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
	<form action="usercp/save_password" method="post" class="validate">
		<div class="input-box">
			<div class="label"><?php __("C_PASSWORD_CURRENT") ?></div>
			<div class="field"><input type="password" name="current" class="required small"></div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_PASSWORD_NEW") ?></div>
			<div class="field"><input type="password" name="new_password" class="required small"></div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_PASSWORD_RETYPE") ?></div>
			<div class="field"><input type="password" name="c_password" class="required small"></div>
		</div>
		<div class="fleft">
			<div class="error-message"><?php __("C_PASSWORD_EMPTY") ?></div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="password"><input type="submit" value="<?php __("C_CHANGE_PASSWORD") ?>"></div>
	</form>
</div>
