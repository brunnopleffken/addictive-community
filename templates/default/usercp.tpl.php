<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span>Control Panel</div>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="index.php?module=usercp" class="transition <?php __($menu[0]) ?>">Profile</a>
		<a href="index.php?module=usercp&amp;view=photo" class="transition <?php __($menu[1]) ?>">Photo</a>
		<a href="index.php?module=usercp&amp;view=signature" class="transition <?php __($menu[2]) ?>">Signature</a>
		<a href="index.php?module=usercp&amp;view=settings" class="transition <?php __($menu[3]) ?>">Settings</a>
		<a href="index.php?module=usercp&amp;view=password" class="transition <?php __($menu[4]) ?>">Password</a>
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
			<div class="label">Member title</div>
			<div class="field"><input type="text" name="member_title" value="<?php __($this->member['member_title']) ?>" class="large"></div>
		</div>
		<div class="inputBox">
			<div class="label">About me</div>
			<div class="field"><textarea name="profile" rows="5" class="large"><?php __($this->member['profile']) ?></textarea></div>
		</div>
		<div class="inputBox">
			<div class="label">E-mail address</div>
			<div class="field"><input type="text" name="email" value="<?php __($this->member['email']) ?>" class="required email medium"></div>
		</div>
		<div class="inputBox">
			<div class="label">Birthday</div>
			<div class="field">
			<?php
				__(Html::Days("b_day", $this->member['b_day']) . " ");
				__(Html::Months("b_month", false, $this->t, $this->member['b_month']) . " ");
				__(Html::Years("b_year", 80, 0, $this->member['b_year']));
			?>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Gender</div>
			<div class="field">
				<select name="gender" class="select2-no-search">
					<option value="0">---</option>
					<option value="M" <?php __($profile['male']) ?>>Male</option>
					<option value="F" <?php __($profile['female']) ?>>Female</option>
				</select>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Location</div>
			<div class="field"><input type="text" name="location" value="<?php __($this->member['location']) ?>" class="medium"></div>
		</div>
		<div class="inputBox">
			<div class="label">Website</div>
			<div class="field"><input type="text" name="website" value="<?php __($this->member['website']) ?>" class="large"></div>
		</div>
		<div class="inputBox">
			<div class="label">Facebook</div>
			<div class="field">
				http://www.facebook.com/ <input type="text" name="im_facebook" value="<?php __($this->member['im_facebook']) ?>" class="small">
				<em>You'll be able to use your Facebook photo as avatar in <a href="?module=usercp&view=photo">Photo Settings</a>.</em>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Twitter</div>
			<div class="field" style="position:relative"><span style="position:absolute;top:5px;left:5px;color:#999">@</span><input type="text" name="im_twitter" value="<?php __($this->member['im_twitter']) ?>" class="small" style="padding-left: 20px"></div>
		</div>
		<div class="fleft">
			<div class="errorMessage">You need to enter a valid e-mail address.</div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="profile"><input type="submit" value="Update Profile"></div>
	</form>

	<?php
		break;
		case "photo":
	?>
	
	<?php echo $facebook_info ?>

	<form action="" method="post" class="validate" enctype="multipart/form-data">
		<div class="inputBox">
			<div class="label">Photo source</div>
			<div class="field">
				<input type="radio" name="photo_type" class="photoSelect" value="gravatar" <?php __($photo_info['gravatar']) ?>> Gravatar<br>
				<input type="radio" name="photo_type" class="photoSelect" value="facebook" <?php __($photo_info['facebook']) ?>> Facebook<br>
				<input type="radio" name="photo_type" class="photoSelect" value="custom" <?php __($photo_info['custom']) ?>> Upload custom photo
			</div>
		</div>
		<div class="inputBox" id="gravatar" style="display: none">
			<div class="label">Gravatar settings</div>
			<div class="field">
				<div class="fleft" style="margin-right: 15px">
					<?php __(Html::Crop($photo_info['gravatar_image'], 120, 120)) ?>
				</div>
				<div class="fleft">
					<b>Gravatar</b> is a service for providing globally unique avatars.<br>
					Your gravatar is associated with <a href="?module=usercp&do=profile">your e-mail address</a>.
					<br><br>Edit or create your Gravatar accessing <a href="https://www.gravatar.com" target="_blank" rel="nofollow">www.gravatar.com</a>.
				</div>
				<div class="fix"></div>
			</div>
		</div>
		<div class="inputBox" id="facebook" style="display: none">
			<div class="label">Facebook photo</div>
			<div class="field">
				<div class="fleft" style="margin-right: 15px">
					<?php __(Html::Crop($photo_info['facebook_image'], 120, 120)) ?>
				</div>
				<div class="fleft">
					In order to use Facebook images, you <strong>must</strong> fill in<br>the "Facebook" text field in <a href="?module=usercp">Profile (User Control Panel)</a>.<br><br>
					Edit or create your Facebook photo accessing <a href="https://www.facebook.com" target="_blank" rel="nofollow">www.facebook.com</a>.
				</div>
				<div class="fix"></div>
			</div>
		</div>
		<div class="inputBox" id="custom" style="display: none">
			<div class="label">Photo upload</div>
			<div class="field">
				<div class="fleft" style="margin-right: 15px">
					<?php __(Html::Crop("public/avatar/" . $this->member['photo'], 120, 120)) ?>
				</div>
				<div class="fleft" style="border:1px dashed #eee;"><input type="file" name="file_upload"></div>
				<div class="fix"></div>
			</div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="photo"><input type="submit" value="Update Photo"></div>
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
			<div class="label">Current</div>
			<div class="field textOnly" id="markdownPreview"></div>
		</div>
		<div class="inputBox">
			<div class="label">Edit signature</div>
			<div class="field">
				<textarea name="signature" rows="8" class="large" id="markdownTextarea"><?php __($this->member['signature']) ?></textarea>
			</div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="signature"><input type="submit" value="Update Signature"></div>
	</form>

	<?php
		break;
		case "settings":
	?>

	<form action="" method="post" class="validate">
		<div class="inputBox">
			<div class="label">Timezone</div>
			<div class="field">
				<select name="timezone" class="select2" style="width:500px">
					<?php __($settings['tz_list']) ?>
				</select>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Language</div>
			<div class="field">
				<select name="language" class="select2-no-search small">
					<option value="en_US">English (US)</option>
				</select>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Template</div>
			<div class="field">
				<select name="template" class="select2-no-search small">
					<option value="default">Default</option>
				</select>
			</div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="settings"><input type="submit" value="Update Settings"></div>
	</form>

	<?php
		break;
		case "password":
	?>

	<form action="" method="post" class="validate">
		<div class="inputBox">
			<div class="label">Current password</div>
			<div class="field"><input type="password" name="current" class="required small"></div>
		</div>
		<div class="inputBox">
			<div class="label">New password</div>
			<div class="field"><input type="password" name="new_password" class="required small"></div>
		</div>
		<div class="inputBox">
			<div class="label">Re-type password</div>
			<div class="field"><input type="password" name="c_password" class="required small"></div>
		</div>
		<div class="fleft">
			<div class="errorMessage">Passwords doesn't match or fields are empty.</div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="password"><input type="submit" value="Change Password"></div>
	</form>

	<?php
		break;
		endswitch;
	?>
</div>