<?php

// include("compareDate.php");

// Is your day a weekend
// Returns true for yes, false for no
// Requires a date in getdate() format and also $i is the number of days after
// your date
function IsWeekend($today, $i){
  $weekend_day = date('w', mktime (0,0,0, $today['mon'], ($today['mday'] + $i), $today['year']));

  // 6 = Sat, 0 = Sun
  if ($weekend_day == 6 || $weekend_day == 0) {
    return true;
  } else {
    return false;
  }
}


// Is your day a holiday?
// Returns true for yes, false for no
// Requires a date in getdate() format and also $i is the number of days after your date
function IsHoliday($today, $i){
  $my_date = date('m/d/Y', mktime (0,0,0, $today['mon'], ($today['mday'] + $i), $today['year']));
  $y = date('Y', mktime (0,0,0, $today['mon'], ($today['mday'] + $i), $today['year']));

  // New Year's Day
  $holidays[0] = format_date($y, 1, 1);

  // Martin Luther King, Jr.
  $holidays[1] = get_holiday($y, 1, 1, 3);

  // Memorial Day
  $holidays[2] = get_holiday($y, 5, 1);

  // Independance Day
  $holidays[3] = observed_day($y, 7, 4);

  // Labor Day
  $holidays[4] = get_holiday($y, 9, 1, 1);

  // Thanksgiving
  $holidays[5] = get_holiday($y, 11, 4, 4);

  // Christmas
  $holidays[6] = format_date($y, 12, 25);

  for($x = 0; $x <= 6; $x++){
    // Now Compare the dates to see if we have a holiday or not...
    $holiday = compareDate($my_date, $holidays[$x]);
    if($holiday == 0){
      return true;
    }
  }

  return false;

  // Valentines Day
  // $holidays[2] = format_date($y, 2, 14);

  // Presidents Day
  // $holidays[3] = get_holiday($y, 2, 1, 3);

  // St. Patricks
  // format_date($y, 3, 17);

  // Easter
  // $holidays[2] = calculate_easter($y);

  // Columbus Day
  //$holidays[8] = get_holiday($y, 10, 1, 2);

  // Columbus Day
  // format_date($y, 10, 31);

  // test- make April 1 a holiday
  // $holidays[10] = format_date($y, 4, 1);
}



// Is your day a working day
// Returns true for yes and false for no
// Requires a date in a format that can be interperated by strtotime function, like MM/DD/YYYY
function IsWorkingDay($my_day){
  $date_info = getdate(strtotime($my_day));
  
  if (IsWeekend($date_info, 0) || IsHoliday($date_info, 0)) {
    return false;
  } else {
    return true;
  }
}


// Return the ith working day in MM/DD/YYYY format
// When $i < 0, return the ith working day before the given date
//      $i = 0, return the given date
//      $i > 0, return the ith working day after the given date
// Requires a date in a format that can be interperated by strtotime function, like MM/DD/YYYY
function get_ith_working_day($start_date, $i) {

  $num_days = abs($i);
  $days_to_add = 0;
  $date_info = getdate(strtotime($start_date));

  if ($i == 0) {
    return $start_date;
  } else {
    // have $num_days loops to get the ith working day
    for ($j=0; $j<$num_days; $j++) {
      // we get one working day after the do-while loop
      do {
	if ($i > 0) {
	  $days_to_add++;
	} else {
	  $days_to_add--;
	}

	$next_day = date('m/d/Y', mktime (0,0,0, $date_info['mon'], ($date_info['mday'] + $days_to_add), 
					  $date_info['year']));
      } while (!IsWorkingDay($next_day));
    }
  } 

  return $next_day;
}


/* US Holiday Calculations in PHP
 * Version 1.02
 * by Dan Kaplan <design@abledesign.com>
 * Last Modified: April 15, 2001
 * ------------------------------------------------------------------------
 * The holiday calculations on this page were assembled for
 * use in MyCalendar:  http://abledesign.com/programs/MyCalendar/
 * 
 * USE THIS LIBRARY AT YOUR OWN RISK; no warranties are expressed or
 * implied. You may modify the file however you see fit, so long as
 * you retain this header information and any credits to other sources
 * throughout the file.  If you make any modifications or improvements,
 * please send them via email to Dan Kaplan <design@abledesign.com>.
 * ------------------------------------------------------------------------
*/

// Gregorian Calendar = 1583 or later
if (!$y || ($y < 1583) || ($y > 4099)) {
    $y = date("Y",time());    // use the current year if nothing is specified
}


// require a 4-digit year input
function format_date($year, $month, $day) {
    // pad single digit months/days with a leading zero for consistency (aesthetics)
    // and format the date as desired: MM/DD/YYYY

    if (strlen($month) == 1) {
        $month = "0". $month;
    }
    if (strlen($day) == 1) {
        $day = "0". $day;
    }

    $date = $month . "/" . $day . "/" . $year;
    return $date;
}

// the following function get_holiday() is based on the work done by
// Marcos J. Montes: http://www.smart.net/~mmontes/ushols.html
//
// if $week is not passed in, then we are checking for the last week of the month
function get_holiday($year, $month, $day_of_week, $week="") {
  if ((($week != "") && (($week > 5) || ($week < 1))) || ($day_of_week > 6) || ($day_of_week < 0)) {
    // $day_of_week must be between 0 and 6 (Sun=0, ... Sat=6); $week must be between 1 and 5
    return FALSE;
  } else {
    if (!$week || ($week == "")) {
      $lastday = date("t", mktime(0,0,0,$month,1,$year));  // days in total in that month
      $temp = (date("w",mktime(0,0,0,$month,$lastday,$year)) - $day_of_week) % 7;
    } else {
      $temp = ($day_of_week - date("w",mktime(0,0,0,$month,1,$year))) % 7;
    }
    
    if ($temp < 0) {
      $temp += 7;
    }
    
    if (!$week || ($week == "")) {
      $day = $lastday - $temp;
    } else {
      $day = (7 * $week) - 6 + $temp;
    }
    
    return format_date($year, $month, $day);
  }
}


function observed_day($year, $month, $day) {
    // sat -> fri & sun -> mon, any exceptions?
    //
    // should check $lastday for bumping forward and $firstday for bumping back,
    // although New Year's & Easter look to be the only holidays that potentially
    // move to a different month, and both are accounted for.

    $dow = date("w", mktime(0, 0, 0, $month, $day, $year));
    
    if ($dow == 0) {
      $dow = $day + 1;
    } elseif ($dow == 6) {
      if (($month == 1) && ($day == 1)) {    // New Year's on a Saturday
	$year--;
	$month = 12;
	$dow = 31;
      } else {
	$dow = $day - 1;
      }
    } else {
      $dow = $day;
    }
    
    return format_date($year, $month, $dow);
}


function calculate_easter($y) {
    // In the text below, 'intval($var1/$var2)' represents an integer division neglecting
    // the remainder, while % is division keeping only the remainder. So 30/7=4, and 30%7=2
    //
    // This algorithm is from Practical Astronomy With Your Calculator, 2nd Edition by Peter
    // Duffett-Smith. It was originally from Butcher's Ecclesiastical Calendar, published in
    // 1876. This algorithm has also been published in the 1922 book General Astronomy by
    // Spencer Jones; in The Journal of the British Astronomical Association (Vol.88, page
    // 91, December 1977); and in Astronomical Algorithms (1991) by Jean Meeus. 

    $a = $y%19;
    $b = intval($y/100);
    $c = $y%100;
    $d = intval($b/4);
    $e = $b%4;
    $f = intval(($b+8)/25);
    $g = intval(($b-$f+1)/3);
    $h = (19*$a+$b-$d-$g+15)%30;
    $i = intval($c/4);
    $k = $c%4;
    $l = (32+2*$e+2*$i-$h-$k)%7;
    $m = intval(($a+11*$h+22*$l)/451);
    $p = ($h+$l-7*$m+114)%31;
    $EasterMonth = intval(($h+$l-7*$m+114)/31);    // [3 = March, 4 = April]
    $EasterDay = $p+1;    // (day in Easter Month)
    
    return format_date($y, $EasterMonth, $EasterDay);
}

function count_dows_in_month($year, $month, $day_of_week) {
    // count how many weeks in the month have a specified day, such as Monday.
    // we know there will be 4 or 5, so no need to check for $weeks<4 or $weeks>5

    $firstday = date("w", mktime(0, 0, 0, $month, 1, $year));
    $lastday = date("t", mktime(0, 0, 0, $month, 1, $year));

    if ($firstday > $day_of_week) {
        // means we need to jump to the second week to find the first $day_of_week
        $d = (7 - ($firstday - $day_of_week)) + 1;
    } elseif ($firstday < $day_of_week) {
        // correct week, now move forward to specified day
        $d = ($day_of_week - $firstday + 1);
    } else {    // $firstday = $day_of_week
        // correct day in first week
        $d = ($firstday - 1);
    }

    $d += 28;    // jump to the 5th week and see if the day exists
    if ($d > $lastday) {
        $weeks = 4;
    } else {
        $weeks = 5;
    }
    return $weeks;
}
