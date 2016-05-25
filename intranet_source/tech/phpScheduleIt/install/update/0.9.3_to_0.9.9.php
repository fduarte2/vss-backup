<?php
/**
* Update program for phpScheduleIt
*
* This will update from version 0.9.0 beta to 0.9.0 stable
*
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-11-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

/**
* Please set this value to a user who has permission to alter tables
*/
$root_user = 'root';
/**
* Please set this value to the password of the user who has permission to alter tables
*/
$root_password = 'password';

/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/../..');
/**
* Template class
*/
include_once(BASE_DIR . '/lib/Template.class.php');


$t = new Template('phpScheduleIt Update: 0.9.3 to 0.9.9', 2);
// Print HTML headers
$t->printHTMLHeader();
if (!isset($_GET['go'])) {
	CmnFns::do_error_box('This will update your version of phpScheduleIt from 0.9.3 to 0.9.9. <br />There is no way to undo this action!', '', false); 
	echo '<h4 align="center"><a href="' . $_SERVER['PHP_SELF'] . '?go=y">Click to proceed</a></h4>';
}
else {
	if (check_version())
		updateVersion($root_user, $root_password);
	else
		echo '<h4 align="center">This version has already been upgraded to 0.9.9.<br />Please delete this file.</h4>';
	
}
$t->printHTMLFooter();

/**
* Check if this version has been upgraded or not
* @param none
* @return true if it can be updated, false if it cannot
*/
function check_version() {
	global $conf;
	$db =  new DBEngine();
	
	$result = $db->db->query('select * from ' . $db->get_table('reservations'));
	$num = $result->numCols();
	return ($num < 11);
}

/**
* Make the database updates
* @param string $root_user
* @param string $root_password
*/
function updateVersion($root_user, $root_password) {
	global $conf;
	
    $dsn = $conf['db']['dbType'] . '://' 
			. $root_user 
			. ':' . $root_password
			. '@' . $conf['db']['hostSpec'] . '/' . $conf['db']['dbName'];

    // Make persistant connection to database
    $db = DB::connect($dsn);

    // If there is an error, print to browser, print to logfile and kill app
    if (DB::isError($db)) {
        die ('Error connecting to database: ' . $db->getMessage() );
    }
	
	$dbe = new DBEngine();
	echo '<h5 align="center">Update Log</h5>';
	$query = array (
					// Add summary to reservations
					array ('ALTER TABLE ' . $dbe->get_table('reservations') . ' ADD COLUMN is_blackout integer(1) NOT NULL DEFAULT 0',
							'Column is_blackout added to the reservations table.'),
					array ('CREATE INDEX res_isblackout ON ' . $dbe->get_table('reservations') . ' (is_blackout)',
							'Index created on is_blackout column.'),
					array ('ALTER TABLE ' . $dbe->get_table('reservations') . ' ADD COLUMN summary text',
							'Column summary added to reservations table.'),
					array ('ALTER TABLE ' . $dbe->get_table('resources') . ' ADD COLUMN autoAssign integer(1)',
							'Column autoAssign added to the resources table.'),
					array ('CREATE INDEX rs_autoAssign ON ' . $dbe->get_table('resources') . ' (autoAssign)',
							'Index created on autoAssign column.'),
					array ('ALTER TABLE ' . $dbe->get_table('resources') . ' CHANGE COLUMN status status char(1) not null default \'a\'',
							'Change resources status column to char(1) from enum.'),
					array ('ALTER TABLE ' . $dbe->get_table('login') . ' CHANGE COLUMN e_add e_add char(1) not null default \'y\'',
							'Change login e_add column to char(1) from enum.'),
					array ('ALTER TABLE ' . $dbe->get_table('login') . ' CHANGE COLUMN e_mod e_mod char(1) not null default \'y\'',
							'Change login e_mod column to char(1) from enum.'),
					array ('ALTER TABLE ' . $dbe->get_table('login') . ' CHANGE COLUMN e_del e_del char(1) not null default \'y\'',
							'Change login e_del column to char(1) from enum.'),
					array ('ALTER TABLE ' . $dbe->get_table('login') . ' CHANGE COLUMN e_html e_html char(1) not null default \'y\'',
							'Change login e_html column to char(1) from enum.'),
					array ('ALTER TABLE ' . $dbe->get_table('reservations') . ' ADD COLUMN scheduleid char(16) not null AFTER memberid',
							'Column scheduleid added to reservations table.'),
					array ('CREATE INDEX res_scheduleid ON ' . $dbe->get_table('reservations') . ' (scheduleid)',
							'Index created on scheduleid column'),
					array ('ALTER TABLE ' . $dbe->get_table('resources') . ' ADD COLUMN scheduleid char(16) not null AFTER machid',
							'Column scheduleid added to resources table.'),
					array ('CREATE INDEX rs_scheduleid ON ' . $dbe->get_table('resources') . ' (scheduleid)',
							'Index created on scheduleid column'),
					array ('CREATE TABLE schedules (
							scheduleid char(16) NOT NULL primary key,
							scheduleTitle char(75),
							dayStart int NOT NULL,
							dayEnd int NOT NULL,
							timeSpan int NOT NULL,
							timeFormat int NOT NULL,
							weekDayStart int NOT NULL,
							viewDays int NOT NULL,
							usePermissions int,
							isHidden int,
							showSummary int,
							adminEmail char(75),
							isDefault int,
							dayOffset int
							)', 'Creating table schedules'),
					array ('CREATE INDEX sh_scheduleid ON schedules (scheduleid)', 'Creating index'),
					array ('CREATE INDEX sh_hidden ON schedules (isHidden)', 'Creating index'),
					array ('CREATE INDEX sh_perms ON schedules (usePermissions)', 'Creating index'),
					array ('create table schedule_permission (
							scheduleid char(16) not null,
							memberid char(16) not null,
							primary key(scheduleid, memberid)
							)', 'Creating table schedule_permission'),
					array ('create index sp_scheduleid on schedule_permission (scheduleid)', 'Creating index'),
					array ('create index sp_memberid on schedule_permission (memberid)', 'Creating index')
					);
	// Make updates
	
	for ($i = 0; $i < count($query); $i++) {
		$result = $db->query($query[$i][0]);
		if (!$dbe->check_for_error($result))
			echo "<p>{$query[$i][1]}</p>";
	}
	
	echo '<h3>Creating default schedule...</h3>';
	$scheduleid = $dbe->get_new_id();
	$result = $dbe->db->query('INSERT INTO schedules VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($scheduleid,'default',480,1200,30,12,0,7,0,0,1,$conf['app']['adminEmail'],1,0));
	$dbe->check_for_error($result);
	echo '<h3>Success</h3>';
	
	echo '<h3>Assigning all current data to default schedule...</h3>';
	$result = $dbe->db->query('UPDATE reservations SET scheduleid = ?', array($scheduleid));
	$dbe->check_for_error($result);
	$result = $dbe->db->query('UPDATE resources SET scheduleid = ?', array($scheduleid));
	$dbe->check_for_error($result);
	echo '<h3>Success</h3>';	
	
	echo '<h3>Successfully updated to phpScheduleIt 0.9.9!</h3>';
	
	?>
	Congratulations! You have finished upgrading phpScheduleIt and are ready to begin
	using it.<br /><br />
	Please be sure to COMPLETELY REMOVE THE 'install' DIRECTORY.  This is critical, for it contains
	database passwords and other sensitive information.  Failing to do so leaves the door wide open
	for anyone to break into your database!
	<br /><br />
	Thank you for using phpScheduleIt!
	<?
}
?>