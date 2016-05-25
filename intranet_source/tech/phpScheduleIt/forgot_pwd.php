<?php
/**
* Reset user password
* This file will allow a user to reset 
*  their password to a randomly generated password.
* This new password will be set in the database
* and it will be emailed to the user.
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-23-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Template class
*/
include_once('lib/Template.class.php');
/**
* PHPMailer
*/
include_once('lib/PHPMailer.class.php');

$t = new Template('Forgot Password');
// Print HTML header
$t->printHTMLHeader();

// Start main table
$t->startMain();

// Set status to false so we print the form by default
$status = false;

// Determine if we are changing the password
if ( (isset($_POST['email'])) && strstr($_SERVER['HTTP_REFERER'],$_SERVER['PHP_SELF']))
    $status = changePassword();

// Print form or success message
if ($status)
    printSuccess();
else
    printPasswordForm();

// End main table
$t->endMain();

// Print HTML footer
$t->printHTMLFooter();


/**
* Print password form
* This function prints out a form allowing
*  a user to enter their email to change
*  their forgotten password
* @param none
*/
function printPasswordForm() {
?>
<h5 align="center">This will change your password to a new, randomly generated one.</h5>
<h5 align="center">After entering your email address and clicking "Change Password",
your new password will be set in the system and emailed to you.</h5>
<br />
<div align="center" style="border: solid 1px #CCCCCC">
<form name="new_pwd" method="post" action="<?=$_SERVER['PHP_SELF']?>">
  Email: <input type="text" class="textbox" name="email" />
  <br />
  <input type="submit" class="button" name="changePassword" value="Change Password" />
  <input type="button" class="button" name="cancel" value="Cancel" onclick="javascript: document.location='index.php';" />
</form>
</div>
<?
}


/**
* Seed the random number generator
* @param none
* @return int seed
*/
function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}


/**
* Change user password
* This function creates a new random 8 character password,
*  sets it in the database and emails it to the user
* @return boolean true or false on success of function
* @see make_seed()
*/
function changePassword() {
    global $conf;
    $techEmail = $conf['app']['techEmail'];
    
    // Connect to database
    $db = new DBEngine();
    
    // Check if user exists
    $email = stripslashes(trim($_POST['email']));
	$result = $db->db->getRow('SELECT * FROM ' . $db->get_table('login') . ' WHERE email="' . $email . '"');

    // Check if error
    $db->check_for_error($result);
 
    if (count($result) <= 0) {
        CmnFns::do_error_box( 'Sorry, we could not find that user in the database.', '', false);
        return false;
    }
    
    // Generate new 8 character password by choosing random 
    // ASCII characters between 48 and 122
    // (valid password characters)
    $pwd = '';
	$num = 0;
    
    for ($i = 0; $i < 8; $i++) {
        // Seed random for older versions of PHP
        mt_srand(make_seed());
        if ($i % 2 == 0)
			$num = mt_rand(97, 122);	// Lowercase letters
		else if ($i %3 == 0)
			$num = mt_rand(48, 58);		// Numbers and colon
		else
			$num = mt_rand(63, 90);		// Uppercase letters and '@ ?'
        // Put password together
        $pwd .= chr($num);
    }
    
    // Set password in database
    $query = 'UPDATE ' . $db->get_table('login') . ' SET password="' . $db->make_password($pwd) . '" WHERE memberid="' . $result['memberid'] . '"';

	$change = $db->db->query($query);
	
	$db->check_for_error($change);
 
    // Send email to user
    $sub = 'Your New Scheduler Password';
    
    $msg = $result['fname'] . ",\n"
            . "Your new password for your phpScheduleIt account on {$conf['app']['title']} is:\n\n"
            . "$pwd\n\n"
            . 'Please Log In at' . CmnFns::getScriptURL()
            . 'with this new password '
            . '(copy and paste it to ensure it is correct) '
            . 'and promptly change your password by clicking the '
            . 'Change My Profile Information/Password '
            . "link in My Control Panel.\n\n"
            . "Please direct any questions to $adminEmail.";

	// Send email    
    $mailer = new PHPMailer();
	$mailer->AddAddress($result['email'], $result['fname']);
	$mailer->FromName = $conf['app']['title'];
	$mailer->From = $adminEmail;
	$mailer->Subject = $sub;
	$mailer->Body = $msg;
	$mailer->Send();
    
    return true;   
}


/**
* Print success message after changed password
* This function simply prints out a message informing
*  the user that thier password was changed and how to
*  log in now
* @param none
*/
function printSuccess() {
	CmnFns::do_message_box('Success!<br />
    			Your new passsword has been emailed to you.<br />
    			Please check your mailbox for your new password, then <a href="index.php">Log In</a>
    			with this new password and promptly change it by clicking the &quot;Change My Profile Information/Password&quot;
    			link in My Control Panel.', 'width: 75%;');
}
?>