<?php
/**
* This functions common to most pages
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-10-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/..');
/**
* Include configuration file
**/
include_once(BASE_DIR . '/config/config.php');
/**
* Include Link class
*/
include_once('Link.class.php');
/**
* Include Pager class
*/
include_once('Pager.class.php');
/**
* Include Template class
*/
if (!class_exists('Template')) {
	include_once('Template.class.php');
}


/**
* Provides functions common to most pages
*/
class CmnFns {
	
	/**
	* Convert minutes to hours
	* @param double $time time to convert in minutes
	* @return string time in 12 hour time
	*/
	function formatTime($time) {
		global $conf;
		
		// Set up time array with $timeArray[0]=hour, $timeArray[1]=minute
		// If time does not contain decimal point
		// then set time array manually
		// else explode on the decimal point
		$hour = intval($time / 60);
		$min = $time % 60;
		if ($conf['app']['timeFormat'] == 24) {			
			$a = '';									// AM/PM does not exist
			if ($hour < 10) $hour = '0' . $hour;
		}
		else {		
			$a = ($hour < 12 || $hour == 24) ? 'am' : 'pm';			// Set am/pm		
			if ($hour > 12) $hour = $hour - 12;			// Take out of 24hr clock
			if ($hour == 0) $hour = 12;					// Don't show 0hr, show 12 am	
		}
		// Set proper minutes (the same for 12/24 format)
		if ($min < 10) $min = 0 . $min;
		// Put into a string and return
		return $hour . ':' . $min . $a;
	}
	
	
	/**
	* Convert timestamp to mm/dd/yyyy
	* @param string $date timestamp
	* @param string $format format to put datestamp into
	* @return string date as mm/dd/yyyy
	*/
	function formatDate($date, $format = 'm/d/Y') {
		return date($format, $date);
	}
	
	
	/**
	* Convert UNIX timestamp to mm/dd/yyyy hh:mm:ss am
	* @param string $ts MySQL timestamp
	* @param string $format format to put datestamp into
	* @return string date/time as mm/dd/yyyy hh:mm:ss am
	*/
	function formatDateTime($ts, $format = '') {
		global $conf;
		if (empty($format))
			$format = 'm/d/Y @ ' . (($conf['app']['timeFormat'] ==24) ? 'H' : 'h') . ':i:s' . (($conf['app']['timeFormat'] == 24) ? '' : ' a');
		return date($format, $ts);
	}
	
	
	/**
	* Convert minutes to hours/minutes
	* @param int $minutes minutes to convert
	* @return string version of hours and minutes
	*/
	function minutes_to_hours($minutes) {
		if ($minutes == 0)
			return '0 hours';
			
		$hours = (intval($minutes / 60) != 0) ? intval($minutes / 60) . ' hours' : '';
		$min = (intval($minutes % 60) != 0) ? intval($minutes % 60) . ' mins' : '';
		return ($hours . ' ' . $min);
	}
	
	/**
	* Return the current script URL directory
	* @param none
	* @return url url of curent script directory
	*/
	function getScriptURL() {
		global $conf;
		$uri = $conf['app']['weburi'];
		return (strrpos($uri, '/') === false) ? $uri : substr($uri, 0, strlen($uri));
	}
	
	
	/**
	* Prints an error message box and kills the app
	* @param string $msg error message to print
	* @param string $style inline CSS style definition to apply to box
	* @param boolean $die whether to kill the app or not
	*/
	function do_error_box($msg, $style='', $die = true) {
		echo '<table border="0" cellspacing="0" cellpadding="0" align="center" class="alert" style="' . $style . '"><tr><td>' . $msg . '</td></tr></table>';
		
		if ($die) {
			$t = new Template();
			$t->endMain();
			$t->printHTMLFooter();
		 	die();
		}
	}
	
	/**
	* Prints out a box with notification message
	* @param string $msg message to print out
	* @param string $style inline CSS style definition to apply to box
	*/
	function do_message_box($msg, $style='') {
		echo '<table border="0" cellspacing="0" cellpadding="0" align="center" class="message" style="' . $style . '"><tr><td>' . $msg . '</td></tr></table>';
	}
	
	/**
	* Returns a reference to a new Link object
	* Used to make HTML links
	* @param none
	* @return Link object
	*/
	function getNewLink() {
		return new Link();
	}
	
	/**
	* Returns a reference to a new Pager object
	* Used to iterate over limited recordesets
	* @param none
	* @return Pager object
	*/
	function getNewPager() {
		return new Pager();
	}
	
	/**
	* Strip out slahses from POST values
	* @param none
	* @return array of cleaned up POST values
	*/
	function cleanPostVals() {
		$return = array();
		
		foreach ($_POST as $key => $val)
			$return[$key] = stripslashes(trim($val));
		
		return $return;
	}
	
	/**
	* Strip out slahses from an array of data
	* @param none
	* @return array of cleaned up data
	*/
	function cleanVals($data) {
		$return = array();
		
		foreach ($data as $key => $val)
			$return[$key] = stripslashes($val);
		
		return $return;
	}
	
	/**
	* Verifies vertical order and returns value
	* @param string $vert value of vertical order
	* @return string vertical order
	*/
	function get_vert_order($get_name = 'vert') {
		// If no vertical value is specified, use ASC
		$vert = isset($_GET[$get_name]) ? $_GET[$get_name] : 'ASC';
	    
		// Validate vert value, default to DESC if invalid
		switch($vert) {
			case 'DESC';
			case 'ASC';
			break;
			default :
				$vert = 'DESC';
			break;
		}
		
		return $vert;
	}
	
	/**
	* Verifies and returns the order to list recordset results by
	* If none of the values are valid, it will return the 1st element in the array
	* @param array $orders all valid order names
	* @return string order of recorset
	*/
	function get_value_order($orders = array(), $get_name = 'order') {
		if (empty($orders))		// Return null if the order array is empty
			return NULL;
			
		// Set default order value
		// If a value is specifed in GET, use that.  Else use the first element in the array
		$order = isset($_GET[$get_name]) ? $_GET[$get_name] : $orders[0];
		
		if (in_array($order, $orders))
			$order = $order;
		else
			$order = $orders[0];
	
		return $order;
	}
	
	
	/**
	* Opposite of php's nl2br function.
	* Subs in a newline for all brs
	* @param string $subject line to make subs on
	* @return reformatted line
	*/
	function br2nl($subject) {
		return str_replace('<br />', "\n", $subject);
	}
	
	/**
	* Writes a log string to the log file specified in config.php
	* @param string $string log entry to write to file
	* @param string $userid memeber id of user performing the action
	* @param string $ip ip address of user performing the action
	*/
	function write_log($string, $userid = NULL, $ip = NULL) {
		global $conf;
		$delim = "\t";
		$file = $conf['app']['logfile'];
		$values = '';

		if (!$conf['app']['use_log'])	// Return if we aren't going to log
			return;
		
		if (empty($ip))
			$ip = $_SERVER['REMOTE_ADDR'];
		
		clearstatcache();				// Clear cached results
		
		if (!is_dir(dirname($file)))
			mkdir(dirname($file), 0777);		// Create the directory
		
		if (!touch($file))
			return;					// Return if we cant touch the file
			
		if (!$fp = fopen($file, 'a'))
			return;					// Return if the fopen fails
		
		flock($fp, LOCK_EX);		// Lock file for writing
		if (!fwrite($fp, '[' . date('D, d M Y H:i:s') . ']' . $delim . $string . $delim . $userid . $delim . $ip . "\r\n"))	// Write log entry
        	return;					// Return if we cant write to the file
		flock($fp, LOCK_UN);		// Unlock file
		fclose($fp);
	}
	
	/**
	* Returns the day name
	* @param int $day_of_week day of the week
	* @param int $type how to return the day name (0 = full, 1 = one letter, 2 = two letter, 3 = three day)
	*/
	function get_day_name($day_of_week, $type = 0) {
		$names = array (
			array ('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
			array ('S', 'M', 'T', 'W', 'T', 'F', 'S'),
			array ('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'),
			array ('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat')
			);
		
		return $names[$type][$day_of_week];
	}

}
?>