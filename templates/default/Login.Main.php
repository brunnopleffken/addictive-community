<form action="login/do" method="post" class="validate" id="memberLoginForm">
	<table class="table-list no-borders" style="width:380px; margin:0">
		<tr>
			<th colspan="7">
				<div class="fleft"><?php __("L_TITLE") ?></div>
				<div class="fright"><a href="javascript:parent.$.fancybox.close();" class="small-button grey white transition"><?php __("L_CLOSE") ?></a></div>
				<div class="clear"></div>
			</th>
		</tr>
		<tr>
			<td><strong><?php __("L_USERNAME") ?></strong></td>
			<td><input type="text" name="username" class="username required small"></td>
		</tr>
		<tr>
			<td><strong><?php __("L_PASSWORD") ?></strong></td>
			<td><input type="password" name="password" class="password required small"></td>
		</tr>
		<tr>
			<td><strong></strong></td>
			<td style="line-height: 1.6em">
				<input type="checkbox" name="anonymous"> <?php __("L_ANONYMOUS") ?><br>
				<input type="checkbox" name="remember" checked> <?php __("L_REMEMBER") ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><?php __("L_NO_ACCOUNT", array("register")) ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">
				<input type="submit" value="<?php __("L_SUBMIT") ?>" class="transition" data-error-message="<?php __("L_ERROR_MESSAGE") ?>">
			</td>
		</tr>
	</table>
</form>
