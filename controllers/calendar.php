<?php

	## ---------------------------------------------------
	#  ADDICTIVE COMMUNITY
	## ---------------------------------------------------
	#  Developed by Brunno Pleffken Hosti
	#  File: calendar.php
	#  Release: v1.0.0
	#  Copyright: (c) 2014 - Addictive Software
	## ---------------------------------------------------


	// ---------------------------------------------------
	// Set actions
	// ---------------------------------------------------

	$act = (Html::Request("act")) ? Html::Request("act") : null;

	switch($act) {
		case "addevent":
			String::PR($_REQUEST);

			$event = array(
				"title"		=> Html::Request("title"),
				"type"		=> Html::Request("type"),
				"author"	=> $this->member['m_id'],
				"day"		=> Html::Request("day"),
				"month"		=> Html::Request("month"),
				"year"		=> Html::Request("year"),
				"timestamp"	=> mktime(
						Html::Request("hour"),
						Html::Request("minute"), 0,
						Html::Request("month"),
						Html::Request("day"),
						Html::Request("year")),
				"added"		=> time(),
				"text"		=> Html::Request("text"),
			);

			$this->Db->Insert("c_events", $event);

			header("Location: index.php?module=calendar&m=1");
			exit;

			break;
	}

	// ---------------------------------------------------
	// MESSAGES AND NOTIFICATIONS
	// ---------------------------------------------------

	$m = Html::Request("m");

	switch($m) {
		case 1:
			$notification = Html::Notification("Your event has been successfully submitted.", "success");
			break;
		default:
			$notification = "";
			break;
	}

	// ---------------------------------------------------
	// What are we viewing?
	// ---------------------------------------------------

	$view = (Html::Request("view")) ? Html::Request("view") : "month";

	switch($view) {

		// ---------------------------------------------------
		// CALENDAR VIEW
		// ---------------------------------------------------

		case "month":

			// ---------------------------------------------------
			// Get current date/year, if not set
			// ---------------------------------------------------

			$c_month	= (Html::Request("month")) ? Html::Request("month") : date("n");
			$c_year		= (Html::Request("year")) ? Html::Request("year") : date("Y");

			/// ---------------------------------------------------
			// Let's see our calendar!
			// ---------------------------------------------------

			// What is the day of today?
			$todayInfo = getdate(time());

			// Create array containing names of days of week.
			$w_days = array(
				i18n::Translate("W_1"), i18n::Translate("W_2"),
				i18n::Translate("W_3"), i18n::Translate("W_4"),
				i18n::Translate("W_5"), i18n::Translate("W_6"),
				i18n::Translate("W_7")
			);

			// What is the first day of the selected month?
			$m_firstday = mktime(0,0,0,$c_month,1,$c_year);

			// How many days does this month contain?
			$num_days = date('t',$m_firstday);

			// Retrieve some information about the first day of the selected month
			$date_components = getdate($m_firstday);

			// What is the name of this month?
			$m_name = "M_" . $date_components['mon'];
			$m_name = i18n::Translate($m_name);

			// What is the index value (0-6) of the first day of the month in question.
			$w_day = $date_components['wday'];

			// Create the table tag opener and day headers
			Template::Add("<table class='calendar'>");
			Template::Add("<tr><th colspan='7'>{$m_name} {$c_year}</th></tr>");
			Template::Add("<tr>");

			// Create the calendar headers
			foreach($w_days as $day) {
				Template::Add("<td class='week'>{$day}</td>");
			}

			// Create the rest of the calendar
			// Initiate the day counter, starting with the 1st.
			$currentDay = 1;
			Template::Add("</tr><tr>");

			// The variable $w_day is used to ensure that the calendar
			// display consists of exactly 7 columns.
			if ($w_day > 0) {
				Template::Add("<td class='fill' colspan='{$w_day}'>&nbsp;</td>");
			}

			$month = str_pad($c_month, 2, "0", STR_PAD_LEFT);

			while ($currentDay <= $num_days) {
				// Seventh column (Saturday) reached. Start a new row.
				if ($w_day == 7) {
					$w_day = 0;
					Template::Add("</tr><tr>");
				}

				$currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
				$date = "{$c_year}-{$c_month}-{$currentDayRel}";

				// Do we have any event this day?
				$this->Db->Query("SELECT COUNT(e_id) AS event_number FROM c_events "
						. "WHERE day = '{$currentDayRel}' AND month = '{$c_month}' AND year = '{$c_year}';");

				$eventCount = $this->Db->Fetch();
				$eventCount = $eventCount['event_number'];

				if($eventCount != 0) {
					$eventMarker = "style=\"background: #f8fcff url('" . $this->p['IMG'] . "/star.png') no-repeat 95% 15%;\"";
				}
				else {
					$eventMarker = "";
				}

				// Create table cell

				if( $c_month == $todayInfo['mon'] &&
					$c_year == $todayInfo['year'] &&
					$currentDay == $todayInfo['mday']
				) {
					Template::Add("<td class='today' rel='{$date}' {$eventMarker}><b><a href=\"{$date}\">{$currentDay}</a></b></td>");
				}
				else {
					Template::Add("<td class='day' rel='{$date}' {$eventMarker}>
						<a href=\"index.php?module=calendar&amp;act=event&amp;date={$date}\">{$currentDay}</a></td>");
				}

				// Increment counters
				$currentDay++;
				$w_day++;
			}

			// Complete the row of the last week in month, if necessary

			if ($w_day != 7) {
				$remaining_days = 7 - $w_day;
				Template::Add("<td class='fill' colspan='{$remaining_days}'>&nbsp;</td>");
			}

			Template::Add("</tr></table>");

			$calendar = Template::Get();
			Template::Clean();

			// Where are we?
			$pageinfo['title'] = "Calendar";
			$pageinfo['bc'] = array("Calendar");

			break;

		// ---------------------------------------------------
		// ADD NEW EVENT VIEW
		// ---------------------------------------------------

		case "addevent":

			// Where are we?
			$pageinfo['title'] = "Add Event";
			$pageinfo['bc'] = array("Calendar", "Add Event");

			break;
	}


?>
