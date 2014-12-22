<form action="index.php?module=report&amp;act=send" method="post" class="validate">
	<table class="tableList noBorders" style="width:350px; margin:0">
		<tr>
			<th colspan="7">
				<div class="fleft"><?php __("R_TITLE") ?></div>
				<div class="fright"><a href="javascript:jQuery.fancybox.close();" class="smallButton grey white transition"><?php __("R_CLOSE") ?></a></div>
			</th>
		</tr>
		<tr>
			<td class="min"><?php __("R_NOTICE") ?></td>
		</tr>
		<tr>
			<td><?php __("R_REASON") ?>:
				<select name="" id="">
					<option value="0" disabled="disabled"><?php __("R_OPTION_CHOOSE") ?></option>
					<option value="1"><?php __("R_OPTION_1") ?></option>
					<option value="2"><?php __("R_OPTION_2") ?></option>
					<option value="3"><?php __("R_OPTION_3") ?></option>
					<option value="4"><?php __("R_OPTION_4") ?></option>
					<option value="5"><?php __("R_OPTION_5") ?></option>
					<option value="6"><?php __("R_OPTION_6") ?></option>
					<option value="7"><?php __("R_OPTION_7") ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td><textarea name="description" class="large required" rows="6" style="resize:none"></textarea></td>
		</tr>
		<tr class="footer">
			<td colspan="2" style="text-align:center; padding:10px">
				<input type="hidden" name="m_id" value="<?php echo $this->Session->sInfo['member_id'] ?>">
				<input type="hidden" name="t_id" value="<?php echo $threadId ?>">
				<input type="hidden" name="p_id" value="<?php echo $postId ?>">
				<input type="submit" value="<?php __("R_SEND") ?>">
			</td>
		</tr>
	</table>
</form>
