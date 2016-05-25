<?php
/**
* This file provides output functions for ctrlpnl.php
* No data manipulation is done in this file
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @author Adam Moore
* @version 04-28-04
* @package Templates
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

// Get Link object
$link = CmnFns::getNewLink();


/**
* This function prints out the announcement table
*
* @param none
* @global $conf
*/
function showAnnouncementTable() {
	global $link;
	global $conf;
    ?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td class="tableTitle">
		    <a href="javascript: void(0);" onclick="showHideCpanelTable('announcements');">&#8250; My Announcements</a>
		  </td>
          <td class="tableTitle">
            <div align="right">
              <? $link->doLink("javascript: help('my_announcements');", '?', '', 'color: #FFFFFF;', 'Help: My Announcements') ?>
            </div>
          </td>
        </tr>
      </table>
      <div id="announcements" style="display: <?= getShowHide('announcements') ?>">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr class="cellColor">
          <td colspan="2">
            <?= '<b>Announcements as of ' . date('m/d/y') . ':</b>' ?>
            <ul style="margin-bottom: 0px; margin-left: 20px; margin-top: 5px">
              <?
				// Cycle through and print out machines
				if (sizeof($conf['ui']['announcement'])<=0) {
					echo "<li>There are no announcements.</li>\n";
				}
				for ($i = count($conf['ui']['announcement'])-1; $i >=0; $i--) {
					if (isset($conf['ui']['announcement'][$i]))
						echo '<li>' . htmlspecialchars($conf['ui']['announcement'][$i]) . '</li>';
				}
				?>
            </ul>
          </td>
        </tr>     
      </table>
	 </div>
    </td>
  </tr>
</table>
<?
}


/**
* Print table listing upcoming reservations
* This function prints a table of all upcoming
* reservations for the current user.  It also
* provides a way for them to modify and delete
* their reservations
* @param mixed $res array of reservation data
* @param string $err last error message from database
*/
function showReservationTable($res, $err) {
	global $link;
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td colspan="7" class="tableTitle">
		    <a href="javascript: void(0);" onclick="showHideCpanelTable('reservation');">&#8250; My Reservations</a>
		  </td>
          <td class="tableTitle">
            <div align="right">
              <? $link->doLink("javascript: help('my_reservations');", '?', '', 'color: #FFFFFF;', 'Help: My Reservations') ?>
            </div>
          </td>
        </tr>
      </table>
      <div id="reservation" style="display: <?= getShowHide('reservation') ?>">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr class="rowHeaders">
          <td width="10%">Date</td>
          <td width="18%">Resource</td>
          <td width="9%">Start Time</td>
          <td width="9%">End Time</td>
          <td width="20%">Created</td>
          <td width="20%">Last Modified</td>
          <td width="7%">Modify</td>
          <td width="7%">Delete</td>
        </tr>
        <tr class="cellColor" style="text-align: center">
          <td>
            <? $link->doLink($_SERVER['PHP_SELF']."?order=date&amp;vert=DESC", "[&#8211;]", "", "", "Sort by descending date") ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF']."?order=date&amp;vert=ASC", "[+]", "", "", "Sort by ascending date") ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF']."?order=name&amp;vert=DESC", "[&#8211;]", "", "", "Sort by descending resource name") ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF']."?order=name&amp;vert=ASC", "[+]", "", "", "Sort by ascending resource name") ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF']."?order=startTime&amp;vert=DESC", "[&#8211;]", "", "", "Sort by descending start time") ?>
			&nbsp;&nbsp;
            <?
			$link->doLink($_SERVER['PHP_SELF']."?order=startTime&amp;vert=ASC", "[+]", "", "", "Sort by ascending start time") ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF']."?order=endTime&amp;vert=DESC", "[&#8211;]", "", "", "Sort by descending end time") ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF']."?order=endTime&amp;vert=ASC", "[+]", "", "", "Sort by ascending end time") ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF']."?order=created&amp;vert=DESC", "[&#8211;]", "", "", "Sort by descending created time") ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF']."?order=created&amp;vert=ASC", "[+]", "", "", "Sort by ascending created time") ?>
          </td>
          <td>
            <?
			$link->doLink($_SERVER['PHP_SELF']."?order=modified&amp;vert=DESC", "[&#8211;]", "", "", "Sort by descending last modified time") ?>
			&nbsp;&nbsp;
            <?
			$link->doLink($_SERVER['PHP_SELF']."?order=modified&amp;vert=ASC", "[+]", "", "", "Sort by ascending last modified time") ?>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <?  	
    
	// Write message if they have no reservations	
	if (!$res)
		echo '        <tr class="cellColor"><td colspan="8" align="center">' . $err . '</td></tr>';

    // For each reservation, clean up the date/time and print it
	for ($i = 0; is_array($res) && $i < count($res); $i++) {
		$rs = $res[$i];	
		$class = 'cellColor' . ($i%2);
        $modified = (isset($rs['modified']) && !empty($rs['modified'])) ?
		CmnFns::formatDateTime($rs['modified']) : 'N/A';
        echo "        <tr class=\"$class\" align=\"center\">"
					. '          <td>' . $link->getLink("javascript: reserve('v','','','" . $rs['resid']. "');", CmnFns::formatDate($rs['date']), '', '', 'View this reservation') . '</td>'
					. '          <td style="text-align:left;">' . $rs['name'] . '</td>'
					. '          <td>' . CmnFns::formatTime($rs['startTime']) . '</td>'
					. '          <td>' . CmnFns::formatTime($rs['endTime']) . '</td>'
                    . '          <td>' . CmnFns::formatDateTime($rs['created']) . '</td>'
                    . '          <td>' . $modified . '</td>'
					. '          <td>' . $link->getLink("javascript: reserve('m','','','" . $rs['resid'] . "');", 'Modify', '', '', 'Modify this reservation') . '</td>'
					. '          <td>' . $link->getLink("javascript: reserve('d','','','" . $rs['resid'] . "');", 'Delete', '', '', 'Delete this reservation') . '</td>'
					. "        </tr>\n";			
	}
	unset($res);
?> 
      </table>
	  </div>
    </td>
  </tr>
</table>
<?
}


/**
* Print table with all user training information
* @param mixed $per permissions array
* @param string $err last database error 
*/
function showTrainingTable($per, $err) {
	global $link;
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td class="tableTitle" colspan="3">
		    <a href="javascript: void(0);" onclick="showHideCpanelTable('permissions');">&#8250; My Permissions</a>
		  </td>
          <td class="tableTitle">
            <div align="right">
              <? $link->doLink("javascript: help('my_training');", '?', '', 'color: #FFFFFF', 'Help: My Permissions') ?>
            </div>
          </td>
        </tr>
      </table>
      <div id="permissions" style="display: <?= getShowHide('permissions') ?>;">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr class="rowHeaders">
          <td width="20%">Resource</td>
          <td width="25%">Location</td>
          <td width="15%">Phone</td>
          <td width="40%">Notes</td>
        </tr>
        <?
	// If they have no training, inform them
	if (!$per)
		echo '<tr><td colspan="4" class="cellColor" align="center">' . $err . '</td></tr>';
	
	// Cycle through and print out machines
    for ($i = 0; is_array($per) && $i < count($per); $i++) {
		$rs = $per[$i];	
		$class = 'cellColor' . ($i%2);
		echo "<tr class=\"$class\">\n"
            . '<td>' . $rs['name'] . '</td>'
			. '<td>' . $rs['location'] . '</td>'
			. '<td>' . $rs['rphone'] . '</td>'
            . '<td>' . $rs['notes'] . '</td>'
			. "</tr>\n";
	}
	unset($per);
    ?>
      </table>
	  </div>
    </td>
  </tr>
</table>
<?
}


/**
* Print out a table of links for user or administrator
* This function prints out a table of links to
* other parts of the system.  If the user is an admin,
* it will print out links to administrative pages, also
* @param none
*/
function showQuickLinks() {
	global $conf;
	global $link;
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td class="tableTitle">
		    <a href="javascript: void(0);" onclick="showHideCpanelTable('quicklinks');">&#8250; My Quick Links</a>
		  </td>
          <td class="tableTitle"><div align="right">
              <? $link->doLink("javascript: help('quick_links');", '?', '', 'color: #FFFFFF', 'Help: My Quick Links') ?>
            </div>
          </td>
        </tr>
      </table>
      <div id="quicklinks" style="display: <?= getShowHide('quicklinks') ?>;">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr style="padding: 5px" class="cellColor">
          <td colspan="2">
            <p><b>&raquo;</b>
              <? $link->doLink('schedule.php', 'Go to the Online Scheduler') ?>
            </p>
            <p><b>&raquo;</b>
              <? $link->doLink('register.php?edit=true', 'Change My Profile Information/Password') ?>
            </p>
            <p><b>&raquo;</b>
              <? $link->doLink('my_email.php', 'Manage My Email Preferences') ?>
            </p>
            <?
		// If it's the admin, print out admin links
		if (Auth::isAdmin()) {
			echo 
				  '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=schedules', 'Manage Schedules') . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=resources', 'Manage Resources') . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=users', 'Manage Users') . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=reservations', 'Manage Reservations') . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('blackouts.php', 'Manage Blackout Times') . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=email', 'Mass Email Users') . "</p>\n"
                . '<p><b>&raquo;</b> ' .  $link->getLink('usage.php', 'Search Scheduled Resource Usage') . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=export', 'Export Database Content') . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('stats.php', 'View System Stats') . "</p>\n";
		}
		?>
            <p><b>&raquo;</b>
              <? $link->doLink('mailto:' . $conf['app']['adminEmail'].'?cc=' . $conf['app']['ccEmail'], 'Email Administrator', '', '', 'Send a non-technical email to the administrator') ?>
            </p>
            <p><b>&raquo;</b>
              <? $link->doLink('index.php?logout=true', 'Log Out') ?>
            </p>
          </td>
        </tr>
      </table>
	  </div>
    </td>
  </tr>
</table>
<?
}

/**
* Print out break to be used between tables
* @param none
*/
function printCpanelBr() {
	echo '<p>&nbsp;</p>';
}

/**
* Returns the proper expansion type for this table
*  based on cookie settings
* @param string table name of table to check
* @return either 'block' or 'none'
*/
function getShowHide($table) {
	if (isset($_COOKIE[$table]) && $_COOKIE[$table] == 'hide') {
		return 'none';
	}
	else
		return 'block';
}
?>
