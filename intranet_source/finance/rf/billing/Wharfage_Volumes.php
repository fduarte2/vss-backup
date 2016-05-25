<?
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

  $Start_Date = $HTTP_GET_VARS[start_date];
  $total_lbs = 0;
  $total_pallets = 0;
  $current_vessel = 0;
  $current_lbs = 0;
  $over30000by = 0;
  $over30Kprinted = FALSE;
  $overratio = 0;
  $today = date('m/d/Y');
?>


<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Total Values From Manifest for Wharfage
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<?
// starting here, information will only conditionally display on page if date is already chosen.
// if no date is selected (I.E. user didnt come here via link), page will be blank.
	if($Start_Date != ""){
		$conn = ora_logon("SAG_Owner@BNI", "SAG");
		if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			printf("</body></html>");
			exit;
		}

		$main_data_cursor = ora_open($conn);
		$vessel_name_cursor = ora_open($conn);
		$sql = "SELECT CM.LR_NUM VESSEL, SUM(CARGO_WEIGHT) WEIGHT, SUM(QTY_EXPECTED) PALLETS FROM CARGO_MANIFEST CM, VOYAGE V WHERE CM.LR_NUM = V.LR_NUM AND CM.COMMODITY_CODE IN ('5100', '5101', '5103') AND V.VESSEL_OPERATOR_ID <> '6564' AND V.DATE_FINISHED > to_date('$Start_Date', 'MM/DD/YYYY') GROUP BY CM.LR_NUM ORDER BY CM.LR_NUM";
		$statement = ora_parse($main_data_cursor, $sql);
		ora_exec($main_data_cursor);

		while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$main_data_row['VESSEL'];
			$statement = ora_parse($vessel_name_cursor, $sql);
			ora_exec($vessel_name_cursor);
			ora_fetch_into($vessel_name_cursor, $vessel_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$current_pallets = $main_data_row['PALLETS'];
			$current_lbs = $main_data_row['WEIGHT'];
?>
	<tr>
		<td><b>Vessel:&nbsp</b></td>
		<td colspan="3"><b><? echo $vessel_name_row['VESSEL_NAME']; ?></b></td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana">Pallets:</font></td>
		<td width="25%"><font size="2" face="Verdana">Lbs:</font></td>
		<td width="25%"><font size="2" face="Verdana">Tons:</font></td>
		<td></td>
	</tr>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana"><? echo $current_pallets; ?></font></td>
		<td width="25%" align="left"><font size="2" face="Verdana"><? echo round($current_lbs, 2); ?></font></td>
		<td width="25%" align="left"><font size="2" face="Verdana"><? echo round(($current_lbs / 2000), 2); ?></font></td>
		<td></td>
	</tr>
<?
			if($total_pallets + $current_pallets > 30000){
				$over30000by = ($total_pallets + $current_pallets) - 30000;
				$overratio = $over30000by / $current_pallets;
			}
			$total_pallets += $current_pallets;
			$total_lbs += $current_lbs;
?>
	<tr>
		<td colspan="4"><font size="2" face="Verdana"><b>Running Total:</b></font></td>
	</tr>
	<tr>
		<td width="25%" align="right"><font size="2" face="Verdana"><b><? echo $total_pallets; ?></b></font></td>
		<td width="25%" align="right"><font size="2" face="Verdana"><b><? echo round($total_lbs, 2); ?></b></font></td>
		<td width="25%" align="right"><font size="2" face="Verdana"><b><? echo round(($total_lbs / 2000), 2); ?></b></font></td>
		<td></td>
	</tr>
<?
			if($over30000by != 0 && $over30Kprinted == FALSE){				
?>
	<tr>
		<td colspan="2" align="right"><font size="2" face="Verdana"><b>30,000 Pallets passed.  <? echo $over30000by; ?> pallets over the 30,000 mark.</b></font></td>
		<td align="right"><font size="2" face="Verdana"><b>Ton overage from previous ship:</b></font></td>
		<td align="left"><b>&nbsp&nbsp<? echo round((($current_lbs * $overratio) / 2000), 2); ?></b></td>
	</tr>
<?
			$over30Kprinted = TRUE;
			}
		}
?>
	<tr>
	</tr>
	<tr>
	</tr>
<?
	}
?>
</table>










<? include("pow_footer.php"); ?>