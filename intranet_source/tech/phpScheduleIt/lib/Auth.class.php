<?php
/**
* Authorization and login functionality
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-18-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/..');
/**
* Include AuthDB class
*/
include_once('db/AuthDB.class.php');
/**
* Include User class
*/
include_once('User.class.php');
/**
* PHPMailer
*/
include_once('PHPMailer.class.php');
/**
* Include Auth template functions
*/
include_once(BASE_DIR . '/templates/auth.template.php');

/**
* This class provides all authoritiative and verification
*  functionality, including login/logout, registration,
*  and user verification
*/
class Auth {
	var $is_loggedin = false;
	var	$login_msg = '';
	var $is_attempt = false;
	var $db;

	/**
	* Create a reference to the database class
	*  and start the session
	* @param none
	*/
	function Auth() {
		$this->db = new AuthDB();
	}

	/**
	* Check if user is the administrator
	* This function checks to see if the currently
	*  logged in user is the administrator, granting
	*  them special permissions
	* @param none
	* @return boolean whether the user is the admin
	*/
	function isAdmin() {
		return isset($_SESSION['sessionAdmin']);
	}

	/**
	* Check user login
	* This function checks to see if the user has
	* a valid session set (if they are logged in)
	* @param none
	* @return boolean whether the user is logged in
	*/
	function is_logged_in() {
		return isset($_SESSION['sessionID']);
	}

	/**
	* Logs the user in
	* @param string $uname username
	* @param string $pass password
	* @param string $cookieVal y or n if we are using cookie
	* @param string $isCookie id value of user stored in the cookie
	* @param string $resume page to forward the user to after a login
	* @return any error message that occured during login
	*/
	function doLogin($uname, $pass, $cookieVal = null, $isCookie = false, $resume = '') {
		global $conf;
		$msg = '';
		
		if (empty($resume)) $resume = 'ctrlpnl.php';		// Go to control panel by default

		$uname = stripslashes($uname);
		$pass = stripslashes($pass);

		$adminEmail = $conf['app']['adminEmail'];

		if ($isCookie !== false) {		// Cookie is set
			$id = $isCookie;
			if ($this->db->verifyID($id))
				$ok_user = $ok_pass = true;
			else {
				$ok_user = $ok_pass = false;
				setcookie('ID', '', time()-3600, '/');	// Clear out all cookies
				$msg .= 'That cookie seems to be invalid';
			}
		}
		else {

			// If we cant find email, set message and flag
			if ( !$id = $this->db->userExists($uname) ) {
				$msg .= 'We could not find that email in our database.<br />';
				$ok_user = false;
			}
			else
				$ok_user = true;

			// If password is incorrect, set message and flag
			if ($ok_user && !$this->db->isPassword($uname, $pass)) {
				$msg .= 'That password did not match the one in our database.<br />';
				$ok_pass = false;
			}
			else
				$ok_pass = true;
		}

		// If the login failed, notify the user and quit the app
		if (!$ok_user || !$ok_pass) {
			$msg .= '<br />You can try:<br />Registering an email address.<br />'
				. 'Or:<br />Try logging in again.';
			return $msg;
		}
		else {
			$this->is_loggedin = true;
			$user = new User($id);	// Get user info

			// If the user wants to set a cookie, set it
			// for their ID and fname.  Expires in 30 days (2592000 seconds)
			if (!empty($cookieVal)) {
				//die ('Setting cookie');
				setcookie('ID', $user->get_id(), time() + 2592000, '/');
			}

			 // If it is the admin, set session variable
			if ($user->get_email() == $conf['app']['adminEmail']) {
				$_SESSION['sessionAdmin'] = $conf['app']['adminEmail'];
			}

			// Set other session variables
			$_SESSION['sessionID'] = $user->get_id();
			$_SESSION['sessionName'] = $user->get_fname();

			// Send them to the control panel
			header('Location: ' . urldecode($resume));
			exit;
		}
	}

	/**
	* Log the user out of the system
	* @param none
	*/
	function doLogout() {
		// Check for valid session
		if (!$this->is_logged_in()) {
			$this->print_login_msg();
			die;
		}
		else {
			// Destroy all session variables
			unset($_SESSION['sessionID']);
			unset($_SESSION['sessionName']);
			if (isset($_SESSION['sessionAdmin'])) unset($_SESSION['sessionAdmin']);
			session_destroy();

			// Clear out all cookies
			setcookie('ID', '', time()-3600, '/');
			// Refresh page
			header('Location: ' . $_SERVER['PHP_SELF']);
			die;
		}
	}

	/**
	* Register a new user
	* This function will allow a new user to register.
	* It checks to make sure the email does not already
	* exist and then stores all user data in the login table.
	* It will also set a cookie if the user wants
	* @param array $data array of user data
	*/
	function do_register_user($data) {
		global $conf;

		// Verify user data
		$msg = $this->check_all_values($data, false);
		if (!empty($msg)) {
			$this->print_register_form(false, $data, $msg);
			return;
		}

		$adminEmail = $conf['app']['adminEmail'];
		$techEmail  = empty($conf['app']['techEmail']) ? 'N/A' : $conf['app']['techEmail'];
		$url        = CmnFns::getScriptURL();

		// Register the new member
		$id = $this->db->insertMember($data);

		$this->db->auto_assign($id);		// Give permission on auto-assigned resources
		
		$mailer = new PHPMailer();
		$mailer->IsHTML(false);
		
		// Email user informing about successful registration
		$subject = $conf['ui']['welcome'];
		$msg = $data['fname'] . ', ' . $conf['ui']['welcome'] . "\n\r\n"
				. "You have successfully registered with the following information:\r\n"
				. 'Name: ' . $data['fname'] . ' ' . $data['lname'] . "\r\n"
				. 'Phone: ' . $data['phone'] . "\r\n"
				. 'Institution: ' . $data['institution'] . "\r\n"
				. 'Position: ' . $data['position'] . "\r\n\r\n"
				. "Please log into the scheduler at this location:\r\n"
				. $url . ".\r\n\r\n"
				. "You can find links to the online scheduler and to edit your profile at My Control Panel.\r\n\r\n"
				. "Please direct any resource or reservation based questions to " . $adminEmail;

		$mailer->AddAddress($data['email'], $data['fname'] . ' ' . $data['lname']);
		$mailer->From = $adminEmail;
		$mailer->FromName = $conf['app']['title'];
		$mailer->Subject = $subject;
		$mailer->Body = $msg;
		$mailer->Send();

		// Email the admin informing about new user
		if ($conf['app']['emailAdmin']) {
			$subject = 'A new user has been added';
			$msg = "Administrator,\r\n\r\n"
					. "A new user has registered with the following information:\r\n"
					. 'Email: ' . $data['email'] . "\r\n"
					. 'Name: ' . $data['fname'] . ' ' . $data['lname'] . "\r\n"
					. 'Phone: ' . $data['phone'] . "\r\n"
					. 'Institution: ' . $data['institution'] . "\r\n"
					. 'Position: ' . $data['position'] . "\r\n\r\n";
			
			$mailer->ClearAllRecipients();
			$mailer->AddAddress($adminEmail);
			$mailer->Subject = $subject;
			$mailer->Body = $msg;
			$mailer->Send();
		}
		// If the user wants to set a cookie, set it
		// for their ID and fname.  Expires in 30 days (2592000 seconds)
		if (isset($data['setCookie'])) {
			setcookie('ID', $id, time() + 2592000, '/');
		}

		// If it is the admin, set session variable
		if ($data['email'] == $adminEmail) {
			$_SESSION['sessionAdmin'] = $adminEmail;
		}

		// Set other session variables
		$_SESSION['sessionID'] = $id;
		$_SESSION['sessionName'] = $data['fname'];

		// Write log file
		CmnFns::write_log('New user registered. Data provided: fname- ' . $data['fname'] . ' lname- ' . $data['lname']
						. ' email- '. $data['email'] . ' phone- ' . $data['phone'] . ' institution- ' . $data['institution']
						. ' position- ' . $data['position'], $id);
		CmnFns::do_message_box('You have successfully registered!. <a href="ctrlpnl.php">Continue...</a>');
	}

	/**
	* Edits user data
	* @param array $data array of user data
	*/
	function do_edit_user($data) {
		global $conf;

		// Verify user data
		$msg = $this->check_all_values($data, true);
		if (!empty($msg)) {
			print_register_form(true, $data, $msg);
			return;
		}

		$this->db->update_user($_SESSION['sessionID'], $data);

		// If it is the admin, set session variable
		if ($data['email'] == $conf['app']['adminEmail']) {
			$_SESSION['sessionAdmin'] = $conf['app']['adminEmail'];
		}

		// Set other session variables
		$_SESSION['sessionName'] = $data['fname'];

		// Write log file
		CmnFns::write_log('User data modified. Data provided: fname- ' . $data['fname'] . ' lname- ' . $data['lname']
						. ' email- '. $data['email'] . ' phone- ' . $data['phone'] . ' institution- ' . $data['institution']
						. ' position- ' . $data['position'], $_SESSION['sessionID']);

		CmnFns::do_message_box('Your profile has been successfully updated!<br />'
				. 'Please return to <a href="ctrlpnl.php">My Control Panel</a>');
	}


	/**
	* Verify that the user entered all data properly
	* @param array $data array of data to check
	* @param boolean $is_edit whether this is an edit or not
	*/
	function check_all_values($data, $is_edit) {
		$msg = '';

		if (empty($data['email']) || !preg_match("/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/", $data['email']))
			$msg .= '- Valid email address is required.<br />';
		if (empty($data['fname']))
			$msg .= '- First name is required.<br />';
		if (empty($data['lname']))
			$msg .= '- Last name is required.<br />';
		if (empty($data['phone']))
			$msg .= '- Phone number is required.<br />';

		// Make sure email isnt in database (and is not current users email)
		if ($is_edit) {
			$user = new User($_SESSION['sessionID']);
			if ($this->db->userExists($data['email']) && ($data['email'] != $user->get_email()) ) {
				$msg .= '- That email is taken already.<br />'
					. 'Please try again with a different email address.<br />';
			}

			if (!empty($data['password'])) {
				if (strlen($data['password']) < 6)
					$msg .= '- Min 6 character password is required.<br />';
				if ($data['password'] != $data['password2'])
					$msg .= '- Passwords do not match.<br />';
			}

			unset($user);
		}
		else {
			if (empty($data['password']) || strlen($data['password']) < 6)
				$msg .= '- Min 6 character password is required.<br />';
			if ($data['password'] != $data['password2'])
				$msg .= '- Passwords do not match.<br />';
			if ($this->db->userExists($data['email'])) {
				$msg .= '- That email is taken already.<br />'
					. 'Please try again with a different email address.<br />';
			}
		}

		return $msg;
	}
	/**
	* Returns whether the user is attempting to log in
	* @param none
	* @return whether the user is attempting to log in
	*/
	function isAttempting() {
		return $this->is_attempt;
	}

	/**
	* Prints out template footer and kills app
	* @param none
	*/
	function kill() {
		die;
	}

	/**
	* Destroy any lingering sessions
	* @param none
	*/
	function clean() {
		// Destroy all session variables
		unset($_SESSION['sessionID']);
		unset($_SESSION['sessionName']);
		if (isset($_SESSION['sessionAdmin'])) unset($_SESSION['sessionAdmin']);
		session_destroy();
	}

	/**
	* Wrapper function to call template 'print_register_form' function
	* @param boolean $edit whether this is an edit or a new register
	* @param array $data values to auto fill
	*/
	function print_register_form($edit, $data, $msg = '') {
		print_register_form($edit, $data, $msg);		// Function in auth.template.php
	}


	/**
	* Wrapper function to call template 'printLoginForm' function
	* @param string $msg error messages to display for user
	* @param string $resume page to resume after a login
	*/
	function printLoginForm($msg = '', $resume = '') {
		printLoginForm($msg, $resume);
	}

	/**
	* Prints a message telling the user to log in
	* @param boolean $kill whether to end the program or not
	*/
	function print_login_msg($kill = true) {
		header('Location: ' . CmnFns::getScriptURL() . '/index.php?auth=no&resume=' . urlencode($_SERVER['PHP_SELF']));
		exit;
	}
}
?>