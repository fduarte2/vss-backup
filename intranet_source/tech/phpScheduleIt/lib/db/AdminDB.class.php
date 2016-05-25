<?php
/**
* This file accesses the database and retrieves data
*  for adminstrative purposes
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-14-04
* @package DBEngine
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/../..');

/**
* DBEngine class
*/
include_once(BASE_DIR . '/lib/DBEngine.class.php');

class AdminDB extends DBEngine {
	
	/**
	* Returns array of user data
	* @param Object $pager pager object
	* @param string $table name of table to retrieve
	* @param string $orders order to return values in
	* @param boolean $limit whether this is a limited query or not
	* @return array of user data
	*/
	function get_all_admin_data(&$pager, $table, $orders, $limit = false) {		
		$return = array();

		if ($limit) {
			$lim = $pager->getLimit();
			$offset = $pager->getOffset();
		}
		else {
			$limit = '';
			$offset = '';
		}
		return $this->get_table_data($table, array('*'), $orders, $lim, $offset);
	}
	
	
	/**
	* Returns an array of all reservation data
	* @param Object $pager pager object
	* @param array $orders order than results should be sorted in
	* @return array of all reservation data
	*/
	function get_reservation_data(&$pager, $orders) {
		$return = array();
		
		$order = CmnFns::get_value_order($orders);	
		$vert = CmnFns::get_vert_order();
		
		if ($order == 'date' && !isset($_GET['vert']))		// Default the date to DESC
			$vert = 'DESC';
		
		// Set up query to get neccessary records ordered by user request first, then logical order
		$query = 'SELECT res.resid, res.date,
			res.startTime, res.endTime,
			res.created, res.modified,
			rs.name,
			l.fname, l.lname, l.memberid
			FROM ' . $this->get_table('reservations') . ' as res, ' . $this->get_table('login') . ' as l, ' . $this->get_table('resources') . ' as rs
			WHERE res.memberid=l.memberid
			AND res.machid=rs.machid
			AND res.is_blackout <> 1
			ORDER BY ' . $order . ' ' . $vert . ', res.date DESC, res.startTime, res.endTime, l.lname, l.fname';
	
		$result = $this->db->limitQuery($query, $pager->getOffset(), $pager->getLimit());
		
		$this->check_for_error($result);
		
		if ($result->numRows() <= 0) {
			$this->err_msg = 'No results';
			return false;
		}
				
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		
		$result->free();
		
		return $return;
	}
	
	/**
	* Returns an array of all resource data
	* @param Object $pager pager object
	* @param array $orders order than results should be sorted in
	* @return array of all resource data
	*/
	function get_all_resource_data(&$pager, $orders) {
		$return = array();
		
		$order = CmnFns::get_value_order($orders);	
		$vert = CmnFns::get_vert_order();
		
		// Set up query to get neccessary records ordered by user request first, then logical order
		$query = 'SELECT rs.*, s.scheduleTitle
			FROM ' . $this->get_table('resources') . ' as rs, ' . $this->get_table('schedules') . ' as s 
			WHERE rs.scheduleid=s.scheduleid
			ORDER BY ' . $order . ' ' . $vert;
	
		$result = $this->db->limitQuery($query, $pager->getOffset(), $pager->getLimit());
		
		$this->check_for_error($result);
		
		if ($result->numRows() <= 0) {
			$this->err_msg = 'No results';
			return false;
		}
				
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		
		$result->free();
		
		return $return;
	}

	
	/**
	* Returns the number of records from a given table
	*  (for paging purposes)
	* @param string $table table to count
	* @return number of records in the table
	*/
	function get_num_admin_recs($table) {
		$query = 'SELECT COUNT(*) as num FROM ' . $this->get_table($table);
		if ($table == 'reservations')
			$query .= ' WHERE is_blackout <> 1';
		// Get # of records
		$result = $this->db->getRow($query);

		// Check query
		$this->check_for_error($result);
			
		return $result['num'];              // # of records
	}
	
	/**
	* Returns an array of data about a schedule
	* @param int $scheduleid schedule id
	* @return array of data associated with that schedule
	*/
	function get_schedule_data($scheduleid) {
		
		$result = $this->db->getRow('SELECT * FROM ' . $this->get_table('schedules') . ' WHERE scheduleid=?', array($scheduleid));
		// Check query
		$this->check_for_error($result);
		
		if (count($result) <= 0) {
			$this->err_msg = 'No results';
			return false;
		}
		
		return $this->cleanRow($result);
	}
	
	/**
	* Inserts a new schedule into the database
	* @param array $rs array of schedule data
	*/
	function add_schedule($rs) {
		$values = array();
		
		$id = $this->get_new_id();
							
		array_push($values, $id);	// Values to insert
		array_push($values, $rs['scheduleTitle']);
		array_push($values, $rs['dayStart']);
		array_push($values, $rs['dayEnd']);
		array_push($values, $rs['timeSpan']);
		//array_push($values, 12);
		array_push($values, $rs['weekDayStart']);
		array_push($values, $rs['viewDays']);
		//array_push($values, 0);
		array_push($values, $rs['isHidden']);
		array_push($values, $rs['showSummary']);
		array_push($values, $rs['adminEmail']);
		array_push($values, $rs['dayOffset']);

		
		$q = $this->db->prepare('INSERT INTO ' . $this->get_table('schedules') . 
			' (scheduleid,scheduleTitle,dayStart,dayEnd,timeSpan,weekDayStart,viewDays,isHidden,showSummary,adminEmail,dayOffset)'
			.' VALUES(?,?,?,?,?,?,?,?,?,?,?)');
		$result = $this->db->execute($q, $values);
		$this->check_for_error($result);
		
		return $id;
	}
	
	/**
	* Edits resource data in database
	* @param array $rs array of values to edit
	*/
	function edit_schedule($rs) {
		$values = array();
		
		array_push($values, $rs['scheduleTitle']);
		array_push($values, $rs['dayStart']);
		array_push($values, $rs['dayEnd']);
		array_push($values, $rs['timeSpan']);
		array_push($values, $rs['weekDayStart']);
		array_push($values, $rs['viewDays']);
		array_push($values, $rs['isHidden']);
		array_push($values, $rs['showSummary']);
		array_push($values, $rs['adminEmail']);
		array_push($values, $rs['dayOffset']);
		array_push($values, $rs['scheduleid']);
		
		
		$sql = 'UPDATE '. $this->get_table('schedules') . ' SET'
				. ' scheduleTitle=?, dayStart=?, dayEnd=?, timeSpan=?,'
				. ' weekDayStart=?, viewDays=?, isHidden=?, showSummary=?, adminEmail=?, dayOffset=?'
				. ' WHERE scheduleid=?';
		
		$q = $this->db->prepare($sql);
		$result = $this->db->execute($q, $values);
		$this->check_for_error($result);
	}
	
	function del_schedule($schedules) {
		// Do not delete default schedule
		$default_schedule = $this->db->getOne('SELECT scheduleid FROM ' . $this->get_table('schedules') . ' WHERE isDefault = 1');
		if (($idx = array_search($default_schedule, $schedules)) !== false)
			unset($schedules[$idx]);
		
		$scheduleids = $this->make_del_list($schedules);

		// Delete all reservations for these schedules
		$result = $this->db->query('DELETE FROM ' . $this->get_table('reservations') . ' WHERE scheduleid IN(' . $scheduleids . ')');
		$this->check_for_error($result);
		// Delete all schedules
		$result = $this->db->query('DELETE FROM ' . $this->get_table('schedules') . ' WHERE scheduleid IN(' . $scheduleids . ')');
		$this->check_for_error($result);
		
		$newid = $this->db->getOne('SELECT scheduleid FROM ' . $this->get_table('schedules'));
		if (empty($newid))
			// Delete all resources for these schedules
			$result = $this->db->query('DELETE FROM ' . $this->get_table('resources') . ' WHERE scheduleid IN(' . $scheduleids . ')');
		else
			// Reassign all resources
			$result = $this->db->query('UPDATE ' . $this->get_table('resources') . ' SET scheduleid = ? WHERE scheduleid IN(' . $scheduleids . ')', array($newid));
		
		$this->check_for_error($result);
	}
	
	/**
	* Sets the default schedule
	* @param string $scheduleid id of default schedule
	*/
	function set_default_schedule($scheduleid) {
		$result = $this->db->query('UPDATE ' . $this->get_table('schedules') . ' SET isDefault = 0');
		$this->check_for_error($result);
		
		$result = $this->db->query('UPDATE ' . $this->get_table('schedules') . ' SET isDefault = 1 WHERE scheduleid = ?', array($scheduleid));
		$this->check_for_error($result);
	}
	
	/**
	* Return the number of records found in a search
	*  for use in paging
	* @param string $lname last name to search for
	* @param object $pager pager object
	* @return number of records found
	*/
	function get_num_search_recs($fname, $lname) {
		$result = $this->db->getRow('SELECT COUNT(*) AS num FROM ' . $this->get_table('login')
				. ' WHERE fname LIKE "' . $fname . '%" AND lname LIKE "' . $lname . '%"');
		
		$this->check_for_error($result);
		return $result['num'];		
	}
	
	/**
	* Search for users matching this first and last name and return the results in an array
	* @param string $fname first name to search for
	* @param string $lname last name to search for
	* @param object $pager pager object
	* @param array $orders order to print results in
	* @return array of user data
	*/
	function search_users($fname, $lname, &$pager, $orders) {
		$return = array();
		
		$order = CmnFns::get_value_order($orders);	
		$vert = CmnFns::get_vert_order();
		
		if ($order == 'date' && !isset($_GET['vert']))		// Default the date to DESC
			$vert = 'DESC';
		
		// Set up query to get neccessary records ordered by user request first, then logical order
		$query = 'SELECT l.*'
				. ' FROM ' . $this->get_table('login') . ' as l'
				. '	WHERE fname LIKE "' . $fname . '%" AND lname LIKE "' . $lname . '%"'
				. ' ORDER BY ' . $order . ' ' . $vert . ', l.lname, l.fname';

		$result = $this->db->limitQuery($query, $pager->getOffset(), $pager->getLimit());
		
		$this->check_for_error($result);
		
		if ($result->numRows() <= 0) {
			$this->err_msg = 'No results';
			return false;
		}
				
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		
		$result->free();
		
		return $return;
	}
	
	/**
	* Returns an array of data about a resource
	* @param int $machID resource id
	* @return array of data associated with that resource
	*/
	function get_resource_data($machid) {
		
		$result = $this->db->getRow('SELECT * FROM ' . $this->get_table('resources') . ' WHERE machid=?', array($machid));
		// Check query
		$this->check_for_error($result);
		
		if (count($result) <= 0) {
			$this->err_msg = 'No results';
			return false;
		}
		
		return $this->cleanRow($result);
	}
	
	/**
	* Deletes a list of users from the database
	* @param array $users list of users to delete
	*/
	function del_users($users) {
		$uids = $this->make_del_list($users);
		// Delete users
		$result = $this->db->query('DELETE FROM ' . $this->get_table('login') . ' WHERE memberid IN(' . $uids . ')');
		$this->check_for_error($result);
		// Delete all reservations for these users
		$result = $this->db->query('DELETE FROM ' . $this->get_table('reservations') . ' WHERE memberid IN(' . $uids . ')');
		$this->check_for_error($result);				
	}
	
	/**
	* Inserts a new resource into the database
	* @param array $rs array of resource data
	*/
	function add_resource($rs) {
		$values = array();
		
		$id = $this->get_new_id();
		
		array_push($values, $id);	// Values to insert
		array_push($values, $rs['scheduleid']);
		array_push($values, $rs['name']);
		array_push($values, $rs['location']);
		array_push($values, $rs['rphone']);
		array_push($values, $rs['notes']);
		array_push($values, 'a');
		array_push($values, $rs['minRes']);
		array_push($values, $rs['maxRes']);
		array_push($values, intval(isset($rs['autoAssign'])));

		
		$q = $this->db->prepare('INSERT INTO ' . $this->get_table('resources') . ' VALUES(?,?,?,?,?,?,?,?,?,?)');
		$result = $this->db->execute($q, $values);
		$this->check_for_error($result);
		
		return $id;
	}
	
	/**
	* Edits resource data in database
	* @param array $rs array of values to edit
	*/
	function edit_resource($rs) {
		$values = array();
		
		$sql = 'SELECT scheduleid FROM ' . $this->get_table('resources') . ' WHERE machid=?';
		$old_id = $this->db->getOne($sql, array($rs['machid']));
		$this->check_for_error($old_id);
		
		array_push($values, $rs['scheduleid']);
		array_push($values, $rs['name']);
		array_push($values, $rs['location']);
		array_push($values, $rs['rphone']);
		array_push($values, $rs['notes']);
		array_push($values, $rs['minRes']);
		array_push($values, $rs['maxRes']);
		array_push($values, intval(isset($rs['autoAssign'])));
		array_push($values, $rs['machid']);
		
		$sql = 'UPDATE '. $this->get_table('resources') . ' SET '
				. 'scheduleid=?, name=?, location=?, rphone=?, notes=?, minRes=?, maxRes=?, autoAssign=? WHERE machid=?';
		
		$q = $this->db->prepare($sql);
		$result = $this->db->execute($q, $values);
		
		if ($old_id != $rs['scheduleid']) {		// Update reservations if schedule changes
			$sql = 'UPDATE ' . $this->get_table('reservations') . ' SET scheduleid=? WHERE machid=?';
			$result = $this->db->query($sql, array($rs['scheduleid'], $rs['machid']));
			$this->check_for_error($result);
		}
	}
	
	/**
	* Deletes a list of resources from the database
	* @param array $rs list of machids to delete
	*/
	function del_resource($rs) {
		$rs_list = $this->make_del_list($rs);
		
		//  Delete resources
		$result = $this->db->query('DELETE FROM ' . $this->get_table('resources') . ' WHERE machid IN (' . $rs_list . ')');
		$this->check_for_error($result);
		//  Delete all reservations using these resources
		$result = $this->db->query('DELETE FROM ' . $this->get_table('reservations') . ' WHERE machid IN (' . $rs_list . ')');
		$this->check_for_error($result);
	}
	
	/**
	* Toggles a resource active/inactive
	* @param string $machid id of resource to toggle
	* @param string $status current status of the resource
	*/
	function tog_resource($machid, $status) {
		$status = ($status == 'a') ? 'u' : 'a';
		$result = $this->db->query('UPDATE ' . $this->get_table('resources') . ' SET status=? WHERE machid=?', array($status, $machid));
		$this->check_for_error($result);
	}
	
	/**
	* Clears all user permissions
	* @param string $memberid member id to clear
	*/
	function clear_perms($memberid) {
		$result = $this->db->query('DELETE FROM ' . $this->get_table('permission') . ' WHERE memberid=?', array($memberid));	
		$this->check_for_error($result);
	}
	
	/**
	* Sets user permissions for resources
	* @param string $memberid member's id
	* @param array $machids array of machids to set
	*/
	function set_perms($memberid, $machids) {
		// Create values array for prepared query
		$values = array();
		for ($i = 0; $i < count($machids); $i++) {
			$values[$i] = array($memberid, $machids[$i]);
		}
		
		$query = 'INSERT INTO ' . $this->get_table('permission') . ' VALUES (?,?)';
		// Prepare query
		$q = $this->db->prepare($query);
		// Execute query
		$result = $this->db->executeMultiple($q, $values);
		$this->check_for_error($result);
		
		unset($values);
	}
	
	
	/**
	* Get a list of users, emails
	* @param none
	* @return array of email data
	*/
	function get_user_email() {
		global $conf;
		$return = array();
		
		// Select all users in the system
		$result = $this->db->query('SELECT fname, lname, email FROM ' . $this->get_table('login') . ' WHERE email <> ? ORDER BY lname, fname', array($conf['app']['adminEmail']));
		// Check query
		$this->check_for_error($result);
		
		if ($result->numRows() <= 0) {
			$this->err_msg = 'No results';
			return false;
		}
				
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		
		$result->free();
		
		return $return;
	}
	
	/**
	* Automatically give permission to all users in the system to use this resource
	* @param string $machid id of resource to auto-assign
	*/
	function auto_assign($machid) {
		$values = array();
		$users = array();
		
		$result = $this->db->getOne('SELECT COUNT(machid) AS num FROM ' . $this->get_table('permission') . ' WHERE machid = ?', array($machid)); 
		$this->check_for_error($result);		// Check if this resource is in the permission table
		
		if ($result['num'] > 0) {				// If it is, only get the users who do not already have permission			
			$exclude_members = array();
			$result = $this->db->query('SELECT memberid FROM ' . $this->get_table('permission') . ' WHERE machid=?', array($machid));
			$this->check_for_error($result);
			
			while($rs = $result->fetchRow())
				$exclude_members[] = $this->db->quote($rs['memberid']);
			
			$result = $this->db->query('SELECT memberid FROM ' . $this->get_table('login') . ' WHERE memberid NOT IN (' . join(',', $exclude_members) . ')');
			//$result = $this->db->query('SELECT memberid FROM login WHERE memberid NOT IN (SELECT memberid FROM permission WHERE machid=?)', array($machid));
			$this->check_for_error($result);
			while ($rs = $result->fetchRow())
				$users[]['memberid'] = $rs['memberid'];
		}
		else {									// Else get all users
			$users = $this->get_table_data('login', array('memberid'));
		}

		for ($i = 0; $i<count($users); $i++) {
			array_push($values, array($users[$i]['memberid'], $machid));
		}
		
		if (count($values) > 0 ) {
			$q = $this->db->prepare('INSERT INTO ' . $this->get_table('permission') . ' VALUES (?,?)');
			$result = $this->db->executeMultiple($q, $values);
			
			$this->check_for_error($result);
		}
	}
	
	/**
	* Reset a password for a user
	* @param string $memberid id of user to reset password for
	* @param string $new_password new password value for the user
	*/
	function reset_password($memberid, $new_password) {
		$result = $this->db->query('UPDATE ' . $this->get_table('login') . ' SET password=? WHERE memberid=?', array($this->make_password($new_password), $memberid));
		$this->check_for_error($result);
	}
}
?>