<?php
/**
* Show information about a given user
* This file lists all stored information
*   into a table for administrative viewing.
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 03-13-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* User class
*/
include_once('lib/User.class.php');
/**
* Template class
*/
include_once('lib/Template.class.php');
/**
* UserInfoDB class
*/
include_once('lib/db/UserInfoDB.class.php');
/**
* Templates for output
*/
include_once('templates/userinfo.template.php');

$user = new User($_GET['user']);

$t = new Template('User Info: ' . $user->get_name());

$t->printHTMLHeader();		// Print HTML header

// Make sure this is the admin
if (!Auth::isAdmin()) {
    CmnFns::do_error_box('This section is only available to the administrator.<br />'
        . '<a href="ctrlpnl.php">Back to My Control Panel</a>');
}

if (!$user->is_valid()) {	// Make sure member ID is valid
    CmnFns::do_error_box('Sorry, memberid: ' . $user->get_id() . 'is not available.');
}

$db = new UserInfoDB();

$prev = $db->get_prev_userid($user);	// Prev memberid
$next = $db->get_next_userid($user);	// Next memberid

$t->startMain();	// Start main table

printUI($user);		// Print user info

printLinks($prev, $next);	// Print links
 
$t->endMain();		// End main table

$t->printHTMLFooter();		// Print HTML footer
?>