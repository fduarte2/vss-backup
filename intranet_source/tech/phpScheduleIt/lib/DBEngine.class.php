<?php
/**
* DBEngine class
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-23-04
* @package DBEngine
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/..');
/**
* Pear::DB
*/
include_once('DB.php');
/**
* CmnFns class
*/
include_once('CmnFns.class.php');

/**
* Provide all database access/manipulation functionality
*/
class DBEngine {

	var $db;							// Reference to the database object
	var $dbs = array();					// List of database names to use.  This will be used if more than one database is created and different tables are associated with multiple databases	
	var $table_to_database = array();	// Array associating tables to databases
	var $prefix;						// Prefix to prepend to all primary keys
	
	var $err_msg = '';
	
	/**
	* DBEngine constructor to initialize object
	* @param none
	*/
	function DBEngine() {
		$this->prefix = $GLOBALS['conf']['db']['pk_prefix'];
		$this->dbs = array ($GLOBALS['conf']['db']['dbName']);
		
		$this->db_connect();
		$this->define_tables();
	}
	
	/**
	* Create a persistent connection to the database
	* @param none
	* @global $conf
	*/
	function db_connect() {
		global $conf;
	
		/***********************************************************
		/ This uses PEAR::DB
		/ See http://www.pear.php.net/manual/en/package.database.php#package.database.db
		/ for more information and syntax on PEAR::DB
		/**********************************************************/
	
		// Data Source Name: This is the universal connection string
		// See http://www.pear.php.net/manual/en/package.database.php#package.database.db
		// for more information on DSN
		$dsn = $conf['db']['dbType'] . '://' . $conf['db']['dbUser'] . ':' . $conf['db']['dbPass'] . '@' . $conf['db']['hostSpec'] . '/' . $this->dbs[0];

		// Make persistant connection to database
		$db = DB::connect($dsn, true);
	
		// If there is an error, print to browser, print to logfile and kill app
		if (DB::isError($db)) {
			die ('Error connecting to database: ' . $db->getMessage() );
		}
		
		// Set fetch mode to return associatve array
		$db->setFetchMode(DB_FETCHMODE_ASSOC);
	
		$this->db = $db;
	}
	
	/////////////////////////////////////////////////////
	// Common functions
	/////////////////////////////////////////////////////
	/**
	* Defines the $table_to_database array
	* This array will relate each table to a database name,
	*  making it very easy to change all table associations
	*  if additional databases are added
	* @param none
	*/
	function define_tables() {
		$this->table_to_database = array (
						'login' 		=> $this->dbs[0],
						'reservations'	=> $this->dbs[0],
						'resources'		=> $this->dbs[0],
						'permission'	=> $this->dbs[0],
						'schedules'		=> $this->dbs[0],
						'schedule_permission' => $this->dbs[0]
														);
						
	}
	
	/**
	* Returns the database and table name in form: database.table
	* @param string $table table to return
	* @return fully qualified table name in form: database.table
	*/
	function get_table($table) {
		return $table;
		//return $this->table_to_database[$table] . '.' . $table;
	}
	
	/**
	* Assigns a table to a database for SQL statements
	* @param string $table name of table to change
	* @param strin $database name of database that this table belongs to
	* @return success of assignment
	*/
	function set_table($table, $database) {
		if (!isset($this->table_to_database[$table]))
			return false;
		else
			$this->table_to_database[$table] = $database;
		return true;
	}
	
	/**
	* Generic database query function.
	* This will return specified fields from one table in a specified order
	* @param string $table name of table to return from
	* @param array $fields array of field values to return
	* @param string $order sql order string
	* @param int $limit limit of query
	* @param int $offset offset of limit
	* @return mixed all data found in query
	*/
	function get_table_data($table, $fields = array('*'), $orders = array(), $limit = NULL, $offset = NULL) {
		$return = array();

		$order = CmnFns::get_value_order($orders);		// Get main order value	
		$vert = CmnFns::get_vert_order();				// Get vertical order
		
		$query = 'SELECT ' . join(', ', $fields)
			. ' FROM ' . $this->get_table($table)
			. (!empty($order) ? " ORDER BY $order $vert" : '');		
		
		// Append any other sorting constraints	
		for ($i = 1; $i < count($orders); $i++)
			$query .= ', ' . $orders[$i];
		//die('lim' . $query);
		if (!is_null($limit) && !is_null($offset))		// Limit query
			$result = $this->db->limitQuery($query, $offset, $limit);
		else										// Standard query
			$result = $this->db->query($query);
		
		$this->check_for_error($result);
		
		if ($result->numRows() <= 0) {		// Check if any records exist
			$this->err_msg = 'There are no records in the ' . $table . ' table.';
			return false;
		}
		
		while ($rs = $result->fetchRow())
			$return[] = $this->cleanRow($rs);
		
		$result->free();
		
		return $return;
	}
	
	/**
	* Deletes a list of rows from the database
	* @param string $table table name to delete rows from
	* @param string $field field name that items are in
	* @param array $to_delete array of items to delete
	*/
	function deleteRecords($table, $field, $to_delete) {
		// Put into string, quoting each value
		$delete = join('","', $to_delete);
		$delete = '"'. $delete . '"';
		
		$result = $this->db->query('DELETE FROM ' . $this->get_table($table) . ' WHERE ' . $field . ' IN (' . $delete . ')');
		
		$this->check_for_error($result);		// Check for an error
		
		return true;
	}		

	
	/**
	* Return all reservations associated with a user
	* @param string $id user id
	* @return array of reservation data
	*/
	function get_user_reservations($id, $order, $vert) {
		$return = array();
		
		$query = 'SELECT * FROM ' 
					. $this->get_table('reservations') . ' as res,'
					. $this->get_table('resources') . ' as rs'
					. ' WHERE res.memberid=?'
					. ' AND rs.machid=res.machid'
					. ' AND res.date>=?'
					. ' AND res.is_blackout <> 1'
					. " ORDER BY $order $vert, res.date, rs.name, res.startTime";
	
		$values = array($id, mktime(0,0,0));

		// Prepare query
		$q = $this->db->prepare($query);
		// Execute query
		$result = $this->db->execute($q, $values);
		// Check if error
		$this->check_for_error($result);
		
		if ($result->numRows() <= 0) {
			$this->err_msg = 'You do not have any reservations scheduled.';
			return false;
		}
		
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		
		$result->free();
		
		return $return;
	}
	

	/**
	* Gets all the resources that the user has permission to reserve
	* @param string $userid user id
	* @return array or resource data
	*/
	function get_user_permissions($userid) {
		$return = array();
		
		$sql = 'SELECT rs.* FROM ' 
					. $this->get_table('permission') . ' as pm,'
					. $this->get_table('resources') . ' as rs'
					. ' WHERE pm.memberid=?'
					. ' AND pm.machid=rs.machid'
					. ' ORDER BY rs.name';
					
		// Execute query
		$result = $this->db->query($sql, array($userid));
		// Check if error
		$this->check_for_error($result);
		
		if ($result->numRows() <= 0) {
			$this->err_msg = 'You do not have permission to use any resources.';
			return false;
		}

		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		
		$result->free();
		
		return $return;
	}
	
	/**
	* Get associative array with machID, resource name, and status
	* This function loops through all resources
	*  and constructs an associative array with the
	*  resource's machID, name and status as
	*  $array[x] => ('machid' => 'this_resource_id', 'name' => 'Resource Name', 'status' => 'a')
	* @param none
	* @return array of machID, resource name, status
	*/
	function get_mach_ids($scheduleid = null) {
		$return = array();
		$values = array();
		
		$sql = 'SELECT machid, name, status FROM ' . $this->get_table('resources');
		if ($scheduleid != null) {
			$sql .= ' WHERE scheduleid = ?';
			$values = array($scheduleid);
		}
		$sql .= ' ORDER BY name';
		
		$result = $this->db->query($sql, $values);
		
		$this->check_for_error($result);
		
		if ($result->numRows() <= 0) {
			$this->err_msg = 'No resources in the database.';
			return false;
		}		

		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		
		$result->free();
		
		return $return;
	}
	
	/**
	* Gets the default scheduleid 
	* @param none
	* @return string scheduleid of default schedule
	*/
	function get_default_id() {
		$result = $this->db->getOne('SELECT scheduleid FROM ' . $this->get_table('schedules') . ' WHERE isDefault = 1 AND isHidden = 0');
		$this->check_for_error($result);

		if (empty($result)) {	// If default is hidden
			$result = $this->db->getOne('SELECT scheduleid FROM ' . $this->get_table('schedules') . ' WHERE isHidden = 0');
			$this->check_for_error($result);
		}

		return $result;
	}
	
	/**
	* Checks to see if the scheduleid is valid
	* @param none
	* @return whether it is valid or not
	*/
	function check_scheduleid($scheduleid) {
		$result = $this->db->getOne('SELECT COUNT(scheduleid) AS num FROM ' . $this->get_table('schedules') . ' WHERE scheduleid = ? AND isHidden <> 1', array($scheduleid));
		$this->check_for_error($result);

		return (intval($result) > 0);
	}
	
		
	/**
	* Gets all data for a given schedule
	* @param string $scheduleid id of schedule
	* @param array of schedule data
	*/
	function get_schedule_data($scheduleid) {
		$result = $this->db->getRow('SELECT * FROM ' . $this->get_table('schedules') . ' WHERE scheduleid = ?', array($scheduleid));
		$this->check_for_error($result);
		
		return $result;
	}
	
	/**
	* Gets the list of available schedules
	* @param none
	*/
	function get_schedule_list() {
		$return = array();
		
		$result = $this->db->query('SELECT scheduleid, scheduleTitle FROM ' . $this->get_table('schedules') . ' WHERE isHidden = 0');
		$this->check_for_error($result);
		
		while ($rs = $result->fetchRow())
			$return[] = $this->cleanRow($rs);
		
		return $return;
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	* Checks to see if there was a database error and die if there was
	* @param object $result result object of query
	*/
	function check_for_error($result) {
		if (DB::isError($result))
			CmnFns::do_error_box('There was an error executing your query: <br />'
				. $result->getMessage()
				. '<br /><a href="javascript: history.back();">Go Back</a>');
		return false;
	}
	
	/**
	* Generates a new random id for primary keys
	* @param string $prefix string to prefix to id
	* @return random id string
	*/
	function get_new_id($prefix = '') {
		// Use the passed in prefix, if it exists
		if (!empty($prefix))
			$this->prefix = $prefix;
		
		// Only use first 3 letters
		$this->prefix = strlen($this->prefix) > 3 ? substr($this->prefix, 0, 3) : $this->prefix;
		
		return uniqid($this->prefix);
	}
	
	/**
	* Enodes a string into an encrypted password string
	* @param string $pass password to encrypt
	* @return encrypted password
	*/
	function make_password($pass) {
		return md5($pass);
	}
	
	/**
	* Strips out slashes for all data in the return row
	* - THIS MUST ONLY BE ONE ROW OF DATA -
	* @param array $data array of data to clean up
	* @return array with same key => value pairs (except slashes)
	*/
	function cleanRow($data) {
		$return = array();
			
		foreach ($data as $key => $val)
			$return[$key] = stripslashes($val);
		return $return;
	}
	
	/**
	* Makes an array of ids in to a comma seperated string of values
	* @param array $data array of data to convert
	* @return string version of the array
	*/
	function make_del_list($data) {
		$c = join('","', $data);
		return '"' . $c . '"';
	}
	
	/**
	* Returns the last database error message
	* @param none
	* @return last error message generated
	*/
	function get_err() {
		return $this->err_msg;
	}

}
?>