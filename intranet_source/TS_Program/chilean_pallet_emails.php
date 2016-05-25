<?
//	$user_cust_num = 1982; // FOR TESTING

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $header_cursor = ora_open($conn);         // general purpose
  $modify_cursor = ora_open($conn);
  $Short_Term_Cursor = ora_open($conn);
  $loop_cursor_for_email2 = ora_open($conn);



	$sql = "SELECT TRANSACTION_ID, CUSTOMER_ID, TO_CHAR(DATE_CONFIRMED, 'MM/DD/YYYY HH24:MI') THE_DATE, FILENAME, ARRIVAL_NUM FROM CHILEAN_PLT_CHANGES_HEADER WHERE EMAIL_SENT = 'N' AND DATE_CONFIRMED IS NOT NULL";
	ora_parse($header_cursor, $sql);
	ora_exec($header_cursor);
	while(ora_fetch_into($header_cursor, $header_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){

		// email 1:
		// always distribute.  Recap of upload.

		$match_pallet_file_xls = "<TABLE border=0 CELLSPACING=1>";
		$match_pallet_file_xls .= "<tr>
						<td><b>IMP</b></td>
						<td><b>PLT_ID</b></td>
						<td><b>Commodity</b></td>
						<td><b>Variety</b></td>
						<td><b>Label</b></td>
						<td><b>Size</b></td>
						<td><b>Hatch</b></td>
						<td><b>Qty</b></td>
						<td><b>Loc</b></td>
						<td><b>Grower</b></td>
						<td><b>Package</b></td>
					</tr>";
		$match_pallet_file = "IMP,PLT_ID,Commodity,Variety,Label,Size,Hatch,Qty,Loc,Grower,Package\r\n";
		$sql = "SELECT * FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."' AND PALLET_TO_DB_COMPARE = 'MATCH'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$match_pallet_file_xls .= "<tr><td>No matched pallets in upload</tr></td>";
		} else {
			do {
				$match_pallet_file .= $header_row['CUSTOMER_ID'].","
										.$Short_Term_Row['PALLET_ID'].","
										.$Short_Term_Row['COMMODITY_CODE'].","
										.$Short_Term_Row['VARIETY'].","
										.$Short_Term_Row['LABEL'].","
										.$Short_Term_Row['CARGO_SIZE'].","
										.$Short_Term_Row['HATCH'].","
										.$Short_Term_Row['QTY'].","
										.$Short_Term_Row['WAREHOUSE_LOCATION'].","
										.$Short_Term_Row['GROWER'].","
										.$Short_Term_Row['PACKAGE']."\r\n";
				$match_pallet_file_xls .= "<tr><td>".$header_row['CUSTOMER_ID']."</td>".
										"<td>".$Short_Term_Row['PALLET_ID']."</td>".
										"<td>".$Short_Term_Row['COMMODITY_CODE']."</td>".
										"<td>".$Short_Term_Row['VARIETY']."</td>".
										"<td>".$Short_Term_Row['LABEL']."</td>".
										"<td>".$Short_Term_Row['CARGO_SIZE']."</td>".
										"<td>".$Short_Term_Row['HATCH']."</td>".
										"<td>".$Short_Term_Row['QTY']."</td>".
										"<td>".$Short_Term_Row['WAREHOUSE_LOCATION']."</td>".
										"<td>".$Short_Term_Row['GROWER']."</td>".
										"<td>".$Short_Term_Row['PACKAGE']."</td></tr>";
			} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$match_pallet_file_xls .= "</TABLE>";
		$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."' AND PALLET_TO_DB_COMPARE = 'MATCH'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$match_count = $Short_Term_Row['THE_COUNT'];
		// ---------------------------------------------------------------
		$noDB_pallet_file_xls = "<TABLE border=0 CELLSPACING=1>";
		$noDB_pallet_file_xls .= "<tr>
						<td><b>IMP</b></td>
						<td><b>PLT_ID</b></td>
						<td><b>Commodity</b></td>
						<td><b>Variety</b></td>
						<td><b>Label</b></td>
						<td><b>Size</b></td>
						<td><b>Hatch</b></td>
						<td><b>Qty</b></td>
						<td><b>Loc</b></td>
						<td><b>Grower</b></td>
						<td><b>Package</b></td>
					</tr>";
		$noDB_pallet_file = "IMP,PLT_ID,Commodity,Variety,Label,Size,Hatch,Qty,Loc,Grower,Package\r\n";
		$sql = "SELECT * FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."' AND PALLET_TO_DB_COMPARE = 'NOTINDB'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$noDB_pallet_file_xls .= "<tr><td>No pallets in upload failed to match manifest</tr></td>";
		} else {
			do {
				$noDB_pallet_file .= $header_row['CUSTOMER_ID'].","
										.$Short_Term_Row['PALLET_ID'].","
										.$Short_Term_Row['COMMODITY_CODE'].","
										.$Short_Term_Row['VARIETY'].","
										.$Short_Term_Row['LABEL'].","
										.$Short_Term_Row['CARGO_SIZE'].","
										.$Short_Term_Row['HATCH'].","
										.$Short_Term_Row['QTY'].","
										.$Short_Term_Row['WAREHOUSE_LOCATION'].","
										.$Short_Term_Row['GROWER'].","
										.$Short_Term_Row['PACKAGE']."\r\n";
				$noDB_pallet_file_xls .= "<tr><td>".$header_row['CUSTOMER_ID']."</td>".
										"<td>".$Short_Term_Row['PALLET_ID']."</td>".
										"<td>".$Short_Term_Row['COMMODITY_CODE']."</td>".
										"<td>".$Short_Term_Row['VARIETY']."</td>".
										"<td>".$Short_Term_Row['LABEL']."</td>".
										"<td>".$Short_Term_Row['CARGO_SIZE']."</td>".
										"<td>".$Short_Term_Row['HATCH']."</td>".
										"<td>".$Short_Term_Row['QTY']."</td>".
										"<td>".$Short_Term_Row['WAREHOUSE_LOCATION']."</td>".
										"<td>".$Short_Term_Row['GROWER']."</td>".
										"<td>".$Short_Term_Row['PACKAGE']."</td></tr>";
			} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$noDB_pallet_file_xls .= "</TABLE>";
		$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."' AND PALLET_TO_DB_COMPARE = 'NOTINDB'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$noDB_count = $Short_Term_Row['THE_COUNT'];
		// ---------------------------------------------------------------
		$noFILE_pallet_file_xls = "<TABLE border=0 CELLSPACING=1>";
		$noFILE_pallet_file_xls .= "<tr>
						<td><b>IMP</b></td>
						<td><b>PLT_ID</b></td>
						<td><b>Commodity</b></td>
						<td><b>Variety</b></td>
						<td><b>Label</b></td>
						<td><b>Size</b></td>
						<td><b>Hatch</b></td>
						<td><b>Qty</b></td>
						<td><b>Loc</b></td>
						<td><b>Grower</b></td>
						<td><b>Package</b></td>
					</tr>";
		$noFILE_pallet_file = "IMP,PLT_ID,Commodity,Variety,Label,Size,Hatch,Qty,Loc,Grower,Package\r\n";
		$sql = "SELECT * FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."' AND PALLET_TO_DB_COMPARE = 'NOTINFILE'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$noFILE_pallet_file_xls .= "<tr><td>No pallets in DB that were not found in uploaded file</tr></td>";
		} else {
			do {
				$noFILE_pallet_file .= $header_row['CUSTOMER_ID'].","
										.$Short_Term_Row['PALLET_ID'].","
										.$Short_Term_Row['COMMODITY_CODE'].","
										.$Short_Term_Row['VARIETY'].","
										.$Short_Term_Row['LABEL'].","
										.$Short_Term_Row['CARGO_SIZE'].","
										.$Short_Term_Row['HATCH'].","
										.$Short_Term_Row['QTY'].","
										.$Short_Term_Row['WAREHOUSE_LOCATION'].","
										.$Short_Term_Row['GROWER'].","
										.$Short_Term_Row['PACKAGE']."\r\n";
				$noFILE_pallet_file_xls .= "<tr><td>".$header_row['CUSTOMER_ID']."</td>".
										"<td>".$Short_Term_Row['PALLET_ID']."</td>".
										"<td>".$Short_Term_Row['COMMODITY_CODE']."</td>".
										"<td>".$Short_Term_Row['VARIETY']."</td>".
										"<td>".$Short_Term_Row['LABEL']."</td>".
										"<td>".$Short_Term_Row['CARGO_SIZE']."</td>".
										"<td>".$Short_Term_Row['HATCH']."</td>".
										"<td>".$Short_Term_Row['QTY']."</td>".
										"<td>".$Short_Term_Row['WAREHOUSE_LOCATION']."</td>".
										"<td>".$Short_Term_Row['GROWER']."</td>".
										"<td>".$Short_Term_Row['PACKAGE']."</td></tr>";
			} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$noFILE_pallet_file_xls .= "</TABLE>";
		$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."' AND PALLET_TO_DB_COMPARE = 'NOTINFILE'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$noFILE_count = $Short_Term_Row['THE_COUNT'];
		// ---------------------------------------------------------------
		$trans_pallet_file_xls = "<TABLE border=0 CELLSPACING=1>";
		$trans_pallet_file_xls .= "<tr>
						<td><b>IMP</b></td>
						<td><b>PLT_ID</b></td>
						<td><b>Commodity</b></td>
						<td><b>Variety</b></td>
						<td><b>Label</b></td>
						<td><b>Size</b></td>
						<td><b>Hatch</b></td>
						<td><b>Qty</b></td>
						<td><b>Loc</b></td>
						<td><b>Grower</b></td>
						<td><b>Package</b></td>
					</tr>";
		$trans_pallet_file = "IMP,PLT_ID,Commodity,Variety,Label,Size,Hatch,Qty,Loc,Grower,Package\r\n";
		$sql = "SELECT * FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."' AND PALLET_TO_DB_COMPARE = 'TOBEINSERT' AND PALLET_ID IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$header_row['ARRIVAL_NUM']."' AND RECEIVER_ID = '".$header_row['CUSTOMER_ID']."')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$trans_pallet_file_xls .= "<tr><td>No pallets.</tr></td>";
		} else {
			do {
				$trans_pallet_file .= $header_row['CUSTOMER_ID'].","
										.$Short_Term_Row['PALLET_ID'].","
										.$Short_Term_Row['COMMODITY_CODE'].","
										.$Short_Term_Row['VARIETY'].","
										.$Short_Term_Row['LABEL'].","
										.$Short_Term_Row['CARGO_SIZE'].","
										.$Short_Term_Row['HATCH'].","
										.$Short_Term_Row['QTY'].","
										.$Short_Term_Row['WAREHOUSE_LOCATION'].","
										.$Short_Term_Row['GROWER'].","
										.$Short_Term_Row['PACKAGE']."\r\n";
				$trans_pallet_file_xls .= "<tr><td>".$header_row['CUSTOMER_ID']."</td>".
										"<td>".$Short_Term_Row['PALLET_ID']."</td>".
										"<td>".$Short_Term_Row['COMMODITY_CODE']."</td>".
										"<td>".$Short_Term_Row['VARIETY']."</td>".
										"<td>".$Short_Term_Row['LABEL']."</td>".
										"<td>".$Short_Term_Row['CARGO_SIZE']."</td>".
										"<td>".$Short_Term_Row['HATCH']."</td>".
										"<td>".$Short_Term_Row['QTY']."</td>".
										"<td>".$Short_Term_Row['WAREHOUSE_LOCATION']."</td>".
										"<td>".$Short_Term_Row['GROWER']."</td>".
										"<td>".$Short_Term_Row['PACKAGE']."</td></tr>";
			} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$trans_pallet_file_xls .= "</TABLE>";
		$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."' AND PALLET_TO_DB_COMPARE = 'TOBEINSERT' AND PALLET_ID IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$header_row['ARRIVAL_NUM']."' AND RECEIVER_ID = '".$header_row['CUSTOMER_ID']."')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$trans_count = $Short_Term_Row['THE_COUNT'];
		// ---------------------------------------------------------------
		$cant_trans_pallet_file_xls = "<TABLE border=0 CELLSPACING=1>";
		$cant_trans_pallet_file_xls .= "<tr>
						<td><b>IMP</b></td>
						<td><b>PLT_ID</b></td>
						<td><b>Commodity</b></td>
						<td><b>Variety</b></td>
						<td><b>Label</b></td>
						<td><b>Size</b></td>
						<td><b>Hatch</b></td>
						<td><b>Qty</b></td>
						<td><b>Loc</b></td>
						<td><b>Grower</b></td>
						<td><b>Package</b></td>
					</tr>";
		$cant_trans_pallet_file = "IMP,PLT_ID,Commodity,Variety,Label,Size,Hatch,Qty,Loc,Grower,Package\r\n";
		$sql = "SELECT * FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."' AND PALLET_TO_DB_COMPARE = 'TOBEINSERT' AND PALLET_ID IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$header_row['ARRIVAL_NUM']."' AND RECEIVER_ID != '".$header_row['CUSTOMER_ID']."')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$cant_trans_pallet_file_xls .= "<tr><td>No pallets.</tr></td>";
		} else {
			do {
				$cant_trans_pallet_file .= $header_row['CUSTOMER_ID'].","
										.$Short_Term_Row['PALLET_ID'].","
										.$Short_Term_Row['COMMODITY_CODE'].","
										.$Short_Term_Row['VARIETY'].","
										.$Short_Term_Row['LABEL'].","
										.$Short_Term_Row['CARGO_SIZE'].","
										.$Short_Term_Row['HATCH'].","
										.$Short_Term_Row['QTY'].","
										.$Short_Term_Row['WAREHOUSE_LOCATION'].","
										.$Short_Term_Row['GROWER'].","
										.$Short_Term_Row['PACKAGE']."\r\n";
				$cant_trans_pallet_file_xls .= "<tr><td>".$header_row['CUSTOMER_ID']."</td>".
										"<td>".$Short_Term_Row['PALLET_ID']."</td>".
										"<td>".$Short_Term_Row['COMMODITY_CODE']."</td>".
										"<td>".$Short_Term_Row['VARIETY']."</td>".
										"<td>".$Short_Term_Row['LABEL']."</td>".
										"<td>".$Short_Term_Row['CARGO_SIZE']."</td>".
										"<td>".$Short_Term_Row['HATCH']."</td>".
										"<td>".$Short_Term_Row['QTY']."</td>".
										"<td>".$Short_Term_Row['WAREHOUSE_LOCATION']."</td>".
										"<td>".$Short_Term_Row['GROWER']."</td>".
										"<td>".$Short_Term_Row['PACKAGE']."</td></tr>";
			} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
		}
		$cant_trans_pallet_file_xls .= "</TABLE>";
		$sql = "SELECT COUNT(*) THE_COUNT FROM CHILEAN_CUSTOMER_PLT_CHANGES WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."' AND PALLET_TO_DB_COMPARE = 'TOBEINSERT' AND PALLET_ID IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$header_row['ARRIVAL_NUM']."' AND RECEIVER_ID != '".$header_row['CUSTOMER_ID']."')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$cant_trans_count = $Short_Term_Row['THE_COUNT'];


		
		
		
		$sql = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$header_row['ARRIVAL_NUM']."' AND RECEIVER_ID = '".$header_row['CUSTOMER_ID']."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$total_vessel_count = $Short_Term_Row['THE_COUNT'];
		
		$sql = "SELECT LR_NUM, VESSEL_NAME FROM VESSEL_PROFILE WHERE LR_NUM = '".$header_row['ARRIVAL_NUM']."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$vessel_name = $header_row['ARRIVAL_NUM'];
			$vessel_num = $header_row['ARRIVAL_NUM'];
		} else {
			$vessel_name = $Short_Term_Row['VESSEL_NAME'];
			$vessel_num = $Short_Term_Row['LR_NUM'];
		}

		$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$header_row['CUSTOMER_ID']."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$email_subj_cust = $header_row['CUSTOMER_ID'];
		} else {
			$email_subj_cust = $Short_Term_Row['CUSTOMER_NAME'];
		}

		
		$Filematch=chunk_split(base64_encode($match_pallet_file));
		$FilenoDB=chunk_split(base64_encode($noDB_pallet_file));
		$FilenoFILE=chunk_split(base64_encode($noFILE_pallet_file));
		$Filetrans=chunk_split(base64_encode($trans_pallet_file));
		$Filecanttrans=chunk_split(base64_encode($cant_trans_pallet_file));
		$Filematchxls=chunk_split(base64_encode($match_pallet_file_xls));
		$FilenoDBxls=chunk_split(base64_encode($noDB_pallet_file_xls));
		$FilenoFILExls=chunk_split(base64_encode($noFILE_pallet_file_xls));
		$Filetransxls=chunk_split(base64_encode($trans_pallet_file_xls));
		$Filecanttransxls=chunk_split(base64_encode($cant_trans_pallet_file_xls));
		
		$mailsubject = $email_subj_cust.": Confirmation of Sort File Upload for ".$vessel_name."\r\n";

		$mailTo = "PoWNoReplies@port.state.de.us";
		$sql = "SELECT EMAIL_ADDR FROM EMAIL_LIST WHERE CUSTOMER_ID = '".$header_row['CUSTOMER_ID']."'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$mailTo .= ",".$Short_Term_Row['EMAIL_ADDR'];
			while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$mailTo .= ",".$Short_Term_Row['EMAIL_ADDR'];
			}
		}
		echo "mailto: ".$mailTo."\n";
//		$mailTo = "awalter@port.state.de.us,sadu@port.state.de.us";

		$mailheaders = "From: " . "POWNoReply@port.state.de.us\r\n";
		$mailheaders .= "Cc: " . "pcutler@port.state.de.us,martym@port.state.de.us,ltreut@port.state.de.us,schapman@port.state.de.us\r\n";
		$mailheaders .= "Bcc: " . "archive@port.state.de.us,awalter@port.state.de.us,lstewart@port.state.de.us,sadu@port.state.de.us\r\n";

		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
		$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
		$mailheaders .= "X-Mailer: PHP4\r\n";
		$mailheaders .= "X-Priority: 3\r\n";
//		$mailheaders .= "Return-Path: MailServer@port.state.de.us\r\n";
		$mailheaders .= "This is a multi-part Content in MIME format.\r\n";

		$Content="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
		$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
		$Content.="\r\n";
		$Content.= "Thank you for uploading your Sort File for the Vessel ".$vessel_name." (POW Vessel # ".$vessel_num.").  Your confirmation number is ".$header_row['TRANSACTION_ID']."\r\n\r\n";
		$Content .= "Summary of Upload:\r\n";
		$Content .= "Total Pallets Expected on Vessel Per Shipping Line: ".$total_vessel_count."\r\n\r\n";
		$Content .= "Of the Total pallets listed on the Carle file, ".$match_count." in your Sort File matched the number expected.  See file MatchedPallets.txt for details.\r\n";
		$Content .= $noDB_count." pallets in your Sort file did not match the Carle number.  See file UnMatchedPallets.txt for details.\r\n";
//		$Content .= "Pallets Transferred to you: ".$noFILE_count.".  See file TransPallets.txt for details.\r\n\r\n";
//		$Content .= "Pallets requested to be transferred to you, but already taken: ".$noFILE_coun	t.".  See file NoTransPallets.txt for details.\r\n\r\n";
		$Content .= $noFILE_count." pallets were in the Carle file but not in your sort file.  These pallets still require sort information.   See file RemainderPallets.txt for details.\r\n\r\n";
		$Content .= "To avoid delays in handling your cargo, you must upload all sort information at least 72 hours prior to the first day of vessel discharge.\r\n***Please DO NOT REPLY to this address.  It is not a monitored mailbox ***\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"MatchedPallets.txt\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$Filematch;
		$Content.="\r\n";
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"MatchedPallets.xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$Filematchxls;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"UnMatchedPallets.txt\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$FilenoDB;
		$Content.="\r\n";
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"UnMatchedPallets.xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$FilenoDBxls;
		$Content.="\r\n";
/*
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"TransPallets.txt\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$Filetrans;
		$Content.="\r\n";
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"TransPallets.xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$Filetransxls;
		$Content.="\r\n";

		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"NoTransPallets.txt\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$Filecanttrans;
		$Content.="\r\n";
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"NoTransPallets.xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$Filecanttransxls;
		$Content.="\r\n";
*/
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"RemainderPallets.txt\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$FilenoFILE;
		$Content.="\r\n";
		$Content.="--MIME_BOUNDRY\r\n";
		$Content.="Content-Type: application/pdf; name=\"RemainderPallets.xls\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$FilenoFILExls;
		$Content.="\r\n";

		
		$Content.="--MIME_BOUNDRY--\n";

		mail($mailTo, $mailsubject, $Content, $mailheaders);


		// email 2:
		// if cargo was attempting transfer, email parties about A) that went through, B) that didn't
		if($trans_count > 0 || $cant_trans_count > 0){
			$mailsubject = $email_subj_cust.": Exceptions\r\n";
			$Content_Trans = "";
			if($trans_count > 0){
				$Content_Trans.="--MIME_BOUNDRY\r\n";
				$Content_Trans.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
				$Content_Trans.="Content-Transfer-Encoding: quoted-printable\r\n";
				$Content_Trans.="\r\n";
				$Content_Trans.="ExceptionA.xls:  These pallets have been added to this vessel although, per the Shipping Line, this pallet belongs to a different receiver.  PoW will determine will resolve during discharge.  If you need resolution earlier, please contact the Shipping Line.\r\n";
				$Content_Trans.="--MIME_BOUNDRY\r\n";
				$Content_Trans.="Content-Type: application/pdf; name=\"ExceptionA.txt\"\r\n";
				$Content_Trans.="Content-disposition: attachment\r\n";
				$Content_Trans.="Content-Transfer-Encoding: base64\r\n";
				$Content_Trans.="\r\n";
				$Content_Trans.=$Filetrans;
				$Content_Trans.="\r\n";
				$Content_Trans.="--MIME_BOUNDRY\r\n";
				$Content_Trans.="Content-Type: application/pdf; name=\"ExceptionA.xls\"\r\n";
				$Content_Trans.="Content-disposition: attachment\r\n";
				$Content_Trans.="Content-Transfer-Encoding: base64\r\n";
				$Content_Trans.="\r\n";
				$Content_Trans.=$Filetransxls;
				$Content_Trans.="\r\n";
			}
/*			if($cant_trans_count > 0){
				$Content_Trans.="--MIME_BOUNDRY\r\n";
				$Content_Trans.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
				$Content_Trans.="Content-Transfer-Encoding: quoted-printable\r\n";
				$Content_Trans.="\r\n";
				$Content_Trans.="ExceptionB.xls:  The following pallets were not assigned to you by the shipping line and have been claimed by a third party.   If you still feel that these pallets are yours kindly contact us.\r\n";
				$Content_Trans.="--MIME_BOUNDRY\r\n";
				$Content_Trans.="Content-Type: application/pdf; name=\"ExceptionB.txt\"\r\n";
				$Content_Trans.="Content-disposition: attachment\r\n";
				$Content_Trans.="Content-Transfer-Encoding: base64\r\n";
				$Content_Trans.="\r\n";
				$Content_Trans.=$Filecanttrans;
				$Content_Trans.="\r\n";
				$Content_Trans.="--MIME_BOUNDRY\r\n";
				$Content_Trans.="Content-Type: application/pdf; name=\"ExceptionB.xls\"\r\n";
				$Content_Trans.="Content-disposition: attachment\r\n";
				$Content_Trans.="Content-Transfer-Encoding: base64\r\n";
				$Content_Trans.="\r\n";
				$Content_Trans.=$Filecanttransxls;
				$Content_Trans.="\r\n";
			}
*/
			$Content.="--MIME_BOUNDRY--\n";

			mail($mailTo, $mailsubject, $Content_Trans, $mailheaders);
		}
/*
		// email 3:
		// if cargo was transferred back to original owner, tell the first claimant they lost it.
		if($trans_count > 0){
			$sql = "SELECT DISTINCT PREV_CUST_FROM_SORT_FILE FROM CARGO_TRACKING_ADDITIONAL_DATA
					WHERE PREV_CUST_FROM_SORT_FILE IS NOT NULL
					AND ARRIVAL_NUM = '".$header_row['ARRIVAL_NUM']."'
					AND RECEIVER_ID = '".$header_row['CUSTOMER_ID']."'
					AND MANIFESTED_RECEIVER_ID = RECEIVER_ID
					AND PALLET_ID IN (SELECT PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES 
									WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."'
									AND PALLET_TO_DB_COMPARE = 'TOBETRANS')";
			ora_parse($loop_cursor_for_email2, $sql);
			ora_exec($loop_cursor_for_email2);
			while(ora_fetch_into($loop_cursor_for_email2, $Email_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
				$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$Email_Row['PREV_CUST_FROM_SORT_FILE']."'";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$email_subj_cust = "No Customer Name Found";
				} else {
					$email_subj_cust = $Short_Term_Row['CUSTOMER_NAME'];
				}
				$mailsubject = $email_subj_cust.": Change in Consignee\r\n";

				$pallet_list = "";
				$mailTo2 = "hdadmin@port.state.de.us";
				$sql = "SELECT EMAIL_ADDR FROM EMAIL_LIST WHERE CUSTOMER_ID = '".$Email_Row['PREV_CUST_FROM_SORT_FILE']."'"; 
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				if(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$mailTo2 .= ",".$Short_Term_Row['EMAIL_ADDR'];
					while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
						$mailTo2 .= ",".$Short_Term_Row['EMAIL_ADDR'];
					}
				}
				echo "mailto2: ".$mailTo2."\n";
//				$mailTo2 = "awalter@port.state.de.us,sadu@port.state.de.us";
				
//				$mailsubject = "Pallets from the vessel ".$vessel_name." have been transferred out of your name.\r\n";

				$mailheaders = "From: " . "hdadmin@port.state.de.us\r\n";
				$mailheaders .= "Cc: " . "martym@port.state.de.us,bdempsey@port.state.de.us,schapman@port.state.de.us,jcarlef@jcarle.cl,jherrera@jcarle.cl,jdarrouy@jcarle.cl,".$mailTo."\r\n";
				$mailheaders .= "Bcc: " . "ltreut@port.state.de.us,awalter@port.state.de.us,lstewart@port.state.de.us,sadu@port.state.de.us,ithomas@port.state.de.us,fvignuli@port.state.de.us\r\n";
				$mailheaders .= "MIME-Version: 1.0\r\n";
				$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
				$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
				$mailheaders .= "X-Mailer: PHP4\r\n";
				$mailheaders .= "X-Priority: 3\r\n";
				$mailheaders  .= "This is a multi-part Content in MIME format.\r\n";

				$Content="--MIME_BOUNDRY\r\n";
				$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
				$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
				$Content.="\r\n";
				$Content.= "The following pallets assigned to you have now been claimed by a different consignee.  This consignee was the one specified by the shipping line for these pallets.\r\n\r\n";
				$Content.= "Pallets:\r\n";
				$sql = "SELECT PALLET_ID FROM CARGO_TRACKING_ADDITIONAL_DATA
						WHERE PREV_CUST_FROM_SORT_FILE = '".$Email_Row['PREV_CUST_FROM_SORT_FILE']."'
						AND ARRIVAL_NUM = '".$header_row['ARRIVAL_NUM']."'
						AND RECEIVER_ID = '".$header_row['CUSTOMER_ID']."'
						AND MANIFESTED_RECEIVER_ID = RECEIVER_ID
						AND PALLET_ID IN (SELECT PALLET_ID FROM CHILEAN_CUSTOMER_PLT_CHANGES 
										WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."'
										AND PALLET_TO_DB_COMPARE = 'TOBETRANS')";
				ora_parse($Short_Term_Cursor, $sql);
				ora_exec($Short_Term_Cursor);
				while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
					$pallet_list .= $Short_Term_Row['PALLET_ID']."\r\n";
				}
				$Content .= $pallet_list;
				$Content.="--MIME_BOUNDRY--\n";

				if($pallet_list != ""){
					mail($mailTo2, $mailsubject, $Content, $mailheaders);
				}
			}
		}
*/
		// email 4:
		// only distribute if mismatched pallets detected.
		// uses previous MailTo list before redefining

		if($noDB_count > 0){
			/*
			$sql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '".$header_row['CUSTOMER_ID']."'";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
			$cust_name = $Short_Term_Row['CUSTOMER_NAME'];

			$mailsubject = $email_subj_cust." - ".$noDB_count." Pallets are missing in your file for the ".$vessel_name."\r\n";

			$mailheaders = "From: " . "hdadmin@port.state.de.us\r\n";
			$mailheaders .= "Cc: " . "martym@port.state.de.us,bdempsey@port.state.de.us,jcarlef@jcarle.cl,jherrera@jcarle.cl,jdarrouy@jcarle.cl,".$mailTo."\r\n";
			$mailheaders .= "Bcc: " . "ltreut@port.state.de.us,awalter@port.state.de.us,hdadmin@port.state.de.us,lstewart@port.state.de.us,sadu@port.state.de.us,ithomas@port.state.de.us,fvignuli@port.state.de.us\r\n";
			$mailheaders .= "MIME-Version: 1.0\r\n";
			$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
			$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
			$mailheaders .= "X-Mailer: PHP4\r\n";
			$mailheaders .= "X-Priority: 3\r\n";
//			$mailheaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
			$mailheaders  .= "This is a multi-part Content in MIME format.\r\n";

//			$mailTo = "awalter@port.state.de.us";
			$mailTo = "rescobar@psw.cl,jcoulahan@murphymarine.com,pfarrell@murphymarine.com";

			$Content="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
			$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
			$Content.="\r\n";
			$Content.= "receiver ".$cust_name." has tried to upload warehouse sorting information for ".$noDB_count." pallets for the upcoming Vessel ".$vessel_name." (POW # ".$vessel_num.").\r\n";
			$Content.= "The list of such pallets are attached.   Your data provided to POW is missing these pallets for this receiver.\r\n";
			$Content.= "If you should determine that these pallets indeed belong to the receiver ".$cust_name.", kindly reply to this message or email hdadmin@port.state.de.us  with all pertinent details about the pallets.\r\n\r\n";
			$Content.= "Your corrections need to arrive at least 72 hours prior to the first day of vessel discharge at the Port of Wilmington in order to be handled in time for discharge.\r\n";

			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/pdf; name=\"UnMatchedPallets.txt\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$FilenoDB;
			$Content.="\r\n";
			$Content.="--MIME_BOUNDRY\r\n";
			$Content.="Content-Type: application/pdf; name=\"UnMatchedPallets.xls\"\r\n";
			$Content.="Content-disposition: attachment\r\n";
			$Content.="Content-Transfer-Encoding: base64\r\n";
			$Content.="\r\n";
			$Content.=$FilenoDBxls;
			$Content.="\r\n";

			
			$Content.="--MIME_BOUNDRY--\n";

			mail($mailTo, $mailsubject, $Content, $mailheaders);
			*/
		}


		$sql = "UPDATE CHILEAN_PLT_CHANGES_HEADER SET EMAIL_SENT = 'Y' WHERE TRANSACTION_ID = '".$header_row['TRANSACTION_ID']."'";
		ora_parse($modify_cursor, $sql);
		ora_exec($modify_cursor);

	}