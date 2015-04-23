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
	<form action="usercp/save_settings" method="post" class="validate">
		<div class="input-box">
			<div class="label"><?php __("C_TIMEZONE") ?></div>
			<div class="field">
				<select name="timezone" class="select2" style="width:500px">
					<?php echo $settings['tz_list'] ?>
				</select>
			</div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_LANGUAGE") ?></div>
			<div class="field">
				<select name="language" class="select2-no-search small">
					<?php echo $settings['lang_list'] ?>
				</select>
			</div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_TEMPLATE") ?></div>
			<div class="field">
				<select name="template" class="select2-no-search small">
					<?php echo $settings['template_list'] ?>
				</select>
			</div>
		</div>
		<div class="fright"><input type="submit" value="<?php __("C_UPDATE_SETTINGS") ?>"></div>
	</form>
</div>
