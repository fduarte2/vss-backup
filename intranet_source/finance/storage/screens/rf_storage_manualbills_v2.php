<?
/*
*	Adam Walter, May/June 2012
*
*	V2 of the Rf-storage manual running page.  Contains far mroe options than just a date range.
*
***********************************************************************************************/

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

//	include("./storage_bills_functions.php"); // TEST LOCATION
	include("storage_bills_functions.php");

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
	$ED_cursor = ora_open($conn);
	$modify_cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	$start_rec = $HTTP_POST_VARS['start_rec'];
	$end_rec = $HTTP_POST_VARS['end_rec'];
	$start_bill = $HTTP_POST_VARS['start_bill'];
	$end_bill = $HTTP_POST_VARS['end_bill'];
	$vessel = $HTTP_POST_VARS['vessel'];
	$cust = $HTTP_POST_VARS['cust'];
	$comm = $HTTP_POST_VARS['comm'];
	$barcode = $HTTP_POST_VARS['barcode'];


	if($submit == "Run Storage"){
		$set_start_rec = $HTTP_POST_VARS['set_start_rec'];
		$set_end_rec = $HTTP_POST_VARS['set_end_rec'];
		$set_start_bill = $HTTP_POST_VARS['set_start_bill'];
		$set_end_bill = $HTTP_POST_VARS['set_end_bill'];
		$set_vessel = $HTTP_POST_VARS['set_vessel'];
		$set_cust = $HTTP_POST_VARS['set_cust'];
		$set_comm = $HTTP_POST_VARS['set_comm'];
		$set_barcode = $HTTP_POST_VARS['set_barcode'];

		$message = Make_RF_Prebills($conn, $set_barcode, $set_start_rec, $set_end_rec, $set_start_bill, $set_end_bill, $set_vessel, $set_cust, $set_comm);
//		echo $message;
		echo Get_display($message, "html");

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'RFMANUSTORAGE'";
		ora_parse($ED_cursor, $sql);
		ora_exec($ED_cursor);
		ora_fetch_into($ED_cursor, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$mailTO = $email_row['TO'];
		$mailheaders = "From: ".$email_row['FROM']."\r\n";

		if($email_row['CC'] != ""){
			$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
		}
		if($email_row['BCC'] != ""){
			$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
		}

		$mailSubject = $email_row['SUBJECT'];

		$body = $email_row['NARRATIVE'];
		$body = str_replace("_1_", "\r\n\r\n".Get_display($message, "text")."\r\n\r\n", $body);

		if(mail($mailTO, $mailSubject, $body, $mailheaders)){
			$sql = "INSERT INTO JOB_QUEUE
						(JOB_ID,
						SUBMITTER_ID,
						SUBMISSION_DATETIME,
						JOB_TYPE,
						JOB_DESCRIPTION,
						DATE_JOB_COMPLETED,
						COMPLETION_STATUS,
						JOB_EMAIL_TO,
						JOB_EMAIL_CC,
						JOB_EMAIL_BCC,
						JOB_BODY)
					VALUES
						(JOB_QUEUE_JOBID_SEQ.NEXTVAL,
						'".$user."',
						SYSDATE,
						'EMAIL',
						'RFMANUSTORAGE',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($modify_cursor, $sql);
			ora_exec($modify_cursor);
		}

		$start_rec = "";
		$end_rec = "";
		$start_bill = "";
		$end_bill = "";
		$vessel = "";
		$cust = "";
		$comm = "";
		$barcode = "";

		$submit = "";
	}

?>
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Manual Scanned-Storage Prebill Generation.
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="select" action="rf_storage_manualbills_v2.php" method="post">
<!--	<tr>
		<td colspan="2"><font size="2" face="Verdana"><b>Please enter at least 1 data value.</b></font></td>
	</tr> !-->
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Date Received (From):  </font></td>
		<td><input type="text" name="start_rec" size="15" maxlength="10" value="<? echo $start_rec; ?>"><a href="javascript:show_calendar('select.start_rec');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Date Received (To):  </font></td>
		<td><input type="text" name="end_rec" size="15" maxlength="10" value="<? echo $end_rec; ?>"><a href="javascript:show_calendar('select.end_rec');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Next Bill Date (From):  </font></td>
		<td><input type="text" name="start_bill" size="15" maxlength="10" value="<? echo $start_bill; ?>"><a href="javascript:show_calendar('select.start_bill');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Next Bill Date (To):  </font></td>
		<td><input type="text" name="end_bill" size="15" maxlength="10" value="<? echo $end_bill; ?>"><a href="javascript:show_calendar('select.end_bill');" 
                     onmouseover="window.status='Date Picker';return true;" 
                     onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0 /></a></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Vessel:  </font></td>
		<td><input type="text" name="vessel" size="20" maxlength="20" value="<? echo $vessel; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Customer:  </font></td>
		<td><input type="text" name="cust" size="20" maxlength="20" value="<? echo $cust; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">(Scanned) Commodity:  </font></td>
		<td><input type="text" name="comm" size="20" maxlength="20" value="<? echo $comm; ?>"></td>
	</tr>
	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Barcode:  </font></td>
		<td><input type="text" name="barcode" size="32" maxlength="32" value="<? echo $barcode; ?>"></td>
	</tr>
<!--	<tr>
		<td width="15%" align="left"><font size="2" face="Verdana">Billing Status:  </font></td>
		<td>	<input type="radio" name="bill" value="any" <? if($set_bill == "any"){?> checked<?}?>>All<br>
				<input type="radio" name="bill" value="" <? if($set_bill == ""){?> checked<?}?>>Currently Billing<br>
				<input type="radio" name="bill" value="N" <? if($set_bill == "N"){?> checked<?}?>>Stopped by Finance<br>
				<input type="radio" name="bill" value="X" <? if($set_bill == "X"){?> checked<?}?>>Marked Unbillable by Autostorage<br>
			</td>
	</tr> !-->
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Pre-Check Storage"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Pre-Check Storage"){
		if((!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $start_rec) && $start_rec != "") ||
			(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $end_rec) && $end_rec != "") || 
			(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $start_bill) && $start_bill != "") || 
			(!ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $end_bill) && $end_bill != "")) {
			echo "<font color=\"#FF0000\">Dates must be in MM/DD/YYYY format.</font>";
		} elseif(($start_rec != "" && Which_Date_Is_First($start_rec, date('m/d/Y')) >= 0) ||
			($end_rec != "" && Which_Date_Is_First($end_rec, date('m/d/Y')) >= 0) ||
			($start_bill != "" && Which_Date_Is_First($start_bill, date('m/d/Y')) >= 0) ||
			($end_bill != "" && Which_Date_Is_First($end_bill, date('m/d/Y')) >= 0)){
			echo "<font color=\"#FF0000\">All entered dates must be earlier than today.</font>";
		} elseif(($cust != "" && !is_numeric($cust)) ||
			($comm != "" && !is_numeric($comm))) {
			echo "<font color=\"#FF0000\">Customer and Commodity must be numeric.</font>";
		} else {
			// passed inspection, lets get on with it.
?>
<table width="100%" border="1">
<form name="update" action="rf_storage_manualbills_v2.php" method="post">
<input type="hidden" name="set_start_rec" value="<? echo $start_rec; ?>">
<input type="hidden" name="set_end_rec" value="<? echo $end_rec; ?>">
<input type="hidden" name="set_start_bill" value="<? echo $start_bill; ?>">
<input type="hidden" name="set_end_bill" value="<? echo $end_bill; ?>">
<input type="hidden" name="set_vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="set_cust" value="<? echo $cust; ?>">
<input type="hidden" name="set_comm" value="<? echo $comm; ?>">
<input type="hidden" name="set_barcode" value="<? echo $barcode; ?>">
<?
			$sql = Make_RF_Storage_SQL($barcode, $start_rec, $end_rec, $start_bill, $end_bill, $vessel, $cust, $comm);
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
<tr>
	<td align="center"><font size="2" face="Verdana">No pallets with the given criteria are marked to bill.</font></td>
</tr>
<?
			} else {
?>
	<tr>
		<td colspan="7" align="center"><font size="3" face="Verdana"><b>Selected Pallets:</b></font><br><font color="#9933FF" size="2" face="Verdana"><b>(Note:  Storage will be attempted on all bills that are billing normally, AND will be retried on those marked as unbillable.)</b></font></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><input type="submit" name="submit" value="Run Storage"></td>
	</tr>
	<tr>
		<td><font size="2" face="Verdana"><b>Barcode</b></font></td>
		<td><font size="2" face="Verdana"><b>Date Received</b></font></td>
		<td><font size="2" face="Verdana"><b>Next Bill Date</b></font></td>
		<td><font size="2" face="Verdana"><b>Customer</b></font></td>
		<td><font size="2" face="Verdana"><b>LR#</b></font></td>
		<td><font size="2" face="Verdana"><b>Commodity(s)</b></font></td>
		<td><font size="2" face="Verdana"><b>Billing Status</b></font></td>
	</tr>
<?
				do {
					$rf_comm = $row['COMMODITY_CODE'];
					if($row['RECEIVING_TYPE'] == "S"){
						// bni, and the rate table, use V for vessel.  Do same here.
						$bni_receiving_type = "V";
					} else {
						$bni_receiving_type = $row['RECEIVING_TYPE'];
					}
					GetBNICommAndUNIT($rf_comm, &$bni_comm, $cust, $bni_receiving_type, &$qty_unit, $conn);

					if($row['BILL'] == ""){
						$billtype = "BILLING NORMALLY";
						$bgcolor = "#FFFFFF";
					} elseif($row['BILL'] == "X"){
						$billtype = "COULD NOT BILL ON LAST ATTEMPT";
						$bgcolor= "#FF0000";
					} else {
						$billtype = "CONTACT TS";
						$bgcolor = "#CCCCCC";
					}

?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo $row['PALLET_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['DATE_REC']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['BILL_DATE']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['RECEIVER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana">Scanned:&nbsp;<? echo $rf_comm; ?><br>(Unscanned:&nbsp;<? echo $bni_comm; ?>)</font></td>
		<td><font size="2" face="Verdana"><? echo $billtype; ?></font></td>
	</tr>
<?
				} while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			}
		}
?>
</form>
</table>
<?
	}
	include("pow_footer.php");
?>