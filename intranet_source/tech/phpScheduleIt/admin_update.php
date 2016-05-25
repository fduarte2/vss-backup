<?php
/**
* Provides interface for making all administrative database changes
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 05-04-04
* @package Admin
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Include Admin class
*/
include_once('lib/Admin.class.php');
/**
* Include PHPMailer
*/
include_once('lib/PHPMailer.class.php');
/**
* Include User class
*/
include_once('lib/User.class.php');

// Make sure this is the admin and is being called from admin.php
if ( (!Auth::isAdmin()) || (!strstr($_SERVER['HTTP_REFERER'],'admin.php')) ) {
    CmnFns::do_error_box('This section is only available to the administrator.<br />'
        . '<a href="ctrlpnl.php">Back to My Control Panel</a>');
	die;
}


$db = new AdminDB();

$tools = array ( 
				'deleteUsers' => 'del_users',
				
				'addResource'	=> 'add_resource',
				'editResource'	=> 'edit_resource',
				'delResource'	=> 'del_resource',
				'togResource'	=> 'tog_resource',
				
				'editPerms' =>	'edit_perms',
				
				'resetPass' => 'reset_password',
				
				'addSchedule'	=> 'add_schedule',
				'editSchedule'	=> 'edit_schedule',
				'delSchedule'	=> 'del_schedule',
				'dfltSchedule'	=> 'set_default_schedule'
				 );

$fn = isset($_POST['fn']) ? $_POST['fn'] : (isset($_GET['fn']) ? $_GET['fn'] : '');	// Set function

if (!isset($tools[$fn]) && !isset($tools[$fn])) {		// Validate tool
	CmnFns::do_error_box('Unknown administrative function.'
				 . '<a href="ctrlpnl.php">Back to My Control Panel</a>');
	die;
}
else {
	if (isset($tools[$fn]))
		eval($tools[$fn] . '();');
}

unset($fn, $tools);

/**
* Adds a schedule to the database
* @param none
*/
function add_schedule() {
	global $db;
	global $conf;
	
	$schedule = check_schedule_data(CmnFns::cleanPostVals());
	$id = $db->add_schedule($schedule);
	
	CmnFns::write_log('Schedule added. ' . $schedule['scheduleTitle'], $_SESSION['sessionID']);
	print_success();
}

/**
* Edits schedule data
* @param none
*/
function edit_schedule() {
	global $db;

	$schedule = check_schedule_data(CmnFns::cleanPostVals());
	$db->edit_schedule($schedule);
	
	CmnFns::write_log('Schedule editied. ' . $schedule['scheduleTitle'] . ' ' . $schedule['scheduleid'], $_SESSION['sessionID']);
	print_success();
}

/**
* Deletes a list of resources
* @param none
*/
function del_schedule() {
	global $db;
	
	$scheduleid = $_POST['scheduleid'];
	
	// Make sure machids are checked
	if (empty($scheduleid))
		print_fail('You did not select any schedules to delete.');
	
	$db->del_schedule($scheduleid);
	CmnFns::write_log('Schedules deleted. ' . join(', ', $scheduleid), $_SESSION['sessionID']);
	print_success();
}

function set_default_schedule() {
	global $db;
	
	$db->set_default_schedule($_POST['scheduleid']);
	CmnFns::write_log('Default schedule changed to ' . $_POST['scheduleid'], $_SESSION['sessionID']);
	print_success();
}

/**
* Deletes a list of users from the database
* @param none
*/
function del_users() {
	global $db;
	
	// Make sure memberids are checked
	if (empty($_POST['memberid']))
		print_fail('You did not select any members to delete.<br />');
	
	$db->del_users($_POST['memberid']);
	CmnFns::write_log('Users deleted. ' . join(', ', $_POST['memberid']), $_SESSION['sessionID']);
	print_success();
}


/**
* Adds a resource to the database
* @param none
*/
function add_resource() {
	global $db;
	global $conf;
	
	$resource = check_resource_data(CmnFns::cleanPostVals());
	$id = $db->add_resource($resource);
	
	if (isset($resource['autoAssign']))		// Automatically give all users permission to reserve this resource
		$db->auto_assign($id);
	
	CmnFns::write_log('Resource added. ' . $resource['name'], $_SESSION['sessionID']);
	print_success();
}

/**
* Edits resource data
* @param none
*/
function edit_resource() {
	global $db;
	
	$resource = check_resource_data(CmnFns::cleanPostVals());
	$db->edit_resource($resource);
	
	if (isset($resource['autoAssign']))		// Automatically give all users permission to reserve this resource 
		$db->auto_assign($resource['machid']);
		
	CmnFns::write_log('Resource editied. ' . $resource['name'] . ' ' . $resource['machid'], $_SESSION['sessionID']);
	print_success();
}

/**
* Deletes a list of resources
* @param none
*/
function del_resource() {
	global $db;
	
	// Make sure machids are checked
	if (empty($_POST['machid']))
		print_fail('You did not select any resources to delete.');
	
	$db->del_resource($_POST['machid']);
	CmnFns::write_log('Resources deleted. ' . join(', ', $_POST['machid']), $_SESSION['sessionID']);
	print_success();
}

/**
* Toggles a resource active/inactive
* @param none
*/
function tog_resource() {
	global $db;
	
	$db->tog_resource($_GET['machid'], $_GET['status']);
	CmnFns::write_log('Resource ' . $_GET['machid'] . ' toggled on/off.', $_SESSION['sessionID']);
	print_success();
}

/**
* Validates schedule data
* @param array $data array of data to validate
* @return validated data
*/
function check_schedule_data($data) {
	$rs = array();
	$msg = array();
	
	if (empty($data['scheduleTitle']))
		array_push($msg, 'Schedule title is required.');
	else
		$rs['scheduleTitle'] = $data['scheduleTitle'];
	
	if (intval($data['dayStart']) >= intval($data['dayEnd']))
		array_push($msg, 'Invalid start/end times');
	else {
		$rs['dayStart']	= $data['dayStart'];
		$rs['dayEnd']	= $data['dayEnd'];
	}
	
	$rs['weekDayStart']	= $data['weekDayStart'];
	$rs['timeSpan'] = $data['timeSpan'];		
	$rs['isHidden'] = $data['isHidden'];
	$rs['showSummary'] = $data['showSummary'];
	
	if (empty($data['viewDays']) || $data['viewDays'] <= 0)
		array_push($msg, 'View days is required');
	else
		$rs['viewDays'] = intval($data['viewDays']);
	
	if ($data['dayOffset'] == '' || $data['dayOffset'] < 0)
		array_push($msg, 'Day offset is required');
	else
		$rs['dayOffset'] = intval($data['dayOffset']);
	
	if (empty($data['adminEmail']))
		array_push($msg, 'Admin email is required');
	else
		$rs['adminEmail']	= $data['adminEmail'];

	if (isset($data['scheduleid']))
		$rs['scheduleid'] = $data['scheduleid'];
	
	if (!empty($msg))
		print_fail($msg, $data);
	
	return $rs;
}

/**
* Validates resource data
* @param array $data array of data to validate
* @return validated data
*/
function check_resource_data($data) {
	$rs = array();
	$msg = array();
	
	$minRes = intval($data['minH'] * 60 + $data['minM']);
	$maxRes = intval($data['maxH'] * 60 + $data['maxM']);
	$data['minRes']	= $minRes;
	$data['maxRes']	= $maxRes;
	
	if (empty($data['name']))
		array_push($msg, 'Resource name is required.');
	else
		$rs['name'] = $data['name'];
	
	if (empty($data['scheduleid']))
		array_push($msg, 'Valid schedule must be selected');
	else
		$rs['scheduleid'] = $data['scheduleid'];
	
	if (intval($minRes) > intval($maxRes)) {
		array_push($msg, 'Minimum reservation length must be less than or equal to maximum reservation length.');
	}
	else {
		$rs['minRes']	= $minRes;
		$rs['maxRes']	= $maxRes;
	}
	
	$rs['rphone']	= $data['rphone'];
	$rs['location'] = $data['location'];
	$rs['notes']	= $data['notes'];
	
	if (isset($data['autoAssign']))
		$rs['autoAssign'] = $data['autoAssign'];

	if (isset($data['machid']))
		$rs['machid'] = $data['machid'];
	
	if (!empty($msg))
		print_fail($msg, $data);
	
	return $rs;
}

/**
* Edit user permissions for what resources they can reserve
* @param none
*/ 
function edit_perms() {
	global $db;
	
	$db->clear_perms($_POST['memberid']);
	$db->set_perms($_POST['memberid'], isset($_POST['machid']) ? $_POST['machid'] : array());
	CmnFns::write_log('Permissions changed for user ' . $_POST['memberid'], $_SESSION['sessionID']);
	
	if (isset($_POST['notify_user']))
		send_perms_email($_POST['memberid']);
	
	print_success();
}

/**
* Sends a notification email to the user that thier permissions have been updated
* @param string $memberid id of member
* @param array $machids array of resource ids that the user now has permission on
*/
function send_perms_email($memberid) {
	global $conf;
	
	$adminEmail = $conf['app']['adminEmail'];
	$appTitle = $conf['app']['title'];
	
	$user = new User($memberid);
	$perms = $user->get_perms();
	
	$subject = "$appTitle Permissions Updated";
	$msg = $user->get_fname() . ",\r\n"
			. "Your $appTitle permissions have been updated.\r\n\r\n";
	$msg .= (empty($perms)) ? "You now do not have permission to use any resources.\r\n" : "You now have permission to use the following resources:\r\n";
	foreach ($perms as $val)
		$msg .= $val . "\r\n";	// Add each resource name
	
	$msg .= "\r\nPlease contact $adminEmail with any questions.";

	$mailer = new PHPMailer();
	$mailer->AddAddress($user->get_email(), $user->get_name());
	$mailer->From = $adminEmail;
	$mailer->FromName = $conf['app']['title'];
	$mailer->Subject = $subject;
	$mailer->Body = $msg;
	$mailer->Send();
}

/**
* Reset the password for a user
* @param none
*/
function reset_password() {
	global $db;
	global $conf;
	
	$data = CmnFns::cleanPostVals();
	
	$password = empty( $data['password'] ) ? $conf['app']['defaultPassword'] : stripslashes($data['password']);
	$db->reset_password($data['memberid'], $password);
	
	if (isset($data['notify_user']))
		send_pwdreset_email($data['memberid'], $password);
		
	CmnFns::write_log('Password reset by admin for user ' . $_POST['memberid'], $_SESSION['sessionID']);
	print_success();
}

/**
* Send a notification email that the password has been reset
* @param string $memberid id of member
* @param string $password new password for user
*/
function send_pwdreset_email($memberid, $password) {
	global $conf;
	
	$adminEmail = $conf['app']['adminEmail'];
	$appTitle = $conf['app']['title'];
	
	$user = new User($memberid);
	
	$subject = "$appTitle Password Reset";
	$msg = $user->get_fname() . ",\r\n"
			. "Your $appTitle password has been reset by the administrator.\r\n\r\n"
			. "Your temporary password is:\r\n\r\n $password\r\n\r\n"
			. "Please use this temporary password (copy and paste to be sure it is correct) to log into $appTitle at " . CmnFns::getScriptURL()
			. " and immediately change it using the 'Change My Profile Information/Password' link in the My Quick Links table.\r\n\r\n"
			. "Please contact $adminEmail with any questions.";
	
	$mailer = new PHPMailer();
	$mailer->AddAddress($user->get_email(), $user->get_name());
	$mailer->From = $adminEmail;
	$mailer->FromName = $conf['app']['title'];
	$mailer->Subject = $subject;
	$mailer->Body = $msg;
	$mailer->Send();
}

/**
* Prints a page with a message notifying the admin of a successful update
* @param none
*/
function print_success() {
	// Get the name/value of anything that was currently being edited
	// This will then be flitered out of the link back so that item will not show up in the edit box
	$return = (!empty($_POST['get'])) ? preg_replace('/&' . $_POST['get'] . '=[\d\w]*/', '', $_SERVER['HTTP_REFERER']) : $_SERVER['HTTP_REFERER'];
	header("Refresh: 2; URL=$return");		// Auto send back after 2 seconds
	$t = new Template('Successful update');
	$t->printHTMLHeader();
	$t->printWelcome();
	$t->startMain();
	CmnFns::do_message_box('Your request was processed successfully.<br />'
				. '<a href="' . $return. '">Go back to system administration</a><br />'
				. 'Or wait to be automatically redirected there.');
	$t->endMain();
	$t->printHTMLFooter();
	die;	
}


/**
* Prints a page notifiying the admin that the requirest failed.
* It will also assign the data passed in to a session variable
*  so it can be reinserted into the form that it came from
* @param string or array $msg message(s) to print to user
* @param array $data array of data to post back into the form
*/
function print_fail($msg, $data = null) {
	if (!is_array($msg))
		$msg = array ($msg);
		
	if (!empty($data))
		$_SESSION['post'] = $data;
	
	$t = new Template('Update failed!');
	$t->printHTMLHeader();
	$t->printWelcome();
	$t->startMain();
	CmnFns::do_error_box('There were problems processing your request.<br /><br />'
			. '- ' . join('<br />- ', $msg) . '<br />'
			. '<br />Please <a href="' . $_SERVER['HTTP_REFERER'] . '">go back</a> and correct any errors.');
	$t->endMain();
	$t->printHTMLFooter();
	die;
}
?>