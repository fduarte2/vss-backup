<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance Systemi - Delete Pre-Invoice";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

	$rfconn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$rfconn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($rfconn));
		exit;
	}
	$ED_cursor = ora_open($rfconn);

	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_third = ora_open($conn);
	$cursor_update = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$vessel = $HTTP_POST_VARS['vessel'];
	$cust = $HTTP_POST_VARS['cust'];
	$bill_start = $HTTP_POST_VARS['bill_start'];
	$bill_end = $HTTP_POST_VARS['bill_end'];
	$bill_num = $HTTP_POST_VARS['bill_num'];
	$reason = str_replace("'", "`", $HTTP_POST_VARS['reason']);
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Delete Prebills and Revert to Previous Bill Date"){
		$subject_desc = "Temporarily";
		$narr_desc = "All related cargo have had their Next Bill Date set 1 billing cycle back";
		$sql_desc = "TEMPORARY";
	} elseif($submit == "Permanently Stop Cargo from Billing"){
		$subject_desc = "Permanently";
		$narr_desc = "All related cargo have had their billing permanently stopped";
		$sql_desc = "PERMANENT";
	}

	if(($submit == "Delete Prebills and Revert to Previous Bill Date" || $submit == "Permanently Stop Cargo from Billing") && $reason != ""){
		$where_clause = "";
		if($vessel != ""){
			$where_clause .= " AND LR_NUM = '".$vessel."'";
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
		$sql = "SELECT LR_NUM, CUSTOMER_ID, TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_END, SERVICE_QTY, SERVICE_UNIT, SERVICE_AMOUNT, BILLING_NUM, LOT_NUM FROM BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND BILLING_TYPE = 'STORAGE'".$where_clause;
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$deleted_count++;
			$mail_details .= "Bill ".$row['BILLING_NUM']."  (LR# ".$row['LR_NUM']."  Cust# ".$row['CUSTOMER_ID']."  ".$row['THE_START']."-".$row['THE_END']."  ".$row['SERVICE_QTY']." ".$row['SERVICE_UNIT']."  ".money_format('%.2n', $row['SERVICE_AMOUNT']).")\r\n";

			$sql = "INSERT INTO DEL_STORAGE_PREBILL_LOG
						(DB_SYSTEM,
						LOT_OR_BARCODE,
						USER_LOGIN,
						DATE_OF_DELETE,
						ARRIVAL_NUM,
						CUSTOMER_ID,
						REASON_FOR_DEL,
						DEL_TYPE)
					VALUES
						('BNI',
						'".$row['LOT_NUM']."',
						'".$user."',
						SYSDATE,
						'".$row['LR_NUM']."',
						'".$row['CUSTOMER_ID']."',
						'".$reason."',
						'".$sql_desc."')";
			ora_parse($cursor_update, $sql);
			ora_exec($cursor_update);
		}

		//  AND BIL.SERVICE_STOP = CT.STORAGE_END
		if($subject_desc == "Temporarily"){
			$sql = "UPDATE CARGO_TRACKING CT
					SET STORAGE_END = (SELECT (MIN(SERVICE_START) - 1) FROM BILLING BIL WHERE CT.LOT_NUM = BIL.LOT_NUM AND SERVICE_STATUS = 'PREINVOICE' AND BILLING_TYPE = 'STORAGE'".$where_clause.")
					WHERE LOT_NUM IN
					(SELECT LOT_NUM FROM BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND BILLING_TYPE = 'STORAGE'".$where_clause.")";
		} else {
			$sql = "UPDATE CARGO_TRACKING CT
					SET STORAGE_END = TO_DATE('01/01/5000', 'MM/DD/YYYY'), COMMENTS = '".$reason."'
					WHERE LOT_NUM IN
					(SELECT LOT_NUM FROM BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND BILLING_TYPE = 'STORAGE'".$where_clause.")";
		}
		ora_parse($cursor_second, $sql);
		ora_exec($cursor_second);

		$sql = "UPDATE BILLING SET SERVICE_STATUS = 'DELETED' WHERE SERVICE_STATUS = 'PREINVOICE' AND BILLING_TYPE = 'STORAGE'".$where_clause;
		ora_parse($cursor_second, $sql);
		ora_exec($cursor_second);

		echo "<font color=\"#0000FF\">".$deleted_count." prebills deleted.<br>Please see your email for confirmation.</font>";
/*
		$mailTO = "cfoster@port.state.de.us,sshoemaker@port.state.de.us\r\n";
//		$mailTO = "awalter@port.state.de.us\r\n";
		$mailheaders = "From: " . "PoWMailServer@port.state.de.us\r\n";
//		$mailheaders .= "Cc: " . "hdadmin@port.state.de.us\r\n"; 
		$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,hdadmin@port.state.de.us\r\n";	
		$mailsubject = $deleted_count." BNI Prebills Deleted\r\n";

		$body = $user." has deleted the following Prebills:\r\n".$mail_details;
		mail($mailTO, $mailsubject, $body, $mailheaders);
*/
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'BNIDELSTPB'";
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
		$mailSubject = str_replace("_3_", $reason, $mailSubject);
		$mailSubject = str_replace("_4_", $subject_desc, $mailSubject);

		$body = $email_row['NARRATIVE'];
		$body = str_replace("_2_", $user." has deleted the following Prebills:\r\n".$mail_details."\r\n".$narr_desc, $body);

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
						'BNIDELSTPB',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($ED_cursor, $sql);
			ora_exec($ED_cursor);
		}
	} elseif($submit == "Delete Prebills" && $reason == ""){
		echo "<font color=\"#FF0000\">Must Enter a Reason (found right above the \"Delete Prebills\" button)</font><br>";
		$submit = "Retrieve Bills";
	}
		
?>

<!-- Delete Prebills - Main page -->
<script type="text/javascript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Delete BNI Storage Pre-Invoices</font>
         <hr><? //include("../bni_links.php"); ?>
      </td>
   </tr>
</table>
<br />

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select_bills" action="delete_bni_storage_prebills.php" method="post">
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
		$sql = "SELECT VP.LR_NUM, VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE VP, VOYAGE VOY WHERE FREE_TIME_START IS NOT NULL AND VP.LR_NUM = VOY.LR_NUM AND VP.LR_NUM IN (SELECT LR_NUM FROM BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND BILLING_TYPE = 'STORAGE') ORDER BY VP.LR_NUM DESC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
						<option value="<? echo $row['LR_NUM']; ?>"<? if($row['LR_NUM'] == $vessel){ ?> selected <? } ?>><? echo $row['THE_VESSEL'] ?></option>
<?
		}
?>
					</select></font></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2" face="Verdana">Customer:</font></td>
		<td align="left"><select name="cust"><option value="">All</option>
<?
		$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_STATUS = 'ACTIVE' AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND BILLING_TYPE = 'STORAGE') ORDER BY CUSTOMER_ID ASC";
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
		<td colspan="2"><font size="2" face="Verdana">Billing Line #:</font></td>
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
			$sql = "SELECT LR_NUM, CUSTOMER_ID, TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_END, SERVICE_QTY, SERVICE_UNIT, SERVICE_AMOUNT, BILLING_NUM FROM BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND BILLING_TYPE = 'STORAGE'";
			if($vessel != ""){
				$sql .= " AND LR_NUM = '".$vessel."'";
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
<form name="delete_bills" action="delete_bni_storage_prebills.php" method="post">
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
		<td><font size="2" face="Verdana"><? echo $first_row['LR_NUM']; ?></font></td>
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
		<td colspan="7" align="center"><font size="2" face="Verdana"><b>Reason:</b></font>
			<input type="text" name="reason" value="<? echo $reason; ?>" size="100" maxlength="100"></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><input type="submit" name="submit" value="Delete Prebills and Revert to Previous Bill Date">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Permanently Stop Cargo from Billing"></td>
	</tr>
</form>
</table>
<?
			}
		}
	}
	include("pow_footer.php");
?>