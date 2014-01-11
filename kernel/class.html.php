<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: class.html.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------

	class Html
	{
		// ---------------------------------------------------
		// Display formatted error message
		// ---------------------------------------------------

		public static function Error($message)
		{
			echo "<h1>Error!</h1><p>" . $message . "</p><hr><em>Addictive Community - (c) 2014 All rights reserved.</em>";
			exit;
		}

		// ---------------------------------------------------
		// List of days of the month (1 to 31)
		// ---------------------------------------------------

		public static function Days($name, $current = 1)
		{
			$retval = "<select name=\"{$name}\" id=\"{$name}\">";

			for($i = 1; $i <= 31; $i++) {
				$selected = ($i == $current) ? "selected" : "";
				$retval .= "<option value=\"{$i}\" {$selected}>{$i}</option>";
			}

			$retval .= "</select>";

			return $retval;
		}

		// ---------------------------------------------------
		// List of months of the year (Jan to Dec)
		// ---------------------------------------------------

		public static function Months($name, $current = 1)
		{
			$retval = "<select name=\"{$name}\" id=\"{$name}\">";

			for($i = 1; $i <= 12; $i++) {
				$selected = ($i == $current) ? "selected" : "";
				$retval .= "<option value=\"{$i}\" {$selected}>{$i}</option>";
			}

			$retval .= "</select>";

			return $retval;
		}

		// ---------------------------------------------------
		// Really nice years drop-down generator
		// ---------------------------------------------------

		public static function Years($name, $before, $after)
		{
			$current = date("Y", time());

			$retval = "<select name=\"{$name}\" id=\"{$name}\">";

			for($i = $current - $before; $i <= $current + $after; $i++) {
				$selected = ($i == $current) ? "selected" : "";
				$retval .= "<option value=\"{$i}\" {$selected}>{$i}</option>";
			}

			$retval .= "</select>";

			return $retval;
		}

		// ---------------------------------------------------
		// Show notification message
		// ---------------------------------------------------

		public static function Notification($message, $code)
		{
			switch($code) {
				case "warning":
					$title = "WARNING!";
					break;
				case "success":
					$title = "SUCCESS!";
					break;
				case "failure":
					$title = "ERROR!";
					break;
				case "info":
					$title = "INFORMATION:";
					break;
			}
			
			$html = "<div class=\"notification " . $code . "\"><p><strong>" . $title . "</strong> " . $message . "</p></div>";
			
			return $html;
		}

		// ---------------------------------------------------
		// "Forum Rules" template
		// ---------------------------------------------------
		
		public static function ForumRules($title, $text)
		{
			$html = "<div class=\"notification warning\"><p><strong>" . $title . "</strong> " . $text . "</p></div>";
			return $html;
		}

		// ---------------------------------------------------
		// Get Gravatar or community avatar image path
		// ---------------------------------------------------

		public static function GetGravatar($email, $size = 96, $mode = "gravatar", $d = "mm", $r = "g", $img = false, $atts = array())
		{
			global $sql;
			
			$av_sql = clone($sql);
			
			if($mode == "gravatar")
			{
				$url = "http://www.gravatar.com/avatar/";
				$url .= md5(strtolower(trim($email)));
				$url .= "?s={$size}&d={$d}&r={$r}";
			}
			elseif($mode == "custom")
			{
				$av_sql->Query("SELECT photo FROM c_members WHERE email = '{$email}';");
				$url = $av_sql->Fetch();
				$url = $url['photo'];
				$url = "public/avatar/{$url}\" width=\"{$size}\" height=\"{$size}";
			}
			
			return $url;
		}

		// ---------------------------------------------------
		// Generate GD image
		// ---------------------------------------------------

		public static function ShowGDImage($content="")
		{
			flush();
			
			@header("Content-Type: image/jpeg");
			
			$font_style = 5;
			$no_chars   = strlen($content);
			
			$charheight = ImageFontHeight($font_style);
			$charwidth  = ImageFontWidth($font_style);
			$strwidth   = $charwidth * intval($no_chars);
			$strheight  = $charheight;
			
			$imgwidth   = $strwidth  + 15;
			$imgheight  = $strheight + 15;
			$img_c_x    = $imgwidth  / 2;
			$img_c_y    = $imgheight / 2;
			
			$im       = ImageCreate($imgwidth, $imgheight);
			$text_col = ImageColorAllocate($im, 0, 0, 0);
			$back_col = ImageColorAllocate($im, 240,240,240);
			
			ImageFilledRectangle($im, 0, 0, $imgwidth, $imgheight, $text_col);
			ImageFilledRectangle($im, 1, 1, $imgwidth - 2, $imgheight - 2, $back_col);
			
			$draw_pos_x = $img_c_x - ($strwidth  / 2) + 1;
			$draw_pos_y = $img_c_y - ($strheight / 2);
			
			ImageString($im, $font_style, $draw_pos_x, $draw_pos_y, $content, $text_col);
			
			ImageJPEG($im);
			ImageDestroy($im);
			
			exit();
		}
	}

?>