<div class="title-bar">
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
	<form action="usercp/save_profile" method="post" class="validate">
		<div class="input-box">
			<div class="label"><?php __("C_MEMBER_TITLE") ?></div>
			<div class="field"><input type="text" name="member_title" value="<?php __($member['member_title']) ?>" class="large"></div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_ABOUT_ME") ?></div>
			<div class="field"><textarea name="profile" rows="5" class="large"><?php __($member['profile']) ?></textarea></div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_EMAIL_ADDR") ?></div>
			<div class="field"><input type="text" name="email" value="<?php __($member['email']) ?>" class="required email medium"></div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_BIRTHDATE") ?></div>
			<div class="field">
				<?php
				__(Html::Days("b_day", $member['b_day']) . " ");
				__(Html::Months("b_month", false, $member['b_month']) . " ");
				__(Html::Years("b_year", 80, 0, $member['b_year']));
				?>
			</div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_GENDER") ?></div>
			<div class="field">
				<select name="gender" class="select2-no-search" style="width: 100px">
					<option value="0">---</option>
					<option value="M" <?php __($profile['male']) ?>><?php __("C_GENDER_MALE") ?></option>
					<option value="F" <?php __($profile['female']) ?>><?php __("C_GENDER_FEMALE") ?></option>
				</select>
			</div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_LOCATION") ?></div>
			<div class="field"><input type="text" name="location" value="<?php __($member['location']) ?>" class="medium"></div>
		</div>
		<div class="input-box">
			<div class="label"><?php __("C_WEBSITE") ?></div>
			<div class="field"><input type="text" name="website" value="<?php __($member['website']) ?>" class="large"></div>
		</div>
		<div class="input-box">
			<div class="label">Facebook</div>
			<div class="field" style="position:relative">
				<span style="position:absolute;top:6px;left:5px;color:#aaa">http://www.facebook.com/</span>
				<input type="text" name="im_facebook" placeholder="username" value="<?php __($member['im_facebook']) ?>" class="medium" style="padding-left:153px">
				<em class="tiny"><?php __("C_FACEBOOK_TIP") ?></em>
			</div>
		</div>
		<div class="input-box">
			<div class="label">Twitter</div>
			<div class="field" style="position:relative">
				<span style="position:absolute;top:6px;left:5px;color:#aaa">@</span>
				<input type="text" name="im_twitter" placeholder="username" value="<?php __($member['im_twitter']) ?>" class="small" style="padding-left: 20px">
			</div>
		</div>
		<div class="fleft">
			<div class="error-message"><?php __("C_EMAIL_MESSAGE") ?></div>
		</div>
		<div class="fright"><input type="submit" value="<?php __("C_UPDATE_PROFILE") ?>"></div>
	</form>
</div>
