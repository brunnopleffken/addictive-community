<form action="index.php?module=report&act=send" method="post">
	<table class="tableList noBorders" style="width: 350px;">
		<tr>
			<th colspan="7">
				<div class="fleft">Report Abuse</div>
				<div class="fright"><a href="javascript:jQuery.fancybox.close();" class="smallButton grey white transition">Close</a></div>
			</th>
		</tr>
		<tr>
			<td class="min">What's wrong with the selected post/thread (optional)?</td>
		</tr>
		<tr>
			<td><textarea name="description" class="large" rows="6"></textarea></td>
		</tr>
		<tr class="footer">
			<td colspan="2" style="text-align:center; padding:10px">
				<input type="hidden" name="m_id" value="<?php echo $this->Session->sInfo['member_id'] ?>">
				<input type="hidden" name="t_id" value="<?php echo $threadId ?>">
				<input type="hidden" name="p_id" value="<?php echo $postId ?>">
				<input type="submit" value="Send">
			</td>
		</tr>
	</table>
</form>