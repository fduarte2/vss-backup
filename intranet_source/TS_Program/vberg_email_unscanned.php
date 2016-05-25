<?
/*
*	Adam Walter, Jun 2012
*
*	Due to circumstances that can be best described as 
*	"Beyond my control" (and otherwise described as "why on
*	Earth is this TS's problem), I have to make an 
*	Additional E-invoice program for 2206 that covers
*	Storage bills for pallets that arent billed as pallets,
*	But instead get listed as pallets.  Yes, that's as insane
*	As it sounds.
*************************************************************/
//	echo "test\n";

	include 'class.ezpdf.php';
/*   $date = $HTTP_SERVER_VARS["argv"][1]; 
   if($date == ""){
	   $date = date('m/d/Y');
   }
14 8*/
	$start_offset = 14;
	$end_offset = 8;

	$start_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - $start_offset, date('Y')));
	$end_date = date('m/d/Y', mktime(0,0,0,date('m'), date('d') - $end_offset, date('Y')));
	echo $start_date."   ".$end_date."\n";

//  $RF_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  $RF_conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($RF_conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($RF_conn));
    	printf("Please try later!");
    	exit;
   }
//   $RF_cursor = ora_open($RF_conn);
   $ED_cursor = ora_open($RF_conn); // for email
   $RF_Detail_Cursor = ora_open($RF_conn);
//   $RF_Short_Term_Cursor = ora_open($RF_conn);

//  $BNI_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
  $BNI_conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($BNI_conn < 1){
    	printf("Error logging on to the BNI Oracle Server: ");
    	printf(ora_errorcode($BNI_conn));
    	printf("Please try later!");
    	exit;
   }
   $BNI_cursor = ora_open($BNI_conn);
   $BNI_Short_Term_Cursor = ora_open($BNI_conn);

	$storage_csv = "INVOICE#,INVOICE DATE,LR,VESSEL,SERVICE,PALLET ID,DESCRIPTION,AMOUNT,BILLED QTY\n";
	$storage_counter = 0;
	$current_starting_date = "QQQ"; // yes, this isn't a date.  It's to guarantee a "false" loop on pass #1.
	$current_comm = "QQQ";
	$current_LR = "QQQ";
	$current_inv = "QQQ";

	// CSV-step 1:  grab all the V-berg storage invoices from BNI
	$sql = "SELECT INVOICE_NUM, TO_CHAR(INVOICE_DATE, 'MM/DD/YYYY') INV_DATE, TO_CHAR(SERVICE_START, 'MM/DD/YYYY') START_DATE, 
				   BIL.LR_NUM, SERVICE_QTY, SERVICE_RATE, SERVICE_DESCRIPTION, NVL(VESSEL_NAME, 'UKN') THE_VES, COMMODITY_CODE
			FROM BILLING BIL, VESSEL_PROFILE VP
			WHERE CUSTOMER_ID = '2206'
			AND EXPORT_FILE IS NULL
			AND SERVICE_STATUS = 'INVOICED'
			AND BILLING_TYPE = 'STORAGE'
			AND INVOICE_DATE >= TO_DATE('".$start_date."', 'MM/DD/YYYY')
			AND INVOICE_DATE <= TO_DATE('".$end_date."', 'MM/DD/YYYY')
			AND BIL.LR_NUM = VP.LR_NUM(+)
			ORDER BY SERVICE_START DESC, LR_NUM, COMMODITY_CODE, SERVICE_QTY";
	ora_parse($BNI_Short_Term_Cursor, $sql);
	ora_exec($BNI_Short_Term_Cursor);
//	echo $sql."\n";
	if(!ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		// do nothing, no bills
	} else {
		do {
			if($BNI_Short_Term_row['INVOICE_NUM'] != $current_inv){
				$storage_counter++;
				$current_inv = $BNI_Short_Term_row['INVOICE_NUM'];
			}
			// for each invoice, we want to do the following...
			$bill_qty = $BNI_Short_Term_row['SERVICE_QTY'];

			// check to see if a bill got "split", and if so, initialize a variable for excluding pallet id's to avoid duplicates
			if($BNI_Short_Term_row['START_DATE'] != $current_starting_date || $BNI_Short_Term_row['LR_NUM'] != $current_LR ||
						$BNI_Short_Term_row['COMMODITY_CODE'] != $current_comm){
				$current_starting_date = $BNI_Short_Term_row['START_DATE'];
				$current_LR = $BNI_Short_Term_row['LR_NUM'];
				$current_comm = $BNI_Short_Term_row['COMMODITY_CODE'];
				$sql_exclude_list = "('9999QQQQ'";
			}


			// create an SQL that gets potential pallets
			// yes, that's a hanging parenthesis after the final WHERE clause item.  Intentional
			$sql = "SELECT PALLET_ID, NVL(SUM(DECODE(ACTIVITY_NUM, 1, QTY_CHANGE, -1 * DECODE(SERVICE_CODE, 9, (-1 * QTY_CHANGE), QTY_CHANGE))), 0) THE_START 
					FROM CARGO_ACTIVITY CA, LR_CONVERSION LRC
					WHERE CUSTOMER_ID = '2206'
					AND LRC.LR_NUM = '".$BNI_Short_Term_row['LR_NUM']."'
					AND CA.ARRIVAL_NUM = TO_CHAR(LRC.OPT_ARRIVAL_NUM)
					AND SERVICE_CODE != '12'
					AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')
					AND DATE_OF_ACTIVITY <= TO_DATE('".$BNI_Short_Term_row['START_DATE']."', 'MM/DD/YYYY')
					AND PALLET_ID IN
						(SELECT PALLET_ID FROM CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC
						WHERE RTBC.BNI_COMM = '".$BNI_Short_Term_row['COMMODITY_CODE']."'
						AND CT.COMMODITY_CODE = RTBC.RF_COMM)
					AND PALLET_ID NOT IN ".$sql_exclude_list.")
					GROUP BY PALLET_ID
					HAVING NVL(SUM(DECODE(ACTIVITY_NUM, 1, QTY_CHANGE, -1 * DECODE(SERVICE_CODE, 9, (-1 * QTY_CHANGE), QTY_CHANGE))), 0) > 0
					ORDER BY THE_START";
			ora_parse($RF_Detail_Cursor, $sql);
			ora_exec($RF_Detail_Cursor);
			echo $sql."\n";

			// now, we have a number of lines = the billed qty, so instead of looping on the DB cursor, we loop on...
			for ($i = 1; $i <= $bill_qty; $i++){
				if(@ora_fetch_into($RF_Detail_Cursor, $RF_Detail_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					// if we found a pallet, enter it into csv.
					$storage_csv .= $BNI_Short_Term_row['INVOICE_NUM'].",".$BNI_Short_Term_row['INV_DATE'].",";
					$storage_csv .= $BNI_Short_Term_row['LR_NUM'].",".$BNI_Short_Term_row['THE_VES'].",";
					$storage_csv .= "STORAGE".",".$RF_Detail_row['PALLET_ID'].",".$BNI_Short_Term_row['SERVICE_DESCRIPTION'].",";
					$storage_csv .= number_format($BNI_Short_Term_row['SERVICE_RATE'], 2, ".", "").",".$RF_Detail_row['THE_START']."\n";
					$sql_exclude_list .= ", '".$RF_Detail_row['PALLET_ID']."'";
				} else {
					// we have no pallet id.
					$storage_csv .= $BNI_Short_Term_row['INVOICE_NUM'].",".$BNI_Short_Term_row['INV_DATE'].",";
					$storage_csv .= $BNI_Short_Term_row['LR_NUM'].",".$BNI_Short_Term_row['THE_VES'].",";
					$storage_csv .= "STORAGE".","."UNKNOWN".",".$BNI_Short_Term_row['SERVICE_DESCRIPTION'].",";
					$storage_csv .= number_format($BNI_Short_Term_row['SERVICE_RATE'], 2, ".", "").","."0"."\n";
				}
			}
			$storage_csv .= "\n";
		} while(ora_fetch_into($BNI_Short_Term_Cursor, $BNI_Short_Term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

		// SEND DA EMAIL!
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'VBERGBNIINV'";
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
		$body = str_replace("_0_", $storage_counter, $body);

		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";

		$attach=chunk_split(base64_encode($storage_csv));

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/excel; name=\"CSVBills.csv\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY--\n";

//		echo "to: ".$mailTO."\r\nsub: ".$mailSubject."\r\n";

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
						'DAILYCRON',
						SYSDATE,
						'EMAIL',
						'VBERGBNIINV',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($ED_cursor, $sql);
			ora_exec($ED_cursor);

			$sql = "UPDATE BILLING
					SET EXPORT_FILE = 'EMAILED'
					WHERE CUSTOMER_ID = '2206'
					AND EXPORT_FILE IS NULL
					AND SERVICE_STATUS = 'INVOICED'
					AND BILLING_TYPE = 'STORAGE'
					AND INVOICE_DATE >= TO_DATE('".$start_date."', 'MM/DD/YYYY')
					AND INVOICE_DATE <= TO_DATE('".$end_date."', 'MM/DD/YYYY')";
//			echo $sql."\r\n";
			ora_parse($BNI_cursor, $sql);
			ora_exec($BNI_cursor);

		}



/*
		$mailTO = "awalter@port.state.de.us,sadu@port.state.de.us";
		$mailSubject = "V-berg test storage bni";
		$body = "test";
		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";
		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= $body;
		$Content.="\r\n";
		$attach=chunk_split(base64_encode($storage_csv));
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/excel; name=\"CSVBills.csv\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
		$Content.="\r\n";

		mail($mailTO, $mailSubject, $Content, $mailheaders);
*/
	}
