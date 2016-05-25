<?
/*
*	Adam Walter, Oct-Nov 2011
*
*	This is the script to print live invoices
*	For the "New" Automated Storage Billing System (new as of Oct/Nov 2011)
*****************************************************************************/
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
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }
  $Short_Term_Cursor = ora_open($conn);
  $cursor = ora_open($conn);
  $modify_cursor = ora_open($conn);
  $ED_cursor = ora_open($conn);

	$start_num = $HTTP_POST_VARS['start_num'];
	$end_num = $HTTP_POST_VARS['end_num'];
	$cust = $HTTP_POST_VARS['cust'];
	$submit = $HTTP_POST_VARS['submit'];

	//$user comes from pow_header.php
	$user_email = $userdata['user_email'];

	if($submit != ""){
		if($start_num == "" || $end_num == "" || $cust == "" || !is_numeric($start_num) || !is_numeric($end_num) || !is_numeric($cust)){
			echo "<font color=\"#FF0000\">Customer, Start, and End Invoice #s must be specified.</font>";
		} elseif($start_num > $end_num){
			echo "<font color=\"#FF0000\">End number must be greater than or equal to starting number.</font>";
		} else {
			$filename = "1608".date('mdyhi').".txt";

			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$cust."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$cust_name = $short_term_row['CUSTOMER_NAME'];

			$email_output = "";

			// loop for each invoice#...
			$current_invoice = $start_num;
			while($current_invoice <= $end_num){

				$sql = "SELECT RB.INVOICE_NUM, NVL(RBD.ARRIVAL_NUM || '-' || VP.VESSEL_NAME, 'TRUCK') THE_VES, RBD.SERVICE_DESCRIPTION, 
							RBD.PALLET_ID, TO_CHAR(RBD.SERVICE_START, 'MM/DD/YYYY') THE_START,
							TO_CHAR(RBD.SERVICE_STOP, 'MM/DD/YYYY') THE_END, RBD.SERVICE_QTY2, RBD.SERVICE_RATE, RBD.SERVICE_AMOUNT
						FROM VESSEL_PROFILE VP, RF_BILLING_DETAIL RBD, RF_BILLING RB
						WHERE RB.BILLING_NUM = RBD.SUM_BILL_NUM
							AND RBD.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+))
							AND RB.INVOICE_NUM = '".$current_invoice."'
							AND RBD.CUSTOMER_ID = '".$cust."'
							AND (RBD.SERVICE_STATUS IS NULL OR RBD.SERVICE_STATUS != 'DELETED')
						ORDER BY RBD.PALLET_ID";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				while(ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$email_output .= $short_term_row['INVOICE_NUM'].",".$short_term_row['THE_VES'].",".$short_term_row['SERVICE_DESCRIPTION'].",".
									$short_term_row['PALLET_ID'].",".$short_term_row['THE_START'].",".$short_term_row['THE_END'].",".
									$short_term_row['SERVICE_QTY2'].",".$short_term_row['SERVICE_RATE'].",".$short_term_row['SERVICE_AMOUNT']."\r\n";
				}

				$email_output .= "\r\n";

				$current_invoice++;
			}

			$email_output = trim($email_output);

			if($email_output == ""){
				$email_output = "No invoices for ".$cust." fell within the invoice range of ".$start_num." to ".$end_num.".";
			}

			$email_output=chunk_split(base64_encode($email_output));

			$sql = "SELECT * FROM EMAIL_DISTRIBUTION
					WHERE EMAILID = 'RFSTOREMAILFILE'";
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
			$mailheaders .= "MIME-Version: 1.0\r\n";
			$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
			$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
			$mailheaders .= "X-Mailer: PHP4\r\n";
			$mailheaders .= "X-Priority: 3\r\n";
			$mailheaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
			$mailheaders  .= "This is a multi-part Content in MIME format.\r\n";

			$mailSubject = $email_row['SUBJECT'];

			$body = $email_row['NARRATIVE'];
			$body = str_replace("_3_", $cust_name, $body);

			$mailTO = str_replace("_0_", $user_email, $mailTO);

			$mailSubject = str_replace("_1_", $start_num, $mailSubject);
			$mailSubject = str_replace("_2_", $end_num, $mailSubject);
			$mailSubject = str_replace("_3_", $cust_name, $mailSubject);

			$Content="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
			$Content.="\r\n";
			$Content.=$body;
			$Content.="\r\n\r\n";
			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/word; name=\"".$filename."\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$email_output;
			$Content.="\r\n";
			$Content.="--MIME_BOUNDRY--\n";

			if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
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
							'RFSTOREMAILFILE',
							SYSDATE,
							'COMPLETED',
							'".$mailTO."',
							'".$email_row['CC']."',
							'".$email_row['BCC']."',
							'".substr($body, 0, 2000)."')";
				ora_parse($modify_cursor, $sql);
				ora_exec($modify_cursor);
			}
		}

		echo "<font color=\"#0000FF\">Email File created.  Please check your Outlook mailbox.</font>";
	}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Scanned Storage Email-txt File Generator
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="makefile" action="rf_storage_email_file.php" method="post">
	<tr>
		<td>Starting Invoice#:&nbsp;&nbsp;<input type="text" name="start_num" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td>Ending Invoice#:&nbsp;&nbsp;<input type="text" name="end_num" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td>Customer#:&nbsp;&nbsp;<input type="text" name="cust" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Send Email File To My Inbox"></td>
	</tr>
</form>
</table>

<? include("pow_footer.php"); ?>