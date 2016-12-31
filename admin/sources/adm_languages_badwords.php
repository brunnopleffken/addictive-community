<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_languages_manager.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Html;
use \AC\Kernel\Http;

$msg = (Http::Request("msg")) ? Http::Request("msg") : "";

switch($msg) {
	case 1:
		$message = Html::Notification("The settings has been successfully changed.", "success");
		break;
	default:
		$message = Html::Notification("Words must be exact match! If you censor the word \"ass\", the word \"asshole\" will NOT be censored.", "warning");
		break;
}

?>

<h1>Bad Words</h1>

<div id="content">
	<div class="grid-row">
		<form action="process.php?do=save" method="post">
			<?php echo $message ?>
			<table class="table-list">
				<tr>
					<th colspan="10">
						<div class="fleft">List of Bad Words</div>
					</th>
				</tr>
				<tr>
					<td class="title-fixed">Replace with...</td>
					<td>
						<input type="text" name="language_bad_words_replacement" class="small" value="<?php echo $Admin->SelectConfig("language_bad_words_replacement") ?>">
					</td>
				</tr>
				<tr>
					<td class="title-fixed">Bad words to be censored<span class="title-desc">One word per line.</span></td>
					<td>
						<textarea name="language_bad_words" rows="10" class="large"><?php echo $Admin->SelectConfig("language_bad_words") ?></textarea>
					</td>
				</tr>
			</table>
			<div class="box fright"><input type="submit" value="Save Bad Words"></div>
		</form>
	</div>
</div>
