<?
/* Created July 2006, Adam Walter
*  
*  This report generates a page by which all Sbrocco "Swing Loads"
*  Are displayed.  A swing load is defined as a pallet that is
*  Received and shipped out on the same day, and were received
*  Via truck.
*
*  Sbrocco is customer 1913.
*
*  Trucked cargo is defined as vessel numbers longer than 4 characters.
************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Reband Report";
  $area_type = "ROOT";

  // Provides header / leftnav
  include("pow_header.php");

/* Pretty much all departments need and can see this report, disabling security check
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }
*/

  $today = date('m/d/Y h:i A');

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
  if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
  }
  $cursor = ora_open($conn);

	$date_start = $HTTP_POST_VARS['date_start'];
	$date_end = $HTTP_POST_VARS['date_end'];
	$submit = $HTTP_POST_VARS['submit'];
	$order_by = $HTTP_POST_VARS['order_by'];

	if($date_start == ""){
		$date_start = "none";
	}
	if($date_end == ""){
		$date_end = "none";
	}



?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Swing Load Detail
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
	if($submit == ""){
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="wha" action="swing_load.php" method="post">
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana">Begin Date:</td>
		<td><input type="text" name="date_start" size="10" maxlength="10"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana">End Date:</td>
		<td><input type="text" name="date_end" size="10" maxlength="10"></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td width="10%"><font size="2" face="Verdana">Order By:</td>
		<td><select name="order_by">
				<option value="pallet">Pallet ID</option>
				<option value="LR">Arrival (Truck) Number</option>
				<option value="date">Date Received</option>
			</select></td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="2">&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Run Swing Load Check"></td>
	</tr>
	<tr>
		<td colspan="3"><font size="2" face="Verdana">(Both date fields are optional.)</td>
	</tr>
</form>
</table>
<?
	} else {
		$sql = "SELECT CT.PALLET_ID THE_PALLET, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH:MI AM') DATE_IN, TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') DATE_OUT, CT.ARRIVAL_NUM THE_ARRIVAL FROM CARGO_TRACKING CT, CARGO_ACTIVITY CA
			WHERE LENGTH(CT.ARRIVAL_NUM) > 4
			AND CUSTOMER_ID = '1913'
			AND CT.PALLET_ID = CA.PALLET_ID
			AND SERVICE_CODE = '6'
			AND TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY') = TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') ";
		
			if($date_start != "none"){
				$sql .= "AND DATE_RECEIVED > TO_DATE('".$date_start."', 'MM/DD/YYYY') ";
			}
			if($date_end != "none"){
				$sql .= "AND DATE_RECEIVED < TO_DATE('".$date_end." 23:59', 'MM/DD/YYYY HH24:MI') ";
			}

			$sql .= "GROUP BY CT.PALLET_ID, DATE_RECEIVED, CT.ARRIVAL_NUM ";
			switch ($order_by){
				case "pallet":
					$sql .= "ORDER BY THE_PALLET, DATE_IN, THE_ARRIVAL";
					break;
				case "LR":
					$sql .= "ORDER BY THE_ARRIVAL, THE_PALLET, DATE_IN";
					break;
				case "date":
					$sql .= "ORDER BY DATE_IN, THE_ARRIVAL, THE_PALLET";
					break;
				default:
					break;
			}
//			echo $sql."<br>";
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td align="center"><font size="3" face="verdana"><b>Swing Load Pallets</b></font></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="verdana"><b>Begin date:  <? echo $date_start; ?></b></font></td>
	</tr>
	<tr>
		<td align="center"><font size="2" face="verdana"><b>End date:  <? echo $date_end; ?></b></font></td>
	</tr>
	<tr>
		<td>
		<table border="1" width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td><font size="2" face="Verdana">Pallet ID</font></td>
				<td><font size="2" face="Verdana">Date/Time IN</font></td>
				<td><font size="2" face="Verdana">Date/Time OUT</font></td>
				<td><font size="2" face="Verdana">Arrival (Truck) Number</font></td>
			</tr>
<?
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td colspan="4"><font size="3" face="Verdana" color="#FF0000">No swingload Pallets for the chosen date range</font></td>
			</tr>
<?
		} else {
			do {
?>
			<tr>
				<td><font size="2" face="Verdana"><? echo $row['THE_PALLET']; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $row['DATE_IN']; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $row['DATE_OUT']; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $row['THE_ARRIVAL']; ?></font></td>
			</tr>
<?
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
?>
		</table>
		</td>
	</tr>
</table>
<?
	}
	include("pow_footer.php");
?>