<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span>An error occoured</div>
</div>

<?php if($notification): ?>
	<div class="notification warning persistent">
		<p><strong>WARNING!</strong> <?php __($message) ?></p>
	</div>
<?php endif; ?>

<?php if($success): ?>
	<div class="notification success persistent">
		<p><strong>SUCCESS!</strong> <?php __($message) ?></p>
	</div>
<?php endif; ?>

<?php if($loginForm): ?>
	<form action="index.php?module=login&amp;act=do" method="post" class="validate">
		<div class="box">
			<div class="inputBox">
				<div class="label">Username</div>
				<div class="field"><input type="text" name="username" class="required medium"></div>
			</div>
			<div class="inputBox">
				<div class="label">Password</div>
				<div class="field"><input type="password" name="password" class="required medium"></div>
			</div>
			<div class="fright"><input type="hidden" name="exception_referrer" value="true"><input type="submit" value="Log In"></div>
		</div>
	</form>
<?php endif; ?>

<?php if(isset($roomPassword)): ?>

	<form action="index.php?module=room&amp;id=<?php echo Html::Request("r_id") ?>&amp;execute=protected" method="post" class="validate">
		<div class="box">
			<div class="inputBox">
				<div class="label">Password</div>
				<div class="field"><input type="password" name="password" class="required medium"></div>
			</div>
			<div class="fright"><input type="hidden" name="exception_referrer" value="true"><input type="submit" value="Proceed"></div>
		</div>
	</form>

<?php endif; ?>