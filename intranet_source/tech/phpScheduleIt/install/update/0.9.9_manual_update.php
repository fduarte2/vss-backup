<?php
/**
* Update program for phpScheduleIt
*
* This will update from version 0.9.0 beta to 0.9.0 stable
*
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-04-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

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
	CmnFns::do_error_box('This will populate the required fields for phpScheduleIt 0.9.9. <br />There is no way to undo this action!', '', false); 
	echo '<h4 align="center"><a href="' . $_SERVER['PHP_SELF'] . '?go=y">Click to proceed</a></h4>';
}
else {
	updateVersion();
}
	$t->printHTMLFooter();
	
function updateVersion() {
	global $conf;

	$dbe = new DBEngine();
	echo '<h5 align="center">Update Log</h5>';
	
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