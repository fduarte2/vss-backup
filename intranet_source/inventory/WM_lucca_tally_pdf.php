<?
  // All POW files need this session file included
  include("pow_session.php");

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
   $Short_Term_Cursor = ora_open($conn);

   $submit = $HTTP_POST_VARS['submit'];
   $order = strtoupper($HTTP_POST_VARS['order']);
   $truck = $HTTP_POST_VARS['truck'];
   $trkcompany = $HTTP_POST_VARS['trkcompany'];
   $lastname = $HTTP_POST_VARS['lastname'];
   $seal = $HTTP_POST_VARS['seal'];

   if($submit == "Generate Tally And Send Email"){
		$email = true;
   } else {
	   $email = false;
   }

   $datetime = date('m/d/Y h:i:s');

   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter');

   $pdf->ezSetMargins(20,20,65,65);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');

	$sql = "SELECT * FROM CARGO_ACTIVITY WHERE ORDER_NUM = '".$order."'
			AND CUSTOMER_ID IN
				(SELECT RECEIVER_ID FROM SCANNER_ACCESS
				WHERE VALID_SCANNER = 'WALMART')
			AND (PALLET_ID IN
					(SELECT PALLET_ID FROM CARGO_TRACKING WHERE CARGO_TYPE_ID IN ('7', '9'))
				OR
				PALLET_ID IN
					(SELECT PALLET_ID FROM WDI_SPLIT_PALLETS WHERE CARGO_TYPE_ID IN ('7', '9'))
				)";
	$ora_success = ora_parse($Short_Term_Cursor, $sql);
	$ora_success = ora_exec($Short_Term_Cursor, $sql);
	if(!ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$pdf->ezText("<b>Order $order has no Lucca/Crossdock activity in our system.</b>", 14, $center);
	} else {
		// this order exists.  Time for the fun!
		$sql = "SELECT NVL(VESSEL_NAME, 'UNKNOWN') THE_VES FROM VESSEL_PROFILE WHERE LR_NUM = '".$row['ARRIVAL_NUM']."'";
		$ora_success = ora_parse($Short_Term_Cursor, $sql);
		$ora_success = ora_exec($Short_Term_Cursor, $sql);
		ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
		$ves_name = $short_term_row['THE_VES'];

		$pdf->ezSetDy(-5);
		$pdf->ezText("<b>WAL-MART DIRECT IMPORTS PIER</b>", 12, $center);
		$pdf->ezSetDy(-3);
		$pdf->ezText("<b>601 N. WALTON BLVD</b>", 12, $center);
		$pdf->ezSetDy(-3);
		$pdf->ezText("<b>BENTONVILLE, AR  72716-0410</b>", 12, $center);
		$pdf->ezSetDy(-3);
		$pdf->ezText("<b>A/P MANAGER/MAIL STOP 0410L37</b>", 12, $center);
		$pdf->ezSetDy(-10);

		$pdf->ezText("PORT OF WILMINGTON", 12, $left);
		$pdf->ezSetDy(-3);
		$pdf->ezText("1 HAUSEL ROAD", 12, $left);
		$pdf->ezSetDy(-3);
		$pdf->ezText("WILMINGTON, DE  19801", 12, $left);
		$pdf->ezSetDy(49);
		$pdf->ezText("Printed:  $datetime", 12, $right);
		$pdf->ezSetDy(-3);
		$pdf->ezText("Order:  $order", 12, $right);
		$pdf->ezSetDy(-3);
		$pdf->ezText("Vessel:  $ves_name", 12, $right);
		$pdf->ezSetDy(-15);
		$pdf->ezText("Truck#:  $truck", 10, $left);
//		$pdf->ezSetDy(-3);
		$pdf->ezText("Trucking Company:  $trkcompany", 10, $left);
//		$pdf->ezSetDy(-3);
		$pdf->ezText("Driver:  $lastname", 10, $left);
		$pdf->ezSetDy(37);
		$pdf->ezText("Ship To:  Lucca Cold Storage", 10, $right);
		$pdf->ezText("2321 Industrial Way", 10, $right);
		$pdf->ezText("Vineland NJ  08360", 10, $right);
		$pdf->ezSetDy(-20);
		
		// DSP = distinct pallet list
		$pallets = array();
		$pallets_heading = array();
		$sql = "SELECT CA.PALLET_ID, NVL(WIM.ITEM_DESCRIPTION, 'UNKNOWN') THE_DESC, DSP.CARGO_SIZE, 
					QTY_CHANGE, SUB_CUSTID, MARK, NVL(TO_CHAR(WM_ITEM_NUM), 'UNKNOWN') MASTER_NUM,
					DSP.BATCH_ID GROWERNUM, WIM.GROWER GROWERNAME
				FROM
				CARGO_ACTIVITY CA,
				(SELECT 'SINGLE' SPLIT_PALLET_ID, CT.* FROM CARGO_TRACKING CT
					WHERE CARGO_TYPE_ID IN ('9')
					AND RECEIVER_ID IN 
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
					AND PALLET_ID NOT IN
						(SELECT PALLET_ID FROM WDI_SPLIT_PALLETS)
					UNION
					SELECT * FROM WDI_SPLIT_PALLETS
					WHERE CARGO_TYPE_ID IN ('9')
					AND RECEIVER_ID IN 
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
				) DSP,
				WM_ITEMNUM_MAPPING WIM
				WHERE CA.ORDER_NUM = '".$order."'
					AND SERVICE_CODE = '6'
					AND ACTIVITY_DESCRIPTION IS NULL
					AND CA.PALLET_ID = DSP.PALLET_ID
					AND CA.CUSTOMER_ID = DSP.RECEIVER_ID
					AND CA.ARRIVAL_NUM = DSP.ARRIVAL_NUM
					AND (CA.BATCH_ID = DSP.SPLIT_PALLET_ID
						OR
						CA.BATCH_ID IS NULL)
					AND DSP.BATCH_ID = TO_CHAR(ITEM_NUM(+))
				ORDER BY PALLET_ID, SUB_CUSTID";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			array_push($pallets, array('qty'=>$row['QTY_CHANGE'],
										'desc'=>$row['THE_DESC'],
										'size'=>$row['CARGO_SIZE'],
										'plt'=>$row['PALLET_ID'],
										'grower'=>$row['SUB_CUSTID'],
										'PO'=>$row['MARK'],
										'item'=>$row['MASTER_NUM'],
										'grownum'=>$row['GROWERNUM'],
										'growname'=>$row['GROWERNAME']));
		}

		$pallets_heading = array('qty'=>'QTY',
									'desc'=>'DESCRIPTION',
									'size'=>'SIZE',
									'plt'=>'PALLET ID',
									'grower'=>'GROWR',
									'PO'=>'BOOKING PO',
									'item'=>'ITEM NO.',
									'grownum'=>'GROWER#',
									'growname'=>'GROWER DESC');

		// ,'cols'=>$arrCol
		$pdf->ezTable($pallets, $pallets_heading, '', array('showHeadings'=>1, 
															'shaded'=>0, 
															'showLines'=>0,
															'fontSize'=>8,
															'width'=>550,
															'colGap'=>2));
		$pdf->ezSetDy(-20);

		$group = array();
		$group_heading = array();
		$plt_total = 0;
		$case_total = 0;
		$sql = "SELECT COUNT(DISTINCT CA.PALLET_ID) THE_PLTS, SUM(QTY_CHANGE) THE_CASES, 
					COMMODITY, MARK, NVL(TO_CHAR(WM_ITEM_NUM), 'UNKNOWN') MASTER_NUM
				FROM
				CARGO_ACTIVITY CA,
				(SELECT 'SINGLE' SPLIT_PALLET_ID, CT.* FROM CARGO_TRACKING CT
					WHERE CARGO_TYPE_ID IN ('9')
					AND RECEIVER_ID IN 
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
					AND PALLET_ID NOT IN
						(SELECT PALLET_ID FROM WDI_SPLIT_PALLETS)
					UNION
					SELECT * FROM WDI_SPLIT_PALLETS
					WHERE CARGO_TYPE_ID IN ('9')
					AND RECEIVER_ID IN 
						(SELECT RECEIVER_ID FROM SCANNER_ACCESS
						WHERE VALID_SCANNER = 'WALMART')
				) DSP,
				WM_ITEMNUM_MAPPING WIM
				WHERE CA.ORDER_NUM = '".$order."'
					AND SERVICE_CODE = '6'
					AND ACTIVITY_DESCRIPTION IS NULL
					AND CA.PALLET_ID = DSP.PALLET_ID
					AND CA.CUSTOMER_ID = DSP.RECEIVER_ID
					AND CA.ARRIVAL_NUM = DSP.ARRIVAL_NUM
					AND (CA.BATCH_ID = DSP.SPLIT_PALLET_ID
						OR
						CA.BATCH_ID IS NULL)
					AND DSP.BATCH_ID = TO_CHAR(ITEM_NUM(+))
				GROUP BY COMMODITY, MARK, NVL(TO_CHAR(WM_ITEM_NUM), 'UNKNOWN')
				ORDER BY COMMODITY, MARK, NVL(TO_CHAR(WM_ITEM_NUM), 'UNKNOWN')";
		$ora_success = ora_parse($cursor, $sql);
		$ora_success = ora_exec($cursor, $sql);
		while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			array_push($group, array('leftcol'=>'',
										'PO'=>$row['MARK'],
										'desc'=>$row['MASTER_NUM'],
										'comm'=>$row['COMMODITY'],
										'plts'=>$row['THE_PLTS'],
										'qty'=>$row['THE_CASES']));
			$plt_total += $row['THE_PLTS'];
			$case_total += $row['THE_CASES'];
		}
		
			array_push($group, array('leftcol'=>'',
										'PO'=>'',
										'desc'=>'',
										'comm'=>'',
										'plts'=>'____',
										'qty'=>'____'));
			array_push($group, array('leftcol'=>'',
										'PO'=>'',
										'desc'=>'',
										'comm'=>'',
										'plts'=>$plt_total,
										'qty'=>$case_total));

		$group_heading = array('leftcol'=>'**SUBTOTALS**',
									'PO'=>'BOOKING PO',
									'desc'=>'ITEM NO.',
									'comm'=>'COMMODITY',
									'plts'=>'#PLTS',
									'qty'=>'QTY');

		$pdf->ezTable($group, $group_heading, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>0,'fontSize'=>8,'width'=>450));
		$pdf->ezSetDy(-20);

		$pdf->ezText("________________________________", 12, $left);
		$pdf->ezSetDy(-5);
		$pdf->ezText("Driver Signature", 12, $left);
		$pdf->ezSetDy(31);
		$pdf->ezText("________________________________", 12, $right);
		$pdf->ezSetDy(-5);
		$pdf->ezText("Date", 12, $right);
		$pdf->ezSetDy(-10);
		$pdf->ezText("________________________________", 12, $left);
		$pdf->ezSetDy(-5);
		$pdf->ezText("Trucking Co.", 12, $left);
		$pdf->ezSetDy(31);
		$pdf->ezText("________________________________", 12, $right);
		$pdf->ezSetDy(-5);
		$pdf->ezText("QTY", 12, $right);
		$pdf->ezSetDy(-10);
		$pdf->ezText("SEAL:  $seal", 12, $left);
		$pdf->ezSetDy(-15);
		$pdf->ezText("Some, Part, or all of this cargo may have been fumigated with Methyl Bromide", 12, $left);
	}


	$pdfcode = $pdf->ezOutput();

	$dir = '/var/www/html/upload/pdf_files';
	if (!file_exists($dir)) {
	 mkdir ($dir, 0775);
	}

	$fname = tempnam($dir . '/', 'PDF_') . '.pdf';
	$fp = fopen($fname, 'w');
	fwrite($fp, $pdfcode);
	fclose($fp);

	list($junk1, $junk2, $junk3, $junk4, $junk5, $junk, $filename) = split("/", $fname);

	if($email == true){
		$attach=chunk_split(base64_encode($pdfcode));

		$sql = "SELECT * FROM EMAIL_DISTRIBUTION
				WHERE EMAILID = 'OFFSITETALLY'";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $email_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	   
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
	  
		$mailSubject = str_replace("_0_", $order, $mailSubject);
		$body = str_replace("_0_", $order, $body);
		$body .= "\r\n\r\n";
	  
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
		$Content.="Content-Type: application/pdf; name=\"".$order.".pdf\"\r\n";
		$Content.="Content-disposition: attachment\r\n";
		$Content.="Content-Transfer-Encoding: base64\r\n";
		$Content.="\r\n";
		$Content.=$attach;
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
						'INSTANT',
						SYSDATE,
						'EMAIL',
						'OFFSITETALLY',
						SYSDATE,
						'COMPLETED',
						'".$mailTO."',
						'".$email_row['CC']."',
						'".$email_row['BCC']."',
						'".substr($body, 0, 2000)."')";
			ora_parse($cursor, $sql);
			ora_exec($cursor);
		}
	}

	header("Location: /upload/pdf_files/$filename");

	// remove files in this directory that are older than 5 mins
	if ($d = @opendir($dir)) {
	  while (($file = readdir($d)) !== false) {
	 if (substr($file,0,4)=="PDF_"){
		// then check to see if this one is too old
		$ftime = filemtime($dir.'/'.$file);
		if (time() - $ftime > 300){
		  unlink($dir.'/'.$file);
		}
	 }
	  }  
	  
	  closedir($d);
	}


// redirect to a temporary PDF file instead of directly writing to the browser
//include("redirect_pdf.php");
?>
