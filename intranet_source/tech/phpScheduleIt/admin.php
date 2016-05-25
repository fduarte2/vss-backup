<?php
/**
* This file provides the interface for all administrative tasks
* No data manipulation is done in this file
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-03-04
* @package Admin
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Template class
*/
include_once('lib/Template.class.php');
/**
* Admin class
*/
include_once('lib/Admin.class.php');

$admin = new Admin(trim($_GET['tool']));

$t = new Template('System Administration');

// Print HTML header
$t->printHTMLHeader();

// Make sure this is the admin
if (!Auth::isAdmin()) {
    CmnFns::do_error_box('This section is only available to the administrator.<br />'
        . '<a href="ctrlpnl.php">Back to My Control Panel</a>');
}

// Print welcome message
$t->printWelcome();

// Start main table
$t->startMain();

if (!$admin->is_error())
	$admin->execute();
else
	CmnFns::do_error_box($admin->get_error_msg());

// End main table
$t->endMain();

// Print HTML footer
$t->printHTMLFooter();
?>