<?
/* Created July 2006, Adam Walter
*  
*  This report shows a listing of all pallets that have been "rebanded"
*  Off of an argentine juice ship.  "Rebanded is defined as having
*  The field QTY_IN_STORAGE > 0 in CARGO_TRACKING (note that this
*  Only applies to argentine juice ships)
*
*  This file also creates a .csv/.xls file in the same directory it
*  Resides in per-pass (though the filename is constant to save space)
*  
*******************************************************************/

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
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
  }
  $cursor = ora_open($conn);
  $temp_data_cursor = ora_open($conn);
  $short_term_cursor = ora_open($conn);

    $vessel = $HTTP_POST_VARS['vessel'];
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Reband Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
	<form name="choose_ship" action="reband_report.php" method="post">
		<td align="center">Vessel:  <select name="vessel" onchange="document.choose_ship.submit(this.form)"><option value="">Please Select a Vessel</option>
<?
	// populate the dropdown box of vessel choices
	$sql = "SELECT * FROM VESSEL_PROFILE VP WHERE SHIP_PREFIX IN ('ARG JUICE', 'ARG FRUIT') AND (LR_NUM > 9773 OR LR_NUM = '1045') ORDER BY LR_NUM DESC";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
							<option value="<? echo $row['LR_NUM']; ?>"><? echo $row['LR_NUM']." - ".$row['VESSEL_NAME']; ?></option>
<?
	}
?>
							</select></td>
	</form>
	</tr>
<?
// this is some crazy logic here; grab all data for given vessel to display it...
// My comments rule, don't they?
// This area shown once vessel is chosen
	if($vessel != ""){
		$filename = "RebandReport".$vessel.".xls";
		$handle = fopen($filename, "w");
		if (!$handle){
			echo "File ".$filename." could not be opened, please contact TS.\n";
			exit;
		}

		
		$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($temp_data_cursor, $sql);
		ora_exec($temp_data_cursor);
		ora_fetch_into($temp_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$output_vessel = $row['LR_NUM']." - ".$row['VESSEL_NAME'];
?>
	<tr>
		<td>&nbsp;<BR>&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><font size="3" face="Verdana"><b>Rebanded Bin Report</b><br>Argentine Juice:  <? echo $output_vessel; ?><br></font><font size="2" face="Verdana">As Of <? echo $today; ?> EST</td>
	</tr>
<?
		fwrite($handle, "\t\t\t\tRebanded Bin Report\n");
		fwrite($handle, "\t\t\t\tArgentine Juice:  $output_vessel\n");
		fwrite($handle, "\t\t\t\tAs Of $today EST\n\n");
?>
	<tr>
		<td align="center"><font size="2" face="Verdana"><a href="<? echo $filename; ?>">Right-Click and choose "Save As" for a tab-delimited version of this report.</font></td>
	</tr>
	<tr>
		<td align="center">
		<table border="1" width="100%" cellpadding="4" cellspacing="0">
			<tr>
				<td align="center"><font size="3" face="Verdana">Exporter</font></td>
				<td align="center"><font size="3" face="Verdana">Receiver</font></td>
				<td align="center"><font size="3" face="Verdana">Mark</font></td>
				<td align="center"><font size="3" face="Verdana">BoL</font></td>
				<td align="center"><font size="3" face="Verdana">POW ID</font></td>
				<td align="center"><font size="3" face="Verdana">Commodity</font></td>
				<td align="center"><font size="3" face="Verdana">Pallet #</font></td>
				<td align="center"><font size="3" face="Verdana">Scanned</font></td>
				<td align="center"><font size="3" face="Verdana">Checker</font></td>
			</tr>
<?
		fwrite($handle, "Exporter\tReceiver\tMark\tBoL\tPOW ID\tCommodity\tPallet\tScanned\tChecker\n");

//, CT.SOURCE_USER THE_SCANNER
			$sql = "SELECT CUP.CUSTOMER_NAME THE_REC, CT.EXPORTER_CODE THE_EXP, CT.CARGO_DESCRIPTION THE_MARK, CT.BOL THE_BOL, CT.BATCH_ID THE_CODE, 
						COP.COMMODITY_NAME THE_COMM, CT.PALLET_ID THE_PALLET, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH:MI AM') THE_SCAN,
						CT.RECEIVER_ID
					FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUP, COMMODITY_PROFILE COP, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE CT.RECEIVER_ID = CUP.CUSTOMER_ID
						AND CT.COMMODITY_CODE = COP.COMMODITY_CODE
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CTAD.JUICE_STRAPCOUNT > 0
						AND CT.ARRIVAL_NUM = '".$vessel."'
					ORDER BY THE_EXP, THE_REC, THE_MARK";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// a little redundant perhaps, but easier to read
			$the_exporter = $row['THE_EXP'];
			$the_receiver = $row['THE_REC'];
			$the_mark = $row['THE_MARK'];
			$the_bol = $row['THE_BOL'];
			$the_code = $row['THE_CODE'];
			$the_comm = $row['THE_COMM'];
			$pallet_id = $row['THE_PALLET'];
			if($row['THE_SCAN'] == ""){
				$the_scan = 'NOT SCANNED';
				$the_scanner = 'NOT SCANNED';
			} else {
				$sql = "SELECT LOGIN_ID THE_EMP 
						FROM PER_OWNER.PERSONNEL PER, CARGO_ACTIVITY CA
						WHERE TO_CHAR(CA.ACTIVITY_ID) = PER.EMPLOYEE_ID
							AND CA.ACTIVITY_NUM = '1'
							AND CA.PALLET_ID = '".$row['THE_PALLET']."'
							AND CA.CUSTOMER_ID = '".$row['RECEIVER_ID']."'
							AND CA.ARRIVAL_NUM = '".$vessel."'";
				ora_parse($short_term_cursor, $sql);
				ora_exec($short_term_cursor);
				ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
				$the_scan = $row['THE_SCAN'];
				$the_scanner = $short_term_row['THE_EMP'];
			}

			fwrite($handle, "$the_exporter\t$the_receiver\t$the_mark\t$the_bol\t$the_code\t$the_comm\t$pallet_id\t$the_scan\t$the_scanner\n");
?>
			<tr>
				<td align="center"><font size="2" face="Verdana"><? echo $the_exporter; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $the_receiver; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $the_mark; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $the_bol; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $the_code; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $the_comm; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $pallet_id; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $the_scan; ?></font></td>
				<td align="center"><font size="2" face="Verdana"><? echo $the_scanner; ?></font></td>
			</tr>
<?
		}
		$rowcount = ora_numrows($cursor);
?>
		</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><font size="3" face="Verdana">Total Bins Rebanded:  <b><? echo $rowcount; ?></b></font></td>
	</tr>
<?
	fwrite($handle, "\n\t\t\t\tTotal Bins Rebanded:  $rowcount");
	fclose($handle);
	}
?>
</table>
<?
	include("pow_footer.php");
?>
