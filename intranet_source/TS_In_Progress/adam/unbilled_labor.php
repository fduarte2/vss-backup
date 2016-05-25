<?
/*
*	Adam Walter, Nov 2011
*
*	Report that shows Labor tickets that aren't "Y" for a date range.
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

  // Connect to RF
  $conn = ora_logon("LABOR@LCS", "LABOR");
//  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  if(!$conn){
	echo "Can not connect to DB.  Please contact TS.";
	exit;
  }
   $cursor = ora_open($conn);
   $Short_Term_Cursor = ora_open($conn);

	
	$submit = $HTTP_POST_VARS['submit'];
	$start_date = $HTTP_POST_VARS['start_date'];
	$end_date = $HTTP_POST_VARS['end_date'];

	if($submit != ""){
		if ($start_date == "" || $end_date == ""){
			echo "<font color=\"#FF0000\">Both dates must be entered.  Please use your browser's \"Back\" button to return to the previous screen.</font>";
			exit;
		} elseif (!ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $start_date) || !ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $end_date)) {
			echo "<font color=\"#FF0000\">Dates (entered - Start: ".$start_date."  --- End: ".$end_date.") must be in MM/DD/YYYY Format.<br>Please use your browser's \"Back\" button to return to the previous screen.</font>";
			exit;
		}
	}

?>

<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Unbilled Labor Tickets
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="meh" action="unbilled_labor.php" method="post">
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Starting Date:  </font></td>
		<td><input type="text" name="start_date" size="15" maxlength="10" value="<? echo $start_date; ?>"><a href="javascript:show_calendar('meh.start_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Ending Date:  </font></td>
		<td><input type="text" name="end_date" size="15" maxlength="10" value="<? echo $end_date; ?>"><a href="javascript:show_calendar('meh.end_date');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Generate Unbilled Labor Ticket List"></td>
	</tr>
</form>
</table>


<?
	if($submit != ""){
		$filename = "./UnbilledTickets.xls";
		$handle = fopen($filename, "w");
		if (!$handle){
			echo "File ".$filename." could not be opened, please contact TS.\n";
			exit;
		}

		$output = "<table border=\"1\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">
						<tr>
							<td width=\"40%\" colspan=\"4\" align=\"center\">Start Date:".$start_date."</td>
							<td width=\"40%\" colspan=\"4\" align=\"center\">End Date:".$end_date."</td>
						</tr>";
//					AND (LTH.BILL_STATUS IS NULL OR LTH.BILL_STATUS != 'Y')
		$sql = "SELECT LTH.TICKET_NUM, LU.USER_NAME, CU.CUSTOMER_NAME THE_CUST, LTH.VESSEL_ID, LTH.COMMODITY_CODE, LTH.SERVICE_GROUP,
					TO_CHAR(LTH.SERVICE_DATE, 'MM/DD/YYYY') THE_DATE, DECODE(LTH.BILL_STATUS, 'U', 'Rejected', 'Not Billed') THE_STAT, JOB_DESCRIPTION
				FROM LABOR.LABOR_TICKET_HEADER LTH, LABOR.LCS_USER LU, LABOR.CUSTOMER CU
				WHERE LTH.USER_ID = LU.USER_ID
					AND LTH.SERVICE_DATE >= TO_DATE('".$start_date."', 'MM/DD/YYYY')
					AND LTH.SERVICE_DATE <= TO_DATE('".$end_date."', 'MM/DD/YYYY')
					AND (LTH.BILL_STATUS = 'N' OR LTH.BILL_STATUS = 'U' OR LTH.BILL_STATUS IS NULL)
					AND LTH.CUSTOMER_ID = CU.CUSTOMER_ID
				ORDER BY LTH.TICKET_NUM"; 
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$output .= "<tr><td colspan=\"8\" align=\"center\">No Unbillied Tickets for date range.</td></tr>";
		} else {
			$output .= "<tr>
							<td><font face=\"Verdana\" size=\"2\"><b>LT#</td>
							<td><font face=\"Verdana\" size=\"2\"><b>Supervisor</td>
							<td><font face=\"Verdana\" size=\"2\"><b>Customer</td>
							<td><font face=\"Verdana\" size=\"2\"><b>Labor<br>Ticket<br>Date</td>
							<td><font face=\"Verdana\" size=\"2\"><b>LR#</td>
							<td><font face=\"Verdana\" size=\"2\"><b>Commodity</td>
							<td><font face=\"Verdana\" size=\"2\"><b>Service Group</td>
							<td><font face=\"Verdana\" size=\"2\"><b>Ticket Status</td>
						</tr>";
			do {
				if($row['JOB_DESCRIPTION'] == "SECURITY"){
					$ticket_type = " (Security)";
				} else {
					$ticket_type = " (Labor)";
				}
				$output .= "<tr>
								<td><font face=\"Verdana\" size=\"2\"><b>".$row['TICKET_NUM']."</td>
								<td><font face=\"Verdana\" size=\"2\"><b>".$row['USER_NAME']."</td>
								<td><font face=\"Verdana\" size=\"2\"><b>".$row['THE_CUST']."</td>
								<td><font face=\"Verdana\" size=\"2\"><b>".$row['THE_DATE']."</td>
								<td><font face=\"Verdana\" size=\"2\"><b>".$row['VESSEL_ID']."</td>
								<td><font face=\"Verdana\" size=\"2\"><b>".$row['COMMODITY_CODE']."</td>
								<td><font face=\"Verdana\" size=\"2\"><b>".$row['SERVICE_GROUP'].$ticket_type."</td>
								<td><font face=\"Verdana\" size=\"2\"><b>".$row['THE_STAT']."</td>
							</tr>";
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$output .= "</table>";
		fwrite($handle, $output);
		fclose($handle);
		?> <p align="center"><a href="UnbilledTickets.xls">Right Click and choose 'Save As' for the XLS version of this list.</a> <?
		echo $output;
	}

include("pow_footer.php"); ?>