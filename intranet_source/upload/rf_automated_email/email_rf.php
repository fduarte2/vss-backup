<?
// Includes we will need
include 'class.ezpdf.php';
include("mail_attach.php");

// Defines
$from_user = "EDI";
$from = "edi@port.state.de.us";

// connect to RF database
$ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
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

$email_cursor = ora_open($ora_conn);
if (!$email_cursor) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($email_cursor));
  exit;
}      

$address_cursor = ora_open($ora_conn);
if (!$address_cursor) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($address_cursor));
  exit;
}      

$update_cursor = ora_open($ora_conn);
if (!$update_cursor) {
  printf("Error opening a cursor on Oracle Server: ");
  printf(ora_errorcode($update_cursor));
  exit;
}               

$stmt = "select * from customer_email where email = 'T'";
$ora_success = ora_parse($email_cursor, $stmt);
$ora_success = ora_exec($email_cursor);

// For Each e-mailed customer
while(ora_fetch_into($email_cursor, $emails, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
  $customer_id = $emails['CUSTOMER_ID'];
  $stmt2 = "select * from order_header where customer_id = '$customer_id' and e_mailed = 'N'";
  $ora_success = ora_parse($address_cursor, $stmt2);
  $ora_success = ora_exec($address_cursor);
  // For Each order
  while(ora_fetch_into($address_cursor, $orders, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    $email == "on";
    $mail_TO = "";
    $address1 = $emails['ADDRESS1'];
    $address2 = $emails['ADDRESS2'];
    $address3 = $emails['ADDRESS3'];
    $address4 = $emails['ADDRESS4'];
    $address5 = $emails['ADDRESS5'];
    if($address1 != "")
     $mail_TO .= "$address1";
    if($address2 != "")
     $mail_TO .= ", $address2";
    if($address3 != "")
     $mail_TO .= ", $address3";
    if($address4 != "")
     $mail_TO .= ", $address4";
    if($address5 != "")
     $mail_TO .= ", $address5";
    // No addresses! Bail Out!
    if($mail_TO == "")
     continue;
    // We have addresses at this point, let us send the message

    //$mail_TO .= ", morecraf@port.state.de.us";

    // first set up the options for mail_attach.php
    $order_num = $orders['ORDER_NUM'];
    $subject = "POW - Order $order_num";
    $filename = "Order$order_num.pdf";
//    $body = "Please find your Checker Tally printout attached in PDF format sent from $from_user <$from>.\nYou will need Adobe Acrobat Reader available at http://www.adobe.com (free download).";
    $attach_data = createPDF($customer_id, $order_num);
    if($attach_data == ""){
      printf("Could not create PDF!\n");
      continue;
    }
    // Send out the message
    mail_attach($mail_TO, "\"$from_user\" <$from>", $subject, "", $filename, $attach_data, 3, "application/pdf");

    // Update the database
    $stmt2 = "update order_header set e_mailed = 'Y' where customer_id = '$customer_id' and order_num = '$order_num'";
    $ora_success = ora_parse($update_cursor, $stmt2);
    $ora_success = ora_exec($update_cursor);
  }
}
exit;

/* ------------------------------------------------------------------
   Function to create out PDFCode for this file.
*/
function createPDF($customer_id, $order_num){
  global $conn, $cursor1, $cursor2;

  $stmt = "select to_char(A.DATE_OF_ACTIVITY, 'MM/DD/YYYY HH24:MI:SS') DATE_OF_ACTIVITY, QTY_CHANGE, QTY_LEFT, SERVICE_CODE, PALLET_ID, ACTIVITY_ID, ARRIVAL_NUM, C.CUSTOMER_NAME from cargo_activity A, customer_profile C where A.customer_id = C.customer_id and order_num like '%$order_num%' and A.customer_id = '$customer_id' order by order_num, date_of_activity";
  $ora_success = ora_parse($cursor1, $stmt);
  $ora_success = ora_exec($cursor1);
  ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
  $rows1 = ora_numrows($cursor1);

  if (!$ora_success) {
    // close Oracle connection
    ora_close($cursor1);
    ora_logoff($ora_conn);
    printf("Oracle Error Occurred While Retrieving Data From CARGO_TRACKING and  
	  CUSTOMER_PROFILE. Please Try Again Later.");
    exit;
  }

  if($rows1 == 0){
    // no records have a similar order number
    return "";
  }

  $customer_name = $row1['CUSTOMER_NAME'];
  $date_of_activity = date('m/d/Y', strtotime($row1['DATE_OF_ACTIVITY']));
  $employee = $row1['ACTIVITY_ID'];
  $arrival_num = $row1['ARRIVAL_NUM'];
  $service_code = $row1['SERVICE_CODE'];

  // Set the checker name
  $per_stmt = "select LOGIN_ID from per_owner.personnel where employee_id = '$employee'";
  $ora_success = ora_parse($cursor2, $per_stmt);
  $ora_success = ora_exec($cursor2);
  $rows3 = ora_numrows($cursor2);
  if (!$ora_success){
    // close Oracle connection
    ora_close($cursor2);
    ora_logoff($ora_conn);
    printf("Oracle Error Occurred While Retrieving Data From PER_OWNER.PERSONNEL. Please Try Again Later.");
    exit;
  }
  if($rows3 >= 0){
    ora_fetch_into($cursor2, $row3, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $checker = $row3['LOGIN_ID'];
  }
  else{
    $checker = "UNKNOWN";
  }

  // Get the Vessel name
  $per_stmt = "select VESSEL_NAME from vessel_profile where arrival_num = '$arrival_num'";
  $ora_success = ora_parse($cursor2, $per_stmt);
  $ora_success = ora_exec($cursor2);
  $rows3 = ora_numrows($cursor2);
  if (!$ora_success){
    // close Oracle connection
    ora_close($cursor2);
    ora_logoff($ora_conn);
    printf("Oracle Error Occurred While Retrieving Data From VESSEL_PROFILE. Please Try Again Later.");
    exit;
  }
  if($rows3 >= 0){
    ora_fetch_into($cursor2, $row3, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $vessel_name = $row3['VESSEL_NAME'];
  }
  else{
    $vessel_name = "UNKNOWN";
  }
  if($vessel_name == "")
    $vessel_name = "UNKNOWN";

  // Get the Shipment name
  $per_stmt = "select SERVICE_NAME from service_category where service_code = '$service_code'";
  $ora_success = ora_parse($cursor2, $per_stmt);
  $ora_success = ora_exec($cursor2);
  $rows3 = ora_numrows($cursor2);
  if (!$ora_success){
    // close Oracle connection
    ora_close($cursor2);
    ora_logoff($ora_conn);
    printf("Oracle Error Occurred While Retrieving Data From SERVICE_CATEGORY. Please Try Again Later.");
    exit;
  }
  if($rows3 >= 0){
    ora_fetch_into($cursor2, $row3, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
    $shipment = $row3['SERVICE_NAME'];
  }
  else{
    $shipment = "UNKNOWN";
  }


  // initiate pdf writer
  $pdf = new Cezpdf('letter','portriat');
  $pdf -> ezSetMargins(20,30,30,30);
  $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

  // write out the intro. for the 1st page
  $pdf->ezText("<b>Port of Wilmington Tally</b>", 16, $center);
  $pdf->ezText("\n", 10, $center);
  $pdf->ezSetDy(-25);

  $general_info = array();
  $lot_info = array();
  $damage_info = array();

  // general information
  array_push($general_info, array('first'=>"Customer:  $customer_name",
				'second'=>"Order Number:  $order_num",
				'third'=>"Date:  $date_of_activity"));
  array_push($general_info, array('first'=>"Checker:  $checker",
				'second'=>"",
				'third'=>"Shipment: $shipment"));


  $pdf->ezTable($general_info, array('first'=>'', 'second'=>'', 'third'=>''), 
	      '', array('cols'=>array('first'=>array('justification'=>'left'), 
				      'second'=>array('justification'=>'left'),
				      'third'=>array('justification'=>'left')),
			'showHeadings'=>0, 'shaded'=>0, 'showLines'=>0, 'fontSize'=>12, 'width'=>540));


  $pdf->ezSetDy(-25);

  $start_time = date('H:i:s', strtotime($row1['DATE_OF_ACTIVITY']));
  $pallets = 0;
  $ctns = 0;

  // process one fetched record at a time
  do {
    $pallets++;
    $end_time = date('H:i:s', strtotime($row1['DATE_OF_ACTIVITY']));
    // Get some information about the pallet
    $pallet_id = $row1['PALLET_ID'];
    $qty = $row1['QTY_CHANGE'];
    $ctns += $qty;

    $stmt2 = "select MARK, CARGO_DESCRIPTION, C.COMMODITY_NAME, V.VESSEL_NAME from cargo_tracking T, commodity_profile C, vessel_profile V where T.arrival_num = V.arrival_num and T.commodity_code = C.commodity_code and pallet_id = '$pallet_id' and receiver_id = '$customer_id'";
    $ora_success = ora_parse($cursor2, $stmt2);
    $ora_success = ora_exec($cursor2);

    if (!$ora_success){
      // close Oracle connection
      ora_close($cursor2);
      ora_logoff($ora_conn);
      printf("Oracle Error Occurred While Retrieving Data From CARGO_TACKING. Please Try Again Later.");
      exit;
    }
    ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

    // add the line item
    array_push($lot_info, array('mark'=>$row2['MARK'], 'desc'=>$row2['CARGO_DESCRIPTION'], 'comm'=>$row2['COMMODITY_NAME'], 
			      'pallet'=>$row1['PALLET_ID'], 'qty'=>$row1['QTY_CHANGE'], 'qty_left'=>$row2['VESSEL_NAME'],
			      'comments'=>$row1['ACTIVITY_DESCRIPTION'],
			      'time'=>$row1['DATE_OF_ACTIVITY']));

  } while (ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)); 

  array_push($lot_info, array('mark'=>"Total:", 'desc'=>'', 'comm'=>'', 
			    'pallet'=>$pallets, 'qty'=>$ctns, 'qty_left'=>'',
			    'comments'=>'',
			    'time'=>''));


  $pdf->ezText("Start Time: $start_time - End Time: $end_time");
  $pdf->ezSetDy(-10);

  // write out vessel discharge information
  $pdf->ezText("<b>Order Information:</b>", 12, $left);
  $pdf->ezSetDy(-10);
  $pdf->ezTable($lot_info, array('mark'=>'Lot/Mark', 'desc'=>'Desc', 'comm'=>'Comm', 
			      'pallet'=>'Pallet ID', 'qty'=>'Quantity',
			      'qty_left'=>'Vessel', 'comments'=>'Comments',
			      'time'=>'Time Scanned'), 
	      '', array('showHeadings'=>1, 'shaded'=>0, 'width'=>540, 'showLines'=>2, 
			'cols'=>array('mark'=>array('justification'=>'center'),
				      'desc'=>array('justification'=>'center'),
				      'comm'=>array('justification'=>'center'),
				      'pallet'=>array('justification'=>'center'),
				      'qty'=>array('justification'=>'center'),
				      'qty_left'=>array('justification'=>'center'),
				      'comments'=>array('justification'=>'center'),
				      'time'=>array('justification'=>'center'))));

  // write the footer
  $today = date('m/j/y'); 
  $format = "Port of Wilmington, Printed: " . $today . "    Pallet Total: $pallets";
  $all = $pdf->openObject();
  $pdf->saveState();
  $pdf->setStrokeColor(0,0,0,1);
  $pdf->addText(50,34,7,$format);
  $pdf->line(30,40,575,40);
  $pdf->restoreState();
  $pdf->closeObject();
  $pdf->addObject($all,'all');
  $pdfcode_email = $pdf->ezOutput();

  return $pdfcode_email;
} // End function
?>
