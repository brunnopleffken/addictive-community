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
	// What are we doing?
	// ---------------------------------------------------

	$act = (Html::Request("act")) ? Html::Request("act") : "month";

	$db = $this->Db;

	switch($act) {

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
			$today_info = getdate(time());
			
			// Create array containing names of days of week.
			$w_days = array(
				$this->t["w_1"], $this->t["w_2"],
				$this->t["w_3"], $this->t["w_4"],
				$this->t["w_5"], $this->t["w_6"],
				$this->t["w_7"]
			);

			// What is the first day of the selected month?
			$m_firstday = mktime(0,0,0,$c_month,1,$c_year);

			// How many days does this month contain?
			$num_days = date('t',$m_firstday);

			// Retrieve some information about the first day of the selected month
			$date_components = getdate($m_firstday);

			// What is the name of this month?
			$m_name = "m_" . $date_components['mon'];
			$m_name = $this->t[$m_name];

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
			$current_day = 1;
			Template::Add("</tr><tr>");
			
			// The variable $w_day is used to ensure that the calendar
			// display consists of exactly 7 columns.
			if ($w_day > 0) { 
				Template::Add("<td class='fill' colspan='{$w_day}'>&nbsp;</td>");
			}
			
			$month = str_pad($c_month, 2, "0", STR_PAD_LEFT);
			
			while ($current_day <= $num_days) {
				// Seventh column (Saturday) reached. Start a new row.
				if ($w_day == 7) {
					$w_day = 0;
					Template::Add("</tr><tr>");
				}
				
				$current_day_rel = str_pad($current_day, 2, "0", STR_PAD_LEFT);
				$date = "{$c_year}-{$c_month}-{$current_day_rel}";
				
				// Do we have any event this day?
				$db->Query("SELECT COUNT(e_id) AS event_number FROM c_events
					WHERE day = '{$current_day_rel}' AND month = '{$c_month}' AND year = '{$c_year}';");
				
				$event_count = $this->Db->Fetch();
				$event_count = $event_count['event_number'];
				
				if($event_count != 0) {
					$event_marker = "style=\"background: #f8fcff url('templates/1/images/star.png') no-repeat 95% 10%;\"";
				}
				else {
					$event_marker = "";
				}
				
				// Create table cell
				
				if( $c_month == $today_info['mon'] &&
					$c_year == $today_info['year'] &&
					$current_day == $today_info['mday']
				) {
					Template::Add("<td class='today' rel='{$date}' {$event_marker}><b><a href=\"{$date}\">{$current_day}</a></b></td>");
				}
				else {
					Template::Add("<td class='day' rel='{$date}' {$event_marker}><a href=\"index.php?module=calendar&amp;act=event&amp;date={$date}\">{$current_day}</a></td>");
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

			Template::Add("</tr></table>");

			$calendar = Template::Get();
			Template::Clear();

			break;

		// ---------------------------------------------------
		// ADD NEW EVENT VIEW
		// ---------------------------------------------------
		
		case "addevent":
			
			break;
	}

	// ---------------------------------------------------
	// Where are we?
	// ---------------------------------------------------
	
	// Page information
	$pageinfo['title'] = "Calendar";
	$pageinfo['bc'] = array("Calendar");

?>