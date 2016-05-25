<?

/* Adam Walter, March 22, 2010
*
*	This program runs in tandem with the 10PM kopke email, but this one
*	shows edits made to orders in days prior to this run.
***********************************************************************************/
include("mail_attach.php");

$date = date('m/d/Y');
//$date = "02/03/2004";

// Directory Structure
$dir = '/web/web_pages/upload/rf_automated_email';
$fname = tempnam($dir . '/', 'POWChanges_') . '.csv';
$fp = fopen($fname, 'w');
list($junk, $junk2, $junk3, $junk4, $junk5, $tname) = split("/", $fname);
echo "$tname\n";

// Defines
$from_user = "EDI";
$from = "pownoreply@port.state.de.us";
$cc = "martym@port.state.de.us,schapman@port.state.de.us,pcutler@port.state.de.us,ithomas@port.state.de.us";
$bcc = "archive@port.state.de.us,awalter@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us";
$to = "pkpk@kopkefruit.com,powcor@kopkefruit.com";
//$to = "awalter@port.state.de.us";
//$to = "hdadmin@port.state.de.us,pkpk@kopkefruit.com,powcor@kopkefruit.com,awalter@port.state.de.us,sadu@port.state.de.us";
$body = "Please find attached file...\n";

// connect to RF database
$ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
//$ora_conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
if (!$ora_conn) {
  printf("Error logging on to Oracle Server: ");
  printf(ora_errorcode($ora_conn));
  exit;
}

// create two cursors
$cursor1 = ora_open($ora_conn);
if (!$cursor1) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor1));
  exit;
}               

$cursor2 = ora_open($ora_conn);
if (!$cursor2) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($cursor2));
  exit;
}               

$subject = "powouts - corrected orders";
$csv = "SERVICE,ORDER_NUM,PALLET_ID,CASES,DATE\n";
$eport_customer_id = "1131";
$send = 1;

// we only want CARGO_ACTIVITY records for days OTHER than the one that was the original sending (+2hours since original email is at 10PM)
$sql = "SELECT DISTINCT ORDER_NUM
		FROM KOPKE_EMAIL_ORDER_EDITS KEOE
		WHERE TO_CHAR(DATE_OF_EDIT, 'MM/DD/YYYY') = TO_CHAR(SYSDATE, 'MM/DD/YYYY')
		AND TO_CHAR(DATE_OF_EDIT, 'MM/DD/YYYY') !=
			(SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY + (6/24)), 'MM/DD/YYYY')
			FROM CARGO_ACTIVITY CA
			WHERE SERVICE_CODE = '6'
			AND CA.ORDER_NUM = KEOE.ORDER_NUM)";
$ora_success = ora_parse($cursor1, $sql);
$ora_success = ora_exec($cursor1);
if(!ora_fetch_into($cursor1, $orders, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
	$body = "No Adjustments Today";
} else {
	// prepare emails
	do {
		$sql = "select A.pallet_id, A.order_num, to_char(a.date_of_activity, 'MM/DD/YY HH24:MI:SS') date_of_activity, A.qty_change, SC.service_name 
				from CARGO_ACTIVITY A, SERVICE_CATEGORY SC 
				where A.customer_id = $eport_customer_id 
					and A.order_num = '".$orders['ORDER_NUM']."' 
					and A.service_code = SC.service_code 
					and A.ACTIVITY_DESCRIPTION IS NULL 
					AND A.SERVICE_CODE = '6' 
					AND A.ARRIVAL_NUM != '4321' 
				order by date_of_activity desc";
		$ora_success = ora_parse($cursor2, $sql);
		$ora_success = ora_exec($cursor2);
		  // For Each order
		while(ora_fetch_into($cursor2, $order_detail, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$service_name = $order_detail['SERVICE_NAME'];
			$order_num = $order_detail['ORDER_NUM'];
			$pallet_id = $order_detail['PALLET_ID'];
			$cases = $order_detail['QTY_CHANGE'];
			$date = $order_detail['DATE_OF_ACTIVITY'];

			$csv .= "$service_name,$order_num,$pallet_id,$cases,$date\n";
//			$send = 1;
		}

//		$sql = "UPDATE KOPKE_EMAIL_ORDER_EDITS SET DATE_EMAIL_SENT = SYSDATE WHERE ORDER_NUM = '".$orders['ORDER_NUM']."' AND TO_CHAR(DATE_OF_EDIT, 'MM/DD/YYYY') = TO_CHAR(SYSDATE, 'MM/DD/YYYY')";
//		$ora_success = ora_parse($cursor2, $sql);
//		$ora_success = ora_exec($cursor2);

	} while (ora_fetch_into($cursor1, $orders, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
}
$csv .= "\n";
fwrite($fp, $csv);
fclose($fp);

// Zip the file up
system("/usr/bin/zip -D POWChanges.zip $tname");
$zfp = fopen("/web/web_pages/upload/rf_automated_email/POWChanges.zip", 'r');
$binary = fread($zfp, filesize("/web/web_pages/upload/rf_automated_email/POWChanges.zip"));
system("/bin/rm -f /web/web_pages/upload/rf_automated_email/POWChanges.zip");
// Ok, now $binary has a zip file in it

if($send == 1){
  mail_attach_with_cc($to, $from, $cc, $bcc, $subject, $body, "POWChanges.zip", $binary, 3, "application/zip");
}

// Delete Old stuff
if ($d = @opendir($dir)) {
  while (($file = readdir($d)) !== false) {
    if (substr($file,0,11)=="POWChanges_"){
       // then check to see if this one is too old
       unlink($dir.'/'.$file);
    }
  }  
closedir($d);
}

exit;
