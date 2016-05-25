<?php
/**
* This file is the control panel, or "home page" for logged in users.
* It provides a listing of all upcoming reservations
*  and functionality to modify or delete them. It also
*  provides links to all other parts of the system.
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-12-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Include Template class
*/
include_once('lib/Template.class.php');
/**
* Include control panel-specific output functions
*/
include_once('templates/cpanel.template.php');

if (!Auth::is_logged_in()) {
    Auth::print_login_msg();	// Check if user is logged in
}

$t = new Template('My Control Panel');
$db = new DBEngine();

$t->printHTMLHeader();
$t->printWelcome();
$t->startMain();

showAnnouncementTable();	// Print out My Announcements

printCpanelBr();

// Valid order values in reservation retreival
$order = array('date', 'name', 'startTime', 'endTime', 'created', 'modified');
$res = $db->get_user_reservations($_SESSION['sessionID'], CmnFns::get_value_order($order), CmnFns::get_vert_order());

showReservationTable($res, $db->get_err());	// Print out My Reservations

printCpanelBr();

if ($conf['app']['use_perms']) {
	showTrainingTable($db->get_user_permissions($_SESSION['sessionID']), $db->get_err());	// Print out My Training
	printCpanelBr();
}

showQuickLinks();			// Print out My Quick Links

$t->endMain();
$t->printHTMLFooter();
?>