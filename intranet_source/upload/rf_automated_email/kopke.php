<?

/* Modified Adam Walter, April 19, 2007
*  Kopke requested that void pallets no longer be displayed,
*  So I have adjusted SQL statement to match.
*
*  Modified Inigo Thomas, March 17, 2010
*  Added Marty to the To list for the nightly email.
*
*  Fogbugz 389:  New service code 30 added for changes to original qty.
***********************************************************************************/
include("mail_attach.php");

$date = date('m/d/Y');
//$date = "04/18/2016";

// Directory Structure
$dir = '/web/web_pages/upload/rf_automated_email';
$fname = tempnam($dir . '/', 'POWActivity_') . '.csv';
$fp = fopen($fname, 'w');
list($junk, $junk2, $junk3, $junk4, $junk5, $tname) = split("/", $fname);
echo "$tname\n";

// Defines
$from_user = "EDI";
$from = "pownoreply@port.state.de.us";
$cc = "martym@port.state.de.us,schapman@port.state.de.us,pcutler@port.state.de.us,ithomas@port.state.de.us";
//$cc = "";
$bcc = "archive@port.state.de.us,awalter@port.state.de.us,sadu@port.state.de.us,lstewart@port.state.de.us";
$to = "pkpk@kopkefruit.com,genfile@kopkefruit.com,cferguson@murphymarine.com";
//$to = "awalter@port.state.de.us,ithomas@port.state.de.us";
//$to = "hdadmin@port.state.de.us,pkpk@kopkefruit.com,genfile@kopkefruit.com,cferguson@murphymarine.com,sadu@port.state.de.us,martym@port.state.de.us,bdempsey@port.state.de.us";
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

$subject = "powouts";
$csv = "SERVICE,ORDER_NUM,PALLET_ID,CASES,DATE\n";
$eport_customer_id = "1131";
$send = 0;

//  $stmt = "select T.pallet_id, CM.commodity_name commodity, C.customer_name customer, T.qty_received, T.qty_in_house, T.warehouse_location, T.cargo_description, T.mark, A.activity_num, A.order_num, A.activity_description, to_char(a.date_of_activity, 'MM/DD/YY HH24:MI:SS') date_of_activity, A.qty_left, A.qty_change, SC.service_name from CARGO_TRACKING T, CUSTOMER_PROFILE C, COMMODITY_PROFILE CM, CARGO_ACTIVITY A, SERVICE_CATEGORY SC where A.customer_id = $eport_customer_id and A.date_of_activity > to_date('$date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') and A.date_of_activity < to_date('$date 23:59:59', 'MM/DD/YYYY HH24:MI:SS') and A.customer_id = C.customer_id and A.pallet_id = T.pallet_id and A.customer_id = T.receiver_id and A.service_code = SC.service_code and T.commodity_code = CM.commodity_code and A.ACTIVITY_DESCRIPTION IS NULL AND A.SERVICE_CODE = '6' order by date_of_activity desc";
  $stmt = "select T.pallet_id, A.order_num, to_char(a.date_of_activity, 'MM/DD/YY HH24:MI:SS') date_of_activity, DECODE(SERVICE_CODE, 30, A.activity_description, A.qty_change) THE_QTY, A.activity_num, 
				DECODE(service_code, 9, 'INV_ADJUST', 13, 'Returned', 7, 'Returned', 6, 'Delivery', 30, 'data_cor', 11, DECODE(activity_num, 1, 'Transferred In', 'Transferred Out'), 'Unknown') the_serv
			from CARGO_TRACKING T, CARGO_ACTIVITY A 
			where A.customer_id = $eport_customer_id 
				and A.date_of_activity >= to_date('$date 00:00:00', 'MM/DD/YYYY HH24:MI:SS') 
				and A.date_of_activity < to_date('$date', 'MM/DD/YYYY') + 1
				and A.pallet_id = T.pallet_id 
				and A.customer_id = T.receiver_id 
				and A.arrival_num = T.arrival_num 
				and (A.ACTIVITY_DESCRIPTION IS NULL OR A.ACTIVITY_DESCRIPTION != 'VOID')
				AND A.SERVICE_CODE IN ('6', '7', '9', '11', '13', '30') 
				AND A.ARRIVAL_NUM != '4321' 
			order by date_of_activity desc";

$ora_success = ora_parse($cursor1, $stmt);
$ora_success = ora_exec($cursor1);
  // For Each order
while(ora_fetch_into($cursor1, $orders, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    $service_name = $orders['THE_SERV'];
    $order_num = $orders['ORDER_NUM'];
    $pallet_id = $orders['PALLET_ID'];
    $cases = $orders['THE_QTY'];
    $date_act = $orders['DATE_OF_ACTIVITY'];
//    $desc = $orders['ACTIVITY_DESCRIPTION'];
    
    // Cancel out any VOID activity based on the spec from Kopke (1/26/04)
	// As of 4/19/2007, this code fragment will never be executed
    if($desc == "VOID"){
      $service_name = "Cancelled";
    }

    $csv .= "$service_name,$order_num,$pallet_id,$cases,$date_act\n";
    $send = 1;
}

// that was deliveries.
// now, we do "transfers to storage", which are defined as:
$sql = "SELECT PALLET_ID, QTY_RECEIVED - NVL(QTY_DAMAGED, 0) THE_REC, DECODE(RECEIVING_TYPE, 'T', 'OTR', 'TO STORAGE') THE_SERVICE
		FROM CARGO_TRACKING
		WHERE RECEIVER_ID = '1131'
			AND (
					(TO_CHAR(DATE_RECEIVED + 1, 'MM/DD/YYYY') = '".$date."'
					AND
					(CARGO_STATUS != 'F' OR CARGO_STATUS IS NULL)
					AND
					FUMIGATION_CODE = 'Y')
				OR
					((FUMIGATION_CODE != 'Y' OR FUMIGATION_CODE IS NULL)
					AND
					TO_CHAR(DATE_RECEIVED + (2/24), 'MM/DD/YYYY') = '".$date."')
				)";

//echo $sql;
$ora_success = ora_parse($cursor1, $sql);
$ora_success = ora_exec($cursor1);
while(ora_fetch_into($cursor1, $storages, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
//	$service = "TO STORAGE";
	$service = $storages['THE_SERVICE'];
	$pallet = $storages['PALLET_ID'];
//	$qty1 = $storages['SERVICE_QTY'];
//	$qty1u = $storages['SERVICE_UNIT'];
	$qty2 = $storages['THE_REC'];
//	$dt = $storages['THE_DATE'];
	$dt = $date;

    $csv .= $service.",NA,".$pallet.",".$qty2.",".$dt."\n";
//	echo $service.",NA,".$pallet.",".$qty2.",".$date."\n";
//    $csv .= $service.",,".$pallet.",".$qty1." (".$qty1u.") - ".$qty2." (".$qty2u."),".$date."\n";
    $send = 1;
}

fwrite($fp, $csv);
fclose($fp);
//echo "csv created\n";
// Zip the file up
system("/usr/bin/zip -D POWActivity.zip $tname");
//echo "zipping done\n";
//exit;
$zfp = fopen("/web/web_pages/upload/rf_automated_email/POWActivity.zip", 'r');
//echo "opened zipped file\n";
$binary = fread($zfp, filesize("/web/web_pages/upload/rf_automated_email/POWActivity.zip"));
//echo "read zipped file\n";
system("/bin/rm -f /web/web_pages/upload/rf_automated_email/POWActivity.zip");
//echo "removed temporary zip file\n";
// Ok, now $binary has a zip file in it

//if($send != 1){
//	echo "mail sent\n";
//}
  mail_attach_with_cc($to, $from, $cc, $bcc, $subject, $body, "POWActivity.zip", $binary, 3, "application/zip");
//echo "mail if() finished\n";
// Delete Old stuff
if ($d = @opendir($dir)) {
  while (($file = readdir($d)) !== false) {
    if (substr($file,0,12)=="POWActivity_"){
       // then check to see if this one is too old
       unlink($dir.'/'.$file);
    }
  }  
closedir($d);
}

exit;
