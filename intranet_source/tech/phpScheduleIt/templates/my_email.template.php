<?php
/**
* Template functions for managing email contacts
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 03-13-04
* @package Templates
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

/**
* Print out a form to let users select what kind of emails they wish to recieve
* @param object $user current user to manage
*/
function print_email_contacts(&$user) {
?>
	<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="mngemail">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr>
	    <td class="tableTitle" colspan="2">Manage My Email Contacts</td></tr>

<?
	echo '<tr class="cellColor" style="font-weight: bold;"><td colspan="2">Email me when:</td></tr>' . "\n"
		. '<tr class="cellColor0">'
		. '  <td width="15%">'
		. '    Yes<input type="radio" name="e_add" value="y" ' . (($user->wants_email('e_add')) ? 'checked="checked"' : '') . ' />'
		. '    No<input type="radio" name="e_add" value="n" ' . ((!$user->wants_email('e_add')) ? 'checked="checked"' : '') . ' />'
		. '  </td>'
		. '  <td>I place a reservation</td>'
		. '</tr>' . "\n"
		. '<tr class="cellColor1">'
		. '  <td>'
		. '    Yes<input type="radio" name="e_mod" value="y" ' . (($user->wants_email('e_mod')) ? 'checked="checked"' : '') . ' />'
		. '    No<input type="radio" name="e_mod" value="n" ' . ((!$user->wants_email('e_mod')) ? 'checked="checked"' : '') . ' />'
		. '  </td>'
		. '  <td>My reservation is modified</td>'
		. '</tr>' . "\n"
		. '<tr class="cellColor0">'		
		. '  <td>'
		. '    Yes<input type="radio" name="e_del" value="y" ' . (($user->wants_email('e_del')) ? 'checked="checked"' : '') . ' />'
		. '    No<input type="radio" name="e_del" value="n" ' . ((!$user->wants_email('e_del')) ? 'checked="checked"' : '') . ' />'
		. '  </td>'
		. '  <td>My reservation is deleted</td>'
		. '</tr>' . "\n"
		. '<tr class="cellColor1">'
		. '<td colspan="2">I prefer: '
		. 'HTML <input type="radio" name="e_html" value="y" ' . (($user->wants_html()) ? 'checked="checked"' : '') . ' /> '
		. 'or plain text <input type="radio" name="e_html" value="n" ' . ((!$user->wants_html()) ? 'checked="checked"' : '') . ' /></td>'
		. '</tr>' . "\n"
		. '</table>' . "\n";
?>
<input type="submit" name="submit" value="Save Email Settings" class="button" />
<input type="reset" name="reset" value="Reset" class="button" />
<br /><br /><input type="button" name="cancel" value="Cancel" class="button" onclick="javascript: document.location='ctrlpnl.php';" />
</form>
<?
}

/**
* Prints a message letting the user know that the update was successful
* @param none
*/
function print_success() {
	CmnFns::do_message_box('Your email preferences were successfully saved!<br />Return to <a href="ctrlpnl.php">My Control Panel</a>');
}
?>