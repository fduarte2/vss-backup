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
 * This file contains code based on the iWebCal calendar-viewing service. This
 * service is available on the Web at http://iWebCal.com, and does not
 * require any programming knowledge or Web server configuration to use.
 * Anyone with an iCal or other .ics calendar file and a place to post
 * it on the Web can view the calendar using iWebCal.
 */
 
 // File version 1.1, last modified April 13, 2003.


// A very simple iWebCal setup, displaying a single calendar.
// iWebCal is capable of handling multiple calendars, as you can see from the main
// iWebCal.com site, which uses very little code beyond the basic iWebCal source to
// do its work. However, this should get you started. :-) It's based on the PHP
// file used to display calendars on the iWebCal site. Note that you'll still need
// to set some environment variables in iWebCal.php, though one is set below.
//
// I've included comments throughout this file to get you up to speed writing your own
// index file, and reading through may help you familiarize yourself with what iWebCal
// and the Calendar object can do.
//
// Note that throughout this file I refer to variables (like the date, or the calendar
// view) that the user has set. If you're not incredibly familiar with PHP, you may be 
// wondering where the user set them. When you submit a form, the form's values are encoded
// into the URL that gets sent to the server. For example, if you have a form with text boxes
// named "first_textbox" and "second_textbox", and you give them values of "one" and "two"
// respectively, your URL might look something like this:
//
// http://mysite.com/my-form-target.php?first_textbox=one&second_textbox=two
//
// Note that if your form is submitted via the POST method, these values won't be visible in
// the URL, but they're sent anyway. If your target page is a PHP page (as in the example 
// above), that page will receive all those values as PHP variables (for both the GET and 
// POST methods). For example, the page my-form-target.php in the example above would be 
// able to assume two variables, $first_textbox and $second_textbox, whose values would be 
// "one" and "two".
//
// In addition, in PHP you can refer to a nonexistant variable without causing any problems 
// -- its value is just not set. So if I loaded my-form-target.php without a form 
// submission, I could still use $first_textbox as a variable. Its value would just be empty. 


// OK. Enough preamble, here we go...


// SET THIS VARIABLE to the location of your calendar (.ics) file. It can be a local file
// on the server (like "/Users/myusername/Library/Calendars/Calendar.ics"), a calendar
// accessible via the Web (like "http://mywebsite.com/calendars/Calendar.ics") or a local
// folder containing one or more calendars (like "/Users/myusername/Library/Calendars").
$myCalendarFile = "/Volumes/home/daf/Library/Calendars/";

// CHANGE THIS VARIABLE if you change the filename of this file. It's used
// to specify URLs for things like form submission. If the file's still
// named index.php, you don't need to do anything.
$thisFilename = "index.php";

include "iWebCal.php";

// Normally you'd just change this value in iWebCal.php, but since this is
// a demo page, we're setting it here so as to leave the default value
// in place in iWebCal.php.
$iWebCal_TASK_SORT_DESTINATION = "javascript:document.controlForm.sort.value='[[NEW_SORT]]';document.controlForm.submit()";


// Start PHP's session-handling so the calendar can be stored from page load to page load.
// This is EXTREMELY important to the page's speed, since (a) loading a new calendar is
// much more time-consuming than displaying an existing one, and (b) every time you
// display a calendar, iWebCal caches information to make future work with that
// calendar quicker.
session_start();

// If a calendar hasn't already been stored in the PHP session, we're starting from
// scratch and should register our calendar with PHP session-handling.
if ($new_session = !session_is_registered("stored_calendar")) {
	session_register("stored_calendar");
}


// If we don't already have a calendar, create one using the file specified in
// $myCalendarFile above.
if ($new_session) {
	$cal = new Calendar($myCalendarFile); 
	
	// Calendar objects validate themselves. If the isValid variable is false, we
	// don't have a valid calendar. However, if a folder was specified instead
	// of a calendar file, the Calendar object will have a list of all .ics files
	// in the folder, which we'd like to get.
	if (!$cal->isValid && $cal->folderContents && sizeof($cal->folderContents)) {
		// $myCalendarFile is a folder. Open the first calendar file in that
		// folder, and store the folder contents to display in a list.
		session_register("folderContents");
		$folderContents = $cal->folderContents;
		session_register("folder_url"); // stored in session rather than URL for security reasons
		$folder_url = $cal->url; // this is just $myCalendarFile, but with a guaranteed trailing slash
		
		if (!$fileInFolder) {
			$fileInFolder = $folderContents[0];
		}
		$cal = new Calendar($cal->url . $fileInFolder);
		
		// Since some links for navigating the calendar are generated by the Calendar object itself,
		// we have to pass fileInFolder to the Calendar object as an additional URL variable:
		$cal->addURLVariable("fileInFolder", $fileInFolder);
	}		
}
else {
	// Read in the stored calendar from the PHP session variable.
	$cal = unserialize($stored_calendar);
}

// If we're browsing a folder the user has selected a different calendar
// from the list, load it.
if (session_is_registered("folder_url") && (($folder_url . $fileInFolder) != $cal->url)) {
	$cal = new Calendar($folder_url . $fileInFolder);
}
// If a calendar has been stored but for some reason it's for a different
// calendar file than the one in $myCalendarFile, create a new calendar
// from the file. 
elseif (!session_is_registered("folder_url") && ($cal->url != $myCalendarFile)) {
	$cal = new Calendar($myCalendarFile);
}

if (!$sort) {
	// Sort tasks by priority at first. ($sort will be set if the user
	// has specified a sort.)
	$sort = "priority";
}
else {
	// If the user has specified a sort, re-sort the tasks accordingly.
	$cal->sortTasks($sort);
}

// If the user has elected to display completed tasks, let our calendar
// object know that.
$cal->showCompletedTasks($showCompleted);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8">
		<title>iWebCal</title>
		<link href="iWebCal.css" rel="stylesheet" media="screen">
		
		<?
		// If the user hasn't specified a date to display, we want to
		// display today's date.
		
		// Get today's date as a UNIX time stamp.
		$today = getdate();
		
		// If any of the month, day, or year parameters haven't been
		// specified by the user, set them based on $today.
		if (!$month) $month = $today["mon"];
		if (!$year) $year = $today["year"];
		if (!$day) $day = $today["mday"];
		
		// If the specified date is not valid (for example, February 31),
		// reset the day of the month to 1. Note that this doesn't help
		// if the user has manually entered weird values into the URL --
		// for example, a month of 15 or something -- but is instead
		// designed to handle cases such as a user paging forward a month
		// while looking at January 31. Users who want to type up their
		// own URLs can do so at their own risk :-).
		if (!checkdate($month, $day, $year)) {
			$day = 1;
		}
		
		// If the user hasn't specified which calendar view to display,
		// choose the Month view.
		if (!$view) $view = "month";
		
		// Explicitly cast the date parameters as integers so arithmetic
		// on them is done properly.
		$year = (int)$year;
		$month = (int)$month;
		$day = (int)$day;
		
		// This URL is the target for the "today" button, which
		// will link to today in the current view mode.
		$todayURL = "${thisFilename}?view=${view}&year=";
		$todayURL .= $today["year"] . "&month=";
		$todayURL .= $today["mon"] . "&day=";
		$todayURL .= $today["mday"];
		// Add a variable for the filename if we're dealing with a folder.
		if (session_is_registered("folder_url")) {
			$todayURL .= "&fileInFolder=${fileInFolder}";
		}
		
		
		// Generate URLs for next/prev buttons
		switch($view) {
			// We need 3 different calculations here depending on which view we're in.
			// (There aren't prev/next buttons in Task view.)
			case "month":
				$nextMonth = ($month == 12) ? 1 : $month+1;
				$prevMonth = ($month == 1) ? 12 : $month-1;
				$nextYear = ($month == 12) ? $year+1 : $year;
				$prevYear = ($month == 1) ? $year-1 : $year;
				$prevURL = "${thisFilename}?view=month&year=${prevYear}&month=${prevMonth}";
				$nextURL = "${thisFilename}?view=month&year=${nextYear}&month=${nextMonth}";
				break;
			case "week":
				list($nextYear, $nextMonth, $nextDay) = explode(",", strftime("%Y,%m,%e", strtotime("+7 days", strtotime("${year}-${month}-${day}"))));
				list($prevYear, $prevMonth, $prevDay) = explode(",", strftime("%Y,%m,%e", strtotime("-7 days", strtotime("${year}-${month}-${day}"))));
				
				$prevURL = "${thisFilename}?view=week&year=${prevYear}&month=${prevMonth}&day=${prevDay}";
				$nextURL = "${thisFilename}?view=week&year=${nextYear}&month=${nextMonth}&day=${nextDay}";
				
				break;
			case "day":
				list($nextYear, $nextMonth, $nextDay) = explode(",", strftime("%Y,%m,%e", strtotime("+1 day", strtotime("${year}-${month}-${day}"))));
				list($prevYear, $prevMonth, $prevDay) = explode(",", strftime("%Y,%m,%e", strtotime("-1 day", strtotime("${year}-${month}-${day}"))));
				
				$prevURL = "${thisFilename}?view=day&year=${prevYear}&month=${prevMonth}&day=${prevDay}";
				$nextURL = "${thisFilename}?view=day&year=${nextYear}&month=${nextMonth}&day=${nextDay}";
				break;
		}
		// Add a variable for the filename if we're dealing with a folder.
		if (session_is_registered("folder_url")) {
			$prevURL .= "&fileInFolder=${fileInFolder}";
			$nextURL .= "&fileInFolder=${fileInFolder}";
		}
		?>
		
	</head>

	<body bgcolor="white" leftmargin="12" marginheight="6" marginwidth="12" topmargin="6">
		<?
		// Calendar objects validate themselves. Use this to check and see if
		// anything went wrong during the calendar creation or loading process.
		if ($cal->isValid) {
			?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
			<tr>
				<td height="4"><img src="img/pix-transparent.gif" alt="" height="1" width="1" border="0"></td>
			</tr>
			<tr height="32">
			<td valign="middle" height="32"><?
				// If $myCalendarFile was a folder of calendar files, $folder_url
				// is registered as a session variable.
				if (session_is_registered("folder_url")) {
					// Create a dropdown with all the ics files in the folder
					// (already stored in $folderContents), and select the current one.
					// This dropdown is contained inside a form for compatibility reasons, but the form
					// itself is never submitted. Instead, selecting an item from the dropdown assigns
					// the new value to a hidden folder item in the controlForm (below), and submits
					// that form. This won't work in NS4.
					?>
					<form action="" method="get" name="folderSelectForm">
					<select name="fileInFolder" size="1" onchange="document.controlForm.fileInFolder.value=document.folderSelectForm.fileInFolder.value;document.controlForm.submit()">
					<?
					foreach($folderContents as $thisCalFile) {
						echo "<option value=\"${thisCalFile}\"";
						if ($thisCalFile == $fileInFolder) {
							echo " selected>";
							echo $cal->title;
						}
						else {
							echo ">";
							echo preg_replace("/\.ics$/", "", $thisCalFile);
						}
						echo "</option>\n";
					}
					?>
					</select>
					</form>
					<?
				}
				else {
					// Just a regular calendar file, no folders.
					// Calendar objects store a title for themselves. This is either a title specified in the
					// calendar file itself, or if no such title exists, a title created based on the
					// calendar's filename.
					?><h1 style="margin:0"><? echo $cal->title; ?></h1><?
				}
				?>	
			</td>			
			</tr>
			<tr>
				<td height="1" bgcolor="#cccccc"><img src="img/pix-transparent.gif" alt="" height="1" width="1" border="0"></td>
			</tr>
			<tr>
				<td height="6"><img src="img/pix-transparent.gif" alt="" height="6" width="1" border="0"></td>
			</tr>
			<tr>
				<td height="18">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<?
							if ($view != "tasks") {
								// If we're displaying Task view, we don't want Previous and Next controls, and since
								// the view title for Task view is just "Tasks," we're going to choose not to display
								// that either.
								?>
								<td valign="middle">
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<? 
											if ($view != "tasks") {
												?>
												<td>
													<!-- Previous button -->
													<a href="<? echo $prevURL ?>"><img src="img/btn-prev.gif" alt="" height="24" width="24" border="0"></a>
												</td>
												
												<td class="ViewTitle">
													&nbsp;
												
													<?
													// Call the printViewTitle() method of the Calendar object to display an appropriate title for the view.
													// For Day, Week, and Month views printViewTitle() displays information about the date range being
													// displayed, appropriate to maintaining a consistent title length. For Task views, the title is just
													// "Tasks," so we're going to hide it in that case.
													if ($view != "tasks")
													$cal->printViewTitle($year, $month, $day, $view);
													?>
											
													&nbsp;
												</td>
												<td>
													<!-- Next button -->
													<a href="<? echo $nextURL ?>"><img src="img/btn-next.gif" alt="" height="24" width="24" border="0"></a>
												</td>
												<?
											}
											?>
										</tr>
									</table>
								</td>
								<?
							}
							?>
							<td align="left" valign="middle">
								<table border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td align="left" valign="middle" bgcolor="white"><?
											if ($view == "tasks") {
												// We need to display some extra controls for a Task view. These are in a separate
												// form that never actually gets submitted, but just changes hidden values in the
												// main control form.
												?>
												<form method="get" name="taskControlForm" action="">
												<script language="JavaScript" type="text/javascript">
												<!--
													// This JavaScript function is called when the user checks or unchecked the
													// "show completed tasks" box.
													function toggleShowCompleted() {
														// Set the main control form's target view to Task view.
														document.controlForm.view.value = "tasks";
														
														// Set the main control form's value for completed task visibility
														// as appropriate.
														if (document.taskControlForm.showCompleted.checked) {
															document.controlForm.showCompleted.value = "1";
														}
														else {
															document.controlForm.showCompleted.value = "0";
														}
														
														// Submit the main control form, refreshing updating the display with
														// the new values.
														document.controlForm.submit();	
													}												
												// -->
												</script>
												<table border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td><input type="checkbox" name="showCompleted" value="1" onclick="toggleShowCompleted()" <? if ($showCompleted) { ?>checked<? } ?>></td>
														<td>&nbsp;Show completed tasks</td>
													</tr>
												</table>
											</form>
											<?
											}
											else {
												// If we're not in Task view, show a Today button to jump to the current date.
												?><a href="<? echo $todayURL ?>"><img src="img/btn-today.gif" alt="" height="20" width="52" border="0"></a><?
											}
										?></td>
										<td align="left" valign="middle" width="36">&nbsp;</td>
										<td align="left" valign="middle">
											<!-- This is our main control form for changing the calendar display. Submitting just loads this page again, with
											the new values specified in the form. -->
											<form action="<? echo $thisFilename ?>" method="get" name="controlForm">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td valign="middle">
															<!-- Month dropdown box -->
															<select class="HeaderControl" name="month" size="1">
																<option value="1" <? if ($month == 1) { ?> selected<? } ?>>Jan</option>
																<option value="2" <? if ($month == 2) { ?> selected<? } ?>>Feb</option>
																<option value="3" <? if ($month == 3) { ?> selected<? } ?>>Mar</option>
																<option value="4" <? if ($month == 4) { ?> selected<? } ?>>Apr</option>
																<option value="5" <? if ($month == 5) { ?> selected<? } ?>>May</option>
																<option value="6" <? if ($month == 6) { ?> selected<? } ?>>Jun</option>
																<option value="7" <? if ($month == 7) { ?> selected<? } ?>>Jul</option>
																<option value="8" <? if ($month == 8) { ?> selected<? } ?>>Aug</option>
																<option value="9" <? if ($month == 9) { ?> selected<? } ?>>Sep</option>
																<option value="10" <? if ($month == 10) { ?> selected<? } ?>>Oct</option>
																<option value="11" <? if ($month == 11) { ?> selected<? } ?>>Nov</option>
																<option value="12" <? if ($month == 12) { ?> selected<? } ?>>Dec</option>
															</select>&nbsp;</td>
														<td valign="middle">
															<!-- Day of the month input box -->
															<input class="HeaderControl" type="text" name="day" value="<? echo $day ?>" size="3" maxlength="2">
														</td>
														<td valign="middle">&nbsp;
															<!-- Year input box -->
															<input class="HeaderControl" type="text" name="year" value="<? echo $year ?>" size="5" maxlength="4">
															
															<!-- Hidden form values for view (changed by the view buttons to the right), completed task visibility
															(changed by the task control form to the left, and task sort column 
															(changed by the linked task column headers, which are generated by the Calendar object). -->
															<input type="hidden" name="view" value="<? echo $view; ?>">
															<input type="hidden" name="showCompleted" value="<? echo $showCompleted ?>">
															<input type="hidden" name="sort" value="<? echo $sort ?>">
															<input type="hidden" name="fileInFolder" value="<? echo $fileInFolder ?>">
														</td>
														<td valign="middle"><img src="img/pix-transparent.gif" alt="" height="4" width="4" border="0"></td>
														<td valign="middle"><input type="image" src="img/btn-go.gif" alt="" height="21" width="20"></td>
													</tr>
												</table>
											</form>
										</td>
									</tr>
								</table>
							</td>
							<td align="right" valign="middle" width="210">
								<table border="0" cellspacing="0" cellpadding="0">
									<tr>
										<!-- The images below control the calendar view mode (Day, Week, Month, or Task). Each linked image's onclick handler
										changes the value of the hidden view field in the control form, then submits that form. -->
										<td><a href="#" onclick="document.controlForm.view.value='day';document.controlForm.submit();"><img src='img/radio-day<? if ($view == "day") echo "-sel"; ?>.gif' alt="Day" height="21" width="38" border="0"></a></td>
										<td><a href="#" onclick="document.controlForm.view.value='week';document.controlForm.submit();"><img src='img/radio-week<? if ($view == "week") echo "-sel"; ?>.gif' alt="Week" height="21" width="44" border="0"></a></td>
										<td><a href="#" onclick="document.controlForm.view.value='month';document.controlForm.submit();"><img src='img/radio-month<? if ($view == "month") echo "-sel"; ?>.gif' alt="Month" height="21" width="51" border="0"></a></td>
										<td><a href="#" onclick="document.controlForm.view.value='tasks';document.controlForm.submit();"><img src='img/radio-tasks<? if ($view == "tasks") echo "-sel"; ?>.gif' alt="Tasks" height="21" width="51" border="0"></a></td>
										<td>&nbsp;&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<!-- This transparent image just provides some white space -->
					<img src="img/pix-transparent.gif" width="10" height="12">
				</td>
			</tr>
			<tr>
				<td valign="top"><? 
					// Call the Calendar's printCal() method. This WRITES MOST OF THE PAGE, displaying the calendar
					// in whatever view has been selected.
					$cal->printCal($year, $month, $day, $view);
					
					// If you need to do debugging, you can use Calendar's dprint() method to print out raw Calendar
					// object data in a reasonably readable form. Just comment out the call to printCal() above and
					// uncomment the line below. For actual deployment, make sure the dprint() call is commented out
					// again.
					//$cal->dprint();


				?></td>
			</tr>
		</table>
		<?
	}
	else {
		// This is the ELSE clause for the (if $cal->isValid) check near the top of
		// the page. If the Calendar isn't valid, we print an error message instead.
		// If the Calendar isn't valid, the Calendar variable errorString will
		// contain a more specific error message, which we display here.
		echo $cal->errorString;
	}
	
	// Calendar objects change every time they're displayed. Generally, this change involves
	// caching calculations so that each time you display the calendar it's a little faster.
	// As such, we want to overwrite our stored PHP session variable copy of the calendar
	// with the current one.
	$stored_calendar = serialize($cal);
	?>
	</body>

</html>