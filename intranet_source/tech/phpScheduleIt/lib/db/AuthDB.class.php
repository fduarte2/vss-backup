<?php
/**
* AuthDB class
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 03-21-04
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
* Provide all database access/manipulation functionality
* @see DBEngine
*/
class AuthDB extends DBEngine {
	
	/**
	* Returns whether a user exists or not
	* @param string $email users email address
	* @return user's id or false if user does not exist
	*/
	function userExists($uname) {
		$data = array (strtolower($uname));
		$result = $this->db->getRow('SELECT memberid FROM ' . $this->get_table('login') . ' WHERE email=?', $data);
		$this->check_for_error($result);
		
		return (!empty($result['memberid'])) ? $result['memberid'] : false;
	}
	
	/**
	* Returns whether the password associated with this username
	*  is correct or not
	* @param string $uname user name
	* @param string $pass password
	* @return whether password is correct or not
	*/
	function isPassword($uname, $pass) {
		$password = $this->make_password($pass);
		$data = array (strtolower($uname), $password);
		$result = $this->db->getRow('SELECT count(*) as num FROM ' . $this->get_table('login') . ' WHERE email=? AND password=?', $data);
		$this->check_for_error($result);
		
		return ($result['num'] > 0 );	
	}
	
	/**
	* Inserts a new user into the database
	* @param array $data user information to insert
	* @return new users id
	*/
	function insertMember($data) {
		$id = $this->get_new_id();
	
		// Put data into a properly formatted array for insertion
		$to_insert = array();
		array_push($to_insert, $id);
		array_push($to_insert, strtolower($data['email']));
		array_push($to_insert, $this->make_password($data['password']));
		array_push($to_insert, $data['fname']);
		array_push($to_insert, $data['lname']);
		array_push($to_insert, $data['phone']);
		array_push($to_insert, $data['institution']);
		array_push($to_insert, $data['position']);
		array_push($to_insert, 'y');
		array_push($to_insert, 'y');
		array_push($to_insert, 'y');
		array_push($to_insert, 'y');
		
		$q = $this->db->prepare('INSERT INTO ' . $this->get_table('login') . ' VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		$result = $this->db->execute($q, $to_insert);
		$this->check_for_error($result);
		
		return $id;
	}
	
	/**
	* Updates user data
	* @param string $userid id of user to update
	* @param array $data array of new data
	*/
	function update_user($userid, $data) {
		
		$to_insert = array();

		array_push($to_insert, strtolower($data['email']));
		array_push($to_insert, $data['fname']);
		array_push($to_insert, $data['lname']);
		array_push($to_insert, $data['phone']);
		array_push($to_insert, $data['institution']);
		array_push($to_insert, $data['position']);
				
		$sql = 'UPDATE ' . $this->get_table('login') 
			. ' SET email=?,'
			. ' fname=?,'
			. ' lname=?,'
			. ' phone=?,'
			. ' institution=?,'
			. ' position=?';
		
		if (isset($data['password']) && !empty($data['password'])) {	// If they are changing passwords
			$sql .= ', password=?';
			array_push($to_insert, $this->make_password($data['password']));
		}
		
		array_push($to_insert, $userid);
		
		$sql .= ' WHERE memberid=?';
		
		$q = $this->db->prepare($sql);
		$result = $this->db->execute($q, $to_insert);
		$this->check_for_error($result);		
	}
	
	/**
	* Checks to make sure the user has a valid ID stored in a cookie
	* @param string $id id to check
	* @return whether the id is valid
	*/
	function verifyID($id) {
		$result = $this->db->getRow('SELECT count(*) as num FROM ' . $this->get_table('login') . ' WHERE memberid=?', array($id));
		$this->check_for_error($result);
		
		return ($result['num'] > 0 );
	}
	
	/**
	* Gives full resource permissions to a user upon registration
	* @param string $id id of user to auto assign
	*/ 
	function auto_assign($id) {
		$values = array();
		$resources = $this->db->query('SELECT machid FROM ' . $this->get_table('resources') . ' WHERE autoAssign=1');
		$this->check_for_error($resources);
		while ($rs = $resources->fetchRow()) {
			array_push($values, array($id, $rs['machid']));
		}

		if (count($values) > 0 ) {
			$q = $this->db->prepare('INSERT INTO ' . $this->get_table('permission') . ' VALUES (?,?)');
			$result = $this->db->executeMultiple($q, $values);
			
			$this->check_for_error($result);
		}
		$resources->free();
	}
}
?>