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
 

/* +------------------------------------------------------------------------------+
   | USER SETTINGS: Change these to match your server configuration.              |
   +------------------------------------------------------------------------------+
*/

// This should be the local path to the iWebCal source directory
$iWebCal_LOCAL_PATH = "/home/design2/public_html/iwebcal/iWebCal";

// This should be the URL (when accessed over the Web) of the iWebCal
// source directory. You don't need the "http://". Do NOT include
// a trailing slash, even if you're specifying the root directory
// of the site. For example, if the iWebCal directory is the root
// directory of your site, just leave this variable blank as it is below.
$iWebCal_URL_PATH = "/iwebcal/iWebCal";


// TASK SORTING VARIABLES: In Task view, column headers can be links that allow
// you to re-sort the list. 

// Task sorting can be turned on and off with the $iWebCal_ENABLE_TASK_SORTING
// variable. The other task sorting variables can be ignored if this is set to
// false.
$iWebCal_ENABLE_TASK_SORTING = true; 

// $iWebCal_TASK_SORT_DESTINATION specifies the actual destination for the sort
// link. This will be inserted into the HREF property of the A (link) tag. In
// many cases you'll want to use JavaScript code, which can be done by starting
// the link with "javascript:".
//
// In most cases you'll need the new sort column somewhere in the URL. 
// iWebCal will insert this data anywhere you insert the string [[NEW_SORT]].
// Note that quotation marks won't be inserted along with the column name, so you'll
// need to include those yourself if they're needed.
//
// You should also be careful with your use of quotation marks. The value of
// $iWebCal_TASK_SORT_DESTINATION will be enclosed in quotation marks as is 
// standard with HTML tag properties, i.e. <a href="your-value-here">. In the case
// of JavaScript values, just use single quotes instead. Most URL values won't
// need quotation marks, but if yours does just use an appropriately URL-encoded
// entity.
//
// A couple simple examples to get you started:
// (1) A simple URL value, reloading the current page (index.php in this case) and
// passing the new sort column as a variable in the URL named sort:
// $iWebCal_TASK_SORT_DESTINATION = "index.php?sort=[[NEW_SORT]]";
//
// (2) A simple JavaScript value. This supposes you have a form named controlForm that
// holds all the variables needed to display the calendar. Submitting this form refreshes
// the calendar with the current form values, since they all get included in the
// request to the Web server. This JavaScript first sets the value of a form element named
// sort to the new sort column, then submits the form.
// $iWebCal_TASK_SORT_DESTINATION = "javascript:document.controlForm.sort.value='[[NEW_SORT]]';document.controlForm.submit();";
//
// The default value below just displays a message about setting this variable. It's not
// intended for actual use, and the entire multi-line definition can (and should) be removed
// and replaced with your value as described above.
$iWebCal_TASK_SORT_DESTINATION = "javascript:document.open(); document.write('"
	.	"<b>Error:</b> Sorting the task list by the [[NEW_SORT]] column was unsuccessful"
	.	": The <b>\$iWebCal_TASK_SORT_DESTINATION</b> variable "
	.	"(located in iWebCal.php) has not been set correctly. You must set this "
	.	"variable so iWebCal knows what page to load or what action to take "
	.	"when a sort is requested.<p>If you\'d prefer to disable task sorting, "
	.	"set <b>\$iWebCal_ENABLE_TASK_SORTING</b> to false.<p>"
	.	"<b>If you\'re a visitor</b> to this site, please let the site administrator know "
	.	"that you received this error message so the problem can be corrected."
	. "');document.close()";


// ERROR REPORTING: Once you've got your site working, uncomment the lines below
// to prevent the user from seeing PHP errors.
//error_reporting(0);
//error_reporting(E_USER_WARNING | E_USER_ERROR | E_USER_NOTICE);


// $iWebCal_MAIN_FILENAME specifies the filename of the main file (script) used for
// displaying calendar files. It's used as a target for forms and buttons that change
// the display. Generally it's just the filename of whatever PHP page you're using
// to display your calendar. In some cases you may want to specify some kind of
// relative path, but in general you're just re-loading whatever page you're currently
// displaying so a simple filename will suffice.
$iWebCal_MAIN_FILENAME = "index.php";


// --------------------------------------------------------------------------------
// You may optionally change the variables below to customize iWebCal:

// $iWebCal_HOUR_HEIGHT is the height of an hour in pixels for the Day and Week views.
// If you change it you'll want to alter the background image img/day-bg.gif
// accordingly.
$iWebCal_HOUR_HEIGHT = 64;

// $iWebCal_TASK_TABLE_WIDTH is the width of the Calendar object's task view.
// You can set it to a numeric value for a fixed pixel width, or to a percentage
// value (in which case it should be in quotes). Values should not be below 550 px.
$iWebCal_TASK_TABLE_WIDTH = 700;


// Apple's Safari currently has problems rendering some page elements whose
// dimensions are specified as percentages. As such, iWebCal checks which browser
// is being used and renders some calendar layouts with either a fixed height
// or a fixed width. These variables define those values (in pixels).
$iWebCal_SAFARI_TABLE_WIDTH = 750;
$iWebCal_SAFARI_TABLE_HEIGHT = 525;


// --------------------------------------------------------------------------------


include "${iWebCal_LOCAL_PATH}/iwcMain.php";


?>