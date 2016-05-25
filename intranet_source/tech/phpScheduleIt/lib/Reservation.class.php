<?php
/**
* Reservation class
* Provides access to reservation data
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-17-04
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
* ResDB class
*/
include_once('db/ResDB.class.php');
/**
* User class
*/
include_once('User.class.php');
/**
* PHPMailer
*/
include_once('PHPMailer.class.php');
/**
* Reservation templates
*/
include_once(BASE_DIR . '/templates/reserve.template.php');


class Reservation {
	var $id 		= null;				//	Properties
	var $date 		= null;				//
	var $start	 	= null;				//
	var $end	 	= null;				//
	var $machid 	= null;				//
	var $memberid 	= null;				//
	var $created 	= null;				//
	var $modified 	= null;				//
	var $type 		= null;				//
	var $is_repeat	= false;			//
	var $repeat		= null;				//
	var $minRes		= null;				//
	var $maxRes		= null;				//
	var $parentid	= null;				//
	var $is_blackout= false;			//
	var $summary	= null;				//
	var $scheduleid	= null;				//
	var $sched		= null;				//

	var $errors     = array();
	var $word		= null;

	var $db;

	/**
	* Reservation constructor
	* Sets id (if applicable)
	* Sets the reservation action type
	* Sets the database reference
	* @param string $id id of reservation to load
	*/
	function Reservation($id = null, $is_blackout = false) {
		if (!empty($id))
			$this->id = $id;

		$this->type = isset($_GET['type']) ? substr($_GET['type'], 0, 1) : null;
		$this->is_blackout = $is_blackout;
		$this->word = $is_blackout ? 'blackout' : 'reservation';
		$this->db = new ResDB();
	}

	/**
	* Loads all reservation properties from the database
	* @param none
	*/
	function load_by_id() {
		$res = $this->db->get_reservation($this->id);	// Get values from DB
		
		if (!$res)		// Quit if reservation doesnt exist
			CmnFns::do_error_box($this->db->get_err());

		$this->date		= $res['date'];
		$this->start	= $res['startTime'];
		$this->end		= $res['endTime'];
		$this->machid	= $res['machid'];
		$this->memberid = $res['memberid'];
		$this->created	= $res['created'];
		$this->modified = $res['modified'];
		$this->parentid = $res['parentid'];	
		$this->summary	= htmlspecialchars($res['summary']);
		$this->scheduleid	= $res['scheduleid'];
		$this->is_blackout	= $res['is_blackout'];
		
		$this->sched = $this->db->get_schedule_data($this->scheduleid);
	}

	/**
	* Loads the required reservation properties using
	*  what is passed in from the querystring
	* @param none
	*/
	function load_by_get() {
		$this->machid 	= $_GET['machid'];
		$this->date   	= $_GET['ts'];
		$this->memberid = $_SESSION['sessionID'];
		$this->scheduleid = $_GET['scheduleid'];
		
		$this->sched = $this->db->get_schedule_data($this->scheduleid);
	}

	/**
	* Deletes the current reservation from the database
	* If this is a recurring reservation, it may delete all reservations in group
	* @param boolean $del_recur whether to delete all recurring reservations in this group
	*/
	function del_res($del_recur) {
		$this->load_by_id();
		$this->type = 'd';

		$this->is_repeat = $del_recur;

		$this->db->del_res($this->id, $this->parentid, $del_recur, mktime(0,0,0));

		$user = new User($this->memberid);		// Set up User object

		if (!$this->is_blackout && $user->wants_email('e_del'))		// Mail the user if they want to be notified
			$this->send_email('e_del', $user);

		CmnFns::write_log($this->word . ' ' . $this->id . ' deleted.', $this->memberid, $_SERVER['REMOTE_ADDR']);
		if ($this->is_repeat)
			CmnFns::write_log('All ' . $this->word . 's associated with ' . $this->id . ' (having parentid ' . $this->parentid . ') were also deleted', $this->memberid, $_SERVER['REMOTE_ADDR']);
		$this->print_success('deleted');
	}

	/**
	* Add a new reservation to the database
	*  after verifying that user has permission
	*  and the time is available
	* @param string $machid id of resource to reserve
	* @param string $memberid id of member making reservation
	* @param float $start starting time of reservation
	* @param float $end ending time of reservation
	* @param array $repeat repeat reservation values
	* @param int $min minimum reservation time
	* @param int $max maximum reservation time
	* @param string $summary reservation summary
	* @param string $scheduleid id of schedule to make reservation on
	*/
	function add_res($machid, $memberid, $start, $end, $repeat, $min, $max, $summary = null, $scheduleid) {
		$this->machid	= $machid;
		$this->memberid = $memberid;
		$this->start	= $start;
		$this->end		= $end;
		$this->repeat 	= $repeat;
		$this->type     = 'a';
		$this->summary	= $summary;
		$this->scheduleid = $scheduleid;

		$dates = array();
		$tmp_valid = false;

		if (!$this->is_blackout) {
			$user = new User($this->memberid);		// Set up a new User object
			$this->check_perms($user);		// Only need to check once
			$this->check_min_max($min, $max);
		}
		$this->check_times();
		
		if ($this->has_errors())			// Print any errors generated above and kill app
			$this->print_all_errors(true);
		
		$is_parent = $this->is_repeat;		// First valid reservation will be parentid (no parentid if solo reservation)
		
		for ($i = 0; $i < count($repeat); $i++) {
			$this->date = $repeat[$i];
			if ($i == 0) $tmp_date = $this->date;			// Store the first date to use in the email
			$is_valid = $this->check_res();

			if ($is_valid) {
				$tmp_valid = true;							// Only one recurring needs to work
				$this->id = $this->db->add_res($this, $is_parent);
				if (!$is_parent) {
					array_push($dates, $this->date);		// Add recurring dates (first date isnt recurring)
				}
				else {
					$this->parentid = $this->id;			// The first reservation is the parent id

				}
				$is_parent = false;							// Parent has already been stored
				CmnFns::write_log($this->word . ' ' . $this->id . ' added.  machid:' . $this->machid .', date:' . $this->date . ', start:' . $this->start . ', end:' . $this->end, $this->memberid, $_SERVER['REMOTE_ADDR']);
			}
		}
		
		if ($this->has_errors())		// Print any errors generated when adding the reservations
			$this->print_all_errors(!$this->is_repeat);
		
		$this->date = $tmp_date;				// Restore first date for use in email
		if ($this->is_repeat) array_unshift($dates, $this->date);		// Add to list of successful dates
		
		sort($dates);
		
		if (!$this->is_blackout && $user->wants_email('e_add')) {		// Notify the user if they want (only 1 email)
			$this->send_email('e_add', $user, $dates);
		}

		if (!$this->is_repeat || $tmp_valid)
			$this->print_success('added', $dates);
	}

	/**
	* Modifies a current reservation, setting new start and end times
	*  or deleting it
	* @param int $start new start time
	* @param int $end new end time
	* @param bool $del whether to delete it or not
	* @param int $min minimum reservation time
	* @param int $max maximum reservation time
	* @param boolean $mod_recur whether to modify all recurring reservations in this group
	* @param string $summary reservation summary
	*/
	function mod_res($start, $end, $del, $min, $max, $mod_recur, $summary = null) {
		$recurs = array();

		$this->load_by_id();			// Load reservation data
		$this->type = 'm';
		$this->summary = $summary;

		if ($del) {						// First, check if this should be deleted
			$this->del_res($mod_recur, mktime(0,0,0));
			return;
		}

		if (!$this->is_blackout) {
			$user = new User($this->memberid);		// Set up a User object
			$this->check_perms($user);				// Check permissions
			$this->check_min_max($min, $max);		// Check min/max reservation times
		}
		
		$this->start = $start;			// Assign new start and end times
		$this->end	 = $end;
		
		$this->check_times();			// Check valid times
		
		$this->is_repeat = $mod_recur;	// If the mod_recur flag is set, it must be a recurring reservation
		$dates = array();

		// First, modify the current reservation
		
		if ($this->has_errors())			// Print any errors generated above and kill app
			$this->print_all_errors(true);
	
		$tmp_valid = false;

		if ($this->is_repeat) {		// Check and place all recurring reservations
			$recurs = $this->db->get_recur_ids($this->parentid, mktime(0,0,0));
			for ($i = 0; $i < count($recurs); $i++) {
				$this->id   = $recurs[$i]['resid'];		// Load reservation data
				$this->date = $recurs[$i]['date'];
				$is_valid   = $this->check_res();			// Check overlap (dont kill)

				if ($is_valid) {
					$tmp_valid = true;				// Only one recurring needs to pass
					$this->db->mod_res($this);		// And place the reservation
					array_push($dates, $this->date);
					CmnFns::write_log($this->word . ' ' . $this->id . ' modified.  machid:' . $this->machid .', date:' . $this->date . ', start:' . $this->start . ', end:' . $this->end, $this->memberid, $_SERVER['REMOTE_ADDR']);
				}
			}
		}
		else {
			if ($this->check_res()) {			// Check overlap
				$this->db->mod_res($this);		// And place the reservation
				array_push($dates, $this->date);
			}
		}
		
		if ($this->has_errors())		// Print any errors generated when adding the reservations
			$this->print_all_errors(!$this->is_repeat);

		if (!$this->is_blackout && $user->wants_email('e_mod')) {		// Notify the user if they want
			$this->send_email('e_mod', $user);
		}

		if (!$this->is_repeat || $tmp_valid)
			$this->print_success('modified', $dates);
	}

	/**
	* Prints a message nofiying the user that their reservation was placed
	* @param string $verb action word of what kind of reservation process just occcured
	* @param array $dates dates that were added or modified.  Deletions are not printed.
	*/
	function print_success($verb, $dates = array()) {
		echo '<script language="JavaScript" type="text/javascript">' . "\n"
			. 'window.opener.document.location.href = window.opener.document.URL;' . "\n"
			. '</script>';
		$date_text = '';
		for ($i = 0; $i < count($dates); $i++) {
			$date_text .= CmnFns::formatDate($dates[$i]) . '<br/>';
		}
		CmnFns::do_message_box('Your ' . $this->word . ' was successfully ' . $verb
					. (($this->type != 'd') ? ' for the follwing dates:<br /><br />' : '.')
					. $date_text . '<br/><br/>'
					. '<a href="javascript: window.close();">Close</a>'
					, 'width: 90%;');
	}

	/**
	* Verify that the user selected appropriate times
	* @return if the time is valid
	*/
	function check_times() {
		$is_valid = ( floatval($this->start) < floatval($this->end) );
		if (!$is_valid)
			$this->add_error('Start time must be less than end time!<br /><br >'
					. 'Current start time is: ' . CmnFns::formatTime($this->start) . '<br />'
					. 'Current end time is: ' . CmnFns::formatTime($this->end)
				);
		return $is_valid;
	}

	/**
	* Check to make sure that the reservation falls within the specified reservation length
	* @param int $min minimum reservation length
	* @param int $max maximum reservation length
	* @param boolean $kill whether to kill the process if the check fails
	* @return if the time is valid
	*/
	function check_min_max($min, $max) {
		$this_length = ($this->end - $this->start);
		$is_valid = ($this_length >= ($min)) && (($this_length) <= ($max));
		if (!$is_valid)
			$this->add_error('Reservation length does not fall within this resource\'s allowed length.<br /><br >'
					. 'Your reservation is: ' . CmnFns::minutes_to_hours($this->end - $this->start) . '<br />'
					. 'Minimum reservation length: ' . CmnFns::minutes_to_hours($min). '<br />'
					. 'Maximum reservation length: ' . CmnFns::minutes_to_hours($max)
					);
		return $is_valid;
	}

	/**
	* Checks to see if a time is already reserved
	* @param bool $kill whether to kill the app
	* @return whether the time is reserved or not
	*/
	function check_res() {
		$is_valid = !($this->db->check_res($this));
		if (!$is_valid) {
			$this->add_error(date('l m/d/Y', $this->date) . ' from ' . CmnFns::formatTime($this->start) . ' to ' . CmnFns::formatTime($this->end) . ' is reserved or unavailable.');
		}
		return $is_valid;
	}

	/**
	* Check if a user has permission to use a resource
	* @param object $user object for this reservations user
	* @param bool whether to kill the app if the user does not have permission
	* @return whether user has permission to use resource
	*/
	function check_perms(&$user, $kill = true) {
		if (Auth::isAdmin())					// Admin always has permission
			return true;

		$has_perm = $user->has_perm($this->machid); // Get user permissions

		if (!$has_perm)
			CmnFns::do_error_box(
					'You do not have permission to use this resource.'
					, 'width: 90%;'
					, $kill);

		return $has_perm;
	}

	/**
	* Prints out the reservation table
	* @param none
	*/
	function print_res() {
		global $conf;
		
		if (!empty($this->id))
			$this->load_by_id();
		else
			$this->load_by_get();

		$rs = $this->db->get_resource_data($this->machid);
		
		begin_reserve_form($this->type == 'r', $this->is_blackout);			// Start form

		start_left_cell();

		print_resource_data($rs, ($this->type == 'r' ? 2 : 1));		// Print resource info

		print_time_info($this, $rs, !$this->is_blackout);	// Print time information

		if (!$this->is_blackout)
			print_user_info($this->type, new User($this->memberid));	// Print user info

		if (!empty($this->id))			// Print created/modified times (if applicable)
			print_create_modify($this->created, $this->modified);
		
		print_summary($this->summary, $this->type);
		
		if (!empty($this->parentid) && ($this->type == 'm' || $this->type == 'd'))
			print_recur_checkbox($this->parentid);
		
		if ($this->type == 'm')
			print_del_checkbox();
		
		print_buttons($this->type);		// Print submit buttons

		print_hidden_fields($this);		// Print hidden form fields

		if ($this->type == 'r') {		// Print out repeat reservation box, if applicable
			divide_table();
			//$weeks = $this->create_week_array($conf['app']['recurringWeeks']);
			print_repeat_box(date('m', $this->date), date('Y', $this->date));
		}

		end_right_cell();

		end_reserve_form();				// End form
	}

	/**
	* Creates an array of unique weeks to display for users to
	*  select recurring reservations for
	* @param array $weekArray week values to split up and put into return array
	* @return array of unique week numbers to use for recurring reservations
	*/
	function create_week_array($weekArray) {
		$weeks = array();
		if (count($weekArray) == 1) {
			for ($i = 1; $i <= $weekArray[0]; $i++) {
				$weeks[] = intval($i);
			}
		}
		else {
			for ($i = 0; $i < count($weekArray); $i++) {
				if (strpos($weekArray[$i], '-') !== false) {
					list($first, $last) = explode('-', $weekArray[$i]);
					for ($j = intval($first); $j <= intval($last); $j++)
						$weeks[] = intval($j);
				}
				else
					$weeks[] = intval($weekArray[$i]);
			}
		}

		$weeks = array_unique($weeks);
		sort($weeks);
		
		return $weeks;
	}

	/**
	* Sends an email notification to the user
	* This function sends an email notifiying the user
	* of creation/modification/deletion of a reservation
	* @param string $type type of modification made to the reservation
	* @param object $res this reservation object
	* @param array $repeat_dates array of dates reserved on
	* @global $conf
	*/
	function send_email($type, &$user, $repeat_dates = null) {
		global $conf;

		$rs = $this->db->get_resource_data($this->machid);

		// Email addresses
		$adminEmail = $this->sched['adminEmail'];
		$techEmail  = $conf['app']['techEmail'];
		$url        = CmnFns::getScriptURL();

		// Format date
		$date   = CmnFns::formatDate($this->get_date());
		$start  = CmnFns::formatTime($this->get_start());
		$end    = CmnFns::formatTime($this->get_end());

		switch ($type) {
			case 'e_add' : $mod = 'created';
			break;
			case 'e_mod' : $mod = 'modified';
			break;
			case 'e_del' : $mod = 'deleted';
			break;
		}


		$to     = $user->get_email();		// Who to mail to
		$subject= "Reservation $mod for $date";
		$uname = $user->get_fname();
		
		$rs['location'] = !empty($rs['location']) ? $rs['location'] : 'n\a';
		$rs['rphone'] = !empty($rs['rphone']) ? $rs['rphone'] : 'n\a';

		$text =
			  "$uname,\r\n<br />"
			. "You have successfully $mod reservation #$this->id.\r\n\r\n<br/><br/>"

			. "Please use this reservation number when contacting the administrator with any questions.\r\n\r\n<br/><br/>"

			. "A reservation on $date between $start and $end for {$rs['name']}"
			. " located at {$rs['location']} has been $mod.\r\n\r\n<br/><br/>";

			if (!empty($repeat_dates)) {
				$text .= "This reservation has been repeated on the following dates:\r\n<br/>";
				for ($d = 0; $d < count($repeat_dates); $d++)
					$text .= CmnFns::formatDate($repeat_dates[$d]) . "\r\n<br/>";
				$text .= "\r\n<br/>";
			}

			if ($type != 'e_add' && $this->is_repeat) {
				$text .= "All recurring reservations in this group were also $mod.\r\n\r\n<br/><br/>";
			}
			
			if (!empty($this->summary))
				$text .= "The following summary was provided for this reservation:\r\n<br/>" . stripslashes($this->summary) . "\r\n\r\n<br/><br/>";

			$text .= "If this is a mistake, please contact the administrator at: $adminEmail"
			. " or by calling {$rs['rphone']}.\r\n\r\n<br/><br/>"

			. "You can view or modify your reservation information at any time by"
			. " logging into {$conf['app']['title']} at:\r\n<br/>"
			. " <a href=\"$url\" target=\"_blank\">$url</a>.\r\n\r\n<br/><br/>";

			if (!empty($techEmail)) $text .= "Please direct all technical questions to <a href='mailto:$techEmail'>$techEmail</a>.\r\n\r\n<br /><br />";

		if ($user->wants_html()) {
			$msg = <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <style type="text/css">
	<!--
	body {
		font-size: 11px;
    	font-family: Verdana, Arial, Helvetica, sans-serif;
		background-color: #F0F0F0;
	}
	a {
		color: #104E8B;
		text-decoration: none;
	}
	a:hover {
		color: #474747;
		text-decoration: underline;
	}
	table tr.header td {
		padding-top: 2px;
		padding-botton: 2px;
		background-color: #CCCCCC;
		color: #000000;
		font-weight: bold;
		font-size: 10px;
		padding-left: 10px;
		padding-right: 10px;
		border-bottom: solid 1px #000000;
	}
	table tr.values td {
		border-bottom: solid 1px #000000;
		padding-left: 10px;
		padding-right: 10px;
		font-size: 10px;
	}
	-->
	</style>
</head>

<body>

$text

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="header">
    <td>Reservation #</td>
    <td>Date</td>
    <td>Resource</td>
    <td>Start Time</td>
    <td>End Time</td>
    <td>Location</td>
    <td>Contact</td>
  </tr>
  <tr class="values">
    <td>$this->id</td>
    <td>$date</td>
    <td>{$rs['name']}</td>
    <td>$start</td>
    <td>$end</td>
    <td>{$rs['location']}</td>
    <td>{$rs['rphone']}</td>
  </tr>
</table>
  </body>
</html>
EOT;
		}
		else {
			$text = strip_tags($text);		// Strip out HTML tags
			$msg = $text;

			$fields = array (	// array[x] = [0] => title, [1] => field value, [2] => length
						array('Reservation #', $this->id, ((strlen($this->id) < 13) ? 13 : strlen($this->id))),
						array('Date', $date, ((strlen($date) < 4) ? 4 : strlen($date))),
						array('Resource', $rs['name'], ((strlen($rs['name']) < 8) ? 8 : strlen($rs['name']))),
						array('Start Time', $start, ((strlen($start) < 10) ? 10 : strlen($start))),
						array('End Time', $end, ((strlen($end) < 8) ? 8 : strlen($end))),
						array('Location', $rs['location'], ((strlen($rs['location']) < 8) ? 8 : strlen($rs['location']))),
						array('Contact', $rs['rphone'], ((strlen($rs['rphone']) < 7) ? 7 : strlen($rs['rphone'])))
						);
			$total_width = 0;

			foreach ($fields as $a) {	// Create total width by adding all width fields plus the '| ' that occurs before every cell and ' ' after
				$total_width += (2 + $a[2] + 1);
			}
			$total_width++;		// Final '|'

			$divider = '+' . str_repeat('-', $total_width - 2) . '+'; 		// Row dividers

			$msg .= $divider . "\n";
			$msg .= "| Reservation $mod" . (str_repeat(' ', $total_width - strlen("Reservation $mod") - 4)) . " |\n";
			$msg .= $divider . "\n";
			foreach ($fields as $a)		// Repeat printing all title fields, plus enough spaces for padding
				$msg .= "| $a[0]" . (str_repeat(' ', $a[2] - strlen($a[0]) + 1));
			$msg .= "|\n";					// Close the row
			$msg .= $divider . "\n";
			foreach ($fields as $a)		// Repeat printing all field values, plus enough spaces for padding
				$msg .= "| $a[1]" . (str_repeat(' ', $a[2] - strlen($a[1]) + 1));
			$msg .= "|\n";					// Close the row
			$msg .= $divider . "\n";
		}

		// Send email using PHPMailer	
		$mailer = new PHPMailer();
		$mailer->AddAddress($to, $uname);
		$mailer->From = $adminEmail;
		$mailer->FromName = $conf['app']['title'];
		// If emailAdmin is set to true, put them in cc
		if ($conf['app']['emailAdmin'])
			$mailer->AddCC($adminEmail, 'Administrator');
		$mailer->Subject = $subject;
		$mailer->Body = $msg;
		$mailer->IsHTML($user->wants_html());
		$mailer->Send();

		unset($rs, $headers, $msg, $fields);
	}


	/**
	* Returns the type of this reservation
	* @param none
	* @return string the 1 char reservation type
	*/
	function get_type() {
		return $this->type;
	}

	/**
	* Returns the ID of this reservation
	* @param none
	* @return string this reservations id
	*/
	function get_id() {
		return $this->id;
	}

	/**
	* Returns the start time of this reservation
	* @param none
	* @return int start time (in minutes)
	*/
	function get_start() {
		return $this->start;
	}

	/**
	* Returns the end time of this reservation
	* @param none
	* @return int ending time (in minutes)
	*/
	function get_end() {
		return $this->end;
	}

	/**
	* Returns the timestamp for this reservation's date
	* @param none
	* @return int reservation timestamp
	*/
	function get_date() {
		return $this->date;
	}

	/**
	* Returns the created timestamp of this reservation
	* @param none
	* @return int created timestamp
	*/
	function get_created() {
		return $this->created;
	}

	/**
	* Returns the modified timestamp of this reservation
	* @param none
	* @return int modified timestamp
	*/
	function get_modified() {
		return $this->modified;
	}

	/**
	* Returns the resource id of this reservation
	* @param none
	* @return string resource id
	*/
	function get_machid() {
		return $this->machid;
	}

	/**
	* Returns the member id of this reservation
	* @param none
	* @return string memberid
	*/
	function get_memberid() {
		return $this->memberid;
	}

	/**
	* Returns the User object for this reservation
	* @param none
	* @return User object for this reservation
	*/
	function &get_user() {
		return $this->user;
	}

	/**
	* Returns the id of the parent reservation
	* This will only be set if this is a recurring reservation
	*  and is not the first of the set
	* @param none
	* @return string parentid
	*/
	function get_parentid() {
		return $this->parentid;
	}
	
	/**
	* Returns the summary for this reservation
	* @param none
	* @return string summary
	*/
	function get_summary() {
		return $this->summary;
	}
	
	/**
	* Returns the scheduleid for this reservation
	* @param none
	* @return string scheduleid
	*/
	function get_scheduleid() {
		return $this->scheduleid;
	}

	/**
	* Whether there were errors processing this reservation or not
	* @param none
	* @return if there were errors or not processing this reservation
	*/
	function has_errors() {
		return count($this->errors) > 0;
	}

	/**
	* Add an error message to the array of errors
	* @param string $msg message to add
	*/
	function add_error($msg) {
		array_push($this->errors, $msg);
	}
	
	/**
	* Return the last error message generated
	* @param none
	* @return the last error message
	*/
	function get_last_error() {
		if ($this->has_errors())
			return $this->errors(count($this->errors)-1);
		else
			return null;
	}
	
	/**
	* Prints out all the error messages in an error box
	* @param boolean $kill whether to kill the app after printing messages
	*/
	function print_all_errors($kill) {
		if ($this->has_errors()) {
			$div = '<hr size="1"/>';
			CmnFns::do_error_box(
				'Please fix the following errors:<br /><br />' . join($div, $this->errors) . '<br /><br />Please <a href="javascript: history.back();">go back</a> and fix these errors.'
				, 'width: 90%;'
				, $kill);
			}
	}

}
?>