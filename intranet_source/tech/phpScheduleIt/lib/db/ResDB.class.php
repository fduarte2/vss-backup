<?php
/**
* ResDB class
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-07-04
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

/**
* Provide all access to database to manage reservations
*/
class ResDB extends DBEngine {

	/**
	* Returns all data about a specific resource
	* @param string $machid id of resource to look up
	* @return array of all resource data
	*/
	function get_resource_data($machid) {
		$return = array();

		$result = $this->db->getRow('SELECT * FROM ' . $this->get_table('resources') . ' WHERE machid=?', array($machid));
		$this->check_for_error($result);

		if (count($result) <= 0)
			$return = 'That resource could not be found.';
		else
			$return = $this->cleanRow($result);

		return $return;
	}

	/**
	* Return all data about a given reservation
	* @param string $resid reservation id
	* @return array of all reservation data
	*/
	function get_reservation($resid) {
		$return = array();

		$result = $this->db->getRow('SELECT * FROM ' . $this->get_table('reservations') . ' WHERE resid=?', array($resid));
		$this->check_for_error($result);

		if (count($result) <= 0) {
			$this->err_msg = 'That reservation could not be found.';
			return false;
		}

		return $this->cleanRow($result);
	}

	/**
	* Checks to see if a given mach/date/start/end is already booked
	* @param Object $res reservation we are checking
	* @return whether time is taken or not
	*/
	function check_res(&$res) {
		$values = array (
					$res->get_machid(),
					$res->get_date(),
					$res->get_start(), $res->get_start(),
                    $res->get_end(), $res->get_end(),
                    $res->get_start(), $res->get_end(),
					$res->get_scheduleid()
				);

		$query = 'SELECT COUNT(resid) AS num FROM ' . $this->get_table('reservations')
				. ' WHERE machid=?'
				. ' AND date=?'
				. ' AND ( '
						. '( '
							. '(? >= startTime AND ? < endTime)'
							. ' OR '
							. '(? > startTime AND ? <= endTime)'
						. ' )'
						. ' OR '
						. '(? <= startTime AND ? >= endTime)'
				  .   ' )'
				. ' AND scheduleid = ? ';

		$id = $res->get_id();
		if ( !empty($id) ) {		// This is only if we need to check for a modification
			$query .= ' AND resid <> ?';
			array_push($values, $id);
		}
		$result = $this->db->getRow($query, $values);
		$this->check_for_error($result);
		return ($result['num'] > 0);	// Return if there are already reservations
	}

	/**
	* Add a new reservation to the database
	* @param Object $res reservation that we are placing
	* @param boolean $is_parent if this is the parent reservation of a group of recurring reservations
	*/
	function add_res(&$res, $is_parent) {
		$id = $this->get_new_id();
		$values = array (
					$id,
					$res->get_machid(),
					$res->get_memberid(),
					$res->get_scheduleid(),
					$res->get_date(),
					$res->get_start(),
					$res->get_end(),
					mktime(),
					null,
					($is_parent ? $id : $res->get_parentid()),
					intval($res->is_blackout),
					$res->get_summary()
				);
		
		$query = 'INSERT INTO ' . $this->get_table('reservations') . ' VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?)';
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $values);
		$this->check_for_error($result);

		unset($values, $query);
		return $id;
	}


	/**
	* Modify current reservation time
	* If this reservation is part of a recurring group, all reservations in the
	*  group will be modified that havent already passed
	* @param Object $res reservation that we are modifying
	* @param int $date todays timestamp
	*/
	function mod_res(&$res) {
		$values = array (
					$res->get_start(),
					$res->get_end(),
					mktime(),
					$res->get_summary(),
					$res->get_id()
				);

		$query = 'UPDATE ' . $this->get_table('reservations')
                . ' SET startTime=?,'
                . ' endTime=?,'
                . ' modified=?,'
				. ' summary=?'
                . ' WHERE resid=?';

		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $values);
		$this->check_for_error($result);
		
		unset($values, $query);
	}

	/**
	* Deletes a reservation from the database
	* If this reservation is part of a recurring group, all reservations
	*  in the group will be deleted that havent already passed
	* @param string $id reservation id
	* @param string $parentid id of parent reservation
	* @param boolean $del_recur whether to delete recurring reservations or not
	* @param int $date timestamp of current date
	*/
	function del_res($id, $parentid, $del_recur, $date) {
		$values = array($id);
		$sql = 'DELETE FROM ' . $this->get_table('reservations') . ' WHERE resid=?';
		if ($del_recur) {			// Delete all recurring reservations
			$sql .= ' OR parentid = ? OR resid = ? AND date >= ?';
			array_push($values, $parentid, $parentid, $date);
		}

		$result = $this->db->query($sql, $values);
		$this->check_for_error($result);
	}

	/**
	* Return all data needed in the emails
	* @param string $id reservation id to look up
	* @return array of data to be used in an email
	*/
	function get_email_info($id) {
		$query = 'SELECT r.*, rs.name, rs.rphone, rs.location'
            . ' FROM '
			. $this->get_table('resources') . ' as rs, '
			. $this->get_table('reservations') . ' as r'
			. ' WHERE r.resid=?'
			. ' AND rs.machid=r.machid';
		$result = $this->db->getRow($query, array($id));

		$this->check_for_error($result);
		return $this->cleanRow($result);
	}

	/**
	* Get an array of all reservation ids and dates for a recurring group
	*  of reservations, including the parent
	* @param string $parentid id of parent reservation for recurring group
	* @param int $date timestamp of current date
	* @return array of all reservation ids and dates
	*/
	function get_recur_ids($parentid, $date) {
		$return = array();

		$sql = 'SELECT resid, date FROM '
				. $this->get_table('reservations')
				. ' WHERE (parentid = ?'
				. ' OR resid = ?)'
				. ' AND date >= ?'
				. ' ORDER BY date ASC';
		$result = $this->db->query($sql, array($parentid, $parentid, $date));

		$this->check_for_error($result);

		if ($result->numRows() <= 0) {
			$this->err_msg = 'This reservation is not recurring.';
			return false;
		}

		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}

		$result->free();

		return $return;
	}
}
?>