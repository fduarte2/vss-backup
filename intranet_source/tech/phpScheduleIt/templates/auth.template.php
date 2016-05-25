<?php
/**
* This file provides output functions for all auth pages
* No data manipulation is done in this file
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 03-13-04
* @package Templates
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

$link = CmnFns::getNewLink();	// Get Link object

/**
* Prints out a form for users can register
*  filling in any values
* @param boolean $edit whether this is an edit or a new register
* @param array $data values to auto fill
* @param string $msg error message to print to user
*/
function print_register_form($edit, $data = array(), $msg = '') {
	global $conf;
			
	$positions    = $conf['ui']['positions'];		// Postions that are availble in the pull down menu
	$institutions = $conf['ui']['institutions'];	// Institutions that are available in the pull down menu 
	
	// Print header
	echo '<h3 align="center">' . (($edit) ? 'Please edit your profile' : 'Please register') . '</h3>' . "\n";
	
	if (!empty($msg))
		CmnFns::do_error_box($msg, '', false);
	
?>
<form name="register" method="post" action="<?= $_SERVER['PHP_SELF'] . '?' . ($edit ? 'edit=' . $edit : '')?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td bgcolor="#333333">
	<table width="100%" border="0" cellspacing="1" cellpadding="2">
	  <tr bgcolor="#FFFFFF">
		<td width="250">
		  <p align="right">* Email address (this will be your login):</p>
		</td>
		<td>
		  <input type="text" name="email" class="textbox" value="<?=isset($data['email']) ? $data['email'] : ''?>" maxlength="75" />
		</td>
	  </tr>
	  <tr bgcolor="#FFFFFF">
		<td>
		  <p align="right">* First Name:</p>
		</td>
		<td>
		  <input type="text" name="fname" class="textbox" value="<?=isset($data['fname']) ? $data['fname'] : ''?>" maxlength="50" />
		</td>
	  </tr>
	  <tr bgcolor="#FFFFFF">
		<td>
		  <p align="right">* Last Name:</p>
		</td>
		<td>
		  <input type="text" name="lname" class="textbox" value="<?=isset($data['lname']) ? $data['lname'] : ''?>" maxlength="50" />
		</td>
	  </tr>
	  <tr bgcolor="#FFFFFF">
		<td>
		  <p align="right">* Phone #:</p>
		</td>
		<td>
		  <input type="text" name="phone" class="textbox" value="<?=isset($data['phone']) ? $data['phone'] : ''?>" size="15" />
		</td>
	  </tr>
	  <tr bgcolor="#FFFFFF">
		<td>
		  <p align="right">Institution:</p>
		</td>
		<td>
		  <?
		  if (empty($institutions[0])) {
			echo '<input type="text" name="institution" class="textbox" value="' . (isset($data['institution']) ? $data['institution'] : '') . '" maxlength="255" />' . "\n";
		  }
		  else {
		  ?>
		  <select name="institution" class="textbox">
		  <?			  
			  // Print out position options
			  for ($i = 0; $i < count($institutions); $i++) {
				echo '<option value="' . $institutions[$i] . '"'
					. ( (isset($data['institution']) && ($data['institution'] == $institutions[$i])) ? ' selected="selected"' : '' )
					. '>' . $institutions[$i] . '</option>' . "\n";
			  }
		  ?>
		  </select>
		  <?
		  }
		  ?>
		</td>
	  </tr>
	  <tr bgcolor="#FFFFFF">
		<td>
		  <p align="right">Position:</p>
		</td>
		<td>
		  <?
		  if (empty($positions[0])) {
			echo '<input type="text" name="position" class="textbox" value="' . (isset($data['position']) ? $data['position'] : '') . '" maxlength="100" />' . "\n";
		  }
		  else {
		  ?>
		  <select name="position" class="textbox">
		  <?			  
			  // Print out position options
			  for ($i = 0; $i < count($positions); $i++) {
				echo '<option value="' . $positions[$i] . '"'
					. ( (isset($data['position']) && ($data['position'] == $positions[$i])) ? ' selected="selected"' : '' )
					. '>' . $positions[$i] . '</option>' . "\n";
			  }
		  ?>
		  </select>
		  <?
		  }
		  ?>
		</td>
	  </tr>
	  <tr bgcolor="#FFFFFF">
		<td>
		  <p align="right">* Password: (6 char min)</p>
		</td>
		<td>
		  <input type="password" name="password" class="textbox" />
		</td>
	  </tr>
	  <tr bgcolor="#FFFFFF">
		<td>
		  <p align="right">* Re-Enter Password:</p>
		</td>
		<td>
		  <input type="password" name="password2" class="textbox" />
		</td>
	  </tr>
	  <? if (!$edit) { ?>
	  <tr bgcolor="#FFFFFF">
		<td>
		  <p align="right">Keep me logged in: (requires cookies)</p>
		</td>
		<td>
		  <input type="checkbox" name="setCookie" value="true" />
		</td>
	  </tr>
	  <? } ?>
	</table>
  </td>
</tr>
</table>
<br />
<? if ($edit) { ?>
<input type="submit" name="update" value="Edit Profile" class="button" />
<input type="button" name="cancel" value="Cancel" class="button" onclick="javascript: document.location='ctrlpnl.php';" />
<? } else {?>
<input type="submit" name="register" value="Register" class="button" />
<input type="button" name="cancel" value="Cancel" class="button" onclick="javascript: document.location='index.php';" />
<? } ?>
</form>
<?
}

/**
* Prints out a login form and any error messages
* @param string $msg error messages to display for user
* @param string $resume page to resume on after login
*/ 
function printLoginForm($msg = '', $resume = '') {
	global $conf;
	$link = CmnFns::getNewLink();
	
	// Check browser information
	echo '<script language="JavaScript" type="text/javascript">checkBrowser();</script>';
	
	if (!empty($msg)) 
		CmnFns::do_error_box($msg, '', false);
?>
<!--
<h3 align="center">
<?= htmlspecialchars($conf['ui']['welcome']) ?>
</h3>
-->
<form name="login" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<table width="60%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
  <td bgcolor="#CCCCCC">
	<table width="100%" border="0" cellspacing="1" cellpadding="3">
	  <tr bgcolor="#EDEDED">
		<td colspan="2">
		  <h5 align="center">Please Log In</h5>
		</td>
	  </tr>
	  <tr bgcolor="#FFFFFF">
		<td width="175">
		  <p><b>Email address:</b></p>
		</td>
		<td>
		  <input type="text" name="email" class="textbox" />
		</td>
	  </tr>
	  <tr bgcolor="#FFFFFF">
		<td width="175">
		  <p><b>Password:</b></p>
		</td>
		<td>
		  <input type="password" name="password" class="textbox" />
		</td>
	  </tr>
	  <tr bgcolor="#FFFFFF">
		<td width="175">
		  <p><b>Keep me logged in:<br />
			(requires cookies)</b></p>
		</td>
		<td>
		  <input type="checkbox" name="setCookie" value="true" />
		</td>
	  </tr>
	  <tr bgcolor="#FAFAFA">
		<td colspan="2">
		  <h4 align="center"><b>First time user?
			<? $link->doLink('register.php', 'Click here to register.', '', '', 'Register for phpScheduleIt') ?>
			</b></h4>
		  <p align="center">
			<input type="submit" name="login" value="Log In" class="button" />
			<input type="hidden" name="resume" value="<?=$resume?>" />
		  </p>
		</td>
	  </tr>
	</table>
  </td>
</tr>
</table>
<p align="center">
<? $link->doLink('roschedule.php', 'View Schedule', '', '', 'View a read-only version of the schedule') ?>
|
<? $link->doLink('forgot_pwd.php', 'I Forgot My Password', '', '', 'Retreive lost password') ?>
|
<? $link->doLink('javascript: help();', 'Help Me', '', '', 'Get online help') ?>
</p>
</form>
<?
}
?>