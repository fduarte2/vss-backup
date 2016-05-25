<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Delete Pre-Invoice";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
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
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_modify = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
	$ED_cursor = ora_open($conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$cust = $HTTP_POST_VARS['cust'];
	$bill_start = $HTTP_POST_VARS['bill_start'];
	$bill_end = $HTTP_POST_VARS['bill_end'];
	$bill_num = $HTTP_POST_VARS['bill_num'];
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Delete Prebills"){
		$where_clause = "";
		if($vessel != ""){
			$where_clause .= " AND ARRIVAL_NUM = '".$vessel."'";
		}
		if($cust != ""){
			$where_clause .= " AND CUSTOMER_ID = '".$cust."'";
		}
		if($bill_start != ""){
			$where_clause .= " AND SERVICE_START >= TO_DATE('".$bill_start."', 'MM/DD/YYYY')";
		}
		if($bill_end != ""){
			$where_clause .= " AND SERVICE_START <= TO_DATE('".$bill_end."', 'MM/DD/YYYY')";
		}
		if($bill_num != ""){
			$where_clause .= " AND BILLING_NUM = '".$bill_num."'";
		}

		$deleted_count = 0;
		$mail_details = "";
		$sql = "SELECT ARRIVAL_NUM, CUSTOMER_ID, TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_END, SERVICE_QTY, SERVICE_UNIT, SERVICE_AMOUNT, BILLING_NUM FROM RF_BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND SERVICE_DESCRIPTION = 'STORAGE'".$where_clause;
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$deleted_count++;
			$mail_details .= "Bill ".$row['BILLING_NUM']."  (LR# ".$row['ARRIVAL_NUM']."  Cust# ".$row['CUSTOMER_ID']."  ".$row['THE_START']."-".$row['THE_END']."  ".$row['SERVICE_QTY']." ".$row['SERVICE_UNIT']."  ".money_format('%.2n', $row['SERVICE_AMOUNT']).")\r\n";
		}


		// get each bill that was selected, in order of newest first (in case we have to undo multiple bills for the same pallet)
		$sql = "SELECT DISTINCT BILLING_NUM, SERVICE_START, TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START FROM RF_BILLING WHERE SERVICE_DESCRIPTION = 'STORAGE' AND SERVICE_STATUS = 'PREINVOICE'".$where_clause." ORDER BY SERVICE_START DESC";
		ora_parse($cursor_first, $sql);
		ora_exec($cursor_first);
		while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// for each bill, update each pallet that is on said bill with the previous "storage bill" date
			$sql = "UPDATE CARGO_TRACKING CT 
					SET BILLING_STORAGE_DATE = TO_DATE('".$first_row['THE_START']."', 'MM/DD/YYYY')
					WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) = 
					(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM RF_BILLING_DETAIL RBD
					WHERE SUM_BILL_NUM = '".$first_row['BILLING_NUM']."'
					AND CT.PALLET_ID = RBD.PALLET_ID
					AND CT.RECEIVER_ID = RBD.CUSTOMER_ID
					AND CT.ARRIVAL_NUM = RBD.ARRIVAL_NUM)";
			ora_parse($cursor_modify, $sql);
			ora_exec($cursor_modify);

			// also, once pallets are "backed up", markt he bill as deleted
			$sql = "UPDATE RF_BILLING SET SERVICE_STATUS = 'DELETED' WHERE BILLING_NUM = '".$first_row['BILLING_NUM']."'";
			ora_parse($cursor_modify, $sql);
			ora_exec($cursor_modify);
		}


		echo "<font color=\"#0000FF\">".$deleted_count." Scanned-System prebills deleted.<br>The storage bills will need to be manually re-run.</font>";
/*
//		$mailTO = "cfoster@port.state.de.us,sshoemaker@port.state.de.us\r\n";
		$mailTO = "awalter@port.state.de.us\r\n";
		$mailheaders = "From: " . "PoWMailServer@port.state.de.us\r\n";
//		$mailheaders .= "Cc: " . "hdadmin@port.state.de.us\r\n"; 
		$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,hdadmin@port.state.de.us\r\n";	
		$mailsubject = $deleted_count." Scanned Prebills Deleted\r\n";

		$body = $user." has deleted the following Prebills:\r\n".$mail_details;
		mail($mailTO, $mailsubject, $body, $mailheaders);
*/
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'RFDELSTPB'";
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
		$mailSubject = str_replace("_0_", $deleted_count, $mailSubject);
		$mailSubject = str_replace("_1_", $user, $mailSubject);

		$body = $email_row['NARRATIVE'];
		$body = str_replace("_2_", $user." has deleted the following Prebills:\r\n".$mail_details, $body);

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
						'RFDELSTPB',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($cursor_modify, $sql);
			ora_exec($cursor_modify);
		}

	}
		
?>

<!-- Delete Prebills - Main page -->
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Delete RF Storage Pre-Invoices</font>
         <hr><? //include("../bni_links.php"); ?>
      </td>
   </tr>
</table>
<br />

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select_bills" action="delete_rf_storage_prebills.php" method="post">
	<tr>
		<td colspan="3"><font size="2" face="Verdana"><b>Dates:</b></font>
	</tr>
	<tr>
		<td width="5%">&nbsp;&nbsp;&nbsp;</td>
		<td width="20%"><font size="2" face="Verdana">Bill Starts on or after:</font></td>
		<td align="left"><input type="text" name="bill_start" value="<? echo $bill_start; ?>" size="15" maxlength="10"><a href="javascript:show_calendar('select_bills.bill_start');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td><font size="2" face="Verdana">Bill Starts no later than:</font></td>
		<td><input type="text" name="bill_end" value="<? echo $bill_end; ?>" size="15" maxlength="10"><a href="javascript:show_calendar('select_bills.bill_end');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Vessel:</font></td>
		<td align="left"><select name="vessel"><option value="">All</option>
<?
		$sql = "SELECT VP.LR_NUM, VESSEL_NAME THE_VESSEL 
				FROM VESSEL_PROFILE VP, VOYAGE_FROM_BNI VOY
				WHERE VOY.FREE_TIME_START IS NOT NULL
					AND VP.LR_NUM = VOY.LR_NUM
					AND TO_CHAR(VP.LR_NUM) IN (SELECT ARRIVAL_NUM FROM RF_BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND SERVICE_DESCRIPTION = 'STORAGE') 
				ORDER BY VP.LR_NUM DESC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){ ?> selected <? } ?>><? echo $row['LR_NUM']."-".$row['THE_VESSEL'] ?></option>
<?
		}
?>
					</select></font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Customer:</font></td>
		<td align="left"><select name="cust"><option value="">All</option>
<?
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_STATUS = 'ACTIVE' AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM RF_BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND SERVICE_DESCRIPTION = 'STORAGE') ORDER BY CUSTOMER_ID ASC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['CUSTOMER_ID']; ?>"<? if($row['CUSTOMER_ID'] == $cust){ ?> selected <? } ?>><? echo $row['CUSTOMER_NAME'] ?></option>
<?
		}
?>
												<select></font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Billing #:</font></td>
		<td align="left"><input type="text" name="bill_num" value="<? echo $bill_num; ?>" size="15" maxlength="12"></td>
	</tr>
	<tr>
		<td colspan="3" align="left"><input type="submit" name="submit" value="Retrieve Bills"></td>
	</tr>
	<tr>
		<td colspan="3"><hr></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve Bills"){
		if($vessel == "" &&
			$cust == "" &&
			$bill_start == "" &&
			$bill_end == "" &&
			$bill_num == ""){
				echo "<font color=\"#FF0000\">At least 1 search criteria must be entered</font><br>";
		} else {
			$sql = "SELECT ARRIVAL_NUM, CUSTOMER_ID, TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_END, SERVICE_QTY, SERVICE_UNIT, SERVICE_AMOUNT, BILLING_NUM FROM RF_BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND SERVICE_DESCRIPTION = 'STORAGE'";
			if($vessel != ""){
				$sql .= " AND ARRIVAL_NUM = '".$vessel."'";
			}
			if($cust != ""){
				$sql .= " AND CUSTOMER_ID = '".$cust."'";
			}
			if($bill_start != ""){
				$sql .= " AND SERVICE_START >= TO_DATE('".$bill_start."', 'MM/DD/YYYY')";
			}
			if($bill_end != ""){
				$sql .= " AND SERVICE_START <= TO_DATE('".$bill_end."', 'MM/DD/YYYY')";
			}
			if($bill_num != ""){
				$sql .= " AND BILLING_NUM = '".$bill_num."'";
			}
			$sql .= " ORDER BY BILLING_NUM";
			ora_parse($cursor_first, $sql);
			ora_exec($cursor_first);
			if(!ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				echo "<font color=\"#FF0000\">No bills match searched criteria</font><br>";
			} else {
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="delete_bills" action="delete_rf_storage_prebills.php" method="post">
<input type="hidden" name="cust" value="<? echo $cust; ?>">
<input type="hidden" name="vessel" value="<? echo $vessel; ?>">
<input type="hidden" name="bill_start" value="<? echo $bill_start; ?>">
<input type="hidden" name="bill_end" value="<? echo $bill_end; ?>">
<input type="hidden" name="bill_num" value="<? echo $bill_num; ?>">
	<tr bgcolor="#DDEEDD">
		<td align="center" colspan="8"><font size="3" face="Verdana"><b>Search Results</b></font></td>
	</tr>
	<tr bgcolor="#EEDDDD">
		<td><font size="2" face="Verdana"><b>Bill#</b></font></td>
		<td><font size="2" face="Verdana"><b>Vessel</b></font></td>
		<td><font size="2" face="Verdana"><b>Cust</b></font></td>
		<td><font size="2" face="Verdana"><b>Bill Start</b></font></td>
		<td><font size="2" face="Verdana"><b>Bill End</b></font></td>
		<td><font size="2" face="Verdana"><b>Qty Billed</b></font></td>
		<td><font size="2" face="Verdana"><b>Amount</b></font></td>
	</tr>
<?
				do {
					if($bgcolor == "#FFFFFF"){
						$bgcolor = "#EEEEEE";
					} else {
						$bgcolor = "#FFFFFF";
					}
?>
	<tr bgcolor="<? echo $bgcolor; ?>">
		<td><font size="2" face="Verdana"><? echo $first_row['BILLING_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['ARRIVAL_NUM']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['CUSTOMER_ID']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['THE_START']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['THE_END']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo $first_row['SERVICE_QTY']." ".$first_row['SERVICE_UNIT']; ?></font></td>
		<td><font size="2" face="Verdana"><? echo money_format('%.2n', $first_row['SERVICE_AMOUNT']); ?></font></td>
	</tr>
<?
				} while(ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
?>
	<tr>
		<td colspan="7" align="center"><input type="submit" name="submit" value="Delete Prebills"></td>
	</tr>
</form>
</table>
<?
			}
		}
	}
	include("pow_footer.php");
?>