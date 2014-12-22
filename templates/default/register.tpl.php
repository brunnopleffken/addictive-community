<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __("R_TITLE") ?></div>
</div>

<?php
	if($step == 1):
?>

<div class="box">
	<div class="stepBox">
		<div class="current"><h3><?php __("R_STEP_1") ?></h3><span class="tiny"><?php __("R_STEP_AGREEMENT") ?></span></div>
		<div class="next"><h3><?php __("R_STEP_2") ?></h3><span class="tiny"><?php __("R_STEP_YOUR_ACCOUNT") ?></span></div>
		<div class="next"><h3><?php __("R_STEP_3") ?></h3><span class="tiny"><?php __("R_STEP_CONFIRMATION") ?></span></div>
	</div>

	<form>
		<div class="inputBox center">
			<textarea style="width: 550px; height: 200px" readonly><?php __("R_EULA") ?></textarea>
		</div>
		<div class="inputBox center">
			<input type="button" value="<?php __("R_AGREE") ?>" onclick="javascript:location.href='?module=register&amp;step=2'"> &nbsp;
			<input type="submit" value="<?php __("R_DISAGREE") ?>" class="cancel" onclick="javascript:location.href='index.php'">
		</div>
	</form>
</div>

<?php
	elseif($step == 2):
?>

<script type="text/javascript">
	function CheckPassword() {
		var password = $('#password').val();
		var confirm = $('#password_conf').val();

		if(password != confirm) {
			$('#passwdMatch').fadeIn().css('display', 'inline-block');
			$('#formSubmit').attr('disabled', 'disabled');
		}
		else {
			$('#passwdMatch').fadeOut().css('display', 'none');
			$('#formSubmit').attr('disabled', false);
		}
	}
</script>

<div class="box">
	<div class="stepBox">
		<div class="previous"><h3><?php __("R_STEP_1") ?></h3><span class="tiny"><?php __("R_STEP_AGREEMENT") ?></span></div>
		<div class="current"><h3><?php __("R_STEP_2") ?></h3><span class="tiny"><?php __("R_STEP_YOUR_ACCOUNT") ?></span></div>
		<div class="next"><h3><?php __("R_STEP_3") ?></h3><span class="tiny"><?php __("R_STEP_CONFIRMATION") ?></span></div>
	</div>

	<?php echo $notification ?>

	<form action="index.php" method="post" class="validate">
		<div class="inputBox">
			<div class="label"><?php __("R_USERNAME") ?></div>
			<div class="field"><input type="text" name="username" class="required small" maxlength="26"> &nbsp; <em><?php __("R_USERNAME_NOTICE") ?></em></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("R_PASSWORD") ?></div>
			<div class="field"><input type="password" name="password" id="password" class="required small"> &nbsp; <em><?php __("R_PASSWORD_NOTICE") ?></em></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("R_RETYPE_PASSWD") ?></div>
			<div class="field"><input type="password" name="password_conf" id="password_conf" class="required small" onblur="CheckPassword()"> &nbsp; <div id="passwdMatch" style="display:none; color:#bb0000; font-weight:bold;"><i class="fa fa-exclamation-triangle" style="font-weight:normal"></i> <?php __("R_PASSWORD_ERROR") ?></div></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("R_EMAIL_ADDR") ?></div>
			<div class="field"><input type="text" name="email" class="required email medium"></div>
		</div>
		<div class="inputBox center">
			<input type="hidden" name="module" value="register">
			<input type="hidden" name="act" value="signup">
			<input type="submit" id="formSubmit" value="<?php __("R_STEP_2_SEND") ?>">
		</div>
	</form>
</div>


<?php
	elseif($step == 3):
?>

<div class="box">
	<div class="stepBox">
		<div class="previous"><h3><?php __("R_STEP_1") ?></h3><span class="tiny"><?php __("R_STEP_AGREEMENT") ?></span></div>
		<div class="previous"><h3><?php __("R_STEP_2") ?></h3><span class="tiny"><?php __("R_STEP_YOUR_ACCOUNT") ?></span></div>
		<div class="current"><h3><?php __("R_STEP_3") ?></h3><span class="tiny"><?php __("R_STEP_CONFIRMATION") ?></span></div>
	</div>

	<div class="inputBox center">
		<i class="fa fa-check" style="font-size: 50px"></i><br><br>
		<h2><?php __("R_DONE_CONGRATULATIONS") ?></h2><br>
		<h3><?php __("R_DONE_SUCCESS") ?></h3>
		<?php if($this->Core->config['general_security_validation'] == "true"): ?>
			<h3><?php __("R_DONE_VALIDATE") ?></h3>
		<?php endif; ?>
	</div>

</div>

<?php endif; ?>
