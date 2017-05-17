<?php

## -------------------------------------------------------
#  ADDICTIVE COMMUNITY
## -------------------------------------------------------
#  Created by Brunno Pleffken Hosti
#  http://github.com/brunnopleffken/addictive-community
#
#  File: Calendar.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## -------------------------------------------------------

namespace AC\Controllers;

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;
use \AC\Kernel\i18n;
use \AC\Kernel\Session\SessionState;
use \AC\Kernel\Template;

class Calendar extends Application
{
	/**
	 * --------------------------------------------------------------------
	 * VIEW CALENDAR
	 * --------------------------------------------------------------------
	 */
	public function index()
	{
		// Define messages
		$message_id = Http::request("m", true);
		$notification = array("",
			Html::notification(i18n::translate("calendar.notification.success"), "success")
		);

		// Page info
		$page_info['title'] = i18n::translate("calendar.title");
		$page_info['bc'] = array(i18n::translate("calendar.title"));
		$this->Set("page_info", $page_info);

		$this->Set("calendar", $this->generateCalendar());
		$this->Set("notification", $notification[$message_id]);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW: ADD NEW EVENT
	 * --------------------------------------------------------------------
	 */
	public function add()
	{
		// Do not allow guests to view this page
		SessionState::noGuest();

		// Page info
		$page_info['title'] = i18n::translate("calendar.add");
		$page_info['bc'] = array(i18n::translate("calendar.title"), i18n::translate("calendar.add"));
		$this->Set("page_info", $page_info);
	}

	/**
	 * --------------------------------------------------------------------
	 * VIEW DAY
	 * --------------------------------------------------------------------
	 */
	public function view()
	{
		// Redirect to Error 404 if the thread doesn't exist
		if(!Http::request("date")) {
			$this->Core->redirect("500");
		}

		// Get date and convert to array
		$date = explode("-", Http::request("date"));

		// Filter by type?
		// NOTE: until now, just private events
		$filter = Http::request("type") ? "AND type = 'private'" : "";

		// Get all events
		Database::query("SELECT e.*, m.username FROM c_events e
				INNER JOIN c_members m ON (e.author = m.m_id)
				WHERE year = {$date[0]}
					AND month = {$date[1]}
					AND day = {$date[2]}
					{$filter}
				ORDER BY timestamp ASC;");

		$events_count = (Database::rows() > 0) ? true : false;
		$events_result = Database::fetchToArray();

		// Format date to make it readable for human beings
		$formatted_date = date(
			$this->Core->config['date_short_format'],
			mktime(0, 0, 0, $date[1], $date[2], $date[0])
		);

		// Get all birthdays
		Database::query("SELECT m_id, username FROM c_members
				WHERE b_day = {$date[2]} AND b_month = {$date[1]};");

		$birthday_count = (Database::rows() > 0) ? true : false;
		$birthday_result = Database::fetchToArray();

		// Page info
		$page_info['title'] = i18n::translate("calendar.title");
		$page_info['bc'] = array(i18n::translate("calendar.title"), $formatted_date);
		$this->Set("page_info", $page_info);

		// Return variables
		$this->Set("date", $date);
		$this->Set("events_count", $events_count);
		$this->Set("events", $events_result);
		$this->Set("bday_count", $birthday_count);
		$this->Set("birthdays", $birthday_result);
		$this->Set("formatted_date", $formatted_date);
	}

	/**
	 * --------------------------------------------------------------------
	 * ADD NEW EVENT TO CALENDAR
	 * --------------------------------------------------------------------
	 */
	public function save()
	{
		$event = array(
			"title"     => Http::request("title"),
			"type"      => Http::request("type"),
			"author"    => SessionState::$user_data['m_id'],
			"day"       => Http::request("day"),
			"month"     => Http::request("month"),
			"year"      => Http::request("year"),
			"timestamp" => mktime(
				Http::request("hour"),
				Http::request("minute"), 0,
				Http::request("month"),
				Http::request("day"),
				Http::request("year")),
			"added"     => time(),
			"text"      => Http::request("text")
		);

		// Insert into database and redirect
		Database::insert("c_events", $event);
		$this->Core->redirect("calendar?m=1");
	}

	/**
	 * --------------------------------------------------------------------
	 * REMOVE EVENT FROM CALENDAR
	 * --------------------------------------------------------------------
	 */
	public function remove($event_id)
	{
		$this->layout = false;

		// Get current logged in member ID
		$member_id = SessionState::$user_data['m_id'];

		// Check if selected event exists
		Database::query("SELECT e_id FROM c_events
				WHERE e_id = '{$event_id}' AND author = '{$member_id}';");

		// if it exists, remove from DB
		// Otherwise, show error message
		if(Database::rows() > 0) {
			Database::delete("c_events", "e_id = {$event_id}");
		}
		else {
			Html::throwError("The selected event doesn't exist.");
		}

		// Redirect back to calendar view
		$this->Core->redirect("calendar");
	}

	/**
	 * --------------------------------------------------------------------
	 * GENERATE CALENDAR
	 * --------------------------------------------------------------------
	 */
	private function generateCalendar()
	{
		// Get current date/year, if not set
		$current_month = (Http::request("month", true)) ? Http::request("month", true) : date("m");
		$current_year  = (Http::request("year", true)) ? Http::request("year", true) : date("Y");

		// What is the day of today?
		$today_info = getdate(time());

		// Create array containing names of days of week.
		$w_days = array(
			i18n::translate("global.dates.w1"), i18n::translate("global.dates.w2"),
			i18n::translate("global.dates.w3"), i18n::translate("global.dates.w4"),
			i18n::translate("global.dates.w5"), i18n::translate("global.dates.w6"),
			i18n::translate("global.dates.w7")
		);

		// What is the first day of the selected month?
		$month_first_day = mktime(0, 0, 0, $current_month, 1, $current_year);

		// How many days does this month contain?
		$num_days = date('t', $month_first_day);

		// Retrieve some information about the first day of the selected month
		$date_components = getdate($month_first_day);

		// What is the name of this month?
		$m_name = "global.dates.m" . $date_components['mon'];
		$m_name = i18n::translate($m_name);

		// What is the index value (0-6) of the first day of the month in question.
		$w_day = $date_components['wday'];

		// Create the table tag opener and day headers
		Template::add("<table class='table calendar'><thead>");
		Template::add("<tr><th colspan='7'>{$m_name} {$current_year}</th></tr>");

		// Create the calendar headers
		Template::add("<tr>");
		foreach($w_days as $day) {
			Template::add("<td>{$day}</td>");
		}
		Template::add("</tr></thead>");

		// Create the rest of the calendar
		// Initiate the day counter, starting with the 1st.
		$current_day = 1;

		Template::add("<tr>");

		// The variable $w_day is used to ensure that the calendar
		// display consists of exactly 7 columns.
		if($w_day > 0) {
			Template::add("<td colspan='{$w_day}'>&nbsp;</td>");
		}

		while($current_day <= $num_days) {
			// Seventh column (Saturday) reached. Start a new row.
			if($w_day == 7) {
				$w_day = 0;
				Template::add("</tr><tr>");
			}

			$current_day_formatted = str_pad($current_day, 2, "0", STR_PAD_LEFT);
			$date = "{$current_year}-{$current_month}-{$current_day_formatted}";

			Database::query("SELECT
					(SELECT COUNT(*) FROM c_events
						WHERE day = '{$current_day_formatted}'
							AND month = '{$current_month}'
							AND year = '{$current_year}') AS event_number,
					(SELECT COUNT(*) FROM c_members
						WHERE b_day = {$current_day_formatted} AND b_month = {$current_month}) AS birthday_number,
					(SELECT SUM(event_number + birthday_number)) AS events_total;");

			$event_count = Database::fetch();
			$event_total = $event_count['events_total'];

			// If day has an event, add class .event to it
			$event_class = ($event_total != 0) ? "event" : "";

			// Create table cell
			if( $current_month == $today_info['mon'] &&
				$current_year  == $today_info['year'] &&
				$current_day   == $today_info['mday']
			) {
				Template::add("<td class='today {$event_class}'><a href='calendar/view?date={$date}'>{$current_day}</a></td>");
			}
			else {
				Template::add("<td class='{$event_class}'><a href='calendar/view?date={$date}'>{$current_day}</a></td>");
			}

			// Increment counters
			$current_day++;
			$w_day++;
		}

		// Complete the row of the last week in month, if necessary
		if($w_day != 7) {
			$remaining_days = 7 - $w_day;
			Template::add("<td colspan='{$remaining_days}'>&nbsp;</td>");
		}

		// Export templates
		Template::add("</tr></table>");
		$calendar = Template::get();
		Template::clean();

		// Define return variables
		$this->Set("c_month", $current_month);
		$this->Set("c_year", $current_year);

		return $calendar;
	}
}
