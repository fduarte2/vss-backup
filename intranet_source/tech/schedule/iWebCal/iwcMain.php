<? 
/* iWebCal v1.1
 * Copyright (C) 2003 David A. Feldman.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of version 2 of the GNU General Public License 
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU 
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the Free Software Foundation, 
 * Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA. Or, 
 * visit http://gnu.org.
 * 
 * This file is part of the iWebCal calendar-viewing service. The iWebCal
 * service is available on the Web at http://iWebCal.com, and does not
 * require any programming knowledge or Web server configuration to use.
 * Anyone with an iCal or other .ics calendar file and a place to post
 * it on the Web can view the calendar using iWebCal.
 */
 
 // File version 1.1, last modified April 13, 2003.

$baseBrowserInfo = explode(" ", $_SERVER["HTTP_USER_AGENT"]);
if (preg_match("/Safari/", $baseBrowserInfo[sizeof($baseBrowserInfo) - 1])) {
	$usingSafari = 1;
}
else {
	$usingSafari = 0;
}


$ACCEPTED_ITEM_TYPES = array(
	"VEVENT",
	"VTODO"
	);
	
$ACCEPTED_PROPERTIES = array(
	"SUMMARY",
	"RRULE",
	"DTSTART",
	"DTEND",
	"PRIORITY",
	"DUE",
	"STATUS",
	"DURATION",
	"DESCRIPTION"
);
$MULTI_VALUE_PROPERTIES = array(
	"RRULE"
);

// [TO DO](1) - Can replace some explode/strftime calls with getdate(), might be quicker

$TYPE_DAY = 0;
$TYPE_WEEK = 1;
$TYPE_MONTH = 2;

// [TO DO](1) - maybe add this to session so it doesn't get calc'ed every time.
//  Also consider other variables to which this might apply.
$topTmp = strtotime("last Sunday");
$WEEKDAY_FULL_NAMES = array();
$WEEKDAY_FULL_NAMES[] = strftime("%A", $topTmp);
for ($i=1;$i<7;$i++) {
	$WEEKDAY_FULL_NAMES[] = strftime("%A", strtotime("+${i} days", $topTmp));
}
$MONTH_FULL_NAMES = array(
	"January",
	"February",
	"March",
	"April",
	"May",
	"June",
	"July",
	"August",
	"September",
	"October",
	"November",
	"December"
);

// Centralized style info (in addition to CSS):
$monthCellColor = "#ffffff";
$monthTodayCellColor = "#eeeeee";
$monthEmptyCellColor = $monthCellColor;
$monthSelectedDayNumberColor = "#660000";


// Use of these variables sort of breaks the object-oriented model a little
// but I'd rather not add parameters to object methods for a workaround
// for a browser bug. With luck Apple will fix the problem in Safari and
// these variables can be removed again.
$safariCurrentContainerHeight = $iWebCal_SAFARI_TABLE_HEIGHT;
$safariCurrentContainerWidth = $iWebCal_SAFARI_TABLE_WIDTH;


// ----------------------------------------------------------------------
// CLASSES

include "${iWebCal_LOCAL_PATH}/Property.php";
include "${iWebCal_LOCAL_PATH}/CalItem.php";
include "${iWebCal_LOCAL_PATH}/Calendar.php";


// ----------------------------------------------------------------------


function fatalError($errString) {
	trigger_error("iWebCal Error: " . $errString);
}

function monthName($monthNum) {
	return strftime("%B", strtotime("2002-$monthNum-06"));
}

function dayNumber($day) {
	switch($day) {
		case "SU":
			return 0;
			break;
		case "MO":
			return 1;
			break;
		case "TU":
			return 2;
			break;
		case "WE":
			return 3;
			break;
		case "TH":
			return 4;
			break;
		case "FR":
			return 5;
			break;
		case "SA":
			return 6;
			break;
	}
	return -999;
}

function offsetByDuration($startdate, $duration) {
	$timeStart = strpos($duration, "T");
	$timestr = substr($duration, $timeStart+1);
	list($hours, $remainder) = explode("H", $timestr);
	if ($hours == $timestr) {
		$hours = 0;
		$remainder = $timestr;
	}
	if ($remainder) {
		list($minutes, $remainder2) = explode("M", $remainder);
		if ($minutes == $remainder) {
			$minutes = 0;
		}
	}
	else {
		$minutes = 0;
	}
	$result = strtotime("+${hours} hours", $startdate);
	$result = strtotime("+${minutes} minutes", $result);
	return $result;
}

// ----------------------------------------------------------------------


?>