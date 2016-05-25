<?php
/**
* This file prints out a registration or edit profile form
* It will fill in fields if they are available (editing)
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 03-04-03
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Include Template class
*/
include_once('lib/Template.class.php');

// Auth included in Template.php
$auth = new Auth();
$t = new Template();

$edit = isset($_GET['edit']);

// Check login status
if ($edit && !$auth->is_logged_in()) {
	$auth->print_login_msg(true);
	$auth->clean();			// Clean out any lingering sessions
}
// Print HTML headers
$t->printHTMLHeader();

$t->set_title(($edit) ? 'Modify My Profile' : 'Register');

// Print the welcome banner if they are logged in
if ($edit)
	$t->printWelcome();

// Begin main table
$t->startMain();

// If we are editing and have not yet submitted an update
if ($edit && !isset($_POST['update'])) {
	$user = new User($_SESSION['sessionID']);
	$data = $user->get_user_data();
}
else
	$data = CmnFns::cleanPostVals();

if (isset($_POST['register'])) {	// New registration
	$auth->do_register_user($data);
}
else if (isset($_POST['update'])) {	// Update registration
	$auth->do_edit_user($data);
}
else {
	$auth->print_register_form($edit, $data);
}

// End main table
$t->endMain();

// Print HTML footer
$t->printHTMLFooter();
?>