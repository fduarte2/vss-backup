<?
/*
*	Adam Walter, Feb 2010
*
*	Script for a daily email of Trucks dwell time average and "still here" NONTWICS
***************************************************************************************/

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if(!$conn){
    $body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
    mail($mailTO, $mailsubject, $body, $mailheaders);
    exit;
  }

  $cursor = ora_open($conn);
  $Short_Term_Cursor = ora_open($conn);

	$mailTo = "skennard@port.state.de.us,gbailey@port.state.de.us,ithomas@port.state.de.us,fvignuli@port.state.de.us,parul@port.state.de.us";
//	$mailTo = "awalter@port.state.de.us,ithomas@port.state.de.us\r\n";
//	$mailTo = "awalter@port.state.de.us\r\n";

	$mailsubject = "Nightly NON-TWIC Closing Report for ".date('m/d/Y', mktime(0,0,0,date('m'), date('d')-1, date('Y')));

	$mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
	$mailheaders .= "Cc: " . "meskridge@port.state.de.us,jcustis@port.state.de.us,sadu@port.state.de.us,vfarkas@port.state.de.us,sshoemaker@port.state.de.us\r\n"; 
//	$mailheaders .= "Bcc: " . "lstewart@port.state.de.us,awalter@port.state.de.us,sadu@port.state.de.us,hdadmin@port.state.de.us\r\n"; 
	$mailheaders .= "Bcc: " . "hdadmin@port.state.de.us\r\n";
	
	$sql = "SELECT COUNT(DISTINCT NTTD.BARCODE) THE_COUNT, SUM(PAID_AMOUNT) THE_AMT, ROUND(AVG(NTS.SCAN_TIME - NTTD.ENTRY_DATETIME) * 24) THE_AVG
			FROM SAG_OWNER.NON_TWIC_TRUCKER_DETAIL NTTD, SAG_OWNER.NON_TWIC_SCANS NTS
			WHERE NTTD.BARCODE = NTS.BARCODE
			AND USER_ID = 'LANE4'
			AND SCAN_TIME = (SELECT MAX(SCAN_TIME) FROM SAG_OWNER.NON_TWIC_SCANS N2 WHERE NTS.BARCODE = N2.BARCODE AND N2.USER_ID = 'LANE4')
			AND STATUS = 'OFFPORT'
			AND TO_CHAR(ENTRY_DATETIME, 'MM/DD/YYYY') = TO_CHAR(SYSDATE - 1, 'MM/DD/YYYY')";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

	$body = "NON-TWIC Trucks:  ".($row['THE_COUNT'] + 0)."\r\n";
	$body .= "Amount Collected:  $".($row['THE_AMT'] + 0)."\r\n\r\n";
//	$body .= "Average :  ".floor($row['THE_COUNT'] / 60)." hours,  ".($row['THE_COUNT'] % 60)." minutes\r\n\r\n";

	$sql = "SELECT BARCODE, TO_CHAR(ENTRY_DATETIME, 'MM/DD/YYYY HH24:MI') THE_TIME, COMPANY_NAME, LISCENSE_NBR, FIRST_NAME || ' ' || LAST_NAME THE_NAME FROM NON_TWIC_TRUCKER_DETAIL WHERE STATUS IN ('INPORT', 'INDOLE') AND (ENTRY_DATETIME < SYSDATE - (9/24) OR ENTRY_DATETIME IS NULL)";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
		$body .= "The following are still showing as being at the port:\r\n";
		$body .= "Barcode -- Driver -- Company -- License -- Entry Time\r\n";
		do {
			$body .= $row['BARCODE']." -- ".$row['THE_NAME']." -- ".$row['COMPANY_NAME']." -- ".$row['LISCENSE_NBR']." -- ".$row['THE_TIME']."\r\n";
		} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
	} else {
		$body .= "All trucks scanned out as of yesterday\r\n";
	}

	mail($mailTo, $mailsubject, $body, $mailheaders);
?>
