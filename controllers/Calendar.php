<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Calendar.php
#  Release: v1.0.0
#  Copyright: (c) 2015 - Addictive Software
## -------------------------------------------------------

class Calendar extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * VIEW CALENDAR
	 * --------------------------------------------------------------------
	 */
	public function Main()
	{
		// Define messages
		$message_id = Html::Request("m");
		$notification = array("",
			Html::Notification(i18n::Translate("C_MESSAGE_1"), "success")
		);

		$this->Set("calendar", $this->_GenerateCalendar());
		$this->Set("notification", $notification[$message_id]);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: ADD NEW EVENT
	 * --------------------------------------------------------------------
	 */
	public function Add()
	{
		// Do not allow guests to view this page
		$this->Session->NoGuest();
	}

	/**
	 * --------------------------------------------------------------------
	 * ADD NEW EVENT TO CALENDAR
	 * --------------------------------------------------------------------
	 */
	public function Save()
	{
		$event = array(
			"title"     => Html::Request("title"),
			"type"      => Html::Request("type"),
			"author"    => $this->Session->member_info['m_id'],
			"day"       => Html::Request("day"),
			"month"     => Html::Request("month"),
			"year"      => Html::Request("year"),
			"timestamp" => mktime(
					Html::Request("hour"),
					Html::Request("minute"), 0,
					Html::Request("month"),
					Html::Request("day"),
					Html::Request("year")),
			"added"     => time(),
			"text"      => Html::Request("text"),
		);

		// Insert into database and redirect
		$this->Db->Insert("c_events", $event);
		$this->Core->Redirect("calendar?m=1");
	}

	/**
	 * --------------------------------------------------------------------
	 * REMOVE EVENT FROM CALENDAR
	 * --------------------------------------------------------------------
	 */
	public function Remove($event_id)
	{
		$this->layout = false;

		// Get current logged in member ID
		$member_id = $this->Session->member_info['m_id'];

		// Check if selected event exists
		$this->Db->Query("SELECT e_id FROM c_events
				WHERE e_id = '{$event_id}' AND author_id = '{$member_id}';");

		// if it exists, remove from DB
		// Otherwise, show error message
		if($this->Db->Rows() > 0) {
			$this->Db->Query("DELETE FROM c_events WHERE e_id = {$event_id}");
		}
		else {
			Html::Error("The selected event doesn't exist.");
		}

		// Redirect back to calendar view
		$this->Core->Redirect("calendar");
	}

	/**
	 * --------------------------------------------------------------------
	 * GENERATE CALENDAR
	 * --------------------------------------------------------------------
	 */
	private function _GenerateCalendar()
	{
		// Get current date/year, if not set
		$current_month = (Html::Request("month")) ? Html::Request("month") : date("m");
		$current_year  = (Html::Request("year")) ? Html::Request("year") : date("Y");

		// What is the day of today?
		$today_info = getdate(time());

		// Create array containing names of days of week.
		$w_days = array(
			i18n::Translate("W_1"), i18n::Translate("W_2"),
			i18n::Translate("W_3"), i18n::Translate("W_4"),
			i18n::Translate("W_5"), i18n::Translate("W_6"),
			i18n::Translate("W_7")
		);

		// What is the first day of the selected month?
		$month_first_day = mktime(0, 0, 0, $current_month, 1, $current_year);

		// How many days does this month contain?
		$num_days = date('t', $month_first_day);

		// Retrieve some information about the first day of the selected month
		$date_components = getdate($month_first_day);

		// What is the name of this month?
		$m_name = "M_" . $date_components['mon'];
		$m_name = i18n::Translate($m_name);

		// What is the index value (0-6) of the first day of the month in question.
		$w_day = $date_components['wday'];

		// Create the table tag opener and day headers
		Template::Add("<table class='calendar'>");
		Template::Add("<tr><th colspan='7'>{$m_name} {$current_year}</th></tr>");
		Template::Add("<tr>");

		// Create the calendar headers
		foreach($w_days as $day) {
			Template::Add("<td class='week'>{$day}</td>");
		}

		// Create the rest of the calendar
		// Initiate the day counter, starting with the 1st.
		$current_day = 1;
		Template::Add("</tr><tr>");

		// The variable $w_day is used to ensure that the calendar
		// display consists of exactly 7 columns.
		if ($w_day > 0) {
			Template::Add("<td class='fill' colspan='{$w_day}'>&nbsp;</td>");
		}

		$month = str_pad($current_month, 2, "0", STR_PAD_LEFT);

		while ($current_day <= $num_days) {
			// Seventh column (Saturday) reached. Start a new row.
			if ($w_day == 7) {
				$w_day = 0;
				Template::Add("</tr><tr>");
			}

			$current_day_formatted = str_pad($current_day, 2, "0", STR_PAD_LEFT);
			$date = "{$current_year}-{$current_month}-{$current_day_formatted}";

			// Do we have any event this day?
			$this->Db->Query("SELECT COUNT(e_id) AS event_number FROM c_events
					WHERE day = '{$current_day_formatted}'
						AND month = '{$current_month}'
						AND year = '{$current_year}';");

			$event_count = $this->Db->Fetch();
			$event_count = $event_count['event_number'];

			if($event_count != 0) {
				$marker = "style=\"background: #f8fcff url('templates/" . $this->config['template'] . "/images/star.png') no-repeat 95% 15%;\"";
			}
			else {
				$marker = "";
			}

			// Create table cell
			if( $current_month == $today_info['mon'] &&
				$current_year == $today_info['year'] &&
				$current_day == $today_info['mday']
			) {
				Template::Add("<td class='day today' rel='{$date}' {$marker}><b><a href=\"calendar/date/{$date}\">{$current_day}</a></b></td>");
			}
			else {
				Template::Add("<td class='day' rel='{$date}' {$marker}>
					<a href=\"calendar/date/{$date}\">{$current_day}</a></td>");
			}

			// Increment counters
			$current_day++;
			$w_day++;
		}
		// Complete the row of the last week in month, if necessary
		if ($w_day != 7) {
			$remaining_days = 7 - $w_day;
			Template::Add("<td class='fill' colspan='{$remaining_days}'>&nbsp;</td>");
		}

		// Export templates
		Template::Add("</tr></table>");
		$calendar = Template::Get();
		Template::Clean();

		// Define return variables
		$this->Set("c_month", $current_month);
		$this->Set("c_year", $current_year);

		return $calendar;
	}
}
