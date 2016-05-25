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

class CalItem {
	var $properties;
	var $type;
	var $basicDates;
	
	var $startDateCache;
	var $endDateCache;
	var $isAllDayCache;
	
	function CalItem($type = NULL) {
		$this->properties = array();
		$this->type = $type;
		$this->basicDates = NULL;
		$this->finiteRepeatDates = NULL;
		$this->repeats = NULL;
		$this->isInfiniteRepeating = false;
		
		$this->startDateCache = NULL;
		$this->isAllDayCache = NULL;
		$this->endDateCache = NULL;
	}
	
	function addProperty($prop) {
		$this->properties[] = $prop;
	}
	
	function propertyArray() {
		return $this->properties;
	}
	
	function dprint() {
		foreach($this->properties as $prop) {
			$prop->dprint();
		}
	}
	
	function getProperty($name) {
		foreach($this->properties as $prop) {
			if($prop->name == $name) {
				return $prop;
			}
		}
		return NULL;
	}
	
	function sortComparison($a, $b) {
		if ($a->isAllDay()) return -1;
		if ($b->isAllDay()) return 1;
		
		// if we're here both are timed
		if ($a->startDate() == $b->startDate()) {
			return 0;
		}
		return (($a->startDate() > $b->startDate()) ? 1 : -1);
	}
	
	function repeatsOnDate($date) { // date should be in the form "YYYY-MM-DD"
		// assumes that the event does repeat
	
		if (!$this->basicDates) {
			$this->calcDates();
		}
		if (in_array($date, $this->basicDates)) {
			return false;
		}

		// see if the date parameter is after the event's start date
		$tDate = strtotime($date);
		$tStart = strtotime($this->basicDates[0]);
		$diff = $tDate - $tStart;
		if ($diff < 0) {
			return false;
		}

		$rrule = $this->getProperty("RRULE");
		$until = $rrule->value("UNTIL");
		$count = $rrule->value("COUNT");
		
		if ($until) {
			$tUntil = strtotime(substr($until, 0, 8)); 
			// iCal 1.0 had this as the day _after_ repeating stopped. iCal 1.0.2 has it as the
			// day of. This means we no longer support iCal 1.0.
		}
						
		// [TO DO](2): Is there anywhere below that the code can prematurely return false
		//  and thus save some execution time?
		$duration = (int)((strtotime($this->basicDates[count($this->basicDates)-1]) -
			strtotime($this->basicDates[0])) / 86400); // days
		for ($testIteration=0;$testIteration<=$duration;$testIteration++) {
			$testDate = strtotime("-${testIteration} days", $tDate);
			// if the event has a stop date, see if the date param is before it
			if ($until) {
				// Note: Changed from >= to > to support iCal 1.0.2, excluding iCal 1.0.
				if ($testDate > $tUntil) {
					continue;
				}
			}
			else {
				$tUntil = NULL;
			}
					
			switch($rrule->value("FREQ")) {
				case "DAILY":
					$interval = (int)$rrule->value("INTERVAL");
					$diff = $testDate - $tStart;
					// [TO DO](1): Is there any danger associated with this sort of date
					//  arithmetic? There does seem to be some sort of weird (roundoff?) 
					//  error when I use seconds instead of days here. Are there any
					//  other potential pitfalls?
					$dayDiff = $diff / 86400;
					if (($dayDiff % $interval) == 0) {
						// should return true unless the stop condition is met
						if (!$count || ($dayDiff < ($interval * $count))) {
							return true;
						}
					}
					break;
	
				case "WEEKLY":
					$days = explode(",", $rrule->value("BYDAY"));
					for ($i=0;$i<count($days);$i++) {
						$days[$i] = dayNumber($days[$i]);
					}
					// startDay = day of week on which the event starts
					$startDay = strftime("%u", $tStart) % 7;
					// startSunday = Sunday beginning week in which event starts
					$startSunday = strtotime("-${startDay} days", $tStart);
					// repeat start day = first repeated day of week
					$repeatStartDay = $days[0];
					// repeatStart = actual date of first repeated day in first week
					$repeatStart = strtotime("+${repeatStartDay} day", $startSunday);
					for ($i=count($days)-1;$i>=0;$i--) {
						$days[$i] = $days[$i] - $days[0];
					}
					// days[] now contains offsets from days[0], a.k.a. repeatStart
					
					$interval = (int)$rrule->value("INTERVAL");
					
					// Set myDiff to the difference (in days) between the date we're looking at
					//  and repeatStart
					$myDiff = (int)(($testDate - $repeatStart) / 86400); // days
					if ($myDiff < 0) {
						continue;
					}
					if ((($whichDay = array_search(($myDiff % ($interval * 7)), $days)) !== false) && ($whichDay !== NULL)) {
						// we're on a repeated day
						// should return true unless the stop condition is met
						if (!$count) return true;
						$skippedInstances = 0;
						$extraInstances = 0;
						foreach ($days as $day) {
							if ($skippable = (($repeatStartDay + $day) <= $startDay)) {
								$skippedInstances++;
							}
							if ((($myDiff % 7) > $day) && !(($myDiff < 7) && $skippable)) {
								$extraInstances++;
							}
						}
						$instancesToDate = ((int)($myDiff / (7 * $interval))		// whole weeks since first repeated instance
							* count($days))							// * instances/week = instances in whole weeks to date
							- $skippedInstances						// subtract anything prior to event start date
							+ $extraInstances;						// add anything already in current week
						
						if ($instancesToDate < $count) return true;
					}
					break;
				case "MONTHLY":
					// [TO DO](2): May be able to centralize more stuff for the two branches here at the top,
					//  and/or at the top of subsections.
					if ($repeatType = $rrule->value("BYMONTHDAY")) {
						// we're repeating on the nth day of every m months
						$repeatType = explode(",", $repeatType);
						if (in_array((int)strftime("%d", $testDate), $repeatType)) {
							// we're on a proper day of the month. Is it a right month?
							$interval = $rrule->value("INTERVAL");
							$firstOfThisMonth = strtotime(strftime("%Y-%m-", $testDate) . "01");
							$firstOfStartMonth = strtotime(substr($this->basicDates[0], 0, 8) . "01");
							// [TO DO](3): Is there a less brute-force way to do this?
							$intervalCount = 0; // used below for checking count stop condition
							for ($j=$firstOfStartMonth; $j<$firstOfThisMonth; $j=strtotime("+${interval} months", $j)) {
								$intervalCount++;
							}
							if ($j == $firstOfThisMonth) {
								// right month. exceeded count?
								if (!$count) return true;
								
								$numInstancesEachMonth = count($repeatType);
								list($thisDayOfMonth, $thisMonth) = explode(",", strftime("%d,%m", $testDate));
								$startDayOfMonth = strftime("%d", $tStart);
								
								// firstMonthAdjust will contain the difference between the number
								// of repeated instances in a normal repeat month and those in the
								// first month. It will always be zero or negative.
	
								// thisMonthAdjust will contain the difference between the number
								// of repeated instances _already_ in the month under current 
								// consideration and those in a normal repeat month.
								// It will always be zero or negative.
								$firstMonthAdjust = 0;
								$thisMonthAdjust = 0;
								foreach($repeatType as $repeatedDay) {
								
									if ($repeatedDay <= $startDayOfMonth) {
										$firstMonthAdjust--;
									}
									
									if ($repeatedDay >= $thisDayOfMonth) {
										$thisMonthAdjust--;
									}
								}
								
								// intervalCount already contains the number of elapsed intervals since
								// the start month (see above). if we're in the start month it's 0.
								// So intervalCount - 1 is the number of full months containing instances
								// that have elapsed so far.
								$instanceCountToDate =
									  ($intervalCount + 1) * $numInstancesEachMonth
										// # instances in all months including first if they were full months
									+ $firstMonthAdjust
									+ $thisMonthAdjust;
									
								if ($instanceCountToDate < $count) return true;
							}
						}
					}
					else {
						$repeatType = $rrule->value("BYDAY");
						$whichDay = substr($repeatType, strlen($repeatType)-2, 2);
						$whichWeek = (int)substr($repeatType, 0, strlen($repeatType)-2);
						$whichDay = dayNumber($whichDay);
						$oneWeekLess = ($whichWeek > 0) ? $whichWeek - 1 : $whichWeek + 1;
						
						$thisDayNumber = strftime("%u", $testDate) % 7;;
						if ($thisDayNumber == $whichDay) {
							// right day of the week. right week?
							// [TO DO](3): Is this faster or slower than % arithmetic?
							list($thisYear, $thisMonth, $thisDay) = explode(",", strftime("%Y,%m,%e", $testDate));
							if (checkdate($thisMonth, $thisDay-(7 * $oneWeekLess), $thisYear) 
								&& !checkdate($thisMonth, $thisDay-(7 * $whichWeek), $thisYear)) {
								// [TO DO]: What would be the best order of checks speed-wise?
								// right week. right month?
								$interval = $rrule->value("INTERVAL");
								$firstOfThisMonth = strtotime(strftime("%Y-%m-", $testDate) . "01");
								$firstOfStartMonth = strtotime(substr($this->basicDates[0], 0, 8) . "01");
								$intervalCount = 0; // used below for checking count stop condition
								for ($j=$firstOfStartMonth; $j<$firstOfThisMonth; $j=strtotime("+${interval} months", $j)) {
									$intervalCount++;
								}
								if ($j == $firstOfThisMonth) {
									// right month. have we exceeded count?
									if (!$count) return true;
									list($startMonth, $startYear) = explode(",", strftime("%m,%Y", $tStart));
									if (($count > 0) && ($startMonth == $thisMonth) && ($startYear == $thisYear)) return true;
	
									$startDayOfMonth = strftime("%d", $tStart);
	
									// We need to know on what day the repeat date falls in the first month
									// so we can figure out if there's a repeated instance that month.
									// First, get the day of the week for the first of the start month:
									$firstOfStartMonth_dayOfWeek = strftime("%u", $firstOfStartMonth) % 7;									
									// Then, get the first instance of the day of the week we repeat on:
									$distanceToRepeatedDay = $whichDay - $firstOfStartMonth_dayOfWeek;
									if ($distanceToRepeatedDay < 0) $distanceToRepeatedDay += 7;
									$firstInstanceOfRepeatedDay = 1 + $distanceToRepeatedDay;
									// Now get the actual day of the month:
									if ($whichWeek > 0) {
										$firstMonthDayOfRepeatedInstance = $firstInstanceOfRepeatedDay
											+ (7 * ($whichWeek-1));
									}
									else {
										for ($k=5;$k>0;$k--) {
											$myDay = $firstInstanceOfRepeatedDay + (($k-1)*7);
											if (checkdate($thisMonth, $myDay, $thisYear)) {
												$lastInstanceOfRepeatedDay = $myDay;
												break;
											}
										}
										$firstMonthDayOfRepeatedInstance = $lastInstanceOfRepeatedDay
											+ (7 * ($whichWeek+1));
									}
									if ($firstMonthDayOfRepeatedInstance > $startDayOfMonth) {
										$repeatedInstancesInFirstMonth = 1;
									}
									else {
										$repeatedInstancesInFirstMonth = 0;
									}
										
									
									// intervalCount contains the number of elapsed intervals since
									// the start month (see above). If we're in the start month
									// it's 0.
									
									$instanceCountToDate = $intervalCount + $repeatedInstancesInFirstMonth - 1;
									if ($instanceCountToDate < $count) return true;
								}
							}								
						}
					}
					break;
				case "YEARLY":
					// are we in the right year?
					list($thisYear, $thisMonth, $thisDayNumber, $thisDay) = explode(",", strftime("%Y,%m,%u,%e", $testDate));
					$thisDayNumber = $thisDayNumber % 7;
					list ($startYear, $startMonth, $startDayOfMonth) = explode(",", strftime("%Y,%m,%e", $tStart));
					$yearDiff = $thisYear - $startYear;
					$interval = $rrule->value("INTERVAL");
					if (($yearDiff % $interval) == 0) {
						// we're in a right year. what about the month?
						$repeatMonths = explode(",", $rrule->value("BYMONTH"));
						if (in_array($thisMonth, $repeatMonths)) {
							// we're in a right month. what about the day?
							if ($repeatOnWeekDay = $rrule->value("BYDAY")) {
								// we're repeating on a specific day of a specific week
								// rather than the original day
								$whichDay = substr($repeatOnWeekDay, strlen($repeatOnWeekDay)-2, 2);
								$whichDay = dayNumber($whichDay);
								$whichWeek = substr($repeatOnWeekDay, 0, strlen($repeatOnWeekDay)-2);
								$oneWeekLess = ($whichWeek > 0) ? $whichWeek - 1 : $whichWeek + 1;
								if ($thisDayNumber == $whichDay) {
									// right day of week. right week?
									// [TO DO]: Is this faster or slower than % arithmetic?
									if (checkdate($thisMonth, $thisDay-(7 * $oneWeekLess), $thisYear)
										&& !checkdate($thisMonth, $thisDay-(7 * $whichWeek), $thisYear)) {
											// [TO DO]: What would be the best order of checks speed-wise?
											// right week. have we exceeded count?
											if (!$count) return true;
											// Are there any instances in the start month?
											if (in_array($startMonth, $repeatMonths)) {
												// We need to know on what day the repeat date falls in the first month
												// so we can figure out if there's a repeated instance that month.
												// First, get the day of the week for the first of the start month:
												$firstOfStartMonth = strtotime(substr($this->basicDates[0], 0, 8) . "01");											
												$firstOfStartMonth_dayOfWeek = strftime("%u", $firstOfStartMonth) % 7;									
												// Then, get the first instance of the day of the week we repeat on:
												$distanceToRepeatedDay = $whichDay - $firstOfStartMonth_dayOfWeek;
												if ($distanceToRepeatedDay < 0) $distanceToRepeatedDay += 7;
												$firstInstanceOfRepeatedDay = 1 + $distanceToRepeatedDay;
												// Now get the actual day of the month:
												if ($whichWeek > 0) {
													$firstMonthDayOfRepeatedInstance = $firstInstanceOfRepeatedDay
														+ (7 * ($whichWeek-1));
												}
												else {
													for ($k=5;$k>0;$k--) {
														$myDay = $firstInstanceOfRepeatedDay + (($k-1)*7);
														if (checkdate($thisMonth, $myDay, $thisYear)) {
															$lastInstanceOfRepeatedDay = $myDay;
															break;
														}
													}
													$firstMonthDayOfRepeatedInstance = $lastInstanceOfRepeatedDay
														+ (7 * ($whichWeek+1));
												}
												if ($firstMonthDayOfRepeatedInstance > $startDayOfMonth) {
													$repeatedInstancesInFirstMonth = 1;
												}
												else {
													$repeatedInstancesInFirstMonth = 0;
												}
											}
											else {
												$repeatedInstancesInFirstMonth = 0;
											}
											
											// Calc # instances in start year and this year
											$repeatedInstancesInFirstYear = $repeatedInstancesInFirstMonth;
											$repeatedInstancesInThisYear = 0;
											if ($startYear == $thisYear) {
												if ($repeatedInstancesInFirstMonth && ($thisMonth == $startMonth))
													return true;
													
												foreach($repeatMonths as $myRepeatMonth) {
													if (($myRepeatMonth > $startMonth) && ($myRepeatMonth < $thisMonth)) {
														$repeatedInstancesInFirstYear++;
													}
												}
												
												if ($repeatedInstancesInFirstYear < $count) return true;
											}
											else {
												foreach($repeatMonths as $myRepeatMonth) {
													if ($myRepeatMonth > $startMonth)
														$repeatedInstancesInFirstYear++;
													if ($myRepeatMonth < $thisMonth) {
														$repeatedInstancesInThisYear++;
													}
												}
												
												// Calc # instances in years between start and this year
												$numberOfInterveningRepeatYears = (int)(($thisYear - $startYear - 1) / $interval);
												$numberOfInterveningInstances = $numberOfInterveningRepeatYears * count($repeatMonths);
												
												$instanceCountToDate = $repeatedInstancesInFirstYear
																	 + $numberOfInterveningInstances
																	 + $repeatedInstancesInThisYear;
												if ($instanceCountToDate < $count) return true;
											}
									
									}		
								}
							}
							else {
								// we're repeating on the original day. still in right month...
								if ($thisDay == $startDayOfMonth) {
									// right day. have we exceeded count?
									if (!$count) return true;
									
									$instanceCountToDate = 0;
									if ($thisYear == $startYear) {
										foreach($repeatMonths as $myRepeatMonth) {
											if (($myRepeatMonth > $startMonth) && ($myRepeatMonth < $thisMonth)) {
												$instanceCountToDate++;
											}
										}
										if ($instanceCountToDate < $count) return true;
									}
									else {
										// calc # instances in first and current years
										$repeatedInstancesInFirstYear = 0;
										$repeatedInstancesThisYear = 0;
										foreach($repeatMonths as $myRepeatMonth) {
											if ($myRepeatMonth > $startMonth)
												$repeatedInstancesInFirstYear++;
											if ($myRepeatMonth < $thisMonth)
												$repeatedInstancesThisYear++;
										}
										
										// calc # intervening instances
										$numberOfInterveningRepeatYears = (int)(($thisYear - $startYear - 1) / $interval);
										$numberOfInterveningInstances = $numberOfInterveningRepeatYears * count($repeatMonths);
										
										$instanceCountToDate = $repeatedInstancesInFirstYear
															 + $numberOfInterveningInstances
															 + $repeatedInstancesThisYear;
															 
										if ($instanceCountToDate < $count) return true;
									}
								}
							}
						}
					}
					break;
			}
		}
		return false;	
	}
	
	function calcDates() {
		// Private function.
		
		// This function returns all dates spanned by the event. This
		// does not include repeated instances governed by an RRULE.
		
		// At least in iCal, if an item has times included, it's one day only. 
		// If it's untimed, then its end date is the day after it ends.
		
		// [TO DO] If, in fact, getDates() is only going to be called once on
		// any item then this should be modified since it stores extra data
		// in the dates array for each item that's only needed on the Calendar
		// level. Wait and see though.
		
		// [TO DO] May want to redo the date storage. Ultimately just need start
		// and duration, rather than each day on which it occurs (as long as lookups
		// can remain fast).
		
		if ($this->type == "VEVENT") {
			// Calculate basic dates -- not taking repetition into account
			$start = $this->startDate();
			if (!$start) {
				return NULL;
			}
			$end = $this->endDate();
			if (!$end) {
				return array($start);
			}
 
			$this->basicDates = array();
			for ($i=$start; $i < $end; $i = strtotime("+1 day", $i)) {
				$this->basicDates[] = strftime("%Y-%m-%d", $i);
			}
		}
	}
	
	function getDates() {
		if (!$this->basicDates) {
			$this->calcDates();
		}
		return $this->basicDates;
	}
	
	function summary($maxlength = 0) {
		$sum = $this->getProperty("SUMMARY");
		if (!$sum) return NULL;
		$sum = stripslashes($sum->value());
		if (!$sum) return NULL;
		if ($maxlength) {
			if ($maxlength < strlen($sum)) {
				return substr($sum, 0, $maxlength-3) . "...";
			}
			return substr($sum, 0, $maxlength);
		}
		return $sum;
	}
	
	function startDate() {
		if (!$this->startDateCache) {
			$prop = $this->getProperty("DTSTART");
			if ($prop->parameter("VALUE")) {
				$this->startDateCache = strtotime($prop->value());
			}
			else {
				$rawValue = $prop->value();
				$datePart = substr($rawValue, 0, 8);
				$timePart = substr($rawValue, 9, 4);
				$this->startDateCache = strtotime("${datePart} ${timePart}");
			}
		}
		return $this->startDateCache;
	}
	
	function endDate() {
		if (!$this->endDateCache) {
			$prop = $this->getProperty("DTEND");
			if ($prop) {
				// DTEND is defined; could be either type of event
				if ($prop->parameter("VALUE")) {
					// iCal 1.0 stores all-day events by including a VALUE parameter
					// set to DATE for the DTEND property, and then setting the
					// property's value to a date stamp (no time). Timed events don't
					// have a VALUE parameter at all, so here it's sufficient to check
					// for the VALUE parameter's existence.
					$this->endDateCache = strtotime($prop->value());
					$this->isAllDayCache = 1;
				}
				else {
					$rawValue = $prop->value();
					$datePart = substr($rawValue, 0, 8);
					$timePart = substr($rawValue, 9, 4);
					$this->endDateCache = strtotime("${datePart} ${timePart}");
					$this->isAllDayCache = 0;
				}
			}
			else {
				// length is defined by a DURATION property
				$prop = $this->getProperty("DURATION");

				// [TO DO] -- DURATION handling needs to be expanded. iCal appears not to use it properly
				// (according to the specification), but is getting closer, and in order to
				// support non-iCal ics files properly this will need to be overhauled a bit.


				// iCal 1.0.2 now specifies all-day events without DTEND, and with a DURATION
				// of P1D. For the moment I'm not supporting the full range of DURATION values
				// possible in the vCalendar spec, just this new iCal addition.
				if ($prop->value() == "P1D") {
					// This is an all-day event (generated by iCal 1.0.2+)
					$this->isAllDayCache = 1;
					
					$startProp = $this->getProperty("DTSTART");
					$startTime = $startProp->value();
					$this->endDateCache = strtotime("+1 day", strtotime($startTime));
				}
				else {
					$this->isAllDayCache = 0;
					$this->endDateCache = offsetByDuration($this->startDate(), $prop->value());
				}


			}
		}

		return $this->endDateCache;
	}
	
	function isAllDay() {
		if ($this->isAllDayCache === NULL) {
			$this->endDate();
		}
		return $this->isAllDayCache;
	}
	
	function display($displayType, $linkString="#") {
		global $usingSafari, $safariCurrentContainerHeight;
		switch ($displayType) {		
			case "month":
				if ($this->isAllDay()) {
					?>
					<table class="AllDayItem" width="100%" cellspacing="1" cellpadding="3">
						<tr>
							<td><? 				
				}
				else {
					?>
					<p class="TimedItem"><? 				
				}
				echo "<a href=\"${linkString}\"";
				if ($linkString == "#") echo " class=\"NoUnderlineLink\"";
				echo "title=\"";
				echo $this->summary();
				echo "\">";
				echo $this->summary(22);
				echo "</a>";
				if ($this->isAllDay()) {
							?></td>
						</tr>
					</table>
					<? 				
				}
				else {
					?></p><?
				}
				return true;
				break;
			case "week":
			case "day":
				$description = $this->descriptionPopup();
				$summaryLength = ($displayType == "week") ? ($description ? 23 : 24) : 0;
				?>
				<table class="Item" width="100%" cellspacing="0" cellpadding="2" height='<? if ($usingSafari) echo $safariCurrentContainerHeight; else echo "100%"; ?>'>
	<? 					if (!$this->isAllDay()) {
						?>
	<tr>
		<td class="HeaderCell" height="12"><? 								list($myStartHour, $myStartMinute, $myStartAMPM) = 
									explode(",", strftime("%I,%M,%p", $this->startDate()));
								$myStartHour = (int)$myStartHour;
								$myStartAMPM = strtolower($myStartAMPM);
								if ($myStartMinute == "00")
									$myStartMinute = "";
								else
									$myStartMinute = ":" . $myStartMinute;
								if ($displayType == "day") echo "<b>";
								echo "${myStartHour}${myStartMinute}${myStartAMPM}";
								if ($displayType == "day") echo "</b>";

								if ($displayType == "day") {
									list($myEndHour, $myEndMinute, $myEndAMPM) = 
										explode(",", strftime("%I,%M,%p", $this->endDate()));
									$myEndHour = (int)$myEndHour;
									$myEndAMPM = strtolower($myEndAMPM);
									if (!(int)$myEndMinute)
										$myEndMinute = "";
									else
										$myEndMinute = ":" . $myEndMinute;
									echo " - ${myEndHour}${myEndMinute}${myEndAMPM}";
								}
								else echo "&nbsp;";
							?></td>
	</tr>
	<? 					}
					?>
	<tr>
		<td class='<? if ($this->isAllDay()) echo "AllDay"; ?>ContentCell' valign="top"><? 							echo "<a href=\"${linkString}\"";
							if ($linkString == "#") echo " class=\"NoUnderlineLink\"";
							echo "title=\"";
							echo $this->summary();
							echo "\">";
							echo $this->summary($summaryLength);
							echo $description;
							echo "</a>";
							?></td>
	</tr>
</table>
<? 				return true;
				break;
			case "day":
				return true;
				break;
		}
		echo "<p><b>iWebCal Error:</b> Invalid calendar display type.</p>";
		return false;
	}			

	function descriptionPopup() {
		global $iWebCal_URL_PATH;
		$prop = $this->getProperty("DESCRIPTION");
		if (!$prop) return "";
		
		$desc = $prop->value();
		if (!$desc) return "";
		
		$popupDocString = $iWebCal_URL_PATH . "/popup-event-info.php?title=Event+Note&content=" . urlencode($desc);
		$result = "<a href=\"#\" onclick=\"javascript:myWin=window.open('" .
			$popupDocString .
			"', 'iwebcal_note_win', 'width=250,height=300,left=30,top=30');\">" .
			"<img src=\"" . $iWebCal_URL_PATH . "/img/note-button.gif\" " .
			"width=\"10\" height=\"9\" border=\"0\">" .
			"</a>";
		return $result;
	}

}


?>