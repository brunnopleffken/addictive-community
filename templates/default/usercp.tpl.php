<div class="roomTitleBar">
	<div class="title fleft"><span><?php echo $this->Core->config['general_communityname'] ?></span>Control Panel</div>
</div>

<div class="navigation">
	<div class="navbar">
		<a href="index.php?module=usercp" class="transition <?php echo $menu[0] ?>">Profile</a>
		<a href="index.php?module=usercp&amp;view=photo" class="transition <?php echo $menu[1] ?>">Photo</a>
		<a href="index.php?module=usercp&amp;view=signature" class="transition <?php echo $menu[2] ?>">Signature</a>
		<a href="index.php?module=usercp&amp;view=settings" class="transition <?php echo $menu[3] ?>">Settings</a>
		<a href="index.php?module=usercp&amp;view=password" class="transition <?php echo $menu[4] ?>">Password</a>
	</div>
</div>

<div class="box noShadow">

	<?php echo $notification ?>

	<?php
		switch($view):
		case "profile":
	?>

	<form action="" method="post" class="validate">
		<div class="inputBox">
			<div class="label">Member title</div>
			<div class="field"><input type="text" name="member_title" value="<?php echo $this->member['member_title'] ?>" class="large"></div>
		</div>
		<div class="inputBox">
			<div class="label">About me</div>
			<div class="field"><textarea name="profile" rows="5" class="large"><?php echo $this->member['profile'] ?></textarea></div>
		</div>
		<div class="inputBox">
			<div class="label">E-mail address</div>
			<div class="field"><input type="text" name="email" value="<?php echo $this->member['email'] ?>" class="required medium"></div>
		</div>
		<div class="inputBox">
			<div class="label">Birthday</div>
			<div class="field">
			<?php
				echo Html::Days("b_day", $this->member['b_day']) . " ";
				echo Html::Months("b_month", false, $this->t, $this->member['b_month']) . " ";
				echo Html::Years("b_year", 80, 0, $this->member['b_year']);
			?>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Gender</div>
			<div class="field">
				<select name="gender" class="select2-no-search">
					<option value="0">---</option>
					<option value="M" <?php echo $profile['male'] ?>>Male</option>
					<option value="F" <?php echo $profile['female'] ?>>Female</option>
				</select>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Location</div>
			<div class="field"><input type="text" name="location" value="<?php echo $this->member['location'] ?>" class="medium"></div>
		</div>
		<div class="inputBox">
			<div class="label">Website</div>
			<div class="field"><input type="text" name="website" value="<?php echo $this->member['website'] ?>" class="large"></div>
		</div>
		<div class="inputBox">
			<div class="label">Facebook</div>
			<div class="field">http://www.facebook.com/ <input type="text" name="im_facebook" value="<?php echo $this->member['im_facebook'] ?>" class="small"></div>
		</div>
		<div class="inputBox">
			<div class="label">Twitter</div>
			<div class="field" style="position:relative"><span style="position:absolute; top:2px; left: 0px">@</span><input type="text" name="im_twitter" value="<?php echo $this->member['im_twitter'] ?>" class="small" style="padding-left: 22px"></div>
		</div>
		<div class="fright"><input type="hidden" name="act" value="profile"><input type="submit" value="Update Profile"></div>
	</form>

	<?php
		break;
		case "photo":
	?>

	<form action="" method="post" class="validate" enctype="multipart/form-data">
		<div class="inputBox">
			<div class="label">Photo type</div>
			<div class="field">
				<input type="radio" name="photo_type" class="photoSelect" value="gravatar" <?php echo $photo_info['gravatar'] ?>> Gravatar<br>
				<input type="radio" name="photo_type" class="photoSelect" value="custom" <?php echo $photo_info['custom'] ?>> Custom
			</div>
		</div>
		<div class="inputBox" id="gravatar" style="display: none">
			<div class="label">Gravatar settings</div>
			<div class="field">
				<div class="fleft" style="margin-right: 15px"><img src="<?php echo $photo_info['gravatar_img_url'] ?>"></div>
				<div class="fleft">{$lang->show['gravatar_desc']}</div>
				<div class="fix"></div>
			</div>
		</div>
		<div class="inputBox" id="custom" style="display: none">
			<div class="label">Photo upload</div>
			<div class="field">
				<div class="fleft" style="margin-right: 15px"><img src="public/avatar/<?php echo $this->member['photo'] ?>" width="120" class="shadow"></div>
				<div class="fleft" style="border:1px dashed #eee;"><input type="file" name="file_upload"></div>
				<div class="fix"></div>
			</div>
		</div>
		<div class="fright"><input type="submit" value="Update Photo"></div>
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
				<textarea name="signature" rows="8" class="large" id="markdownTextarea"><?php echo $this->member['signature'] ?></textarea>
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
					<?php echo $settings['tz_list'] ?>
				</select>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Language</div>
			<div class="field">
				<select name="language" class="select2-no-search">
					<option value="en_US">English (US)</option>
				</select>
			</div>
		</div>
		<div class="inputBox">
			<div class="label">Template</div>
			<div class="field">
				<select name="template" class="select2-no-search">
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
		<div class="fright"><input type="hidden" name="act" value="password"><input type="submit" value="Change Password"></div>
	</form>

	<?php
		break;
		endswitch;
	?>
</div>