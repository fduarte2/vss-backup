<?
/* Created Adam Walter, May 2006.
*  This program functions to generate counters for the finance department
*  used in the calculations of pallet, weight, and carton counts
*  to determine the amount to be charged to PacSeaways for services.
*  SQL statements under logical development
*
* Of note is that Lauritzen ships are VESSEL_OPERATOR_ID number 6564, and
* as they need to be excluded from 2 of the counts, you'll find mentions
* of this number in the first 2 SQL statements.
*/
    
  
  
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "RF Reports";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

  $Start_Date = $HTTP_POST_VARS[start_date];
  $total_lbs_wharfage = 0;
  $total_pallets_wharfage = 0;
  $total_lbs_backhaul = 0;
  $total_pallets_backhaul = 0;
  $total_lbs_truckloading = 0;
  $total_pallets_truckloading = 0;
  $total_cartons_terminalservice = 0;
  $today = date('m/d/Y');
?>


<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Summary of Counter Values
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
// starting here, information will only conditionally display on page if date is already chosen.
	if($Start_Date != ""){
		$conn = ora_logon("SAG_Owner@BNI", "SAG");
		if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			printf("</body></html>");
			exit;
		}

// one item at a time.  First, wharfage:
		$main_data_cursor = ora_open($conn);
		$sql = "SELECT SUM(CARGO_WEIGHT) WEIGHT, SUM(QTY_EXPECTED) PALLETS FROM CARGO_MANIFEST CM, VOYAGE V WHERE CM.LR_NUM = V.LR_NUM AND CM.COMMODITY_CODE IN ('5100', '5101', '5103') AND V.DATE_FINISHED > to_date('$Start_Date', 'MM/DD/YYYY') AND V.VESSEL_OPERATOR_ID <> '6564'";
		$statement = ora_parse($main_data_cursor, $sql);
		ora_exec($main_data_cursor);
        ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$total_lbs_wharfage = $main_data_row['WEIGHT'];
		$total_pallets_wharfage = $main_data_row['PALLETS'];
?>
	<tr>
		<td width="25%" align="left"><a href="Wharfage_Volumes.php?start_date=<? echo $Start_Date; ?>">Wharfage Counter:</a></td>
		<td colspan="3" align="left">&nbsp;</td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana" align="right">Pallets:</font></td>
		<td colspan="3" align="left"><font size="2" face="Verdana" align="right"><? echo $total_pallets_wharfage; ?></font></td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana" align="right">Lbs:</font></td>
		<td colspan="3" align="left"><font size="2" face="Verdana" align="right"><? echo round($total_lbs_wharfage, 2); ?></font></td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana" align="right">Tons:</font></td>
		<td colspan="3" align="left"><font size="2" face="Verdana" align="right"><? echo round(($total_lbs_wharfage / 2000), 2); ?></font></td>
	</tr>

<?
// 2nd, Backhaul.  same as wharfage, but to format on the web page, I run it again.
		$sql = "SELECT SUM(CARGO_WEIGHT) WEIGHT, SUM(QTY_EXPECTED) PALLETS FROM CARGO_MANIFEST CM, VOYAGE V WHERE CM.LR_NUM = V.LR_NUM AND CM.COMMODITY_CODE IN ('5100', '5101', '5103') AND V.VESSEL_OPERATOR_ID <> '6564' AND V.DATE_FINISHED > to_date('$Start_Date', 'MM/DD/YYYY')";
		$statement = ora_parse($main_data_cursor, $sql);
		ora_exec($main_data_cursor);
        ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$total_lbs_backhaul = $main_data_row['WEIGHT'];
		$total_pallets_backhaul = $main_data_row['PALLETS'];

?>
	<tr>
		<td width="25%" align="left"><a href="Backhaul_Volumes.php?start_date=<? echo $Start_Date; ?>">Backhaul Counter:</a></td>
		<td colspan="3" align="left">&nbsp;</td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana" align="right">Pallets:</font></td>
		<td colspan="3" align="left"><font size="2" face="Verdana" align="right"><? echo $total_pallets_backhaul; ?></font></td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana" align="right">Lbs:</font></td>
		<td colspan="3" align="left"><font size="2" face="Verdana" align="right"><? echo round($total_lbs_backhaul, 2); ?></font></td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana" align="right">Tons:</font></td>
		<td colspan="3" align="left"><font size="2" face="Verdana" align="right"><? echo round(($total_lbs_backhaul / 2000), 2); ?></font></td>
	</tr>
<?
// 3rd, Truck Loading
		$sql = "SELECT SUM(CARGO_WEIGHT) WEIGHT, SUM(QTY_EXPECTED) PALLETS FROM CARGO_MANIFEST CM, VOYAGE V WHERE CM.LR_NUM = V.LR_NUM AND CM.COMMODITY_CODE IN ('5100', '5101', '5901', '5900') AND V.DATE_FINISHED > to_date('$Start_Date', 'MM/DD/YYYY')";
		$statement = ora_parse($main_data_cursor, $sql);
		ora_exec($main_data_cursor);
        ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$total_lbs_truckloading = $main_data_row['WEIGHT'];
		$total_pallets_truckloading = $main_data_row['PALLETS'];					
?>
	<tr>
		<td width="25%" align="left"><a href="Truck_Loading_Volumes.php?start_date=<? echo $Start_Date; ?>">Truck Loading Counter:</a></td>
		<td colspan="3" align="left">&nbsp;</td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana" align="right">Pallets:</font></td>
		<td colspan="3" align="left"><font size="2" face="Verdana" align="right"><? echo $total_pallets_truckloading; ?></font></td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana" align="right">Lbs:</font></td>
		<td colspan="3" align="left"><font size="2" face="Verdana" align="right"><? echo round($total_lbs_truckloading, 2); ?></font></td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana" align="right">Tons:</font></td>
		<td colspan="3" align="left"><font size="2" face="Verdana" align="right"><? echo round(($total_lbs_truckloading / 2000), 2); ?></font></td>
	</tr>
<?
// 4th, Terminal Service Charges
		$sql = "SELECT SUM(QTY2_EXPECTED) CARTONS FROM CARGO_MANIFEST CM, VOYAGE V WHERE CM.LR_NUM = V.LR_NUM AND CM.COMMODITY_CODE IN ('5100', '5101', '5900', '5901') AND V.DATE_FINISHED > to_date('$Start_Date', 'MM/DD/YYYY')";
		$statement = ora_parse($main_data_cursor, $sql);
		ora_exec($main_data_cursor);
        ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$total_cartons_terminalservice = $main_data_row['CARTONS'];				

?>
	<tr>
		<td width="25%" align="left"><a href="Terminal_Service_Volumes.php?start_date=<? echo $Start_Date; ?>">Terminal Service Counter:</a></td>
		<td colspan="3" align="left">&nbsp;</td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana" align="right">Cartons:</font></td>
		<td colspan="3" align="left"><font size="2" face="Verdana" align="right"><? echo $total_cartons_terminalservice; ?></font></td>
	</tr>


	<tr>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
<?
	} else {
// if this is the first time to the page, ask for a date.
?>



	<tr>
		<td colspan="4">
			<font size="2" face="Verdana"><b>Please select starting date.</b></font>
		</td>
	</tr>
	<tr>
		<td colspan="4">
		<form action="Finance_Counters.php" method="post" name="scan">
			&nbsp;&nbsp;&nbsp;Start Date: <input type="textbox" name="start_date" size=10 value=""><a href="javascript:show_calendar('scan.start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0>
		</td>
	</tr>
	<tr>
		<td colspan = "4" align="left">&nbsp;&nbsp;&nbsp;<input type="submit" value="submit"></td>
	</tr>
<?   }   ?>
</table>










<? include("pow_footer.php"); ?>