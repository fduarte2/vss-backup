<?
/*
*	Sends out email notifications to Walmart that a ship has been pre-QCed.
*
*	Mar 2011.
****************************************************************************/

//  $conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    	printf("Error logging on to the RF Oracle Server: ");
    	printf(ora_errorcode($conn));
    	printf("Please try later!");
    	exit;
   }
   $cursor = ora_open($conn);
   $cursor2 = ora_open($conn);
   $short_term_cursor = ora_open($conn);

   $mod_cursor = ora_open($conn);

	$sql = "SELECT * FROM JOB_QUEUE WHERE JOB_DESCRIPTION = 'WDIPULLS'
			AND COMPLETION_STATUS = 'PENDING'
			AND JOB_TYPE = 'EMAIL'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$temp = explode(";", $row['VARIABLE_LIST']);
		$vessel = $temp[0];
		$new_plts = $temp[1];
		
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'WDIPULLS'";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$mailheaders = "From: ".$email_row['FROM']."\r\n";
		if($email_row['TEST'] == "Y"){
			$mailTO = "lstewart@port.state.de.us";
			$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,awalter@port.state.de.us\r\n";
		} else {
			$mailTO = $email_row['TO'];
			if($email_row['CC'] != ""){
				$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
			}
			if($email_row['BCC'] != ""){
				$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
			}
		}
/*
		$mailTO = $email_row['TO'];
		$mailheaders = "From: ".$email_row['FROM']."\r\n";

		if($email_row['CC'] != ""){
			$mailheaders .= "Cc: ".$email_row['CC']."\r\n";
		}
		if($email_row['BCC'] != ""){
			$mailheaders .= "Bcc: ".$email_row['BCC']."\r\n";
		}
*/
		$mailSubject = $email_row['SUBJECT'];

		$body = $email_row['NARRATIVE'];

		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$vessel."'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_name = $short_term_row['VESSEL_NAME'];

		$total_so_far = 0;
		$xls_file_base = 	"<TABLE border=0 CELLSPACING=1>";
		$xls_file_base .= "<tr><td colspan=1>Arrival#</td>
								<td colspan=1>Vessel Name</td>
								<td colspan=1>WM Item#</td>
								<td colspan=1>Item Description</td>
								<td colspan=1>Barcode</td>
								<td colspan=1>Case Count</td>
								<td colspan=1>Receiving PO</td>
								<td colspan=1>Grower#</td>
								<td colspan=1>Hatch/Deck</td>
							</tr>";

		$sql = "SELECT PALLET_ID, BOL, NVL(WM_COMMODITY_NAME, 'UNKNOWN') THE_ITEM, QTY_RECEIVED, MARK, BATCH_ID, SUB_CUSTID, HATCH
				FROM CARGO_TRACKING CT, WM_ITEM_COMM_MAP WICM
				WHERE CT.BOL = TO_CHAR(WICM.ITEM_NUM(+))
					AND RECEIVER_ID = '3000'
					AND ARRIVAL_NUM = '".$vessel."'
					AND UPPER(WAREHOUSE_LOCATION) LIKE '%QC%'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		if(!ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$xls_file_base .= "<tr><td colspan=9>No Pallets marked QC</td></tr>";
		} else {
			do {
				if($short_term_row['SUB_CUSTID'] < 0){
					$short_term_row['SUB_CUSTID'] = "MULTIPLE";
				}

				$xls_file_base .= "<tr>
										<td colspan=1>".$vessel."</td>
										<td colspan=1>".$vessel_name."</td>
										<td colspan=1>".$short_term_row['BOL']."</td>
										<td colspan=1>".$short_term_row['THE_ITEM']."</td>
										<td colspan=1>".$short_term_row['PALLET_ID']."</td>
										<td colspan=1>".$short_term_row['QTY_RECEIVED']."</td>
										<td colspan=1>".$short_term_row['MARK']."</td>
										<td colspan=1>".$short_term_row['SUB_CUSTID']."</td>
										<td colspan=1>".$short_term_row['HATCH']."</td>
									</tr>";

				$total_so_far++;
			} while(ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$xls_file_base .= "</table>";
		$xls_attach_base=chunk_split(base64_encode($xls_file_base));

		
		
		
		$body = str_replace("_0_", $vessel_name, $body);
		$body = str_replace("_1_", $new_plts, $body);
		$body = str_replace("_2_", $total_so_far, $body);

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

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"QCPullsForVessel".$vessel_name.date('m/d/Y').".xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$xls_attach_base;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY--\n";

		if(mail($mailTO, $mailSubject, $Content, $mailheaders)){
			$sql = "UPDATE JOB_QUEUE SET
						DATE_JOB_COMPLETED = SYSDATE,
						COMPLETION_STATUS = 'COMPLETE',
						JOB_EMAIL_TO = '".$mailTO."',
						JOB_EMAIL_CC = '".$email_row['CC']."',
						JOB_EMAIL_BCC = '".$email_row['BCC']."',
						JOB_BODY = '".substr($body, 0, 2000)."'
					WHERE
						JOB_ID = '".$row['JOB_ID']."'";
			ora_parse($mod_cursor, $sql);
			ora_exec($mod_cursor);
		}
	}