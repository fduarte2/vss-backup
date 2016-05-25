<?
/* Adam Walter, March 2007.
*  A page to count all pallets in warehouse A2
*  And determine their billed amounts
*  For use by finance due to some new contract
*
****************************************************/
  
  // All POW files need this session file included
  include("pow_session.php");
/*
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $BNIcursor = ora_open($conn);
*/
  $RFconn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($RFconn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($RFconn));
      	exit;
  }
  $RFcursor = ora_open($RFconn);
  $RFDataCursor = ora_open($RFconn);

  $submit = $HTTP_POST_VARS['submit'];
  $dateStart = $HTTP_POST_VARS['dateStart'];
  $dateEnd = $HTTP_POST_VARS['dateEnd'];
  $BadStartDate = 0;
  $BadEndDate = 0;

  $vessel_total = 0;
  $customer_total = 0;
  $overall_total = 0;

  if(!ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $dateStart) && $submit == 'submit'){
   $BadStartDate = 1;
   $BadDate = 1;
  }

  if(!ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $dateEnd) && $submit == 'submit'){
   $BadEndDate = 1;
   $BadDate = 1;
  }

   if(ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $dateStart)){
	   $temp = split("/", $dateStart);
//	   $oraStartDate = date("d-M-Y", mktime(0,0,0,$temp[0],$temp[1],$temp[2]));
   }

   if(ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $dateEnd)){
	   $temp = split("/", $dateEnd);
//	   $oraEndDate = date("d-M-Y", mktime(0,0,0,$temp[0],$temp[1]+1,$temp[2])); // +1 so the less than operator works later
   }

if($submit != 'submit' || $BadDate == 1){

  // Define some vars for the skeleton page
  $title = "A2 Report";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }
?>

<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Warehouse A2 Details
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="the_form" action="A2.php" method="post">
	<tr>
		<td width="1%">&nbsp;</td>
		<td colspan="3"><font size="3" face="Verdana">Please choose a date range:</font></td>
	</tr>
	<tr>
		<td colspan="2" width="3%">&nbsp;</td>
		<td width="15%"><font size="2" face="Verdana">Start Date:</font></td>
		<td><input type="textbox" name="dateStart" size="15" maxlength="20" value="<? echo $dateStart; ?>"><a href="javascript:show_calendar('the_form.dateStart');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a><? if($BadStartDate == 1 && $submit == 'submit'){ ?>&nbsp;&nbsp;&nbsp;<font color="ff0000" size="2">MM/DD/YYYY format please</font><? } ?></td>
	</tr>
	<tr>
		<td colspan="2" width="3%">&nbsp;</td>
		<td width="15%"><font size="2" face="Verdana">End Date:</font></td>
		<td><input type="textbox" name="dateEnd" size="15" maxlength="20" value="<? echo $dateEnd; ?>"><a href="javascript:show_calendar('the_form.dateEnd');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a><? if($BadEndDate == 1 && $submit == 'submit'){ ?>&nbsp;&nbsp;&nbsp;<font color="ff0000" size="2">MM/DD/YYYY format please</font><? } ?></td>
	</tr>
	<tr>
		<td width="3%" colspan="2">&nbsp;</td>
		<td colspan="2"><input type="submit" name="submit" value="submit"></td>
	</tr>
</form>
</table>
<? include("pow_footer.php"); ?>

<?
} else {
	$current_cust = "0";
	$current_date = "0";
	$current_arrival = "0";

	$filename = "A2.xls";
	$handle = fopen($filename, "w");
	if (!$handle){
		echo "File ".$filename." could not be opened, please contact TS.\n";
		exit;
	} ?> 


<table border="0" align="center" cellpadding="0" cellspacing="1">
<?
	// this wonderfully long SQL statement makes a subtable of all distinct pallet/customer/arrival combinations, and then determines
	// which of those combinations have been billed
	$sql = "SELECT RBD.PALLET_ID PALLET_ID, 
				to_char(RBD.SERVICE_DATE, 'MM/DD/YYYY') SERVICE_DATE,
				RBD.CUSTOMER_ID CUSTOMER_ID,
				RBD.SERVICE_AMOUNT SERVICE_AMOUNT,
				RBD.ARRIVAL_NUM ARRIVAL_NUM,
				RB.INVOICE_NUM INVOICE_NUM
				FROM RF_BILLING_DETAIL RBD, (SELECT DISTINCT PALLET_ID, ARRIVAL_NUM, RECEIVER_ID
											FROM CARGO_TRACKING_AUDIT_ARCHIVE
											WHERE WAREHOUSE_LOCATION LIKE 'A2%'
											AND AUDIT_TIME > TO_DATE('".$dateStart."', 'MM/DD/YYYY') 
											AND AUDIT_TIME < TO_DATE('".$dateEnd."', 'MM/DD/YYYY')) CTA,
				RF_BILLING RB
				WHERE RBD.PALLET_ID = CTA.PALLET_ID
				AND RBD.SUM_BILL_NUM = RB.BILLING_NUM
				AND RB.SERVICE_STATUS = 'INVOICED'
				AND RBD.CUSTOMER_ID = CTA.RECEIVER_ID
				AND RBD.ARRIVAL_NUM = CTA.ARRIVAL_NUM
				AND RBD.SERVICE_DATE > to_date('".$dateStart."', 'MM/DD/YYYY')
				AND RBD.SERVICE_DATE < to_date('".$dateEnd."', 'MM/DD/YYYY')
				ORDER BY CUSTOMER_ID, ARRIVAL_NUM, SERVICE_DATE, PALLET_ID";
	ora_parse($RFcursor, $sql);
	ora_exec($RFcursor);
	if(!ora_fetch_into($RFcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// no records, includes an "exit" to preclude me having to put rest of code in an "else"
?>
	<tr>
		<td align="center"><font size="3" face="Verdana">No warehouse A2 Billing Records for the given date range.  <a href="A2.php">Click Here</a> to return to the previous page.</font></td>
	</tr></table>
<?
	exit;
	}
?>
	<tr>
		<td align="center"><b><font size="4" face="Verdana">Invoiced Inventory for A2, <? echo $dateStart; ?> -- <? echo $dateEnd; ?>:</b><BR></font></td>
	</tr>
	<tr>
		<td align="center">&nbsp;<BR><font size="2" face="Verdana">Note:  <b>Only</b> Invoiced inventory that has a warehouse location containing "A2" will be reported here.<BR>&nbsp;<BR>&nbsp;<BR>&nbsp;<BR></font></td>
	</tr><? fwrite($handle, "Invoiced Inventory for A2, ".$dateStart." -- ".$dateEnd."\n\n"); ?>
		 <? fwrite($handle, "Note:  Only Invoiced inventory that has a warehouse location containing \"A2\" will be reported here.\n\n"); ?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><a href="<? echo $filename; ?>">(Tab-delimited) Excel version of this report</a><BR>(be sure to Right-Click and choose "Save As" to obtain the latest version of the report; Left-Clicking may cause your Browser to show an older, cached version of the report)</font></td>
	</tr>
	<tr>
		<td align="center">
		<table border="1" align="center" cellpadding="2" cellspacing="1">
			<tr bgcolor="CC9900">
				<td><font size="3" face="Verdana">Customer#</font></td>
				<td><font size="3" face="Verdana">Arrival Number</font></td>
				<td><font size="3" face="Verdana">Date of Bill</font></td>
				<td><font size="3" face="Verdana">Pallet ID</font></td>
				<td><font size="3" face="Verdana">Invoice #</font></td>
				<td align="right"><font size="3" face="Verdana">Amount</font></td>
			</tr><? fwrite($handle, "Customer#\tArrival Number\tDate of Bill\tPallet\tInvoice #\tAmount\n\n"); ?>
<?
	$current_cust = $row['CUSTOMER_ID'];
	$current_date = $row['SERVICE_DATE'];
	$current_arrival = $row['ARRIVAL_NUM'];

	$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$current_cust."'";
	ora_parse($RFDataCursor, $sql);
	ora_exec($RFDataCursor);
	ora_fetch_into($RFDataCursor, $DataRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
			<tr bgcolor="#CC6666">
				<td colspan="6"><font size="2" face="Verdana"><? echo $DataRow['CUSTOMER_NAME']; ?></font></td>
			</tr><? fwrite($handle, $DataRow['CUSTOMER_NAME']."\n"); ?>
<?
	if(is_numeric($current_arrival)){
		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$current_arrival."'";
		ora_parse($RFDataCursor, $sql);
		ora_exec($RFDataCursor);
		ora_fetch_into($RFDataCursor, $DataRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_name = $DataRow['VESSEL_NAME'];
	} else {
		$vessel_name = "Vessel Unknown";
	}
?>			
			<tr bgcolor="#66CC00">
				<td>&nbsp;</td>
				<td colspan="5"><font size="2" face="Verdana"><? echo $current_arrival."-".$vessel_name; ?></font></td>
			</tr><? fwrite($handle, "\t".$DataRow['VESSEL_NAME']."\n"); ?>
			<tr bgcolor="#B0B0F0">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="4"><font size="2" face="Verdana"><? echo $current_date; ?></font></td>
			</tr><? fwrite($handle, "\t\t".$current_date."\n"); ?>
			<tr bgcolor="<? echo $bgcolor; ?>">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $row['INVOICE_NUM']; ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo $row['SERVICE_AMOUNT']; ?></font></td>
			</tr><? fwrite($handle, "\t\t\t#".$row['PALLET_ID']."\t".$row['INVOICE_NUM']."\t".$row['SERVICE_AMOUNT']."\n"); ?>
<?
	$vessel_total += $row['SERVICE_AMOUNT'];
	$customer_total += $row['SERVICE_AMOUNT'];
	$overall_total += $row['SERVICE_AMOUNT'];

	while(ora_fetch_into($RFcursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		if($bgcolor == "#F0F0F0"){
			$bgcolor = "#FFFFFF";
		} else {
			$bgcolor = "#F0F0F0";
		}

		if($row['CUSTOMER_ID'] != $current_cust){
			// print out subtotals and reset
?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="4" align="left"><font size="2" face="Verdana">Vessel Total:</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo $vessel_total; ?></font></td>
			</tr><? fwrite($handle, "\tVessel Total:\t\t\t\t".$vessel_total."\n"); ?>
			<tr>
				<td colspan="5" align="left"><font size="2" face="Verdana">Customer Total:</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo $customer_total; ?></font></td>
			</tr><? fwrite($handle, "Customer Total:\t\t\t\t\t".$customer_total."\n"); ?>
<?
			$vessel_total = 0;
			$customer_total = 0;

			// and start new customer
			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$row['CUSTOMER_ID']."'";
			ora_parse($RFDataCursor, $sql);
			ora_exec($RFDataCursor);
			ora_fetch_into($RFDataCursor, $DataRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
			<tr bgcolor="#CC6666">
				<td colspan="6"><font size="2" face="Verdana"><? echo $DataRow['CUSTOMER_NAME']; ?></font></td>
			</tr><? fwrite($handle, $DataRow['CUSTOMER_NAME']."\n"); ?>
<?
			if(is_numeric($current_arrival)){
				$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$current_arrival."'";
				ora_parse($RFDataCursor, $sql);
				ora_exec($RFDataCursor);
				ora_fetch_into($RFDataCursor, $DataRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$vessel_name = $DataRow['VESSEL_NAME'];
			} else {
				$vessel_name = "Vessel Unknown";
			}
?>
			<tr bgcolor="#66CC00">
				<td>&nbsp;</td>
				<td colspan="5"><font size="2" face="Verdana"><? echo $current_arrival."-".$vessel_name; ?></font></td>
			</tr><? fwrite($handle, "\t".$DataRow['VESSEL_NAME']."\n"); ?>
			<tr bgcolor="#B0B0F0">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="4"><font size="2" face="Verdana"><? echo $row['SERVICE_DATE']; ?></font></td>
			</tr><? fwrite($handle, "\t\t".$current_date."\n"); ?>
			<tr bgcolor="<? echo $bgcolor; ?>">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $row['INVOICE_NUM']; ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo $row['SERVICE_AMOUNT']; ?></font></td>
			</tr><? fwrite($handle, "\t\t\t#".$row['PALLET_ID']."\t".$row['INVOICE_NUM']."\t".$row['SERVICE_AMOUNT']."\n"); ?>
<?
			$vessel_total += $row['SERVICE_AMOUNT'];
			$customer_total += $row['SERVICE_AMOUNT'];
			$overall_total += $row['SERVICE_AMOUNT'];

		} elseif($row['ARRIVAL_NUM'] != $current_arrival){
			// print out subtotal and reset
?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="4" align="left"><font size="2" face="Verdana">Vessel Total:</font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo $vessel_total; ?></font></td>
			</tr><? fwrite($handle, "\tVessel Total:\t\t\t\t".$vessel_total."\n"); ?>
<?
			$vessel_total = 0;

			if(is_numeric($current_arrival)){
				$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$current_arrival."'";
				ora_parse($RFDataCursor, $sql);
				ora_exec($RFDataCursor);
				ora_fetch_into($RFDataCursor, $DataRow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$vessel_name = $DataRow['VESSEL_NAME'];
			} else {
				$vessel_name = "Vessel Unknown";
			}
?>
			<tr bgcolor="#66CC00">
				<td>&nbsp;</td>
				<td colspan="5"><font size="2" face="Verdana"><? echo $current_arrival."-".$vessel_name; ?></font></td>
			</tr><? fwrite($handle, "\t".$DataRow['VESSEL_NAME']."\n"); ?>
			<tr bgcolor="#B0B0F0">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="4"><font size="2" face="Verdana"><? echo $row['SERVICE_DATE']; ?></font></td>
			</tr><? fwrite($handle, "\t\t".$current_date."\n"); ?>
			<tr bgcolor="<? echo $bgcolor; ?>">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $row['INVOICE_NUM']; ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo $row['SERVICE_AMOUNT']; ?></font></td>
			</tr><? fwrite($handle, "\t\t\t#".$row['PALLET_ID']."\t".$row['INVOICE_NUM']."\t".$row['SERVICE_AMOUNT']."\n"); ?>
<?
			$vessel_total += $row['SERVICE_AMOUNT'];
			$customer_total += $row['SERVICE_AMOUNT'];
			$overall_total += $row['SERVICE_AMOUNT'];

		} elseif($row['SERVICE_DATE'] != $current_date){
?>
			<tr bgcolor="#B0B0F0">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="4"><font size="2" face="Verdana"><? echo $row['SERVICE_DATE']; ?></font></td>
			</tr><? fwrite($handle, "\t\t".$current_date."\n"); ?>
			<tr bgcolor="<? echo $bgcolor; ?>">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $row['INVOICE_NUM']; ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo $row['SERVICE_AMOUNT']; ?></font></td>
			</tr><? fwrite($handle, "\t\t\t#".$row['PALLET_ID']."\t".$row['INVOICE_NUM']."\t".$row['SERVICE_AMOUNT']."\n"); ?>
<?
			$vessel_total += $row['SERVICE_AMOUNT'];
			$customer_total += $row['SERVICE_AMOUNT'];
			$overall_total += $row['SERVICE_AMOUNT'];

		} else {
?>
			<tr bgcolor="<? echo $bgcolor; ?>">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
				<td><font size="2" face="Verdana"><? echo $row['INVOICE_NUM']; ?></font></td>
				<td align="right"><font size="2" face="Verdana">$<? echo $row['SERVICE_AMOUNT']; ?></font></td>
			</tr><? fwrite($handle, "\t\t\t#".$row['PALLET_ID']."\t".$row['INVOICE_NUM']."\t".$row['SERVICE_AMOUNT']."\n"); ?>
<?
			$vessel_total += $row['SERVICE_AMOUNT'];
			$customer_total += $row['SERVICE_AMOUNT'];
			$overall_total += $row['SERVICE_AMOUNT'];

		}

		$current_cust = $row['CUSTOMER_ID'];
		$current_date = $row['SERVICE_DATE'];
		$current_arrival = $row['ARRIVAL_NUM'];
	}

?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="4" align="left"><font size="2" face="Verdana">Vessel Total:</font></td>
		<td align="right"><font size="2" face="Verdana">$<? echo $vessel_total; ?></font></td>
	</tr><? fwrite($handle, "\tVessel Total:\t\t\t\t".$vessel_total."\n"); ?>
	<tr>
		<td colspan="5" align="left"><font size="2" face="Verdana">Customer Total:</font></td>
		<td align="right"><font size="2" face="Verdana">$<? echo $customer_total; ?></font></td>
	</tr><? fwrite($handle, "Customer Total:\t\t\t\t\t".$customer_total."\n"); ?>
	<tr>
		<td colspan="5" align="left"><font size="2" face="Verdana"><b>Overall Total:</b></font></td>
		<td align="right"><font size="2" face="Verdana">$<? echo round($overall_total, 2); ?></font></td>
	</tr><? fwrite($handle, "\nOverall Total:\t\t\t\t\t".round($overall_total, 2)."\n"); ?>

	</table></td></tr>
	<tr>
		<td align="center"><font size="2" face="Verdana"><BR><BR><a href="A2.php">Return to the previous page.</a></font></td>
	</tr>
</table>
<?
	fclose($handle);
} ?>