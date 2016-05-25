<?php
/**
* This file provides output functions for reserve.php
* No data manipulation is done in this file
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-08-04
* @package Templates
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

/**
* Print out information about this resource
* This function prints out a table containing
*  all information about a given resource
* @param array $rs array of resource information
*/
function print_resource_data(&$rs, $colspan = 1) {
?>
<tr><td colspan="<?=$colspan?>">
<h3 align="center">
  <?=$rs['name']?>
</h3>
</td></tr>
<tr><td>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr class="tableBorder">
    <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td width="100" class="formNames">Location:</td>
          <td class="cellColor"><?=$rs['location']?>
          </td>
        </tr>
        <tr>
          <td width="100" class="formNames">Phone:</td>
          <td class="cellColor"><?=$rs['rphone']?>
          </td>
        </tr>
        <tr>
          <td width="100" class="formNames">Notes:</td>
          <td class="cellColor"><?=$rs['notes']?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<?
	unset($rs);
}


/**
* Print out available times or current reservation's time
* This function will print out all available times to make
*  a reservation or will print out the selected reservation's time
*  (if this is a view).
* @param object $rs reservation object
* @param array $res resource data array
* @param string $classType reservation or blackout
* @global $conf
*/
function print_time_info(&$rs, &$res, $print_min_max = true) {
	global $conf;

	$type = $rs->get_type();
	$interval = $rs->sched['timeSpan'];
	$startDay = $rs->sched['dayStart'];
	$endDay	  = $rs->sched['dayEnd'];
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="1">
     <tr class="tableBorder">
      <td>
       <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
         <td colspan="3" class="cellColor">
         <h5 align='center'>
<?
         // Print message depending on viewing type
         switch($type) {
            case 'r' : $msg = 'Please select the starting and ending times:';
                break;
            case 'm' : $msg = 'Please change the starting and ending times:';
                break;
            default : $msg = 'Reserved time:';
                break;
        }
        echo $msg;
?>
        </h5>
        </td>
       </tr>
      <tr>
       <td width="25%" class="formNames"><?= CmnFns::formatDate($rs->get_date())?></td>
<?
        // Show reserved time or select boxes depending on type
        if ( ($type == 'r') || ($type == 'm') ) {
            // Start time select box
            echo '<td class="formNames">Start Time:'
                   . "<select name=\"startTime\" class=\"textbox\">\n";
            // Start at startDay time, end 30 min before endDay
            for ($i = $startDay; $i < $endDay; $i+=$interval) {
                echo '<option value="' . $i . '"';
                // If this is a modification, select corrent time
                if ( ($rs->get_start() == $i) )
                    echo ' selected="selected" ';
                echo '>' . CmnFns::formatTime($i) . '</option>';
            }
            echo "</select>\n</td>\n";

            // End time select box
            echo '<td class="formNames">End Time:'
                   . "<select name=\"endTime\" class=\"textbox\">\n";
            // Start at 30 after startDay time, end 30 at endDay time
            for ($i = $startDay+$interval; $i < $endDay+$interval; $i+=$interval) {
                echo "<option value=\"$i\"";
                // If this is a modification, select corrent time
                if ( ($rs->get_end() == $i) )
                    echo ' selected="selected" ';
                echo '>' . CmnFns::formatTime($i) . "</option>\n";
            }
            echo "</select>\n</td>\n";
			if ($print_min_max) {
				echo '</tr><tr class="cellColor">'
						. '<td colspan="3">Minimum Reservation Length: ' . CmnFns::minutes_to_hours($res['minRes'])
						. '<input type="hidden" name="minRes" value="' . $res['minRes'] . '" />'
						. '</td></tr>'
						. '<tr class="cellColor">'
						. '<td colspan="3">Maximum Reservation Length: ' . CmnFns::minutes_to_hours($res['maxRes'])
						. '<input type="hidden" name="maxRes" value="' . $res['maxRes'] . '" />'
						. '</td>';
			}
        }
        else {
            echo '<td class="formNames">Start Time:<br />' . CmnFns::formatTime($rs->get_start()) . "</td>\n"
			      . '<td class="formNames">End Time:<br />' . CmnFns::formatTime($rs->get_end()) . "</td>\n";

        }
        // Close off table
        echo "</tr>\n</table>\n</td>\n</tr>\n</table>\n<p>&nbsp;</p>\n";
		unset($rs);
}



/**
* Print out information about reservation's owner
* This function will print out information about
*  the selected reservation's user.
* @param string $type viewing type
* @param Object $user User object of this user
*/
function print_user_info($type, &$user) {
	if (!$user->is_valid()) {
		$user->get_error();
	}
	$user = $user->get_user_data();
?>
   <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr class="tableBorder">
     <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
        <td colspan="2" class="cellColor"><h5 align="center"><?=($type=='v' || $type=='d') ? 'Reserved for:' : 'Will be reserved for:'?></h5></td></tr>
       <tr>
        <td width="100" class="formNames">Name:</td>
         <td class="cellColor"><?= $user['fname'] . ' ' . $user['lname']?></td>
          </tr>
          <tr>
           <td width="100" class="formNames">Phone:</td>
           <td class="cellColor"><?= $user['phone']?></td>
          </tr>
          <tr>
           <td width="100" class="formNames">Email:</td>
           <td class="cellColor"><?= $user['email']?></td>
          </tr>
        </table>
      </td>
     </tr>
    </table>
    <p>&nbsp;</p>
    <?
	unset($user);
}


/**
* Print out created and modifed times in a table, if they exist
* @param int $c created timestamp
* @param int $m modified stimestamp
*/
function print_create_modify($c, $m) {
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr class="tableBorder">
     <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
       <td class="formNames">Created:</td>
       <td class="cellColor"><?= CmnFns::formatDateTime($c)?></td>
	   </tr>
       <tr>
       <td class="formNames">Modified:</td>
       <td class="cellColor"><?= !empty($m) ? CmnFns::formatDateTime($m) : 'N/A' ?></td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
   <p>&nbsp;</p>
<?
}

/**
* Prints out a checkbox to modify all recurring reservations associated with this one
* @param string $parentid id of parent reservation
*/
function print_recur_checkbox($parentid) {
	?>
	<p align="left"><input type="checkbox" name="mod_recur" value="<?=$parentid?>" />Update all recurring records in group?</p>
	<?
}

function print_del_checkbox() {
?>
	<p align="left"><input type="checkbox" name="del" value="true" />Delete?</p>
<?
}

/**
* Print out form buttons
* This function will prints out form buttons
*  depending on what type needs to be printed out
* @param string $type reservation viewing type
*/
function print_buttons($type) {
	// Print buttons depending on type
    echo '<p>';
	switch($type) {
  	    case 'm' :
            echo '<input type="submit" name="submit" value="Modify" class="button" />'
				. '<input type="hidden" name="fn" value="modify" />';
	    break;
        case 'd' :
            echo '<input type="submit" name="submit" value="Delete" class="button" />'
					. '<input type="hidden" name="fn" value="delete" />';
	    break;
        case 'v' :
            echo '<input type="button" name="close" value="Close Window" class="button" onclick="window.close();" /></p>';
	    break;
        case 'r' :
            echo '<input type="submit" name="submit" value="Save" class="button" />'
					. '<input type="hidden" name="fn" value="create" />';
        break;
    }
    // Print cancel button as long as type is not "view"
	if ($type != 'v')
		echo '&nbsp;&nbsp;&nbsp;<input type="button" name="close" value="Cancel" class="button" onclick="window.close();" /></p>';
}

/**
* Prints a box where users can select if they want
*  to repeat a reservation
* @param int $month month of current reservation
* @param int $year year of current reservation
*/
function print_repeat_box($month, $year) {
?>
<table width="200" border="0" cellspacing="0" cellpadding="0" class="recur_box">
  <tr>
    <td style="padding: 5px;">
	 <p style="margin-bottom: 8px;">
	  Repeat every:<br/> 
	  <select name="frequency" class="textbox">
	    <option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
	  </select>
      <select name="interval" class="textbox" onchange="javascript: showHideDays(this);">
	    <option value="none">-- Never --</option>
	    <option value="day">Days</option>
	    <option value="week">Weeks</option>
		<option value="month_date">Months (date)</option>
	    <option value="month_day">Months (day)</option>
      </select>
    </p>
	<div id="week_num" style="position: relative; visibility: hidden; overflow: show; display: none;">
	<p>
	<select name="week_number" class="textbox">
	  <option value="1">First Days</option>
	  <option value="2">Second Days</option>
	  <option value="3">Third Days</option>
	  <option value="4">Fourth Days</option>
	  <option value="last">Last Days</option>
	</select>
	</p>
	</div>
	<div id="days" style="position: relative; visibility: hidden; overflow: show; display: none;">
        <p style="margin-bottom: 8px;">
		Repeat on:<br/>
        <input type="checkbox" name="repeat_day[]" value="0" />Sun<br />
		<input type="checkbox" name="repeat_day[]" value="1" />Mon<br />
		<input type="checkbox" name="repeat_day[]" value="2" />Tue<br />
		<input type="checkbox" name="repeat_day[]" value="3" />Wed<br />
		<input type="checkbox" name="repeat_day[]" value="4" />Thu<br />
		<input type="checkbox" name="repeat_day[]" value="5" />Fri<br />
		<input type="checkbox" name="repeat_day[]" value="6" />Sat
        </p>
	</div>
	<div id="until" style="position: relative;">
		<p>
		Repeat until date:
		<input type="text" name="_repeat_until" disabled="disabled" value="Choose Date" class="textbox"/><input type="button" onclick="chooseDate('repeat_until', <?=$month . ', ' . $year?>);" value="..." />	
		<input type="hidden" name="repeat_until" value="" />
		</p>
	</div>
	</td>
  </tr>
</table>
<?
}

/**
* Print out the reservation summary or a box to add/edit one
* @param string $summary summary to edit
* @param string $type type of reservation
*/
function print_summary($summary, $type) {
?>
   <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr class="tableBorder">
     <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
	    <td class="cellColor"><h5 align="center">Summary</h5></td>
		</tr>
		<tr>
		<td class="cellColor" style="text-align: left;">
		<?
		if ($type == 'r' || $type == 'm')
			echo '<textarea class="textbox" name="summary" rows="3" cols="30">' . $summary . '</textarea>';
		else
			echo (!empty($summary) ? $summary : 'N/A');
		?>
		</td>
	   </tr>
      </table>
     </td>
    </tr>
   </table>
   <p>&nbsp;</p>
<?
}

/**
* Print hidden form fields
* This function will print hidden form fields
*  depending on viewing type
* @param Object $res this reservation
*/
function print_hidden_fields(&$res) {

    if ($res->get_type() == 'r') {
        echo '<input type="hidden" name="ts" value="' . $res->get_date() . '" />' . "\n"
              . '<input type="hidden" name="machid" value="' . $res->get_machid(). '" />' . "\n"
			  . '<input type="hidden" name="scheduleid" value="' . $res->sched['scheduleid'] . '" />' . "\n";
    }
    else {
        echo '<input type="hidden" name="resid" value="' . $res->get_id() . '" />' . "\n";
    }
	unset($res);
}


/**
* Opens form for reserve
* @param bool $show_repeat whether to show the repeat box
* @param bool $is_blackout if this is a blackout
*/
function begin_reserve_form($show_repeat, $is_blackout = false) {
	echo '<form name="reserve" method="post" action="' . $_SERVER['PHP_SELF'] . '?is_blackout=' . intval($is_blackout) . '" style="margin: 0px" onsubmit="return ' . (($show_repeat) ? 'check_reservation_form(this)' : 'check_for_delete(this)') . ';">' . "\n";
}

/**
* Closes reserve form
* @param none
*/
function end_reserve_form() {
	echo '</form>';
}

function start_left_cell() {
?>
<table width="100%" cellspacing="0" border="0" cellpadding="0">
<?
}

function divide_table() {
?>
</td><td style="vertical-align: top; padding-left: 15px;">
<?
}

function end_right_cell() {
?>
</td></tr>
</table>
<?
}
?>