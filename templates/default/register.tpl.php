<div class="roomTitleBar">
	<div class="title fleft"><span><?php echo $this->Core->config['general_communityname'] ?></span>Create Account</div>
</div>

<?php
	if($step == 1):
?>

<div class="box">
	<div class="stepBox">
		<div class="current"><h3>Step 1</h3><span class="tiny">Agreement</span></div>
		<div class="next"><h3>Step 2</h3><span class="tiny">Your Account</span></div>
		<div class="next"><h3>Step 3</h3><span class="tiny">Confirmation</span></div>
	</div>

	<form>
		<div class="inputBox center">
			<textarea style="width: 550px; height: 200px" readonly>We are not responsible for any messages posted. The messages express the views of the author of the message, not necessarily the views of this community. Messages that harass, abuse or threaten other members; have obscene or otherwise objectionable content; have spam, commercial or advertising content or links may be removed and may result in the loss of your account. Please do not post any private information unless you want it to be available publicly. Never assume that you are completely anonymous and cannot be identified by your posts.</textarea>
		</div>
		<div class="inputBox center">
			<input type="button" value="Agree" onclick="javascript:location.href='index.php?module=register&amp;step=2'"> &nbsp;
			<input type="submit" value="Disagree" class="cancel" onclick="javascript:location.href='index.php'">
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
		<div class="previous"><h3>Step 1</h3><span class="tiny">Agreement</span></div>
		<div class="current"><h3>Step 2</h3><span class="tiny">Your Account</span></div>
		<div class="next"><h3>Step 3</h3><span class="tiny">Confirmation</span></div>
	</div>

	<form action="index.php" method="post" class="validate">
		<div class="inputBox">
			<div class="label">Username</div>
			<div class="field"><input type="text" name="username" class="required small" maxlength="26"> &nbsp; <em>Between 3 and 26 characters</em></div>
		</div>
		<div class="inputBox">
			<div class="label">Password</div>
			<div class="field"><input type="password" name="password" id="password" class="required small"> &nbsp; <em>Between 6 and 32 characters</em></div>
		</div>
		<div class="inputBox">
			<div class="label">Re-type password</div>
			<div class="field"><input type="password" name="password_conf" id="password_conf" class="required small" onblur="CheckPassword()"> &nbsp; <div id="passwdMatch" style="display:none; color:#bb0000; font-weight:bold;"><img src="<?php echo $this->p['IMG'] ?>/exclamation.png" style="vertical-align: text-bottom"> Passwords does not match!</div></div>
		</div>
		<div class="inputBox">
			<div class="label">E-mail address</div>
			<div class="field"><input type="text" name="email" class="required email medium"></div>
		</div>
		<div class="inputBox center">
			<input type="hidden" name="module" value="register">
			<input type="hidden" name="act" value="signup">
			<input type="submit" id="formSubmit" value="Create Account">
		</div>
	</form>
</div>


<?php
	elseif($step == 3):
?>

<div class="box">
	<div class="stepBox">
		<div class="previous"><h3>Step 1</h3><span class="tiny">Agreement</span></div>
		<div class="previous"><h3>Step 2</h3><span class="tiny">Your Account</span></div>
		<div class="current"><h3>Step 3</h3><span class="tiny">Confirmation</span></div>
	</div>

	<div class="inputBox center">
		<img src="<?php echo $this->p['IMG'] ?>/check.png"><br><br>
		<h2>Congratulations!</h2><br>
		<h3>You have successfully registered your account.</h3>
	</div>

</div>

<?php endif; ?>