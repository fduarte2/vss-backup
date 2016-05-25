<?
/*
*	Adam Walter, Nov-Dec 2011
*
*	Page to "undelete" deleted prebills.  Just in cas Finance, you know, screws up ;p
*************************************************************************************/

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
	printf("Error logging on to the BNI Oracle Server: ");
	printf(ora_errorcode($conn));
	printf("Please try later!");
	exit;
	}
	$cursor_first = ora_open($conn);
	$cursor_second = ora_open($conn);
	$cursor_modify = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);
	$ED_cursor = ora_open($conn);

	$start_num = $HTTP_POST_VARS['start_num'];
	$end_num = $HTTP_POST_VARS['end_num'];
	if($end_num == ""){
		$end_num = $start_num;
	}
	$submit = $HTTP_POST_VARS['submit'];

	if($submit == "Un-Delete Prebills"){
		$undeleted_count = 0;
		$mail_details = "";
		$sql = "SELECT ARRIVAL_NUM, CUSTOMER_ID, TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START, 
					TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_END, SERVICE_QTY, SERVICE_UNIT, SERVICE_AMOUNT, BILLING_NUM 
				FROM RF_BILLING 
				WHERE SERVICE_STATUS = 'DELETED' 
					AND SERVICE_DESCRIPTION = 'STORAGE'
					AND BILLING_NUM >= '".$start_num."'
					AND BILLING_NUM <= '".$end_num."'
				ORDER BY BILLING_NUM";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$undeleted_count++;
			$mail_details .= "Bill ".$row['BILLING_NUM']."  (LR# ".$row['ARRIVAL_NUM']."  Cust# ".$row['CUSTOMER_ID']."  ".$row['THE_START']."-".$row['THE_END']."  ".$row['SERVICE_QTY']." ".$row['SERVICE_UNIT']."  ".money_format('%.2n', $row['SERVICE_AMOUNT']).")\r\n";
		}

		$sql = "SELECT DISTINCT BILLING_NUM, SERVICE_START, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_STOP 
				FROM RF_BILLING 
				WHERE SERVICE_STATUS = 'DELETED' 
					AND SERVICE_DESCRIPTION = 'STORAGE'
					AND BILLING_NUM >= '".$start_num."'
					AND BILLING_NUM <= '".$end_num."'
				ORDER BY SERVICE_START DESC";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			// for each bill, update each pallet that is on said bill with the previous "storage bill" date
			// for a bill with partial deleteions, we do NOT want to reset bills that were deleted *from* this bill
			$sql = "UPDATE CARGO_TRACKING CT 
					SET BILLING_STORAGE_DATE = TO_DATE('".$row['THE_STOP']."', 'MM/DD/YYYY') + 1
					WHERE (PALLET_ID, RECEIVER_ID, ARRIVAL_NUM) = 
					(SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM RF_BILLING_DETAIL RBD
					WHERE SUM_BILL_NUM = '".$row['BILLING_NUM']."'
						AND RBD.SERVICE_STATUS != 'DELETED'
						AND CT.PALLET_ID = RBD.PALLET_ID
						AND CT.RECEIVER_ID = RBD.CUSTOMER_ID
						AND CT.ARRIVAL_NUM = RBD.ARRIVAL_NUM)";
			ora_parse($cursor_modify, $sql);
			ora_exec($cursor_modify);

			// also, once pallets are "backed up", mark the bill as preinvoiced (again)
			$sql = "UPDATE RF_BILLING SET SERVICE_STATUS = 'PREINVOICE' WHERE BILLING_NUM = '".$row['BILLING_NUM']."'";
			ora_parse($cursor_modify, $sql);
			ora_exec($cursor_modify);
		}

		echo "<font color=\"#0000FF\">".$undeleted_count." Scanned-System prebills un-deleted.<br>The next bill dates for the pallets affected have been set to <br>a next billing date of (Storage End + 1 day) for all undeleted bills.</font>";
/*
		$mailTO = "cfoster@port.state.de.us,sshoemaker@port.state.de.us\r\n";
//		$mailTO = "awalter@port.state.de.us\r\n";
		$mailheaders = "From: " . "PoWMailServer@port.state.de.us\r\n";
//		$mailheaders .= "Cc: " . "hdadmin@port.state.de.us\r\n"; 
		$mailheaders .= "Bcc: " . "awalter@port.state.de.us,lstewart@port.state.de.us,hdadmin@port.state.de.us\r\n";	
		$mailsubject = $undeleted_count." Scanned Prebills Un-Deleted\r\n";

		$body = $user." has un-deleted the following Prebills:\r\n".$mail_details;
		mail($mailTO, $mailsubject, $body, $mailheaders);
*/
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'RFUNDELSTPB'";
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
		$mailSubject = str_replace("_0_", $undeleted_count, $mailSubject);
		$mailSubject = str_replace("_1_", $user, $mailSubject);

		$body = $email_row['NARRATIVE'];
		$body = str_replace("_2_", $user." has un-deleted the following Prebills:\r\n".$mail_details, $body);

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
						'RFUNDELSTPB',
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

<!-- UnDelete Prebills - Main page -->
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Un-Delete RF Storage Pre-Invoices</font>
         <hr>
      </td>
   </tr>
</table>
<br />

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="select_bills" action="undelete_rf_storage_prebills.php" method="post">
	<tr>
		<td>Starting Pre-Invoice#:&nbsp;&nbsp;<input type="text" name="start_num" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td>Ending Pre-Invoice#:&nbsp;&nbsp;<input type="text" name="end_num" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Retrieve Pre-Invoices"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve Pre-Invoices"){
		if($start_num == "" || $end_num == "" || !is_numeric($start_num) || !is_numeric($end_num)){
			echo "<font color=\"#FF0000\">At least the Starting Invoice # must be entered, and all entries must be numeric.</font>";
		} elseif($start_num > $end_num){
			echo "<font color=\"#FF0000\">End number must be greater than or equal to starting number.</font>";
		} else {
			$sql = "SELECT ARRIVAL_NUM, CUSTOMER_ID, TO_CHAR(SERVICE_START, 'MM/DD/YYYY') THE_START, TO_CHAR(SERVICE_STOP, 'MM/DD/YYYY') THE_END, 
						SERVICE_QTY, SERVICE_UNIT, SERVICE_AMOUNT, BILLING_NUM 
					FROM RF_BILLING 
					WHERE SERVICE_STATUS = 'DELETED' 
						AND SERVICE_DESCRIPTION = 'STORAGE'
						AND BILLING_NUM >= '".$start_num."'
						AND BILLING_NUM <= '".$end_num."'
					ORDER BY BILLING_NUM";
			ora_parse($cursor_first, $sql);
			ora_exec($cursor_first);
			if(!ora_fetch_into($cursor_first, $first_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				echo "<font color=\"#FF0000\">No Deleted Pre-bills within specified Preinvoice# range (".$start_num." - ".$end_num.")</font><br>";
			} else {
?>
<table border="1" width="100%" cellpadding="4" cellspacing="0">
<form name="undelete_bills" action="undelete_rf_storage_prebills.php" method="post">
<input type="hidden" name="start_num" value="<? echo $start_num; ?>">
<input type="hidden" name="end_num" value="<? echo $end_num; ?>">
	<tr bgcolor="#DDEEDD">
		<td align="center" colspan="8"><font size="3" face="Verdana"><b>Deleted Prebill Search Results</b></font></td>
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
		<td colspan="7" align="center"><input type="submit" name="submit" value="Un-Delete Prebills"></td>
	</tr>
</form>
</table>
<?
			}
		}
	}
	include("pow_footer.php");
?>