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
  $total_cartons = 0;
  $current_cartons = 0;
  $over3milby = 0;
  $over3milprinted = FALSE;
  $over4milby = 0;
  $over4milprinted = FALSE;
  $over5milby = 0;
  $over5milprinted = FALSE;
  $over9point6milby = 0;
  $over9point6milprinted = FALSE;
  $today = date('m/d/Y');
?>


<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Total Values From Manifest for Truck Loading
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

		$main_data_cursor = ora_open($conn);
		$vessel_name_cursor = ora_open($conn);
		$sql = "SELECT CM.LR_NUM VESSEL, SUM(QTY2_EXPECTED) CARTONS FROM CARGO_MANIFEST CM, VOYAGE V WHERE CM.LR_NUM = V.LR_NUM AND CM.COMMODITY_CODE IN ('5100', '5101', '5900', '5901') AND V.DATE_FINISHED > to_date('$Start_Date', 'MM/DD/YYYY') GROUP BY CM.LR_NUM ORDER BY CM.LR_NUM";
		$statement = ora_parse($main_data_cursor, $sql);
		ora_exec($main_data_cursor);

		while(ora_fetch_into($main_data_cursor, $main_data_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = ".$main_data_row['VESSEL'];
			$statement = ora_parse($vessel_name_cursor, $sql);
			ora_exec($vessel_name_cursor);
			ora_fetch_into($vessel_name_cursor, $vessel_name_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$current_cartons = $main_data_row['CARTONS'];
?>
	<tr>
		<td><b>Vessel:&nbsp;</b></td>
		<td colspan="3"><b><? echo $vessel_name_row['VESSEL_NAME']; ?></b></td>
	</tr>
	<tr>
		<td width="25%"><font size="2" face="Verdana">Cartons:</font></td>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td width="25%" align="left"><font size="2" face="Verdana"><? echo $current_cartons; ?></font></td>
		<td colspan="3">&nbsp;</td>
	</tr>
<?
			if($total_cartons + $current_cartons > 3000000){
				$over3milby = ($total_cartons + $current_cartons) - 3000000;
			}
			if($total_cartons + $current_cartons > 4000000){
				$over4milby = ($total_cartons + $current_cartons) - 4000000;
			}
			if($total_cartons + $current_cartons > 5000000){
				$over5milby = ($total_cartons + $current_cartons) - 5000000;
			}
			if($total_cartons + $current_cartons > 9600000){
				$over9point6milby = ($total_cartons + $current_cartons) - 9600000;
			}
			$total_cartons += $current_cartons;
?>
	<tr>
		<td colspan="4"><font size="2" face="Verdana"><b>Running Total:</b></font></td>
	</tr>
	<tr>
		<td width="25%" align="right"><font size="2" face="Verdana"><b><? echo $total_cartons; ?></b></font></td>
		<td colspan="3">&nbsp;</td>
	</tr>
<?
			if($over3milby != 0 && $over3milprinted == FALSE){				
?>
	<tr>
		<td colspan="2" align="right"><font size="2" face="Verdana"><b>3 million Cartons passed.  <? echo $over3milby; ?> cartons over the 3 million mark.</b></font></td>
		<td colspan="2">&nbsp;</td>
	</tr>
<?
			$over3milprinted = TRUE;
			}
			if($over4milby != 0 && $over4milprinted == FALSE){				
?>
	<tr>
		<td colspan="2" align="right"><font size="2" face="Verdana"><b>4 million Cartons passed.  <? echo $over4milby; ?> cartons over the 4 million mark.</b></font></td>
		<td colspan="2">&nbsp;</td>
	</tr>
<?
			$over4milprinted = TRUE;
			}
			if($over5milby != 0 && $over5milprinted == FALSE){				
?>
	<tr>
		<td colspan="2" align="right"><font size="2" face="Verdana"><b>5 million Cartons passed.  <? echo $over5milby; ?> cartons over the 5 million mark.</b></font></td>
		<td colspan="2">&nbsp;</td>
	</tr>
<?
			$over5milprinted = TRUE;
			}
			if($over9point6milby != 0 && $over9point6milprinted == FALSE){				
?>
	<tr>
		<td colspan="2" align="right"><font size="2" face="Verdana"><b>9.6 million Cartons passed.  <? echo $over9point6milby; ?> cartons over the 9.6 million mark.</b></font></td>
		<td colspan="2">&nbsp;</td>
	</tr>
<?
			$over9point6milprinted = TRUE;
			}
		}
?>
	<tr>
	</tr>
	<tr>
	</tr>
<?
	} else {
// if this page is reached by methods other than the link from Finance_counters.php, this will show.
?>



	<tr>
		<td colspan="4">
			<font size="2" face="Verdana"><b>Please select starting date.</b></font>
		</td>
	</tr>
	<tr>
		<td colspan="4">
		<form action="Billable_Volumes.php" method="post" name="scan">
			&nbsp;&nbsp;&nbsp;Start Date: <input type="textbox" name="start_date" size=10 value=""><a href="javascript:show_calendar('scan.start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0>
		</td>
	</tr>
	<tr>
		<td colspan = "4" align="left">&nbsp;&nbsp;&nbsp;<input type="submit" value="submit"></td>
	</tr>
<?   }   ?>
</table>










<? include("pow_footer.php"); ?>