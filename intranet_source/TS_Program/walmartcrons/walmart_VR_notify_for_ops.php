<?
/*
*	Sends out email notifications to Walmart that a ship has arrived.
*
*	Nov/Dec 2010.
****************************************************************************/

//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
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

	$sql = "SELECT * FROM JOB_QUEUE WHERE JOB_DESCRIPTION = 'VR_OPS'
			AND COMPLETION_STATUS = 'PENDING'
			AND JOB_TYPE = 'EMAIL'";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'VR_OPS'";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

		$mailheaders = "From: ".$email_row['FROM']."\r\n";
		if($email_row['TEST'] == "Y"){
			$mailTO = "awalter@port.state.de.us";
			$mailheaders .= "Cc: archive@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us\r\n";
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

		$sql = "SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$row['VARIABLE_LIST']."'";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$vessel_name = $short_term_row['VESSEL_NAME'];


		// attach a file of base pallets
		$xls_file_base = 	"<TABLE border=1 CELLSPACING=1>";

		$sql = "SELECT DISTINCT WICM.WM_COMMODITY_NAME
				FROM CARGO_TRACKING CT, WM_ITEM_COMM_MAP WICM
				WHERE CT.BOL = TO_CHAR(WICM.ITEM_NUM)
					AND ARRIVAL_NUM = '".$row['VARIABLE_LIST']."'
					AND RECEIVER_ID = '3000'
					AND QTY_IN_HOUSE > 0
					AND CARGO_STATUS IN ('HOLD', 'A-C', 'A-Q')
					ORDER BY WICM.WM_COMMODITY_NAME";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		if(!ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$xls_file_base .= "<tr><td colspan=1>No Base Pallets Found</td></tr>";
		} else {
			$xls_file_base .= "<tr><td>QC</td>
									<td>&nbsp;</td>
									<td>Master</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td></tr>";
			$xls_file_base .= "<tr><td>Comment</td>
									<td>PO#</td>
									<td>Item No.</td>
									<td>Item Description</td>
									<td>Pallet ID</td>
									<td>Location</td>
									<td>Cartons</td>
									<td>Date Received</td></tr>";
			do {			
				$total_item_plt = 0;
				$total_item_case = 0;
				
				$sql = "SELECT PALLET_ID, CARGO_STATUS, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'N/A') THE_DATE,
							MARK, WAREHOUSE_LOCATION, QTY_RECEIVED, BOL
						FROM CARGO_TRACKING
						WHERE ARRIVAL_NUM = '".$row['VARIABLE_LIST']."'
							AND BOL IN
								(SELECT TO_CHAR(ITEM_NUM)
								FROM WM_ITEM_COMM_MAP
								WHERE WM_COMMODITY_NAME = '".$row2['WM_COMMODITY_NAME']."'
								)
							AND RECEIVER_ID = '3000'
							AND QTY_IN_HOUSE > 0
							AND CARGO_STATUS IN ('HOLD', 'A-C', 'A-Q')
						ORDER BY CARGO_STATUS, BOL, PALLET_ID";
				ora_parse($short_term_cursor, $sql);
				ora_exec($short_term_cursor);
				while(ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$xls_file_base .= "<tr><td>".$short_term_row['CARGO_STATUS']."</td>
											<td>".$short_term_row['MARK']."</td>
											<td>".$short_term_row['BOL']."</td>
											<td>".$row2['WM_COMMODITY_NAME']."</td>
											<td>".$short_term_row['PALLET_ID']."</td>
											<td>".$short_term_row['WAREHOUSE_LOCATION']."</td>
											<td>".$short_term_row['QTY_RECEIVED']."</td>
											<td>".$short_term_row['THE_DATE']."</td></tr>";
					$total_item_plt++;
					$total_item_case += $short_term_row['QTY_RECEIVED'];
				}

				$xls_file_base .= "<tr><td><b>TOTALS</b></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td><b>".$total_item_plt."</b></td>
										<td>&nbsp;</td>
										<td><b>".$total_item_case."</b></td>
										<td>&nbsp;</td></tr>";
			} while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

			$sql = "SELECT PALLET_ID, CARGO_STATUS, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'N/A') THE_DATE,
						MARK, WAREHOUSE_LOCATION, QTY_RECEIVED, BOL
					FROM CARGO_TRACKING
					WHERE ARRIVAL_NUM = '".$row['VARIABLE_LIST']."'
						AND BOL = 'MULTIPLE'
						AND RECEIVER_ID = '3000'
						AND QTY_IN_HOUSE > 0
						AND CARGO_STATUS IN ('HOLD', 'A-C', 'A-Q')
					ORDER BY CARGO_STATUS, BOL, PALLET_ID";
			ora_parse($short_term_cursor, $sql);
			ora_exec($short_term_cursor);
			while(ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$xls_file_base .= "<tr><td>".$short_term_row['CARGO_STATUS']."</td>
										<td>".$short_term_row['MARK']."</td>
										<td>".$short_term_row['BOL']."</td>
										<td>MULTIPLE</td>
										<td>".$short_term_row['PALLET_ID']."</td>
										<td>".$short_term_row['WAREHOUSE_LOCATION']."</td>
										<td>".$short_term_row['QTY_RECEIVED']."</td>
										<td>".$short_term_row['THE_DATE']."</td></tr>";
				$total_item_plt++;
				$total_item_case += $short_term_row['QTY_RECEIVED'];
			}

		}

					
		$xls_file_base .= "</table>";





		// new Dec 2015:  USDA-holds as a separate section
		$xls_file_base .= 	"<br>";
		$xls_file_base .= 	"<br>";
		$xls_file_base .= 	"<TABLE border=1 CELLSPACING=1>";

		$sql = "SELECT DISTINCT WICM.WM_COMMODITY_NAME
				FROM CARGO_TRACKING CT, WM_ITEM_COMM_MAP WICM, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.BOL = TO_CHAR(WICM.ITEM_NUM)
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.ARRIVAL_NUM = '".$row['VARIABLE_LIST']."'
					AND CT.RECEIVER_ID = '3000'
					AND QTY_IN_HOUSE > 0
					AND CARGO_STATUS IS NULL
					AND USDA_HOLD = 'Y'
				ORDER BY WICM.WM_COMMODITY_NAME";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		if(!ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$xls_file_base .= "<tr><td colspan=1>No USDA-Hold Pallets Found</td></tr>";
		} else {
			$xls_file_base .= "<tr><td>QC</td>
									<td>&nbsp;</td>
									<td>Master</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td></tr>";
			$xls_file_base .= "<tr><td>Comment</td>
									<td>PO#</td>
									<td>Item No.</td>
									<td>Item Description</td>
									<td>Pallet ID</td>
									<td>Location</td>
									<td>Cartons</td>
									<td>Date Received</td></tr>";
			do {			
				$total_item_plt = 0;
				$total_item_case = 0;
				
				$sql = "SELECT CT.PALLET_ID, CARGO_STATUS, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'N/A') THE_DATE,
							MARK, WAREHOUSE_LOCATION, QTY_RECEIVED, BOL
						FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
						WHERE CT.ARRIVAL_NUM = '".$row['VARIABLE_LIST']."'
							AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
							AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
							AND CT.PALLET_ID = CTAD.PALLET_ID
							AND BOL IN
								(SELECT TO_CHAR(ITEM_NUM)
								FROM WM_ITEM_COMM_MAP
								WHERE WM_COMMODITY_NAME = '".$row2['WM_COMMODITY_NAME']."'
								)
							AND CT.RECEIVER_ID = '3000'
							AND QTY_IN_HOUSE > 0
							AND CARGO_STATUS IS NULL
							AND USDA_HOLD = 'Y'
						ORDER BY CARGO_STATUS, BOL, CT.PALLET_ID";
				ora_parse($short_term_cursor, $sql);
				ora_exec($short_term_cursor);
				while(ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$xls_file_base .= "<tr><td>".$short_term_row['CARGO_STATUS']."</td>
											<td>".$short_term_row['MARK']."</td>
											<td>".$short_term_row['BOL']."</td>
											<td>".$row2['WM_COMMODITY_NAME']."</td>
											<td>".$short_term_row['PALLET_ID']."</td>
											<td>".$short_term_row['WAREHOUSE_LOCATION']."</td>
											<td>".$short_term_row['QTY_RECEIVED']."</td>
											<td>".$short_term_row['THE_DATE']."</td></tr>";
					$total_item_plt++;
					$total_item_case += $short_term_row['QTY_RECEIVED'];
				}

				$xls_file_base .= "<tr><td><b>TOTALS</b></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td><b>".$total_item_plt."</b></td>
										<td>&nbsp;</td>
										<td><b>".$total_item_case."</b></td>
										<td>&nbsp;</td></tr>";
			} while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

			$sql = "SELECT CT.PALLET_ID, CARGO_STATUS, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH24:MI'), 'N/A') THE_DATE,
						MARK, WAREHOUSE_LOCATION, QTY_RECEIVED, BOL
					FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
					WHERE CT.ARRIVAL_NUM = '".$row['VARIABLE_LIST']."'
						AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
						AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
						AND CT.PALLET_ID = CTAD.PALLET_ID
						AND BOL = 'MULTIPLE'
						AND CT.RECEIVER_ID = '3000'
						AND QTY_IN_HOUSE > 0
						AND CARGO_STATUS IS NULL
						AND USDA_HOLD = 'Y'
					ORDER BY CARGO_STATUS, BOL, CT.PALLET_ID";
			ora_parse($short_term_cursor, $sql);
			ora_exec($short_term_cursor);
			while(ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$xls_file_base .= "<tr><td>".$short_term_row['CARGO_STATUS']."</td>
										<td>".$short_term_row['MARK']."</td>
										<td>".$short_term_row['BOL']."</td>
										<td>MULTIPLE</td>
										<td>".$short_term_row['PALLET_ID']."</td>
										<td>".$short_term_row['WAREHOUSE_LOCATION']."</td>
										<td>".$short_term_row['QTY_RECEIVED']."</td>
										<td>".$short_term_row['THE_DATE']."</td></tr>";
				$total_item_plt++;
				$total_item_case += $short_term_row['QTY_RECEIVED'];
			}

		}

		$xls_file_base .= "</table>";



		$xls_attach_base=chunk_split(base64_encode($xls_file_base));


		$sql = "SELECT COUNT(DISTINCT CT.PALLET_ID) THE_COUNT
				FROM CARGO_TRACKING CT, CARGO_TRACKING_ADDITIONAL_DATA CTAD
				WHERE CT.ARRIVAL_NUM = '".$row['VARIABLE_LIST']."'
					AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM
					AND CT.RECEIVER_ID = CTAD.RECEIVER_ID
					AND CT.PALLET_ID = CTAD.PALLET_ID
					AND CT.RECEIVER_ID = '3000'
					AND QTY_IN_HOUSE > 0
					AND (CARGO_STATUS IN ('HOLD', 'A-C', 'A-Q')
						OR
						USDA_HOLD = 'Y')";
		ora_parse($short_term_cursor, $sql);
		ora_exec($short_term_cursor);
		ora_fetch_into($short_term_cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$held_pallets = $short_term_row['THE_COUNT'];

		$body = str_replace("_1_", $held_pallets, $body);
		$mailSubject = str_replace("_0_", $vessel_name, $mailSubject);

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
		$Content.="Content-Type: application/pdf; name=\"QCPallets_OPS.xls\"\r\n";
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