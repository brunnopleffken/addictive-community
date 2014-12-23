<?php if($view == "inbox"): ?>

<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __("M_TITLE") ?></div>
	<div class="buttons fright">
		<a href="?module=messenger&amp;view=compose" class="defaultButton transition"><?php __("M_NEW_MESSAGE") ?></a>
	</div>
</div>

<?php __($notification) ?>

<div class="navigation">
	<div class="navbar">
		<a href="?module=messenger" class="transition selected"><?php __("M_FOLDERS") ?></a>
		<a href="" class="transition"><?php __("M_ATTACHMENTS") ?></a>
	</div>
	<div class="subnav">
		<a href="?module=messenger" class="transition selected"><?php __("M_FOLDER_INBOX") ?></a>
		<a href="?module=messenger&amp;view=sent" class="transition"><?php __("M_FOLDER_SENT") ?></a>
		<a href="?module=messenger&amp;view=drafts" class="transition"><?php __("M_FOLDER_DRAFTS") ?></a>
		<div class="progressBar" style="width: 204px; float: right; margin-top: 9px"><div class="fill" style="width: <?php __($percentageWidth) ?>"></div><span><?php __("M_STORAGE", array($numResults, $maxStorageSize)) ?></span></div>
	</div>
</div>

<form action="?module=messenger&amp;act=delete" method="post" class="personalMessenger">
	<table class="tableList">
		<tr>
			<th colspan="7">
				<div class="fleft"><?php __("M_FOLDER_INBOX") ?></div>
				<div class="fright">
					<a class="smallButton grey white transition" id="messengerDeleteMessages"><?php __("M_DELETE_SELECTED") ?></a>
					<a class="smallButton grey white transition" data-check="checkDeleteMessage"><?php __("M_SELECT_ALL") ?></a>
				</div>
			</th>
		</tr>
		<tr class="subtitle">
			<td class="min">&nbsp;</td>
			<td><?php __("M_SUBJECT") ?></td>
			<td width="20%"><?php __("M_FROM") ?></td>
			<td width="22%"><?php __("M_DATE") ?></td>
			<td class="min center"><i class="fa fa-check-square-o"></i></td>
		</tr>

		<?php
			if($numResults):
				foreach($results as $pm):
		?>
		<tr>
			<td class="min" style="font-size: 17px"><i class="fa <?php __($pm['icon_class']) ?>"></i></td>
			<td style="font-size: 14px;"><a href="?module=messenger&amp;read=<?php __($pm['pm_id']) ?>"><?php __($pm['subject']) ?></a></td>
			<td><a href="?module=profile&amp;id=<?php __($pm['from_id']) ?>"><?php __($pm['username']) ?></a></td>
			<td><?php __($pm['sent_date']) ?></td>
			<td class="min"><input type="checkbox" name="pm[]" value="<?php __($pm['pm_id']) ?>" class="checkDeleteMessage"></td>
		</tr>
		<?php
				endforeach;
			else:
		?>
		<tr>
			<td colspan="5" class="min center"><?php __("M_NO_PM_INBOX") ?></td>
		</tr>
		<?php
			endif;
		?>
	</table>
</form>

<?php elseif($view == "sent"): ?>

<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __("M_TITLE") ?></div>
	<div class="buttons fright">
		<a href="?module=messenger&amp;view=compose" class="defaultButton transition"><?php __("M_NEW_MESSAGE") ?></a>
	</div>
</div>

<?php __($notification) ?>

<div class="navigation">
	<div class="navbar">
		<a href="?module=messenger" class="transition selected"><?php __("M_FOLDERS") ?></a>
		<a href="" class="transition"><?php __("M_ATTACHMENTS") ?></a>
	</div>
	<div class="subnav">
		<a href="?module=messenger" class="transition"><?php __("M_FOLDER_INBOX") ?></a>
		<a href="?module=messenger&amp;view=sent" class="transition selected"><?php __("M_FOLDER_SENT") ?></a>
		<a href="?module=messenger&amp;view=drafts" class="transition"><?php __("M_FOLDER_DRAFTS") ?></a>
		<div class="progressBar" style="width: 204px; float: right; margin-top: 9px"><div class="fill" style="width: <?php __($percentageWidth) ?>"></div><span><?php __("M_STORAGE", array($numResults, $maxStorageSize)) ?></span></div>
	</div>
</div>

<form action="?module=messenger&amp;act=delete" method="post" class="personalMessenger">
	<table class="tableList">
		<tr>
			<th colspan="7">
				<div class="fleft"><?php __("M_FOLDER_SENT") ?></div>
				<div class="fright">
					<a class="smallButton grey white transition" id="messengerDeleteMessages"><?php __("M_DELETE_SELECTED") ?></a>
					<a class="smallButton grey white transition" data-check="checkDeleteMessage"><?php __("M_SELECT_ALL") ?></a>
				</div>
			</th>
		</tr>
		<tr class="subtitle">
			<td><?php __("M_SUBJECT") ?></td>
			<td width="20%"><?php __("M_TO") ?></td>
			<td width="22%"><?php __("M_DATE") ?></td>
			<td class="min center"><i class="fa fa-check-square-o"></i></td>
		</tr>

		<?php
			if($numResults):
				foreach($results as $pm):
		?>
		<tr>
			<td style="font-size: 14px;"><a href="?module=messenger&amp;read=<?php __($pm['pm_id']) ?>"><?php __($pm['subject']) ?></a></td>
			<td><a href="?module=profile&amp;id=<?php __($pm['from_id']) ?>"><?php __($pm['username']) ?></a></td>
			<td><?php __($pm['sent_date']) ?></td>
			<td class="min"><input type="checkbox" name="pm[]" value="<?php __($pm['pm_id']) ?>" class="checkDeleteMessage"></td>
		</tr>
		<?php
				endforeach;
			else:
		?>
		<tr>
			<td colspan="4" class="min center"><?php __("M_NO_PM_SENT") ?></td>
		</tr>
		<?php
			endif;
		?>
	</table>
</form>

<?php elseif($view == "compose"): ?>

<?php
	$this->header .= '<script type="text/javascript" src="resources/markdown.parser.js"></script>';
	$this->header .= '<script type="text/javascript">$(document).ready(function(){$(\'#post\').markdownRealTime()});</script>';
?>

<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __("M_TITLE") ?>: <?php __("M_COMPOSE") ?></div>
</div>

<div class="box">
	<form action="?module=messenger&amp;act=send" method="post" class="validate">
		<div class="inputBox">
			<div class="label"><?php __("M_TO") ?></div>
			<div class="field">
				<input type="hidden" name="to" id="pmTo" class="medium required">
			</div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("M_SUBJECT") ?></div>
			<div class="field"><input type="text" name="subject" class="large required"></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("M_MESSAGE") ?></div>
			<div class="field">
				<?php echo Html::Toolbar() ?>
				<textarea name="post" id="post" class="full required" rows="12"></textarea>
			</div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("M_ATTACHMENTS") ?></div>
			<div class="field"><input type="file" name="attachment"></div>
		</div>
		<div class="inputBox">
			<div class="label"><?php __("M_PREVIEW") ?></div>
			<div class="field textOnly">
				<div id="markdownPreview"></div>
			</div>
		</div>
		<div class="fleft">
			<div class="errorMessage"><?php __("M_PM_ERROR") ?></div>
		</div>
		<div class="fright">
			<input type="submit" value="<?php __("M_SEND") ?>">
		</div>
	</form>
</div>

<?php elseif($read): ?>

<?php
	$this->header .= '<script type="text/javascript" src="resources/markdown.parser.js"></script>';
	$this->header .= '<script type="text/javascript">$(document).ready(function(){$(\'.parsing\').markdownParser()});</script>';
?>

<div class="roomTitleBar">
	<div class="title fleft"><span><?php __($this->Core->config['general_communityname']) ?></span><?php __($message['subject']) ?></div>
	<div class="buttons fright">
		<a href="?module=messenger&amp;act=reply&amp;message=<?php __($message['pm_id']) ?>" class="defaultButton transition"><?php __("M_REPLY") ?></a>
		<a href="?module=messenger&amp;act=forward&amp;message=<?php __($message['pm_id']) ?>" class="defaultButton transition"><?php __("M_FORWARD") ?></a>
		<a href="?module=messenger&amp;act=delete&amp;message=<?php __($message['pm_id']) ?>" class="defaultButton grey transition"><?php __("M_DELETE") ?></a>
	</div>
</div>

<div class="postReply">
	<div class="author">
		<div class="photostack">
			<a href="?module=profile&amp;id=<?php __($message['from_id']) ?>">
				<?php __(Html::Crop($message['avatar'], 96, 96, "avatar")) ?>
			</a>
		</div>
		<p class="name"><a href="?module=profile&amp;id=<?php __($message['from_id']) ?>"><?php __($message['username']) ?></a></p>
		<p class="memberTitle"><?php __($message['member_title']) ?></p>
	</div>
	<div class="content">
		<div class="date"><?php __("M_SENT_ON", array($message['sent_date'])) ?></div>
		<div class="text">
			<span class="parsing"><?php __($message['message']) ?></span>
			<div class="signature parsing"><?php __($message['signature']) ?></div>
		</div>
	</div>
</div>

<?php endif; ?>
