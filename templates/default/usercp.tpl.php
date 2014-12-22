<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span>Control Panel</div>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="?module=usercp" class="transition <?php __($menu[0]) ?>"><?php __("C_PROFILE") ?></a>
		<a href="?module=usercp&amp;view=photo" class="transition <?php __($menu[1]) ?>"><?php __("C_PHOTO") ?></a>
		<a href="?module=usercp&amp;view=signature" class="transition <?php __($menu[2]) ?>"><?php __("C_SIGNATURE") ?></a>
		<a href="?module=usercp&amp;view=settings" class="transition <?php __($menu[3]) ?>"><?php __("C_SETTINGS") ?></a>
		<a href="?module=usercp&amp;view=password" class="transition <?php __($menu[4]) ?>"><?php __("C_PASSWORD") ?></a>
	</div>
</div>

<div class="box noShadow">

	<?php __($notification) ?>

	<?php
		switch($view):
		case "profile":
	?>

	<form action="" method="post" class="validate">
		<div class="inputBox">
			<div class="label"><?php __("C_MEMBER_TITLE") ?></div>
			<div class="field"><input type="text" name="member_title" value="<?php __($this->member['member_title']) ?>" class="large"></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_ABOUT_ME") ?></div>
			<div class="field"><textarea name="profile" rows="5" class="large"><?php __($this->member['profile']) ?></textarea></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_EMAIL_ADDR") ?></div>
			<div class="field"><input type="text" name="email" value="<?php __($this->member['email']) ?>" class="required email medium"></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_BIRTHDATE") ?></div>
			<div class="field">
			<?php
				__(Html::Days("b_day", $this->member['b_day']) . " ");
				__(Html::Months("b_month", false, $this->t, $this->member['b_month']) . " ");
				__(Html::Years("b_year", 80, 0, $this->member['b_year']));
			?>
			</div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_GENDER") ?></div>
			<div class="field">
				<select name="gender" class="select2-no-search">
					<option value="0">---</option>
					<option value="M" <?php __($profile['male']) ?>><?php __("C_GENDER_MALE") ?></option>
					<option value="F" <?php __($profile['female']) ?>><?php __("C_GENDER_FEMALE") ?></option>
				</select>
			</div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_LOCATION") ?></div>
			<div class="field"><input type="text" name="location" value="<?php __($this->member['location']) ?>" class="medium"></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_WEBSITE") ?></div>
			<div class="field"><input type="text" name="website" value="<?php __($this->member['website']) ?>" class="large"></div>
		</div>
		<div class="inputBox">
			<div class="label">Facebook</div>
			<div class="field" style="position:relative">
				<span style="position:absolute;top:6px;left:5px;color:#aaa">http://www.facebook.com/</span>
				<input type="text" name="im_facebook" placeholder="username" value="<?php __($this->member['im_facebook']) ?>" class="medium" style="padding-left:153px">
				<em><?php __("C_FACEBOOK_TIP") ?></em>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Twitter</div>
			<div class="field" style="position:relative">
				<span style="position:absolute;top:6px;left:5px;color:#aaa">@</span>
				<input type="text" name="im_twitter" placeholder="username" value="<?php __($this->member['im_twitter']) ?>" class="small" style="padding-left: 20px">
			</div>
		</div>
		<div class="fleft">
			<div class="errorMessage"><?php __("C_EMAIL_MESSAGE") ?></div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="profile"><input type="submit" value="<?php __("C_UPDATE_PROFILE") ?>"></div>
	</form>

	<?php
		break;
		case "photo":
	?>

	<?php echo $facebook_info ?>

	<form action="" method="post" class="validate" enctype="multipart/form-data">
		<div class="inputBox">
			<div class="label"><?php __("C_PHOTO_SOURCE") ?></div>
			<div class="field">
				<input type="radio" name="photo_type" class="photoSelect" value="gravatar" <?php __($photo_info['gravatar']) ?>> Gravatar<br>
				<input type="radio" name="photo_type" class="photoSelect" value="facebook" <?php __($photo_info['facebook']) ?>> Facebook<br>
				<input type="radio" name="photo_type" class="photoSelect" value="custom" <?php __($photo_info['custom']) ?>> <?php __("C_PHOTO_CUSTOM") ?>
			</div>
		</div>
		<div class="inputBox" id="gravatar" style="display: none">
			<div class="label"><?php __("C_GRAVATAR_SETTINGS") ?></div>
			<div class="field">
				<div class="fleft" style="margin-right: 15px">
					<?php __(Html::Crop($photo_info['gravatar_image'], 120, 120)) ?>
				</div>
				<div class="fleft">
					<?php __("C_GRAVATAR_MESSAGE") ?>
				</div>
				<div class="fix"></div>
			</div>
		</div>
		<div class="inputBox" id="facebook" style="display: none">
			<div class="label"><?php __("C_FACEBOOK_SETTINGS") ?></div>
			<div class="field">
				<div class="fleft" style="margin-right: 15px">
					<?php __(Html::Crop($photo_info['facebook_image'], 120, 120)) ?>
				</div>
				<div class="fleft">
					<?php __("C_FACEBOOK_MESSAGE") ?>
				</div>
				<div class="fix"></div>
			</div>
		</div>
		<div class="inputBox" id="custom" style="display: none">
			<div class="label"><?php __("C_PHOTO_UPLOAD") ?></div>
			<div class="field">
				<div class="fleft" style="margin-right: 15px">
					<?php __(Html::Crop("public/avatar/" . $this->member['photo'], 120, 120)) ?>
				</div>
				<div class="fleft" style="border:1px dashed #eee;"><input type="file" name="file_upload"></div>
				<div class="fix"></div>
			</div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="photo"><input type="submit" value="<?php __("C_UPDATE_PHOTO") ?>"></div>
	</form>

	<?php
		break;
		case "signature":
	?>

	<?php
		$this->header .= '<script type="text/javascript" src="resources/markdown.parser.js"></script>';
		$this->header .= '<script type="text/javascript">$(document).ready(function(){$(\'#markdownTextarea\').markdownRealTime()});</script>';
	?>

	<form action="" method="post" class="validate">
		<div class="inputBox">
			<div class="label"><?php __("C_SIGNATURE_CURRENT") ?></div>
			<div class="field textOnly" id="markdownPreview"></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_SIGNATURE_EDIT") ?></div>
			<div class="field">
				<textarea name="signature" rows="8" class="large" id="markdownTextarea"><?php __($this->member['signature']) ?></textarea>
			</div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="signature"><input type="submit" value="<?php __("C_SIGNATURE_UPDATE") ?>"></div>
	</form>

	<?php
		break;
		case "settings":
	?>

	<form action="" method="post" class="validate">
		<div class="inputBox">
			<div class="label"><?php __("C_TIMEZONE") ?></div>
			<div class="field">
				<select name="timezone" class="select2" style="width:500px">
					<?php echo $settings['tz_list'] ?>
				</select>
			</div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_LANGUAGE") ?></div>
			<div class="field">
				<select name="language" class="select2-no-search small">
					<?php echo $settings['lang_list'] ?>
				</select>
			</div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_TEMPLATE") ?></div>
			<div class="field">
				<select name="template" class="select2-no-search small">
					<?php echo $settings['template_list'] ?>
				</select>
			</div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="settings"><input type="submit" value="<?php __("C_UPDATE_SETTINGS") ?>"></div>
	</form>

	<?php
		break;
		case "password":
	?>

	<form action="" method="post" class="validate">
		<div class="inputBox">
			<div class="label"><?php __("C_PASSWORD_CURRENT") ?></div>
			<div class="field"><input type="password" name="current" class="required small"></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_PASSWORD_NEW") ?></div>
			<div class="field"><input type="password" name="new_password" class="required small"></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("C_PASSWORD_RETYPE") ?></div>
			<div class="field"><input type="password" name="c_password" class="required small"></div>
		</div>
		<div class="fleft">
			<div class="errorMessage"><?php __("C_PASSWORD_EMPTY") ?></div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="password"><input type="submit" value="<?php __("C_CHANGE_PASSWORD") ?>"></div>
	</form>

	<?php
		break;
		endswitch;
	?>
</div>
