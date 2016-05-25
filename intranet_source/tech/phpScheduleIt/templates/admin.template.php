<?php
/**
* This file provides output functions for the admin class
* No data manipulation is done in this file
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 05-04-04
* @package Templates
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

$link = CmnFns::getNewLink();	// Get Link object

/**
* Return the tool name
* @param none
*/
function getTool() {
	return $_GET['tool'];
}

/**
* Prints out list of current schedules
* @param Object $pager pager object
* @param mixed $schedules array of schedule data
* @param string $err last database error
*/
function print_manage_schedules(&$pager, $schedules, $err) {
	global $link;

?>
<form name="manageSchedule" method="post" action="admin_update.php" onsubmit="return checkAdminForm();">
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td colspan="7" class="tableTitle">&#8250; All Schedules</td>
        </tr>
        <tr class="rowHeaders">
          <td>Title</td>
          <td width="7%">Start</td>
          <td width="7%">End</td>
          <td width="11%">Interval</td>  
		  <td width="10%">Day Start</td>    
          <td width="20%">Admin Contact</td>
		  <td width="7%">Default</td>
		  <td width="5%">Edit</td>
          <td width="7%">Delete</td>
        </tr>
        <?

	if (!$schedules)
		echo '<tr class="cellColor0"><td colspan="8" style="text-align: center;">' . $err . '</td></tr>' . "\n";
		
    for ($i = 0; is_array($schedules) && $i < count($schedules); $i++) {
		$cur = $schedules[$i];
        echo "<tr class=\"cellColor" . ($i%2) . "\" align=\"center\">\n"
            . '<td style="text-align:left">' . $cur['scheduleTitle'] . "</td>\n"
            . '<td style="text-align:left">';
        echo CmnFns::formatTime($cur['dayStart']);
		echo "</td>\n"
            . '<td style="text-align:left">';
        echo CmnFns::formatTime($cur['dayEnd']);
        echo "</td>\n"
            . '<td style="text-align:left">';
        echo CmnFns::minutes_to_hours($cur['timeSpan']);
		echo "</td>\n"
		    . '<td style="text-align:left">';
        echo CmnFns::get_day_name($cur['weekDayStart'], 0);
		echo "</td>\n"
		 . '<td style="text-align:left">';
	    echo $cur['adminEmail'];
		echo "</td>\n"
			. '<td><input type="radio" value="' . $schedules[$i]['scheduleid'] . "\" name=\"isDefault\"" . ($schedules[$i]['isDefault'] == 1 ? ' checked="checked"' : '') . ' onclick="javacript: setSchedule(\'' . $schedules[$i]['scheduleid'] . '\');" /></td>'
            . '<td>' . $link->getLink($_SERVER['PHP_SELF'] . '?' . preg_replace("/&scheduleid=[\d\w]*/", "", $_SERVER['QUERY_STRING']) . '&amp;scheduleid=' . $cur['scheduleid'] . ((strpos($_SERVER['QUERY_STRING'], $pager->getLimitVar())===false) ? '&amp;' . $pager->getLimitVar() . '=' . $pager->getLimit() : ''), 'Edit', '', '', 'Edit data for ' . $cur['scheduleTitle']) . "</td>\n"
            . "<td><input type=\"checkbox\" name=\"scheduleid[]\" value=\"" . $cur['scheduleid'] . "\" /></td>\n"
            . "</tr>\n";
    }
	
    // Close table
    ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?
	echo submit_button('Delete Schedules', 'scheduleid') . hidden_fn('delSchedule');
?>
</form>
<form id="setDefaultSchedule" name="setDefaultSchedule" method="post" action="admin_update.php">
<input type="hidden" name="scheduleid" value=""/>
<input type="hidden" name="fn" value="dfltSchedule"/>
</form>
<?
}


/**
* Interface to add or edit schedule information
* @param mixed $rs array of schedule data
* @param boolean $edit whether this is an edit or not
* @param object $pager Pager object
*/
function print_schedule_edit($rs, $edit, &$pager) {
	global $conf;
    ?>
<form name="addSchedule" method="post" action="admin_update.php" <?= $edit ? "" : "onsubmit=\"return checkAddSchedule();\"" ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td width="200" class="formNames">Schedule Title:</td>
          <td class="cellColor"><input type="text" name="scheduleTitle" class="textbox" value="<?= isset($rs['scheduleTitle']) ? $rs['scheduleTitle'] : '' ?>" />
          </td>
        </tr>
		<tr>
		  <td class="formNames">Day Start:</td>
		  <td class="cellColor"><select name="dayStart" class="textbox">		  
		  <?
		  for ($time = 0; $time <= 1410; $time += 30)
		  	echo '<option value="' . $time . '"' . ((isset($rs['dayStart']) && ($rs['dayStart'] == $time)) ? ' selected="selected"' : '') . '>' . CmnFns::formatTime($time) . '</option>' . "\n";
		  ?>		  
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="formNames">Day End:</td>
		  <td class="cellColor"><select name="dayEnd" class="textbox">		  
		  <?
		  for ($time = 30; $time <= 1440; $time += 30)
		  	echo '<option value="' . $time . '"' . ((isset($rs['dayEnd']) && ($rs['dayEnd'] == $time)) ? (' selected="selected"') : (($time==1440 && !isset($rs['dayEnd'])) ? ' selected="selected"' : '')) . '>' . CmnFns::formatTime($time) . '</option>' . "\n";
		  ?>		  
		  </select>
		  </td>
		</tr>
        <tr>
          <td class="formNames">Time Span:</td>
          <td class="cellColor"><select name="timeSpan" class="textbox">		  
		  <?
		  $spans = array (30, 10, 15, 60, 120, 180, 240);
		  for ($i = 0; $i < count($spans); $i++)
		  	echo '<option value="' . $spans[$i] . '"' . ((isset($rs['timeSpan']) && ($rs['timeSpan'] == $spans[$i])) ? (' selected="selected"') : '') . '>' . CmnFns::minutes_to_hours($spans[$i]) . '</option>' . "\n";
		  ?>		  
		  </select>
		  </td>
        </tr>
        <tr>
          <td class="formNames">Week Day Start:</td>
          <td class="cellColor"><select name="weekDayStart" class="textbox">		  
		  <?
		  for ($i = 0; $i < 7; $i++)
		  	echo '<option value="' . $i . '"' . ( (isset($rs['weekDayStart']) && $rs['weekDayStart'] == $i) ? ' selected="selected"' : '') . '>' . CmnFns::get_day_name($i) . '</option>' . "\n";
		  ?>		  
		  </select>
		  </td>
        </tr>
        <tr>
          <td class="formNames">Days To Show:</td>
          <td class="cellColor"><input type="text" name="viewDays" class="textbox" size="2" maxlength="2" value="<?= isset($rs['viewDays']) ? $rs['viewDays'] : '7' ?>" />
          </td>
        </tr>
		<tr>
		  <td class="formNames">Reservation Offset:</td>
		  <td class="cellColor"><input type="text" name="dayOffset" class="textbox" size="2" maxlength="2" value="<?= isset($rs['dayOffset']) ? $rs['dayOffset'] : '0' ?>" />
          </td>
		</tr>
		<tr>
		  <td class="formNames">Hidden:</td>
		   <td class="cellColor"><select name="isHidden" class="textbox">		  
		  <?
		  $yesNo = array('No', 'Yes');
		  for ($i = 0; $i < 2; $i++)
		  	echo '<option value="' . $i . '"' . ((isset($rs['isHidden']) && ($rs['isHidden'] == $i)) ? (' selected="selected"') : '') . '>' . $yesNo[$i]  . '</option>' . "\n";
		  ?>		  
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="formNames">Show Summary:</td>
		   <td class="cellColor"><select name="showSummary" class="textbox">		  
		  <?
		  for ($i = 1; $i >= 0; $i--)
		  	echo '<option value="' . $i . '"' . ((isset($rs['showSummary']) && ($rs['showSummary'] == $i)) ? (' selected="selected"') : '') . '>' . $yesNo[$i]  . '</option>' . "\n";
		  ?>		  
		  </select>
		  </td>
		</tr>
		<tr>
          <td class="formNames">Admin Email:</td>
          <td class="cellColor"><input type="text" name="adminEmail" maxlength="75" class="textbox" value="<?= isset($rs['adminEmail']) ? $rs['adminEmail'] : $conf['app']['adminEmail'] ?>" />
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />
<?	
        // Print out correct buttons
        if (!$edit) {
            echo submit_button('Add Schedule', 'scheduleid') . hidden_fn('addSchedule')
			. ' <input type="reset" name="reset" value="Clear" class="button" />' . "\n";
        }
		else {
            echo submit_button('Edit Schedule', 'scheduleid') . cancel_button($pager) . hidden_fn('editSchedule')
				. '<input type="hidden" name="scheduleid" value="' . $rs['scheduleid'] . '" />' . "\n";
        	// Unset variables	
			unset($rs);
		}
		echo "</form>\n";
}

/**
* Prints out the user management table
* @param Object $pager pager object
* @param mixed $users array of user data
* @param string $err last database error
*/
function print_manage_users(&$pager, $users, $err) {
	global $link;
	
?>

<form name="manageUser" method="post" action="admin_update.php" onsubmit="return checkAdminForm();">
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td colspan="7" class="tableTitle">&#8250; All Members </td>
        </tr>
        <tr class="rowHeaders">
          <td width="21%">Name</td>
          <td width="28%">Email</td>
          <td width="15%">Institution</td>
          <td width="12%">Phone</td>
          <td width="8%">Password</td>
          <td width="10%">Permissions</td>
          <td width="6%">Delete</td>
        </tr>
        <tr class="cellColor0" style="text-align: center">
          <td><? printDescLink($pager, 'lname', 'Sort by descending last name') ?> &nbsp;&nbsp; <? printAscLink($pager, 'lname', 'Sort by ascending last name') ?> </td>
          <td><? printDescLink($pager, 'email', 'Sort by descending email address') ?> &nbsp;&nbsp; <? printAscLink($pager, 'email', 'Sort by ascending email address') ?> </td>
          <td><? printDescLink($pager, 'institution', 'Sort by descending institution') ?> &nbsp;&nbsp; <? printAscLink($pager, 'institution', 'Sort by ascending institution') ?> </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <?
	
	if (!$users)
		echo '<tr class="cellColor0"><td colspan="7" style="text-align: center;">' . $err . '</td></tr>' . "\n";
	
	for ($i = 0; is_array($users) && $i < count($users); $i++) {
		$cur = $users[$i];
		$fname = $cur['fname'];
		$lname = $cur['lname'];
		$email = $cur['email'];
		
        echo "<tr class=\"cellColor" . ($i%2) . "\" align=\"center\">\n"
               . '<td style="text-align:left;">' . $link->getLink("javascript: viewUser('". $cur['memberid'] . "');", $fname . ' ' . $lname, '', '', "View information about $fname $lname") . "</td>\n"
               . '<td style="text-align:left;">' . $link->getLink("mailto:$email", $email, '', '', "Send email to $fname $lname") . "</td>\n"
               . '<td style="text-align:left;\">' . $cur['institution'] . "</td>\n"
               . '<td style="text-align:left;">' . $cur['phone'] . "</td>\n"
               . '<td>' . $link->getLink("admin.php?tool=pwreset&amp;memberid=" . $cur['memberid'], 'Reset', '', '', "Reset password for $fname $lname") .  "</td>\n"
               . '<td>' . $link->getLink("admin.php?tool=perms&amp;memberid=" . $cur['memberid'], 'Edit', '', '', "Edit permissions for $fname $lname") . "</td>\n"
               . '<td><input type="checkbox" name="memberid[]" value="' . $cur['memberid'] . '" /></td>'. "\n"
              . "</tr>\n";
    }
	
    // Close users table
    ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?
	echo submit_button('Delete Users') . hidden_fn('deleteUsers') . '</form>';
?>
<form name="name_search" action="<?=$_SERVER['PHP_SELF']?>" method="get">
	<p align="center">
	<? print_lname_links(); ?>
	</p>
	<br />
	<p align="center">
	First Name: <input type="text" name="firstName" class="textbox" />
	Last Name: <input type="text" name="lastName" class="textbox" />	
	<input type="hidden" name="searchUsers" value="true" />
	<input type="hidden" name="tool" value="<?=getTool()?>" />
	<input type="hidden" name="<?=$pager->getLimitVar()?>" value="<?=$pager->getLimit()?>" />
	<? if (isset($_GET['order'])) { ?>
		<input type="hidden" name="order" value="<?=$_GET['order']?>" />
	<? } ?>
	<? if (isset($_GET['vert'])) { ?>
		<input type="hidden" name="vert" value="<?=$_GET['vert']?>" />
	<? } ?>
	<input type="submit" name="searchUsersBtn" value="Search Users" class="button" />
	</p>
</form>
<?
}


/**
* Prints out the links to select last names
* @param none
*/
function print_lname_links() {
	$letters = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
	echo '<a href="javascript: search_user_lname(\'\');">All Users</a>';
	foreach($letters as $letter) {
		echo '<a href="javascript: search_user_lname(\''. $letter . '\');" style="padding-left: 10px; font-size: 12px;">' . $letter . '</a>';
	}
}

/**
* Prints out list of current resources
* @param Object $pager pager object
* @param mixed $resources array of resource data
* @param string $err last database error
*/
function print_manage_resources(&$pager, $resources, $err) {
	global $link;

?>
<form name="manageResource" method="post" action="admin_update.php" onsubmit="return checkAdminForm();">
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td colspan="8" class="tableTitle">&#8250; All Resources</td>
        </tr>
        <tr class="rowHeaders">
          <td>Name</td>
          <td width="18%">Location</td>
		  <td width="12%">Schedule</td>
          <td width="10%">Phone</td>
          <td width="25%">Notes</td>
          <td width="5%">Edit</td>
          <td width="9%">Status</td>
          <td width="7%">Delete</td>
        </tr>
        <tr class="cellColor" style="text-align: center">
          <td> <? printDescLink($pager, 'name', 'Sort by descending resource name') ?> &nbsp;&nbsp; <? printAscLink($pager, 'name', 'Sort by ascending resource name') ?> </td>
          <td> <? printDescLink($pager, 'location', 'Sort by descending location') ?> &nbsp;&nbsp; <? printAscLink($pager, 'location', 'Sort by ascending location') ?> </td>
          <td> <? printDescLink($pager, 'scheduleTitle', 'Sort by descending schedule title') ?> &nbsp;&nbsp; <? printAscLink($pager, 'scheduleTitle', 'Sort by ascending schedule title') ?> </td>
		  <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <?

	if (!$resources)
		echo '<tr class="cellColor0"><td colspan="8" style="text-align: center;">' . $err . '</td></tr>' . "\n";
		
    for ($i = 0; is_array($resources) && $i < count($resources); $i++) {
		$cur = $resources[$i];
        echo "<tr class=\"cellColor" . ($i%2) . "\" align=\"center\">\n"
            . '<td style="text-align:left">' . $cur['name'] . "</td>\n"
            . '<td style="text-align:left">';
        echo isset($cur['location']) ?  $cur['location'] : '&nbsp;';
		echo "</td>\n"
            . '<td style="text-align:left">' . $cur['scheduleTitle'] . "</td>\n";
        echo '<td style="text-align:left">';
        echo isset($cur['rphone']) ?  $cur['rphone'] : '&nbsp;';
		echo "</td>\n"
            . '<td style="text-align:left">';
        echo isset($cur['notes']) ?  $cur['notes'] : '&nbsp;';
		echo "</td>\n"
            . '<td>' . $link->getLink($_SERVER['PHP_SELF'] . '?' . preg_replace("/&machid=[\d\w]*/", "", $_SERVER['QUERY_STRING']) . '&amp;machid=' . $cur['machid'] . ((strpos($_SERVER['QUERY_STRING'], $pager->getLimitVar())===false) ? '&amp;' . $pager->getLimitVar() . '=' . $pager->getLimit() : ''), 'Edit', '', '', 'Edit data for ' . $cur['name']) . "</td>\n"
            . '<td>' . $link->getLink("admin_update.php?fn=togResource&amp;machid=" . $cur['machid'] . "&amp;status=" . $cur['status'], $cur['status'] == 'a' ? 'Active' : 'Inactive', '', '', 'Toggle this resource active/inactive') . "</td>\n"
            . "<td><input type=\"checkbox\" name=\"machid[]\" value=\"" . $cur['machid'] . "\" /></td>\n"
            . "</tr>\n";
    }
	
    // Close table
    ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?
	echo submit_button('Delete Resources', 'machid') . hidden_fn('delResource') . '</form>';
}


/**
* Interface to add or edit resource information
* @param mixed $rs array of resource data
* @param boolean $edit whether this is an edit or not
* @param object $pager Pager object
*/
function print_resource_edit($rs, $scheds, $edit, &$pager) {
	global $conf;
	$start = 0;
	$end   = 1440;
	$mins = array(0, 10, 15, 30);
	
	if ($edit) {
		$minH = intval($rs['minRes'] / 60);
		$minM = intval($rs['minRes'] % 60);
		$maxH = intval($rs['maxRes'] / 60);
		$maxM = intval($rs['maxRes'] % 60);
	}

    ?>
<form name="addResource" method="post" action="admin_update.php" <?= $edit ? "" : "onsubmit=\"return checkAddResource(this);\"" ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td width="200" class="formNames">Resource Name:</td>
          <td class="cellColor"><input type="text" name="name" class="textbox" value="<?= isset($rs['name']) ? $rs['name'] : '' ?>" />
          </td>
        </tr>
        <tr>
          <td class="formNames">Location:</td>
          <td class="cellColor"><input type="text" name="location" class="textbox" value="<?= isset($rs['location']) ? $rs['location'] : '' ?>" />
          </td>
        </tr>
        <tr>
          <td class="formNames">Phone:</td>
          <td class="cellColor"><input type="text" name="rphone" class="textbox" value="<?= isset($rs['rphone']) ? $rs['rphone'] : '' ?>" />
          </td>
        </tr>
        <tr>
          <td class="formNames">Notes:</td>
          <td class="cellColor"><textarea name="notes" class="textbox" rows="3" cols="30"><?= isset($rs['notes']) ? $rs['notes'] : '' ?>
</textarea>
          </td>
        </tr>
		<tr>
		<td class="formNames">Schedule:</td>
		<td class="cellColor">
		<select name="scheduleid" class="textbox">
		<?
		if (empty($scheds))
			echo '<option value="">Please add schedules</option>';
		else {
			for ($i = 0; $i < count($scheds); $i++)
				echo '<option value="' . $scheds[$i]['scheduleid'] . '"' . (isset($rs['scheduleid']) && $scheds[$i]['scheduleid'] == $rs['scheduleid'] ? ' selected="selected"' : '') . '>' . $scheds[$i]['scheduleTitle'] . "</option>\n";
		}
		?>
		</select>
		</td>
		</tr>
		<tr>
		  <td class="formNames">Minimum Reservation Time:</td>
		  <td class="cellColor">
		  <select name="minH" class="textbox">		  
		  <?
		  for ($h = 0; $h < 25; $h++)
		  	echo '<option value="' . $h . '"' . ((isset($minH) && $minH == $h) ? ' selected="selected"' : '') . '>' . $h . ' hours</option>' . "\n";
		  ?>
		  </select>
		  <select name="minM" class="textbox">
		  <?
		  foreach ($mins as $m)
		  	echo '<option value="' . $m . '"' . ((isset($minM) && $minM == $m) ? ' selected="selected"' : '') . '>' . $m . ' minutes</option>' . "\n";
		  ?>		  
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="formNames">Maximum Reservation Time:</td>
		  <td class="cellColor">
		  <select name="maxH" class="textbox">		  
		  <?
		  for ($h = 0; $h < 25; $h++)
		  	echo '<option value="' . $h . '"' . ((isset($maxH) && $maxH == $h) ? ' selected="selected"' : '') . '>' . $h . ' hours</option>' . "\n";
		  ?>
		  </select>
		  <select name="maxM" class="textbox">
		  <?
		  foreach ($mins as $m)
		  	echo '<option value="' . $m . '"' . ((isset($maxM) && $maxM == $m) ? ' selected="selected"' : '') . '>' . $m . ' minutes</option>' . "\n";
		  ?>		  
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="formNames">Auto-assign permission:</td>
		  <td class="cellColor"><input type="checkbox" name="autoAssign" <?=(isset($rs['autoAssign']) && ($rs['autoAssign'] == 1)) ? 'checked="checked"' : ''?>/>
		  </td>
		</tr>
      </table>
    </td>
  </tr>
</table>
<br />
<?	
        // Print out correct buttons
        if (!$edit) {
            echo submit_button('Add Resource', 'machid') . hidden_fn('addResource')
			. ' <input type="reset" name="reset" value="Clear" class="button" />' . "\n";
        }
		else {
            echo submit_button('Edit Resource', 'machid') . cancel_button($pager) . hidden_fn('editResource')
				. '<input type="hidden" name="machid" value="' . $rs['machid'] . '" />' . "\n";
        	// Unset variables	
			unset($rs);
		}
		echo "</form>\n";
}


/**
* Interface for managing user training
* Provide interface for viewing and managing
*  user training information
* @param object $user User object of user to manage
* @param array $rs list of resources
*/
function print_manage_perms(&$user, $rs, $err) {    
	global $link;
	
	if (!$user->is_valid()) {
		CmnFns::do_error_box($user->get_error() . '<br /><a href="' . $_SERVER['PHP_SELF'] . '?tool=users">Back</a>', '', false);
		return;
	}
			
	echo '<h3>' . $user->get_name() . '</h3>';
    ?>
<form name="train" method="post" action="admin_update.php">
  <table border="0" cellspacing="0" cellpadding="1">
    <tr>
      <td class="tableBorder">
        <table cellspacing="1" cellpadding="2" border="0" width="100%">
          <tr class="rowHeaders">
            <td width="240">Resource Name</td>
            <td width="60">Allowed</td>
          </tr>
          <?
			if (!$rs) echo '<tr class="cellColor0" style="text-align: center;"><td colspan="2">' . $err . '</td></tr>';
			
			for ($i = 0; is_array($rs) && $i < count($rs); $i++) {
				echo '<tr class="cellColor"><td>' . $rs[$i]['name'] . '</td><td style="text-align: center;">'
					. '<input type="checkbox" name="machid[]" value="' . $rs[$i]['machid'] . '"';
				if ($user->has_perm($rs[$i]['machid']))
					echo ' checked="checked"';
				echo '/></td></tr>';
		  	}
    
    // Close off tables/forms.  Print buttons and hidden field
    ?>
          <tr class="cellColor1">
            <td>&nbsp;</td>
            <td style="text-align: center;">
              <input type="checkbox" name="checkAll" onclick="checkAllBoxes(this);" />
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <input type="hidden" name="memberid" value="<?=$user->get_id()?>" />
  <p style="padding-top: 5px; padding-bottom: 5px;"><input type="checkbox" name="notify_user" value="true" />Notify user of update?</p>
  <?= submit_button('Save') . hidden_fn('editPerms')?>
  <input type="button" name="cancel" value="Back to Manage Users" class="button" onclick="document.location='<?=$_SERVER['PHP_SELF']?>?tool=users';" />
</form>
<?
}


/**
* Interface for managing reservations
* Provide a table to allow admin to modify or delete reservations
* @param Object $pager pager object
* @param mixed $res reservation data
* @param string $err last database error
*/
function print_manage_reservations(&$pager, $res, $err) {
	global $link;
	
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td colspan="8" class="tableTitle">&#8250; User Reservations</td>
        </tr>
        <tr class="rowHeaders">
          <td width="10%">Date</td>
          <td width="27%">User</td>
          <td width="22%">Resource</td>
          <td width="10%">Start Time</td>
          <td width="10%">End Time</td>
          <td width="7%">View</td>
          <td width="7%">Modify</td>
          <td width="7%">Delete</td>
        </tr>
        <tr class="cellColor" style="text-align: center">
          <td> <? printDescLink($pager, 'date', 'Sort by descending date') ?> &nbsp;&nbsp; <? printAscLink($pager, 'date', 'Sort by ascending date') ?> </td>
          <td> <? printDescLink($pager, 'lname', 'Sort by descending user name') ?> &nbsp;&nbsp; <? printAscLink($pager, 'lname', 'Sort by ascending user name') ?> </td>
          <td> <? printDescLink($pager, 'name', 'Sort by descending resource name') ?> &nbsp;&nbsp; <? printAscLink($pager, 'name', 'Sort by ascending resource name') ?> </td>
          <td> <? printDescLink($pager, 'startTime', 'Sort by descending start time') ?> &nbsp;&nbsp; <? printAscLink($pager, 'startTime', 'Sort by ascending start time') ?> </td>
          <td> <? printDescLink($pager, 'endTime', 'Sort by descending end time') ?> &nbsp;&nbsp; <? printAscLink($pager, 'endTime', 'Sort by ascending end time') ?> </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <?	
	// Write message if they have no reservations	
	if (!$res)
		echo '<tr class="cellColor"><td colspan="8" align="center">' . $err . '</td></tr>';

	// For each reservation, clean up the date/time and print it
	for ($i = 0; is_array($res) && $i < count($res); $i++) {
		$cur = $res[$i];
		$fname = $cur['fname'];
		$lname = $cur['lname'];	
        echo "<tr class=\"cellColor" . ($i%2) . "\" align=\"center\">\n"
					. '<td>' . CmnFns::formatDate($cur['date']) . '</td>'
					. '<td style="text-align:left">' . $link->getLink("javascript: viewUser('" . $cur['memberid'] . "');", $fname . ' ' . $lname, '', '', "View user info for $fname $lname") . "</td>"
                    . '<td style="text-align:left">' . $cur['name'] . "</td>"
					. '<td>' . CmnFns::formatTime($cur['startTime']) . '</td>'
					. '<td>' . CmnFns::formatTime($cur['endTime']) . '</td>'
                    . '<td>' . $link->getLink("javascript: reserve('v','','','" . $cur['resid']. "');", 'View', '', '', 'View this reservation information') . '</td>'
					. '<td>' . $link->getlink("javascript: reserve('m','','','" . $cur['resid']. "');", 'Modify', '', '', 'Modify this reservation') . '</td>'
					. '<td>' . $link->getLink("javascript: reserve('d','','','" . $cur['resid']. "');", 'Delete', '', '', 'Delete this reservation') . '</td>'
					. "</tr>\n";		
	}
    ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?
}


/**
* Prints out GUI list to of email addresses
* Prints out a table with option to email users,
*  and prints form to enter subject and message of email
* @param array $users user data
* @param string $sub subject of email
* @param string $msg message of email
* @param array $usr users to send to
* @param string $err last database error
*/
function print_manage_email($users, $sub, $msg, $usr, $err) {
	?>
<form name="emailUsers" method="post" action="<?=$_SERVER['PHP_SELF'] . '?tool=' . $_GET['tool']?>">
  <table width="60%" border="0" cellspacing="0" cellpadding="1">
    <tr>
      <td class="tableBorder">
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td colspan="3" class="tableTitle">&#8250; Email Users</td>
          </tr>
          <tr class="rowHeaders">
            <td width="15%">&nbsp;</td>
            <td width="40%">User</td>
            <td width="45%">Email</td>
          </tr>
          <?
	if (!$users)
		echo '<tr class="cellColor0" style="text-align: center;"><td colspan="3">' . $err . '</td></tr>';
    // Print users out in table
    for ($i = 0; is_array($users) && $i < count($users); $i++) {
		$cur = $users[$i];
        echo '<tr class="cellColor' . ($i%2) . "\">\n"
            . '<td style="text-align: center"><input type="checkbox" ';
		if ( empty($usr) || in_array($cur['email'], $usr) )
			echo 'checked="checked" ';
		echo 'name="emailIDs[]" value="' . $cur['email'] . "\" /></td>\n"
            . '<td>&lt;' . $cur['lname'] . ', ' . $cur['fname'] . '&gt;</td>'
            . '<td>' . $cur['email'] . '</td>'
            . "</tr>\n";
    }
    ?>
          <tr>
            <td colspan="3" class="cellColor0">Check All
              <input type="checkbox" name="checkAll" checked="checked" onclick="checkAllBoxes(this);" />
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br />
  <table width="60%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="15%"><p>Subject</p>
      </td>
      <td><input type="text" name="subject" size="60" class="textbox" value="<?=$sub?>"/>
      </td>
    </tr>
    <tr>
      <td valign="top"><p>Message</p>
      </td>
      <td><textarea rows="10" cols="60" name="message" class="textbox"><?=$msg?></textarea>
      </td>
    </tr>
  </table>
  <input type="submit" name="previewEmail" value="Next &gt;" class="button" />
</form>
<?
}

/**
* Prints out a preview of the email to be sent
* @param string $sub subject of email
* @param string $msg message of email
* @param array $usr array of users to send the email to
*/
function preview_email($sub, $msg, $usr) {
?>
<h5>Please review this email before sending.</h5>
<table width="60%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td bgcolor="#DEDEDE">
      <table width="100%" cellpadding="3" cellspacing="1" border="0">
        <tr class="cellColor0">
          <td><?=$sub?>
          </td>
        </tr>
        <tr class="cellColor0">
          <td><?=$msg?>
          </td>
        </tr>
		<tr class="cellColor0">
		  <td>
		  <?
		  if (empty($usr)) echo 'No users selected!';
		  foreach ($usr as $email) echo $email . '<br />'
		  ?>
		  </td>
		</tr>
      </table>
    </td>
  </tr>
</table>
<br />
<form action="<?=$_SERVER['PHP_SELF'] . '?tool=' . $_GET['tool']?>" method="post" name="send_email">
<input type="button" name="goback" value="&lt; Go Back" class="button" onclick="history.back();" />
<input type="submit" name="sendEmail" value="Send Email" class="button" />
</form>
<?
}


/**
* Actually sends the email to all addresses in POST
* @param string $subject subject of email
* @param string $msg email message
* @param array $success array of users that email was successful for
*/
function print_email_results($subject, $msg, $success) {
    if (!$success)
		CmnFns::do_error_box('Sorry, there was a problem sending your email. Please try again later.', '', false);
	else {
		CmnFns::do_message_box('The email sent successfully.');
	}

    echo '<h4 align="center">Please <u>do not</u> refresh this page. Doing so will send the email again.<br/>'
        . '<a href="' . $_SERVER['PHP_SELF'] . '?tool=email">Return to email management</a></h4>';
}

/**
* Prints out a list of tables and all the fields in them
*  with an option to select which tables and fields should be exported
*  and in which format
* @param array $tables array of tables
* @param array $fields array of fields for each table
*/
function show_tables($tables, $fields) {
	echo '<h5>Please select which tables and which fields to export:</h5>'
		. '<form name="get_fields" action="' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '" method="post">' . "\n";
	for ($i = 0; $i < count($tables); $i++) {
		echo '<p><input type="checkbox" name="table[]" value="' . $tables[$i] . '"  checked="checked" onclick="javascript: toggle_fields(this);" />' . $tables[$i] . "</p>\n";
		
		echo '<select name="table,' . $tables[$i] . '[]" multiple="multiple" size="5" class="textbox">' . "\n";
		echo '<option value="all" selected="selected">- all fields -</option>' . "\n";
		for ($k = 0; $k < count($fields[$tables[$i]]); $k++)
			echo  '<option value="' . $fields[$tables[$i]][$k] . '">' . $fields[$tables[$i]][$k] . '</option>' . "\n";

		echo '</select>' . "<br />\n";
	}
	echo '<p><input type="radio" name="type" value="xml" checked="checked" />XML'
		. '<input type="radio" name="type" value="csv" />CSV'
		. '</p><br /><input type="submit" name="submit" value="Export Data" class="button" /></form>';
}

/**
* Begins the line of table data
* @param boolean $xml if this is in XML or not
* @param string $table_name name of this table
*/
function start_exported_data($xml, $table_name) {
	echo '<pre>';
	echo ($xml) ? "&lt;$table_name&gt;\r\n" : '';
}

/**
* Prints out the exported data in XML or CSV format
* @param array $data array of data to print out
* @param boolean $xml whether to print XML or not
*/
function print_exported_data($data, $xml) {
	$first_row = true;
	for ($x = 0; $x < count($data); $x++) {
		echo ($xml) ? "\t&lt;record&gt;\r\n" : '';
		
		if (!$xml && $first_row) {				// Print out names of fields for first row of CSV
				$keys = array_keys($data[$x]);
				for ($i = 0; $i < count($keys); $i++) {
					echo '"' . $keys[$i] . '"';
					if ($i < count($keys)-1) echo ',';
				} 
				echo "\r\n";
		}
		
		$first_row = false;

		$first_csv = '"';
		foreach ($data[$x] as $k => $v) {
			echo ($xml) ? "\t\t&lt;$k&gt;$v&lt;/$k&gt;\r\n" : $first_csv . addslashes($v) . '"';
			$first_csv = ',"';
		}
		echo ($xml) ? "\t&lt;/record&gt;\r\n" : "\r\n";
	}	
}

/**
* Prints out an interface to manage blackout times for this resource
* @param array $resource array of resource data
* @param array $blackouts array of blackout data
*/
function print_blackouts($resource, $blackouts) {
	for ($i = 0; $i < count($resouce); $i++)
		echo $resouce[$i] . '<br />';
}

/**
* Ends the line of table data
* @param boolean $xml if this is in XML or not
* @param string $table_name name of this table
*/
function end_exported_data($xml, $table_name) {
	echo ($xml) ? "&lt;/$table_name&gt;\r\n" : '';
	echo '</pre>';
}

/**
* Prints the form to reset a users password
* @param object $user user object
*/
function print_reset_password(&$user) {
?>
<form name="resetpw" method="post" action="admin_update.php">
  <table border="0" cellspacing="0" cellpadding="1" width="50%">
    <tr>
      <td class="tableBorder">
        <table cellspacing="1" cellpadding="2" border="0" width="100%">
          <tr class="rowHeaders">
		  	<td colspan="2">Reset Password for <?=$user->get_name()?></td>
		  </tr>
		  <tr class="cellColor">
            <td width="50%" valign="top">Please enter the new password</td>
			<td><input type="password" value="" class="textbox" name="password" />
			<br />
			<i>If no value is specified, the default password set in the config file will be used.</i>
			</td>
		  <tr class="cellColor">
		    <td colspan="2"><input type="checkbox" name="notify_user" value="true" checked="checked"/>Notify user that password has been changed?</td>
		  </tr>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <input type="hidden" name="memberid" value="<?=$user->get_id()?>" />
  <br />
  <?= submit_button('Save') . hidden_fn('resetPass')?>
  <input type="button" name="cancel" value="Back to Manage Users" class="button" onclick="document.location='<?=$_SERVER['PHP_SELF']?>?tool=users';" />
</form>
<?
}

/**
* Prints out a link to reorder recordset ascending order
* @param Object $pager pager object
* @param string $order order to sort result set by
* @param string $text link text
* @see print_asc_desc_link()
*/
function printAscLink(&$pager, $order, $text) {
	print_asc_desc_link($pager, $order, $text, 'ASC');
}

/**
* Prints out a link to reorder recordset descending order
* @param Object $pager pager object
* @param string $order order to sort result set by
* @param string $text link text
* @see print_asc_desc_link()
*/
function printDescLink(&$pager, $order, $text) {
	print_asc_desc_link($pager, $order, $text, 'DESC');
}

/**
* This function extends the printAscLink and printDescLink, printing out
*  a link to reorder a recordset in a certain order
* This was added to keep the current printAsc/DescLink functions in place, but put
*  all logic into one function
* @param Object $pager pager object
* @param string $order order to sort result set by
* @param string $text link text
* @param string $vert ascending or descending order
*/
function print_asc_desc_link(&$pager, $order, $text, $vert) {
	global $link;
	
	$tool = getTool();
	$page = $pager->getPageNum();
	
	$plus_minus = ($vert == 'ASC') ? '[+]' : '[&#8211;]';		// Plus or minus box
	$limit_str = '&amp;' . $pager->getLimitVar() . '=' . $pager->getLimit();
	$page_str  = '&amp;' . $pager->getPageVar() . '=' . $pager->getPageNum();
	$vert_str  = "&amp;vert=$vert";
	
	// Fix up the query string
	$query =  $_SERVER['QUERY_STRING'];
	if (eregi('(\?|&)' . $pager->getLimitVar() . "=[0-9]*", $query))
		$query = eregi_replace('(\?|&)' . $pager->getLimitVar() . "=[0-9]*", $limit_str, $query);
	else
		$query .= $limit_str;
	
	if (eregi('(\?|&)' . $pager->getPageVar() . "=[0-9]*", $query))
		$query = eregi_replace('(\?|&)' . $pager->getPageVar() . "=[0-9]*", $page_str, $query);
	else
		$query .= $page_str;	
	
	if (eregi("(\?|&)vert=[a-zA-Z]*", $query))
		$query = eregi_replace("(\?|&)vert=[a-zA-Z]*", $vert_str, $query);
	else
		$query .= $vert_str;

	if (eregi("(\?|&)order=[a-zA-Z]*", $query))
		$query = eregi_replace("(\?|&)order=[a-zA-Z]*", "&amp;order=$order", $query);
	else
		$query .= "&amp;order=$order";
		
	$link->doLink($_SERVER['PHP_SELF'] . '?' . $query, $plus_minus, '', '', $text);
}

/**
* Returns a button to cancel editing
* @param none
* @return string of html for a cancel button 
*/
function cancel_button(&$pager) {
	return '<input type="button" name="cancel" value="Cancel" class="button" onclick="javascript: document.location=\'' . $_SERVER['PHP_SELF'] . '?tool=' . $_GET['tool'] . '&amp;' . $pager->getLimitVar() . '=' . $pager->getLimit() . '&amp;' . $pager->getPageVar() . '=' . $pager->getPageNum() . '\';" />' . "\n";
}

/**
* Returns a submit button with $value value
* @param string $value value of button
* @param string $get_value value in the query string for editing an item (ie, to edit a resource its machid)
* @return string of html for a submit button
*/
function submit_button($value, $get_value = '') {
	return '<input type="submit" name="submit" value="' . $value . '" class="button" />' . "\n"
			. '<input type="hidden" name="get" value="' . $get_value  . '" />' . "\n";
}

/**
* Returns a hidden fn field
* @param string $value value of the hidden field
* @return string of html for hidden fn field
*/
function hidden_fn($value) {
	return '<input type="hidden" name="fn" value="'. $value . '" />' . "\n";
}
?>