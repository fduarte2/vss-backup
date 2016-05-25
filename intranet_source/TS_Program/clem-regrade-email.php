<?
/*
*	Adam Walter, Oct 20, 2009.
*
*	Cron auto-email to Clem people regarding 
*	Regrades of Clementine pallets.
*	Expected to run everyday, as close to, but not after, midnight as possible
*************************************************************************/
 
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$mail_date = date('m/d/Y');

//	$cursor_first = ora_open($conn);
//	$cursor_second = ora_open($conn);
//	$cursor_third = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$mailsubject = "Regrades for Today, ".date('m/d/Y');

	$mailTo = "dcarizzo@gmail.com,";
	$mailTo .= "s.bricks@freshfruitexport.com\r\n";
//	$mailTo = "awalter@port.state.de.us\r\n";

	$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
//	$mailheaders .= "Cc: " . "hdadmin@port.state.de.us,awalter@port.state.de.us\r\n"; 
	$mailheaders .= "Bcc: " . "archive@port.state.de.us,awalter@port.state.de.us,lstewart@port.state.de.us,sadu@port.state.de.us\r\n"; 


//			AND CT.RECEIVER_ID = '439'
	$sql = "SELECT
				SUBSTR(ct.pallet_id, 18, 6) the_pallet,
				ct.cargo_size,
				dil.inspector_id,
				ct.exporter_code
			FROM cargo_tracking ct
			INNER JOIN dc_inspection_log dil
				ON ct.arrival_num = dil.arrival_num
			INNER JOIN dc_inspected_pallet dip
				ON dil.transaction_id = dip.transaction_id
				AND ct.pallet_id = dip.pallet_id
			LEFT JOIN voyage
				ON TO_CHAR(voyage.lr_num) = ct.arrival_num
			WHERE
				voyage.port_of_destination is null
				AND ct.receiver_id = '835'
				AND dil.action_type IN ('REGRADE', 'BOTH')
				AND TO_CHAR(dil.inspection_datetime, 'MM/DD/YYYY') = '".date('m/d/Y')."'
			ORDER BY
				dil.inspector_id,
				ct.cargo_size,
				ct.exporter_code,
				SUBSTR(ct.pallet_id, 18, 6)";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	if(!ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$file = "No Regraded files today";
	} else {
//		$file = "<TABLE border=0 CELLSPACING=1>";
		$file .= "Regrader,Size,Station,Pallet\r\n";
		do {
			$file .= $Short_Term_Row['INSPECTOR_ID'].",".
							$Short_Term_Row['CARGO_SIZE'].",".
							$Short_Term_Row['EXPORTER_CODE'].",".
							$Short_Term_Row['THE_PALLET']."\r\n";
		} while(ora_fetch_into($Short_Term_Cursor, $Short_Term_Row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
//		$file .= "</table>";
	}

	$File_out=chunk_split(base64_encode($file));

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

	$Content.="--MIME_BOUNDRY\r\n";
	$Content.="Content-Type: application/pdf; name=\"Regrades.txt\"\r\n";
	$Content.="Content-disposition: attachment\r\n";
	$Content.="Content-Transfer-Encoding: base64\r\n";
	$Content.="\r\n";
	$Content.=$File_out;
	$Content.="\r\n";
	$Content.="--MIME_BOUNDRY--\n";

	mail($mailTo, $mailsubject, $Content, $mailheaders);
