<?php if(!$act): ?>

<form action="index.php?module=login&amp;act=do" method="post" class="validate" id="memberLoginForm">
	<table class="tableList noBorders" style="width:380px; margin:0">
		<tr>
			<th colspan="7">
				<div class="fleft">Login</div>
				<div class="fright"><a href="javascript:parent.$.fancybox.close();" class="smallButton grey white transition">Close</a></div>
				<div class="clear"></div>
			</th>
		</tr>
		<tr>
			<td><strong>Username</strong></td>
			<td><input type="text" name="username" class="username required small"></td>
		</tr>
		<tr>
			<td><strong>Password</strong></td>
			<td><input type="password" name="password" class="password required small"></td>
		</tr>
		<tr>
			<td><strong></strong></td>
			<td style="line-height: 1.6em">
				<input type="checkbox" name="anonymous"> Anonymous login<br>
				<input type="checkbox" name="remember" checked> Remember my current session
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center">Don't have an account? <a href="index.php?module=register"><strong>Sign up here!</strong></a></td>
		</tr>
		<tr>
			<td colspan="2" class="center">
				<input type="submit" value="Log In" class="transition">
			</td>
		</tr>
	</table>
</form>

<?php endif; ?>