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

$msg = (Http::request("msg")) ? Http::request("msg") : "";

switch($msg) {
	case 1:
		$message = Html::notification("The settings has been successfully changed.", "success");
		break;
	default:
		$message = Html::notification("Words must be exact match! If you censor the word \"ass\", the word \"asshole\" will NOT be censored.", "warning");
		break;
}

?>

<h1>Bad Words</h1>

<div class="block">
	<form action="process.php?do=save" method="post">
		<?php echo $message ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="10">
						<div class="fleft">List of Bad Words</div>
					</th>
				</tr>
			</thead>
			<tr>
				<td class="font-w600">Replace with...</td>
				<td>
					<input type="text" name="language_bad_words_replacement" class="form-control span-2" value="<?php echo $Admin->selectConfig("language_bad_words_replacement") ?>">
				</td>
			</tr>
			<tr>
				<td class="font-w600">Bad words to be censored<small>One word per line.</small></td>
				<td>
					<textarea name="language_bad_words" rows="10" class="form-control span-6"><?php echo $Admin->selectConfig("language_bad_words") ?></textarea>
				</td>
			</tr>
		</table>
		<div class="text-right">
			<input type="submit" class="btn btn-default" value="Save Settings">
		</div>
	</form>
</div>
