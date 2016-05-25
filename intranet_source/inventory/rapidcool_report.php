<?


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System - Walmart";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$the_date = $HTTP_POST_VARS['the_date'];
	$chamber = $HTTP_POST_VARS['chamber'];

?>
<script language="JavaScript" src="../functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Rapid Cool Report</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form action="rapidcool_report.php" method="post" name="generate">
	<tr>
		<td><font size="2" face="Verdana"><b>Date:</b></font></td>
		<td><font size="2" face="Verdana"><input name="the_date" type="text" size="10" maxlength="10" value="<? echo $the_date; ?>"></font><a href="javascript:show_calendar('generate.the_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td width="20%"><font size="2" face="Verdana"><b>Chamber</b></font></td>
		<td><select name="chamber"><option value="">All</option>
							<option value="1" <? if($chamber == 1){?> selected <?}?>>1</option>
							<option value="2" <? if($chamber == 2){?> selected <?}?>>2</option>
							<option value="3" <? if($chamber == 3){?> selected <?}?>>3</option>
							<option value="4" <? if($chamber == 4){?> selected <?}?>>4</option>
					</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Generate Report"></td>
	</tr>
</form>
</table>
<?
	if($submit != ""){
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>LR</b></font></td>
		<td><font size="2" face="Verdana"><b>Transfer Time</b></font></td>
		<td><font size="2" face="Verdana"><b>Description</b></font></td>
		<td><font size="2" face="Verdana"><b>Qty</b></font></td>
		<td><font size="2" face="Verdana"><b>From Location</b></font></td>
	</tr>
<?
	$sql = "SELECT PALLET_ID, CUSTOMER_NAME, ARRIVAL_NUM, TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY HH:MI AM') THE_TIME, 
				QTY_LEFT, ACTIVITY_DESCRIPTION, DATE_OF_ACTIVITY, FROM_LOCATION 
			FROM CARGO_ACTIVITY CA, CUSTOMER_PROFILE CP
			WHERE SERVICE_CODE = '23'
			AND CA.CUSTOMER_ID = CP.CUSTOMER_ID
			AND TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$the_date."'
			AND (DATE_OF_ACTIVITY) = 
				(SELECT MIN(DATE_OF_ACTIVITY)
						FROM CARGO_ACTIVITY CA2
						WHERE CA2.PALLET_ID = CA.PALLET_ID
							  AND CA2.ARRIVAL_NUM = CA.ARRIVAL_NUM
							  AND CA2.CUSTOMER_ID = CA.CUSTOMER_ID
							  AND CA2.SERVICE_CODE = '23'
							  AND TO_CHAR(CA2.DATE_OF_ACTIVITY, 'MM/DD/YYYY') = '".$the_date."')";
			if($chamber != ""){
				$sql .= " AND ACTIVITY_DESCRIPTION LIKE '%CR".$chamber."'";
			}
			$sql .= " ORDER BY ACTIVITY_DESCRIPTION, DATE_OF_ACTIVITY";
	ora_parse($cursor, $sql);
	ora_exec($cursor, $sql);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
	<tr>
		<td colspan="6"><font size="3" face="Verdana"><b>No Pallets matching Search Criteria</b></font></td>
	</tr>
<?
		} else {
			$plts = 0;
			do {
?>
	<tr>
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['CUSTOMER_NAME']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['THE_TIME']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ACTIVITY_DESCRIPTION']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['QTY_LEFT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['FROM_LOCATION']; ?></font></td>
	</tr>
<?
				$plts++;
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	<tr>
		<td colspan="7"><font size="2" face="Verdana"><b>Total Plts:  <? echo $plts; ?></font></td>
	</tr>
<?
		}
	}
	include("pow_footer.php");
?>