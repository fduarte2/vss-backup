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

$sortInProgress = NULL;

if (!$iWebCal_POWERED_BY_LOGO) {
	$iWebCal_POWERED_BY_LOGO = "http://interfacethis.com/iwebcal/img/powered-by-logo.gif";
}

class Calendar {
	var $items;
	var $dateIndex;
	var $repeatingEvents;
	var $discardedProperties;
	var $repeatingInstances; // this variable's use only works because calendar isn't editable
	var $url;
	var $internalUrl;
	var $title;
	var $isValid;
	var $folderContents;
	var $errorString;
	var $taskIndex;
	var $taskShowCompleted;
	var $taskSort;
	var $extraURLVariables;
	
	// --------------------------------------------------
	
	function Calendar($url = NULL) {
		global $USER_PREFS, $iWebCal_Prefs;
	
		$this->items = array();
		$this->dateIndex = array();
		$this->taskIndex = array();
		$this->discardedProperties = array();
		$this->repeatingEvents = array();
		$this->repeatingInstances = array();
		$this->title = "Untitled";
		$this->url = NULL;
		$this->internalUrl = NULL;
		$this->isValid = true;
		$this->folderContents = false;
		$this->taskShowCompleted = false;
		$this->taskSort = "";
		$this->extraURLVariables = array();
		
		if ($url) {
			$this->url = trim($url);
			$this->internalUrl = trim($url);
			
			// First, see if the file is a directory.
			if (is_dir($this->internalUrl)) {
				// It's a folder. Invalid calendar but it's a folder.
				$this->isValid = false;
				$this->errorString = "Folder specified rather than file. Its contents are available from the Calendar object (see iWebCal documentation).";
				
				// remove trailing slash (if any) from URL
				$this->url = preg_replace("/\/$/", "", $this->url);
				$this->internalUrl = preg_replace("/\/$/", "", $this->internalUrl);
				
				// Now add a trailing slash so we know there's one.
				$this->url = $this->url . "/";
				$this->internalUrl = $this->internalUrl . "/";
				
				$myDirHandle = opendir($this->internalUrl);
				if ($myDirHandle) {
					$this->folderContents = array();
					while ($thisFile = readdir($myDirHandle)) {
						if (preg_match("/\.ics$/", $thisFile)) {
							$this->folderContents[] = $thisFile;
						}
					}
					closedir($myDirHandle);
					if (!sizeof($this->folderContents)) {
						$this->errorString = "Folder specified rather than file, but it contains no calendar files.";
					}
				}
				else {
					$this->errorString = "Folder specified rather than file, but iWebCal was unable to read its contents.";
				}
				
				return NULL;
			}
							
			// Next, try to open the url unchanged
			if (!file_exists($this->internalUrl)) {
				// Not a valid local file as submitted.
				
				// Assume it's remote, and get file piece.
				if (preg_match("/^webcal:\/\//", $this->internalUrl)) {
					$this->internalUrl = substr($this->internalUrl, 9);
				}
				elseif (preg_match("/^http:\/\//", $this->internalUrl)) {
					$this->internalUrl = substr($this->internalUrl, 7);
				}
				$this->internalUrl = "http://" . preg_replace("/ /", "%20", $this->internalUrl);
		
				$fp = fopen(preg_replace("/ /", "%20", $this->internalUrl), "r");
				if (!$fp) {
					$this->isValid = false;
					if (is_dir($this->internalUrl)) {
						$this->isFolder = true;
					}
					$this->errorString = "Calendar file couldn't be opened.";
					return NULL;
				}
			}
			else {
				$fp = fopen($this->internalUrl, "r");
			}
				
			preg_match("/\/([^\/]*)\.ics/", $this->internalUrl, $matches);
			$this->title = $matches[1];
			$lineCount = 0;
			$cal_data = array();
			while ($line = fgets($fp, 4096)) {
				$lineCount++;
				if ($lineCount > 24000) {
					$this->isValid = false;
					$this->errorString = "Sorry, iWebCal cannot display iCal files this large.";
					return NULL;
				}
				$line = rtrim($line);
				$line_nospace = ltrim($line);
				if ($line_nospace != $line) {
					// this is part of the previous line
					$cal_data[count($cal_data)-1] .= " " . $line_nospace;
				}
				else {
					$cal_data[] = $line_nospace;
				}		
			}
			
			
			if (!$cal_data || !count($cal_data)) {
				$this->isValid = false;
				$this->errorString = "Calendar file is empty.";
				return NULL;
			}
			$firstProp = new Property($cal_data[0]);
			if (!$firstProp->match("BEGIN", "VCALENDAR")) {
				$this->isValid = false;
				$this->errorString = "Not a valid iCal calendar file.";
				return NULL;
			}
						
			$item = NULL;
			for ($index=1;$index<count($cal_data);$index++) {
				$prop = new Property($cal_data[$index]);
				
				if ($prop) {
					if ($item) {
						// an item of some type is open
						
						if ($prop->match("BEGIN")) {
							// It's a sub-item (like an alarm). right now we don't handle them.
							// We also assume only one level of nested data.
							// [TO DO] For a future version: Handle this in a more robust manner
							// by allowing CalItems to store a list of sub-items, themselves
							// CalItems.
							while (1) {
								$prop = new Property($cal_data[$index]);
								$index++;
								if ($prop->match("END")) {
									break;
								}
							}
						}
						if ($prop->match("END")) {
							// something is ending. figure it's our item
							$this->addItem($item);
							$item = NULL;
						}
						else {
							// add property to item
							if (in_array($prop->name, $GLOBALS["ACCEPTED_PROPERTIES"])) {
								$item->addProperty($prop);
							}
							else {
								if (!(in_array($prop->name, $this->discardedProperties))) {
									$this->discardProperty($prop->name);
								}
							}
						}
					}
					else {
						// no current item
						
						$propVal = $prop->value();
						if ($prop->match("BEGIN") && in_array($propVal, $GLOBALS["ACCEPTED_ITEM_TYPES"])) {
							// open an event
							$item = new CalItem($propVal);
						}
						elseif ($prop->match("X-WR-CALNAME")) {
							$this->title = $prop->value();
						}
						
						// if not a recognized item begin line, ignore until
						// one is found
					}
				}
						
			}
			
			// So OK, we've loaded the file. Store it in user prefs
			$exp = strtotime("+3 months");
			if (!isset($iWebCal_Prefs)) {
				$USER_PREFS = array();
				$USER_PREFS["cal urls"] = array();
				$USER_PREFS["cal titles"] = array();
				setcookie("iWebCal_Prefs", serialize($USER_PREFS), $exp);
			}
			else {
				$USER_PREFS = unserialize(stripslashes($iWebCal_Prefs));
			}
			if (!in_array($this->url, $USER_PREFS["cal urls"])) {
				$USER_PREFS["cal urls"][] = $this->url;
				$USER_PREFS["cal titles"][] = $this->title;
			}
			
			setcookie("iWebCal_Prefs", serialize($USER_PREFS), $exp);
			
			if (count($this->taskIndex)) {
				$this->sortTasks("priority");
			}
			
			fclose($fp);		
		}
	}
	
	// --------------------------------------------------

	function addItem($item) {
		// Add item to main array
		$this->items[] = &$item;
		
		if ($item->type == "VEVENT") {
			// Index item by date(s) if appropriate
			$itemDates = $item->getDates();
			if ($itemDates) {
				foreach($itemDates as $thisDate) {
					$parts = explode("-", $thisDate);
					
					$this->dateIndex[(int)$parts[0]][(int)$parts[1]][(int)$parts[2]][] = &$item;
				}
			}
			
			// Index item by repeating schedule if appropriate
			if ($item->getProperty("RRULE")) {
				$this->repeatingEvents[] = &$item;
			}
		}
		elseif ($item->type == "VTODO") {
			// Add to task index
			$this->taskIndex[] = &$item;
		}				
	}
	
	// --------------------------------------------------

	function discardProperty($name) {
		if (!(in_array($name, $this->discardedProperties))) {
			$this->discardedProperties[] = $name;
		}
	}
	
	// --------------------------------------------------

	function dprint() {
		echo "<p><b>Discarded Properties:</b><br>";
		foreach($this->discardedProperties as $dp) {
			echo $dp;
			echo "<br>";
		}
		echo "</p>";
		?>
<table cellspacing="1" cellpadding="6" bgcolor="#999999">
	<? 		$i=0;
		foreach($this->items as $item) {
			echo "<tr><td bgcolor=#ffffff valign=top>$i</td><td bgcolor=#ffffff>";
			$item->dprint();
			echo "</td></tr>";
			$i++;
		}
		echo "</table>";
		echo "<p>";
	}
	
	// --------------------------------------------------
	
	function itemsForDate($year, $month, $day) {
		$staticInstances = $this->dateIndex[$year][$month][$day];
	
		if ($cachedInstances = $this->repeatingInstances[$year][$month][$day]) {
			// we've calculated this day before and cached it. Hooray!
			if ($cachedInstances == -1) return $staticInstances;
			$result = array_merge($cachedInstances, $staticInstances);
			usort($result, array("CalItem", "sortComparison"));
			return $result;
		}		
	
		$result = array();
		foreach ($this->repeatingEvents as $evt) {
			if ($evt->repeatsOnDate(strftime("%Y-%m-%d", strtotime("${year}-${month}-${day}")))) {
				$result[] = $evt;
			}
		}
		if ($result) {
			$this->repeatingInstances[$year][$month][$day] = $result;
		}
		else {
			$this->repeatingInstances[$year][$month][$day] = -1;
		}
		$result = array_merge($result, $staticInstances);
		usort($result, array("CalItem", "sortComparison"));
		return $result;
	}

	// --------------------------------------------------

	function printMonth($year, $month, $day) {
		global $WEEKDAY_FULL_NAMES, $iWebCal_MAIN_FILENAME, $usingSafari, $iWebCal_SAFARI_TABLE_HEIGHT;
		$today = getdate();
		
		if ($usingSafari) {
			$mainTableHeight = "";
		}
		else {
			$mainTableHeight = "100%";
		}
	
		// 0 is Sunday
		$startWeekDay = strftime("%u", strtotime("${year}-${month}-01")) % 7;
		
		if (!$GLOBALS["iWC_FORK_ROOT_PROD"]) {
			?>
	<table width="100%" cellspacing="0" cellpadding="0" height="100%">
		<tr>
			<td><? 		}
		?>
				<table class="CalMonth" width="100%" cellspacing="1" cellpadding="0" height="<? echo $mainTableHeight ?>">
					<tr><? 				// print days of the week at the top
				for ($i=0; $i<7; $i++) {
					?>
						<td class="WeekColHeaderCell" align="center" valign="middle" height="18"><? echo $WEEKDAY_FULL_NAMES[$i];
					?></td>
						<? 				}
				?></tr>
					<? 
			for ($i=0; $i<7; $i++) {
				if (checkdate($month, $i-$startWeekDay+1, $year)) {
					$firstDay = $i;
					break;
				}
			}
			for ($i=27; $i<42; $i++) {
				if (!checkdate($month, $i-$startWeekDay+1, $year)) {
					$lastDay = $i-1;
					$numRows = (int)floor($i / 7) + 1;
					break;
				}
			}
			
			$colWidth = "14%";
			if ($usingSafari) {
				$rowHeight = (int)round($iWebCal_SAFARI_TABLE_HEIGHT / $numRows);
			}
			else {
				$rowHeight = (int)round(100 / $numRows) . "%";
			}
						
			for ($i=0;$i<$firstDay;$i++) {
				?>
					<td class="EmptyCell" bgcolor='<? echo $GLOBALS["monthEmptyCellColor"] ?>' width="<? echo $colWidth ?>" height="<? echo $rowHeight ?>">&nbsp;</td>
					<? 				
			}
			for ($i=$firstDay;$i<=$lastDay;$i++) {
				if ($i % 7 == 0) {
					?>
					<tr><? 				}
				$thisDay = $i - $startWeekDay + 1; 
				$thisFile = $this->url;
				$thisLink = "${iWebCal_MAIN_FILENAME}?view=day&year=${year}&month=${month}&day=${thisDay}&file=${thisFile}";
				foreach ($this->extraURLVariables as $varName => $varVal) {
					$thisLink .= "&${varName}=${varVal}";
				}
				?>
						<td valign="top" bgcolor='<? echo ((($thisDay == $today["mday"]) && ($month == $today["mon"])) ? $GLOBALS["monthTodayCellColor"] : $GLOBALS["monthCellColor"]); ?>' width="<? echo $colWidth ?>" height="<? echo $rowHeight ?>">
							<table class="DayTable" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td class='<? echo (($thisDay == $day) ? "SelectedDayHeaderCell" : "HeaderCell") ?>' align="right" height="16"><a href="<? echo $thisLink ?>"><? echo $thisDay ?></a>&nbsp;</td>
								</tr>
								<tr>
									<td class="ContentCell"><? 									
								$myItems = $this->itemsForDate($year, $month, $i-$startWeekDay+1);
								if ($myItems) {
									foreach($myItems as $item) $item->display("month", $thisLink);
								}
								?></td>
								</tr>
							</table>
						</td>
						<? 	
				if ($i % 7 == 6) {
					?></tr>
					<? 				}
			}
			for ($i=$lastDay+1;$i<($numRows * 7);$i++) {
				?>
					<td class="EmptyCell" bgcolor='<? echo $GLOBALS["monthEmptyCellColor"] ?>' width="<? echo $colWidth ?>" height="<? echo $rowHeight ?>">&nbsp;</td>
					<? 				
			}
				
			
			?>
				</table>
				<? 		if (!$GLOBALS["iWC_FORK_ROOT_PROD"]) {
			?></td>
		</tr>
		<tr>
			<td align="center" valign="bottom" height="46"><a href="http://iwebcal.com"><img src='<? echo $GLOBALS["iWebCal_POWERED_BY_LOGO"] ?>' height="39" width="94" border="0"></a></td>
		</tr>
	</table>
	<? 		}
	}
	
	function getDayLayout($year, $month, $day) {
		$items = $this->itemsForDate($year, $month, $day);
		if (!$items || !count($items)) return NULL;
		// from here on out we can assume that $items contains at least one item
		
		usort($items, startDateComparison);
		// items is now sorted by start date ascending			
		
		$columnCount = 1;
		$result = NULL;
		$openItems = array();
		$openItems[0] = array();
		$untimedItems = array();
		
		foreach($items as $item) {
			if ($item->isAllDay()) {
				$untimedItems[] = $item;
			}
			else {
				$itemStart = $item->startDate();
			
				// remove closed items, and determine the lowest column
				// with no overlap
				$lowestColumn = 0;
				for ($i=$columnCount-1;$i>=0;$i--) {
					$overlap = false;
					foreach ($openItems[$i] as $thisKey => $thisOpenItem) {
						if ($thisOpenItem->endDate() <= $itemStart) {
							unset($openItems[$i][$thisKey]);
						}
						else {
							$overlap = true;
						}
					}
					if (!$overlap) $lowestColumn = $i + 1;
				}
				
				if ($lowestColumn) {
					// an existing column has room for this item
					$openItems[$lowestColumn-1][] = $item;
					$result[$lowestColumn-1][] = $item;
				}
				else {
					// we need a new column
					$openItems[$columnCount][] = $item;
					$result[$columnCount][] = $item;
					$columnCount++;
				}
			}
		}
		$result[] = $untimedItems;
		
		return $result;
		
	}
	
	function printUntimedEventsForDay($layout, $view) {
		global $iWebCal_URL_PATH;
		if ($layout) {
			$classString = ($view == "week") ? "Week" : "Day";
			$items = array_pop($layout);
			
			if ($items && count($items)) {
				?>
	<table class="UntimedEventTable" width="100%" cellspacing="0" cellpadding="0">
		<? 					for ($i=0;$i<count($items);$i++) {
						?>
		<tr>
			<td>
				<table class="OuterItemTable" width="100%" cellspacing="1" cellpadding="0" height="100%">
					<tr>
						<td valign="top"><? $items[$i]->display($view) ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="1"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
		</tr>
		<? 					}
					?>
	</table>
	<? 			}
		}
	}
		
	function printTimedEventsForDay($layout, $view, $initialMinute) {
		global $iWebCal_HOUR_HEIGHT, $iWebCal_URL_PATH, $usingSafari, $safariCurrentContainerHeight, $safariCurrentContainerWidth;
		if ($layout && (count($layout) > 1)) {
			?>
	<table class="TimedEventTable" width='<? if ($usingSafari) echo $safariCurrentContainerWidth; else echo "100%"; ?>' cellspacing="0" cellpadding="0">
		<tr><? 					// remove timed events from layout
					array_pop($layout);

					$whichCol = 0;
					$colWidth = $usingSafari ? (int)ceil($safariCurrentContainerWidth / count($layout)) - 2 : (int)ceil(100 / count($layout)) . "%";
					foreach($layout as $col) {
						if ($whichCol > 0) {
							?>
			<td width="1"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
			<? 						
						}
						?>
			<td valign="top" width="<? echo $colWidth ?>">
				<div style="position:relative;height:100%">
					<? 							
								$currentOffset = $initialMinute;
								foreach($col as $item) {
									$myStart = $item->startDate();
									$myEnd = $item->endDate();
								
									// [TO DO] Do we need a more robust way to do this? Timed events are generally considered
									// to be confined to one day right now. 
								
									// The fix here for midnight helps users who set their
									// end times to midnight, and in addition works with an iCal bug that sets a midnight end time
									// to the wrong day. 
									if (!((int)strftime("%H%M", $myEnd))) {
										// End date is midnight.
									
										// iCal handles this wrong and sets the end date prior to the
										// start date. Fix this.
									
										// Now decrement end date just slightly so it's on the same day as start
										$myEnd--;
									}								
								
									$myStartOffset = ((int)strftime("%H", $myStart) * 60) + (int)strftime("%M", $myStart);
									$myEndOffset = ((int)strftime("%H", $myEnd) * 60) + (int)strftime("%M", $myEnd);
									$myDuration = $myEndOffset - $myStartOffset;
									$myStartSpace = $myStartOffset - $currentOffset;
								
									?>
					<div style="height:<? echo (int)($myDuration * $iWebCal_HOUR_HEIGHT / 60) ?>;position:absolute;top:<? echo (int)(($myStartSpace + $currentOffset - $initialMinute) * $iWebCal_HOUR_HEIGHT / 60) ?>">
						<table class="OuterItemTable" width='<? if ($usingSafari) echo $colWidth; else echo "100%"; ?>' cellspacing="1" cellpadding="0" height="<? echo ($safariCurrentContainerHeight = (int)($myDuration * $iWebCal_HOUR_HEIGHT / 60)) ?>">
							<tr>
								<td valign="top"><? 
									$item->display($view); 
								?></td>
							</tr>
						</table>
					</div>
					<? 								
									$currentOffset += $myStartSpace + $myDuration;
								}
								?></div>
			</td>
			<? 						$whichCol++;
					}
					?></tr>
	</table>
	<? 		}
	}
	
	function printDay($year, $month, $day) {
		global $iWebCal_HOUR_HEIGHT, $iWebCal_URL_PATH;
		$dayLayout = $this->getDayLayout($year, $month, $day);
		
		// get start time for the first event
		if (count($dayLayout) > 1) {
			$firstStart = $dayLayout[0][0]->startDate();
			$initialMinuteOffset = (int)strftime("%H", $firstStart) * 60;
		}
		else {
			$initialMinuteOffset = 540; // 9am
		}
		
		// prepare to print hour marks
		$firstHour = (int)($initialMinuteOffset / 60);
		
		// get end time for the last event
		$lastEnd = 0;
		for ($i=0;$i<count($dayLayout)-1;$i++) {
			if (count($dayLayout[$i])) {
				$thisEnd = $dayLayout[$i][count($dayLayout[$i])-1]->endDate();
				if ($thisEnd > $lastEnd) $lastEnd = $thisEnd;
			}
		}
		if ($lastEnd == 0) {
			$lastHour = 17;
		}
		else {
			$lastHour = (int)strftime("%H", $lastEnd) + 1;
		}
		
		?>
	<table class="CalDay" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td><? $this->printUntimedEventsForDay($dayLayout, "day"); ?></td>
		</tr>
		<tr>
			<td class="Calendar_EventTypeSeparator" height="4"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="4" width="1"></td>
		</tr>
		<tr>
			<td>
				<table width="100%" cellspacing="0" cellpadding="0" background="<? echo $iWebCal_URL_PATH ?>/img/day-bg.gif">
					<tr>
						<td valign="top" bgcolor="white" width="32">
							<table width="100%" cellspacing="0" cellpadding="0">
								<? 									for ($hour=$firstHour; $hour<=$lastHour; $hour++) {
										?>
								<tr>
									<td align="right" valign="top" height="<? echo $iWebCal_HOUR_HEIGHT ?>"><? 
												if (($hour == 0) || ($hour == 24)) echo "mid";
												elseif ($hour == 12) echo "noon";
												else {
													echo $hour % 12;
													echo ":00";
												}  
												?>&nbsp;</td>
								</tr>
								<? 									}
									?>
							</table>
						</td>
						<td width="6"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="4" width="1"></td>
						<td valign="top">
							<table width="100%" cellspacing="0" cellpadding="0" height="7">
								<tr>
									<td height="6"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
								</tr>
							</table>
							<? 
								$this->printTimedEventsForDay($dayLayout, "day", $initialMinuteOffset);
								?></td>
						<td width="6"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="4" width="1"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<? 		 if (!$GLOBALS["iWC_FORK_ROOT_PROD"]) {
		 	 ?>
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center" valign="bottom" height="42"><a href="http://iwebcal.com"><img src='<? echo $GLOBALS["iWebCal_POWERED_BY_LOGO"] ?>' height="39" width="94" border="0"></a></td>
		</tr>
	</table>
	<? 		 }
	}
	
	function printWeek($year, $month, $day) {
		global $iWebCal_HOUR_HEIGHT, $iWebCal_MAIN_FILENAME, $iWebCal_URL_PATH, $safariCurrentContainerWidth, $usingSafari, $iWebCal_SAFARI_TABLE_WIDTH;
		
		$tDate = strtotime("${year}-${month}-${day}");
		if (strftime("%u", $tDate) == 7) {
			$firstDisplayedDate = $tDate;
		}
		else {
			$firstDisplayedDate = strtotime("last Sunday", strtotime("${year}-${month}-${day}"));
		}
		$dayLayouts = array();
		$lastHour = 0;
		$firstHour = 24;
		for ($j=0;$j<7;$j++) {
			$thisDate = strtotime("+${j} days", $firstDisplayedDate);
			$displayedDates[] = getdate($thisDate);
		}
		foreach ($displayedDates as $dInfo) {
			$thisLayout = $this->getDayLayout($dInfo["year"], $dInfo["mon"], $dInfo["mday"]);
			if (count($thisLayout) > 1) {
				for ($i=0;$i<count($thisLayout)-1;$i++) {
					if (count($thisLayout[$i])) {
						$thisEndHour = (int)strftime("%H", $thisLayout[$i][count($thisLayout[$i])-1]->endDate());
						if ($thisEndHour > $lastHour) $lastHour = $thisEndHour;
						
						$thisStartHour = (int)strftime("%H", $thisLayout[0][0]->startDate());
						if ($thisStartHour < $firstHour) $firstHour = $thisStartHour;
					}
				}
			}
			
			$dayLayouts[] = $thisLayout;
		}

		// get start time for the first event
		if ($firstHour == 24) $firstHour = 9;

		$initialMinuteOffset = $firstHour * 60;

		// get end time for the last event
		if (!$lastHour) $lastHour = 17;
			
		?>
	<table class="CalWeek" width='<? if ($usingSafari) echo $iWebCal_SAFARI_TABLE_WIDTH; else echo "100%"; ?>' cellspacing="0" cellpadding="0">
		<tr>
			<td></td>
			<? 		 		// Possibly for absolute positioning: calculate column widths based on # sub-cols
				$colCounts = array();
				$totalCols = 0;
				$dayIndex = 0;
				foreach($dayLayouts as $layout) {
					$colCounts[$dayIndex] = count($layout)-1;
					if ($colCounts[$dayIndex] <= 0) $colCounts[$dayIndex] = 1;
					$totalCols += $colCounts[$dayIndex];
					$dayIndex++;
				}
				$dayIndex = 0;
				foreach ($displayedDates as $dInfo) {
					?>
			<td></td>
			<? 				if ($usingSafari) {
					$myColWidth = (int)round($colCounts[$dayIndex] / $totalCols * ($iWebCal_SAFARI_TABLE_WIDTH - 32));
					$safariColWidths[$dayIndex] = $myColWidth;
				}
				else {
					$myColWidth = (int)round($colCounts[$dayIndex] / $totalCols * 100) . "%";
				}
			?>
			<td class="ColHeader" align="center" width="<? echo $myColWidth ?>" height="16"><? 					
					$myURL = $this->url;
					$thisLink = "${iWebCal_MAIN_FILENAME}?view=day&year=" . 
						$dInfo["year"] . 
						"&month=" . $dInfo["mon"] . 
						"&day=" . $dInfo["mday"] . 
						"&file=${myURL}";
					foreach ($this->extraURLVariables as $varName => $varVal) {
						$thisLink .= "&${varName}=${varVal}";
					}
					echo "<a href=\"${thisLink}\">";
					echo substr($dInfo["weekday"], 0, 3);
					echo " ";
					echo $dInfo["mday"];
					echo "</a>";
					?></td>
			<? 					$dayIndex++;
				}
				?></tr>
		<tr>
			<td colspan="15" height="3"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
		</tr>
		<tr>
			<td colspan="15" bgcolor="#cccccc" height="1"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
		</tr>
		<tr>
			<td></td>
			<? 		 		
			foreach ($dayLayouts as $thisLayout) {
		 		?>
			<td class="VerticalSeparator" width="1"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
			<td valign="top"><? $this->printUntimedEventsForDay($thisLayout, "week"); ?></td>
			<? 				
			}
			?></tr>
		</tr>
		<tr>
			<td class="EventTypeSeparator" colspan="15" height="1"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
		</tr>
		<tr>
			<td valign="top" bgcolor="white" width="32">
				<table width='<? if ($usingSafari) echo ""; else echo "100%"; ?>' cellspacing="0" cellpadding="0">
					<? 						for ($hour=$firstHour; $hour<=$lastHour; $hour++) {
							?>
					<tr>
						<td align="right" valign="top" height="<? echo $iWebCal_HOUR_HEIGHT ?>"><? 
									if (($hour == 0) || ($hour == 24)) echo "mid";
									elseif ($hour == 12) echo "noon";
									else {
										echo $hour % 12;
										echo ":00";
									}  
									?>&nbsp;</td>
					</tr>
					<? 						}
						?>
				</table>
			</td>
			<? 	
			$dayIndex = 0;
			foreach ($dayLayouts as $thisLayout) {
		 		?>
			<td class="VerticalSeparator" width="1"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
			<td valign="top" background="<? echo $iWebCal_URL_PATH ?>/img/day-bg.gif">
				<table width='<? if ($usingSafari) echo ""; else echo "100%"; ?>' cellspacing="0" cellpadding="0">
					<tr>
						<td valign="top">
							<table width="100%" cellspacing="0" cellpadding="0" height="6">
								<tr>
									<td height="6"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
								</tr>
							</table>
							<? 							$safariCurrentContainerWidth = $safariColWidths[$dayIndex];
							$this->printTimedEventsForDay($thisLayout, "week", $initialMinuteOffset);
							?></td>
					</tr>
				</table>
			</td>
			<? 			$dayIndex++;		
			}
			?></tr>
		<tr>
			<td class="HorizontalSeparator" colspan="15" height="1"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
		</tr>
	</table>
	<? 		 if (!$GLOBALS["iWC_FORK_ROOT_PROD"]) {
		 	 ?>
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center" valign="bottom" height="46"><a href="http://iwebcal.com"><img src='<? echo $GLOBALS["iWebCal_POWERED_BY_LOGO"] ?>' height="39" width="94" border="0"></a></td>
		</tr>
	</table>
	<? 		 }
	}		


	function printTasks($year, $month, $day) {
		global $iWebCal_URL_PATH, $iWebCal_TASK_SORT_DESTINATION, $iWebCal_TASK_SORT_DESTINATION_TYPE, $iWebCal_ENABLE_TASK_SORTING, $iWebCal_TASK_TABLE_WIDTH;
		?>
	<table class="CalTasks" width="<? echo $iWebCal_TASK_TABLE_WIDTH ?>" cellspacing="0" cellpadding="0">
		<tr>
			<td width="4">&nbsp;</td>
			<td class="TaskColHeaderCell"><? 					if ($iWebCal_ENABLE_TASK_SORTING && ($this->taskSort != "completed")) {
						echo "<a href=\"" . preg_replace("/\[\[NEW_SORT\]\]/", "completed", $iWebCal_TASK_SORT_DESTINATION) . "\">";
					}
					?><img src="<? echo $iWebCal_URL_PATH ?>/img/completed-header.gif" height="12" width="15" border="0"><? 					if ($iWebCal_ENABLE_TASK_SORTING && ($this->taskSort != "completed")) {
						?></a><? 					}
					?></td>
			<td width="12">&nbsp;</td>
			<td class="TaskColHeaderCell"><? 					if ($iWebCal_ENABLE_TASK_SORTING && ($this->taskSort != "summary")) {
						echo "<a href=\"" . preg_replace("/\[\[NEW_SORT\]\]/", "summary", $iWebCal_TASK_SORT_DESTINATION) . "\" class=\"NormalLink\">";
					}
					?>Description<? 					if ($iWebCal_ENABLE_TASK_SORTING && ($this->taskSort != "summary")) {
						?></a><? 					}
				?></td>
			<td width="24">&nbsp;</td>
			<td class="TaskColHeaderCell"><? 					if ($iWebCal_ENABLE_TASK_SORTING && ($this->taskSort != "due")) {
						echo "<a href=\"" . preg_replace("/\[\[NEW_SORT\]\]/", "due", $iWebCal_TASK_SORT_DESTINATION) . "\" class=\"NormalLink\">";
					}
					?>Due Date<? 					if ($iWebCal_ENABLE_TASK_SORTING && ($this->taskSort != "due")) {
						?></a><? 					}
				?></td>
			<td width="24">&nbsp;</td>
			<td class="TaskColHeaderCell"><? 					if ($iWebCal_ENABLE_TASK_SORTING && ($this->taskSort != "priority")) {
						echo "<a href=\"" . preg_replace("/\[\[NEW_SORT\]\]/", "priority", $iWebCal_TASK_SORT_DESTINATION) . "\" class=\"NormalLink\">";
					}
					?>Priority<? 					if ($iWebCal_ENABLE_TASK_SORTING && ($this->taskSort != "priority")) {
						?></a><? 					}
				?></td>
			<td width="4">&nbsp;</td>
		</tr>
		<? 			foreach ($this->taskIndex as $thisTask) {
				if ($prop = $thisTask->getProperty("STATUS")) {
					$thisCompleted = $prop->value();
				}
				else {
					$thisCompleted = 0;
				}
				
				if ($this->taskShowCompleted || !$thisCompleted) {
					// spacer stuff
					?>
		<tr>
			<td colspan="9" height="3"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="2" width="2"></td>
		</tr>
		<tr>
			<td class="HorizontalSeparator" colspan="9" height="1"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
		</tr>
		<tr>
			<td colspan="9" height="3"><img src="<? echo $iWebCal_URL_PATH ?>/img/pix-transparent.gif" height="1" width="1"></td>
		</tr>
		<? 				
					if ($prop = $thisTask->getProperty("PRIORITY")) {
						switch ($prop->value()) {
							case 1:
								$prio = "Very&nbsp;Important";
								$cellStyle = "HighPriority";
								break;
							case 5:
								$prio = "Important";
								$cellStyle = "MedPriority";
								break;
							case 9:
								$prio = "Not&nbsp;Important";
								$cellStyle = "LowPriority";
								break;
							default:
								$prio = "None";
								$cellStyle = "";
								break;
						}
					}
					else {
						$prio = "None";
						$cellStyle = "";
					}
					if ($thisCompleted) {
						$cellStyle = "Completed";
						$completedImg = "${iWebCal_URL_PATH}/img/completed-check.gif";
					}
					else {
						$completedImg = "${iWebCal_URL_PATH}/img/pix-transparent.gif";
					}
	
					echo "<tr><td></td>";
					
					echo "<td valign=\"top\" class=\"${cellStyle}\"><img src=\"${completedImg}\" width=\"15\" height=\"12\" border=\"0\"></td><td></td>";
					echo "<td valign=\"top\" class=\"${cellStyle}\">" . $thisTask->summary() . "</td><td></td>";
					
					if ($prop = $thisTask->getProperty("DUE")) {
						$dueDate = strftime("%m.%d.%Y", strtotime(substr($prop->value(), 0, 8)));
					}
					else {
						$dueDate = "";
					}
					echo "<td valign=\"top\" class=\"${cellStyle}\">${dueDate}</td><td></td>";
					echo "<td valign=\"top\" class=\"${cellStyle}\">${prio}</td>";	
					echo "<td></td></tr>";
				}	
			}
			?>
		<tr>
			<td colspan="9">&nbsp;</td>
		</tr>
	</table>
	<? 		if (!$GLOBALS["iWC_FORK_ROOT_PROD"]) {
			?>
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center" valign="bottom" height="39"><a href="http://iwebcal.com"><img src='<? echo $GLOBALS["iWebCal_POWERED_BY_LOGO"] ?>' height="39" width="94" border="0"></a></td>
		</tr>
	</table>
	<? 		}
	}


	function printCal($year, $month, $day, $view) {
		$year = (int)$year;
		$month = (int)$month;
		$day = (int)$day;
		switch($view) {
			case "month":
				$this->printMonth($year, $month, $day);
				break;
			case "day":
				$this->printDay($year, $month, $day);
				break;
			case "week":
				$this->printWeek($year, $month, $day);
				break;
			case "tasks":
				$this->printTasks($year, $month, $day);
				break;
		}
	}
	
	function printViewTitle($year, $month, $day, $view) {
		$year = (int)$year;
		$month = (int)$month;
		$day = (int)$day;
		switch($view) {
			case "month":
				echo $GLOBALS["MONTH_FULL_NAMES"][$month-1];
				echo " ";
				echo $year;
				break;
			case "week":
				$tDate = strtotime("${year}-${month}-${day}");
				if (strftime("%u", $tDate) == 7) {
					$firstDisplayedDate = $tDate;
				}
				else {
					$firstDisplayedDate = strtotime("last Sunday", $tDate);
				}
				$lastDisplayedDate = strtotime("+6 days", $firstDisplayedDate);
				$infoS = getdate($firstDisplayedDate);
				$infoF = getdate($lastDisplayedDate);
				
				if ($infoS["year"] != $infoF["year"]) {
					$m1 = substr($infoS["month"], 0, 3);
					$m2 = substr($infoF["month"], 0, 3);
					
					echo "${m1} " . $infoS["mday"] . ", " . $infoS["year"] . " - ${m2} " . $infoF["mday"] . ", " . $infoF["year"];
				}
				elseif ($infoS["mon"] != $infoF["mon"]) {
					$m1 = substr($infoS["month"], 0, 3);
					$m2 = substr($infoF["month"], 0, 3);
					echo "${m1} " . $infoS["mday"] . " - ${m2} " . $infoF["mday"] . ", " . $infoS["year"];
				}
				else {
					echo $infoS["month"] . " " . $infoS["mday"] . " - " . $infoF["mday"] . ", " . $infoS["year"];
				}
				break;
			case "day":
				$tDate = strtotime("${year}-${month}-${day}");
				echo strftime("%A, %b %e, %Y", $tDate);
				break;
			case "tasks":
				echo "Tasks";
				break;
		}
				
				
	}
	
	function addURLVariable($name, $value) {
		$this->extraURLVariables[$name] = $value;
	}
	
	function showCompletedTasks($newSetting) {
		if ($newSetting) {
			$this->taskShowCompleted = true;
		}
		else {
			$this->taskShowCompleted = false;
		}
	}
	
	function sortTasks($column) {
		global $sortInProgress;
		if ($column != $this->taskSort) {
			$sortInProgress = $column;
			usort($this->taskIndex, taskCompare);
			$this->taskSort = $column;
			$sortInProgress = NULL;
		}
	}
}



// ------

function startDateComparison($item1, $item2) {
	$date1 = $item1->startDate();
	$date2 = $item2->startDate();
	
	if ($date1 == $date2) return 0;
	return ($date1 > $date2) ? 1 : -1;
}

function taskdueCompare($item1, $item2) {
	$prop1 = $item1->getProperty("DUE");
	$prop2 = $item2->getProperty("DUE");
	if ($prop1 && $prop2) {
		$due1 = $prop1->value();
		$due2 = $prop2->value();
		if ($due1 < $due2) return -1;
		return ($due1 > $due2);
	}
	elseif ($prop1) return -1;
	else return 1;
}

function taskpriorityCompare($item1, $item2) {
	$prop1 = $item1->getProperty("PRIORITY");
	$prop2 = $item2->getProperty("PRIORITY");
	if ($prop1 && $prop2) {
		$pri1 = $prop1->value();
		$pri2 = $prop2->value();
		if ($pri1 < $pri2) return -1;
		return ($pri1 > $pri2);
	}
	elseif ($prop1) return -1;
	else return 1;
}

function taskcompletedCompare($item1, $item2) {
	$prop1 = $item1->getProperty("STATUS");
	$prop2 = $item2->getProperty("STATUS");
	if ($prop1 && $prop2) return 0;
	elseif ($prop1) return -1;
	else return 1;
}

function tasksummaryCompare($item1, $item2) {
	$prop1 = $item1->summary();
	$prop2 = $item2->summary();
	return strcasecmp($prop1, $prop2);
}

function taskCompare($item1, $item2) {
	global $sortInProgress;
	eval("\$result = task${sortInProgress}Compare(\$item1, \$item2);");
	return $result;
	return 0;
}



?>