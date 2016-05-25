<?
/*
*	Adam Walter, Oct 17, 2009.
*
*	This page is designed to be a recap of Abitibi Bills.
*	Bills must be in an "INVOICED" state in RF_BILLING to use this.
*************************************************************************/

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
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$Short_Term_Data = ora_open($conn);
	$invoice_cursor = ora_open($conn);
	$detail_cursor = ora_open($conn);

	$date_start = $HTTP_POST_VARS['date_start'];
	$date_end = $HTTP_POST_VARS['date_end'];
	$invoice = $HTTP_POST_VARS['invoice'];
	$submit = $HTTP_POST_VARS['submit'];

	$filename = "MostRecentAbititi.xls";

	$show_file = true;
	$message = "";

	if($submit != ""){
		if($invoice == "" && $date_start == "" && $date_end == ""){
			$show_file = false;
			$message = "<font color=\"#FF0000\">Must enter serach criteria</font>";
		} elseif(($date_start == "" && $date_end != "") || ($date_start != "" && $date_end == "")){
			$show_file = false;
			$message = "<font color=\"#FF0000\">IF using dates, both a start and an end must be supplied</font>";
		}
	}
?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Abitibi Condensed Storage Bill (Run RF Storage first)
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<?
	if($submit == "" || $show_file == false){
		if(!$show_file){
			echo $message;
		}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get_data" action="Abitibi_condensed_bill.php" method="post">
	<tr>
		<td align="left" width="15%"><font size="2" face="Verdana">Start date:  </td>
		<td><input type="text" name="date_start" size="15" maxlength="10"><a href="javascript:show_calendar('get_data.date_start');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td align="left" width="15%"><font size="2" face="Verdana">End date:  </td>
		<td><input type="text" name="date_end" size="15" maxlength="10"><a href="javascript:show_calendar('get_data.date_end');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td align="left" width="15%"><font size="2" face="Verdana">Invoice:  </td>
		<td><input type="text" name="invoice" size="20" maxlength="20">
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Generate Abitibi Spreadsheet"></td>
	</tr>
</form>
</table>
<?
	} else { // submit pressed, values good, show file.
		$fp = fopen($filename, "w");
		if(!$fp){
			echo "error opening file.  Please contact TS";
			include("pow_footer.php");
			exit;
		} else {

			$sql = "SELECT INVOICE_NUM, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') THE_DATE, SUM(SERVICE_AMOUNT) THE_SUM FROM RF_BILLING WHERE 1 = 1 AND ";
			if($invoice != ""){
				$sql .= "INVOICE_NUM = '".$invoice."' AND ";
			}
			if($date_start != ""){
				$sql .= "SERVICE_START >= TO_DATE('".$date_start."', 'MM/DD/YYYY') AND ";
			}
			if($date_end != ""){
				$sql .= "SERVICE_STOP <= TO_DATE('".$date_end."', 'MM/DD/YYYY') AND ";
			}
			$sql .= "CUSTOMER_ID IN ('113', '312') AND SERVICE_STATUS = 'INVOICED' AND SERVICE_DESCRIPTION = 'STORAGE' GROUP BY INVOICE_NUM, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') ORDER BY INVOICE_NUM";
			ora_parse($invoice_cursor, $sql);
			ora_exec($invoice_cursor);
			while(ora_fetch_into($invoice_cursor, $invoice_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				// for each invoice...

				fwrite($fp, "Invoice#\t".$invoice_row['INVOICE_NUM']."\n");
				fwrite($fp, "Invoice Date\t".$invoice_row['THE_DATE']."\n");
				fwrite($fp, "Amount\t".money_format('%.2n', $invoice_row['THE_SUM'])."\n");

				$sql = "SELECT FREETIME FROM RF_STORAGE_RATE WHERE CUSTOMER_ID = '312'";
				ora_parse($Short_Term_Data, $sql);
				ora_exec($Short_Term_Data);
				ora_fetch_into($Short_Term_Data, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				fwrite($fp, "Free Time\t".$Short_Term_Row['FREETIME']." Days\n\n");

				fwrite($fp, "Received\tShipped\tContainer\tMark\tSize\tOrder\tStart Date\tEnd Date\tCycle\tNet Tons\tRate/NT\tAmount\n");
				// get sum of data, convert weight to NET TONS within SQL
				$sql = "SELECT TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY') THE_REC, CT.CONTAINER_ID, CT.CARGO_DESCRIPTION, TO_CHAR(RBD.SERVICE_START, 'MM/DD/YYYY') THE_START, TO_CHAR(RBD.SERVICE_STOP, 'MM/DD/YYYY') THE_END, (RBD.SERVICE_STOP - RBD.SERVICE_START) CYCLE_DAYS, SUM(CT.WEIGHT * 0.0011) NET_TONS, RBD.SERVICE_RATE, SUM(RBD.SERVICE_AMOUNT) THE_AMOUNT FROM CARGO_TRACKING CT, RF_BILLING_DETAIL RBD WHERE CT.PALLET_ID = RBD.PALLET_ID AND RBD.CUSTOMER_ID IN ('113', '312') AND CT.RECEIVER_ID = '312' AND RBD.COMMODITY_CODE = CT.COMMODITY_CODE AND RBD.SUM_BILL_NUM IN (SELECT BILLING_NUM FROM RF_BILLING WHERE INVOICE_NUM = '".$invoice_row['INVOICE_NUM']."') GROUP BY TO_CHAR(CT.DATE_RECEIVED, 'MM/DD/YYYY'), CT.CONTAINER_ID, CT.CARGO_DESCRIPTION, TO_CHAR(RBD.SERVICE_START, 'MM/DD/YYYY'), TO_CHAR(RBD.SERVICE_STOP, 'MM/DD/YYYY'), (RBD.SERVICE_STOP - RBD.SERVICE_START), RBD.SERVICE_RATE ORDER BY CT.CONTAINER_ID";
				ora_parse($detail_cursor, $sql);
				ora_exec($detail_cursor);
				while(ora_fetch_into($detail_cursor, $detail_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$sql = "SELECT NVL(TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY'), 'NONE') THE_MAX FROM CARGO_ACTIVITY WHERE CUSTOMER_ID = '312' AND SERVICE_CODE = '6' AND ACTIVITY_DESCRIPTION LIKE 'DMG%' AND DATE_OF_ACTIVITY <= TO_DATE('".$detail_row['THE_END']."', 'MM/DD/YYYY') AND PALLET_ID IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE CONTAINER_ID = '".$detail_row['CONTAINER_ID']."' AND RECEIVER_ID = '312' AND CARGO_DESCRIPTION = '".$detail_row['CARGO_DESCRIPTION']."')";
					ora_parse($Short_Term_Data, $sql);
					ora_exec($Short_Term_Data);
					ora_fetch_into($Short_Term_Data, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

					$temp = explode(" ", $detail_row['CARGO_DESCRIPTION']);
					fwrite($fp, $detail_row['THE_REC']."\t".
								$Short_Term_Row['THE_MAX']."\t".						
								$detail_row['CONTAINER_ID']."\t".
								$temp[0]."\t".
								$temp[1]."\t".
								$temp[2]."\t".
								$detail_row['THE_START']."\t".
								$detail_row['THE_END']."\t".
								$detail_row['CYCLE_DAYS']."\t".
								round($detail_row['NET_TONS'], 3)."\t".
								$detail_row['SERVICE_RATE']."\t".
								money_format('%.2n', $detail_row['THE_AMOUNT'])."\n");
				}

				fwrite($fp, "\t\t\t\t\t\t\t\t\t\tTotal:\t".money_format('%.2n', $invoice_row['THE_SUM'])."\n\n\n");

			}

			fwrite($fp, "\n\n\n");

		}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td>Click <a href="<? echo $filename; ?>">Here</a> for the file.</td>
	</tr>
</table>
<?
	}
	include("pow_footer.php");
?>