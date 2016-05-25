<?
/* Adam Walter, October 2006
*
*  This script is intended to be run as a cron job.
*  It creates a report(s) listing the rebanded cargo off of an RF-based vessel
*  Please note:  This report is expected to run with the
*  Vessel Productivity Report in sequence; this one runs at 10:55, 
*  And the VPR at 11:00AM.  Splitting these times more than 5 mintues
*  May result in some wierd emails to TS, as people ask why they are getting
*  Reports for vessels that are still here, etc.
*******************************************************************************/
   include 'class.ezpdf.php';

  
  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($conn));
      	exit;
  }
  $cursor = ora_open($conn);

  $RFconn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($RFconn < 1){
    	printf("Error logging on to the Oracle Server: ");
     	printf(ora_errorcode($RFconn));
      	exit;
  }
  $RFcursor = ora_open($RFconn);

  $right_now = date('m/d/Y h:i a');

  $columnheaders = array('one'=>'EXPORTER CODE', 'two'=>'Receiver', 'three'=>'Mark', 'four'=>'BoL', 'five'=>'POW ID', 'six'=>'Commodity', 'seven'=>'Pallet ID', 'eight'=>'Scanned', 'nine'=>'Checker');


	$sql = "SELECT SVC.VESSEL THE_VES, VP.VESSEL_NAME THE_SHIP FROM SUPER_VESSEL_CLOSE SVC, VESEL_PROFILE VP WHERE PROD_REPORT_RUN = 'R' AND SVC.VESSEL = VP.LR_NUM";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
		echo "No reports pending for vessel productivity or rebands.";
		exit;
	} else {
		do {
			$data = "";
			$current_arrival_num = $row['THE_SHIP'];
			$mailsubject = "Reband Report, Vessel ".$current_arrival_num."\r\n";

			$pdf = new Cezpdf('letter','portrait');

			$pdf->ezSetMargins(20,20,30,30);
			$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
			$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
			$pdf->setFontFamily('Helvetica.afm', $tmp);

			$pdf->ezStartPageNumbers(300, 10, 8, '','',1);

			$pdf->ezText("Reband Report", 20, $center);
			$pdf->ezSetDy(-10);
			$pdf->ezText("<b>Vessel ".$current_arrival_num."</b>", 14, $center);
			$pdf->ezSetDy(-15);
			$pdf->ezText("<i>As of $right_now</i>", 14, $center);

			$sql = "SELECT CUP.CUSTOMER_NAME THE_REC, CT.EXPORTER_CODE THE_EXP, CT.CARGO_DESCRIPTION THE_MARK, CT.BOL THE_BOL, CT.BATCH_ID THE_CODE, COP.COMMODITY_NAME THE_COMM, CT.PALLET_ID THE_PALLET, TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY HH:MI AM') THE_SCAN, CT.SOURCE_USER THE_SCANNER
				FROM CARGO_TRACKING CT, CUSTOMER_PROFILE CUP, COMMODITY_PROFILE COP
				WHERE CT.RECEIVER_ID = CUP.CUSTOMER_ID
				AND CT.COMMODITY_CODE = COP.COMMODITY_CODE
				AND CT.QTY_IN_STORAGE > 0
				AND CT.QTY_IN_STORAGE IS NOT NULL
				AND ARRIVAL_NUM = '".$row['THE_VES']."'
				ORDER BY THE_EXP, THE_REC, THE_MARK";
			ora_parse($RFcursor, $sql);
			ora_exec($RFcursor);
			if(!ora_fetch_into($RFcursor, $RFrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
				// no rebands, do nothing
			} else {
				$data = array();

				do {
					if($RFrow['THE_SCAN'] != ""){
						array_push($data, array('one'=>$RFrow['THE_EXP'], 'two'=>$RFrow['THE_REC'], 'three'=>$RFrow['THE_MARK'], 'four'=>$RFrow['THE_BOL'], 'five'=>$RFrow['THE_CODE'], 'six'=>$RFrow['THE_COMM'], 'seven'=>$RFrow['THE_PALLET'], 'eight'=>$RFrow['THE_SCAN'], 'nine'=>$RFrow['THE_SCANNER']));
					} else {
						array_push($data, array('one'=>$RFrow['THE_EXP'], 'two'=>$RFrow['THE_REC'], 'three'=>$RFrow['THE_MARK'], 'four'=>$RFrow['THE_BOL'], 'five'=>$RFrow['THE_CODE'], 'six'=>$RFrow['THE_COMM'], 'seven'=>$RFrow['THE_PALLET'], 'eight'=>'NOT SCANNED', 'nine'=>'NOT SCANNED'));
					}
				} while(ora_fetch_into($RFcursor, $RFrow, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
			
				$pdf->ezTable($data, $columnheaders, '', array('showHeadings'=>1, 'shaded'=>0, 'showLines'=>2, 'width'=>550,'fontSize'=>8));

				$pdfcode = $pdf->ezOutput();

				$File=chunk_split(base64_encode($pdfcode));


				$mailToTest = "awalter@port.state.de.us";
				$mailTo = "OPSSupervisors@port.state.de.us, other people";
				$mailsubject = "Reband Report for ".$row['THE_SHIP'];

				$mailheaders  = "From: " . "MailServer@port.state.de.us\r\n";
//				$mailheaders .= "Cc: meskridge@port.state.de.us, phemphill@port.state.de.us,lstewart@port.state.de.us, awalter@port.state.de.us\r\n";
//				$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us\r\n";
				$mailheaders .= "MIME-Version: 1.0\r\n";
				$mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
				$mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
				$mailheaders .= "X-Mailer: PHP4\r\n";
				$mailheaders .= "X-Priority: 3\r\n";
				$mailheaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
				$mailheaders  .= "This is a multi-part Contentin MIME format.\r\n";

				$Content="--MIME_BOUNDRY\r\n";
				$Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
				$Content.="Content-Transfer-Encoding: quoted-printable\r\n";
				$Content.="\r\n";
				//$Content.=" Just sent you the attached file for review.\n";
				$Content.="\r\n";
				$Content.="--MIME_BOUNDRY\r\n";
				$Content.="Content-Type: application/pdf; name=\"Reband Report $row['THE_SHIP'].pdf\"\r\n";
				$Content.="Content-disposition: attachment\r\n";
				$Content.="Content-Transfer-Encoding: base64\r\n";
				$Content.="\r\n";
				$Content.=$File;
				$Content.="\r\n";
				$Content.="--MIME_BOUNDRY--\n";

				mail($mailToTest, $mailsubject, $Content, $mailheaders);
			}
		}
	}
	
