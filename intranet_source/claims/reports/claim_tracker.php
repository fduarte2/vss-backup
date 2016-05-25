<?
   include("pow_session.php");

   // File: track_report_print.php
   //
   // This page generate a PDF file of the tracker report

   // form processor
   if(!$claim_id){
     printf("Missing Claim ID- please go back and try again (Should not get here).");
     exit;
   }
   // Connect to the database
   include("connect.php");

   // To be used to eliminate trailing zeros
   $trans = array(".00"=>"");

//   $conn = pg_connect ("host=$host dbname=$db user=$dbuser");
//   if (!$conn) {
//      die("Could not open connection to database server");
//   }

   $ora_conn = ora_logon("SAG_OWNER@BNI", "SAG");
   if (!$ora_conn) {
     printf("Error logging on to the BNI Oracle Server: " . ora_errorcode($ora_conn));
     printf("Please report to TS!");
     exit;
   }
   $cursor_main = ora_open($ora_conn);
   $cursor = ora_open($ora_conn);


   $stmt = "select * from claim_log_oracle where claim_id = '$claim_id' order by line_id";
   $ora_success = ora_parse($cursor_main, $stmt);
   $ora_success = ora_exec($cursor_main);
//   echo $stmt."<br>";
//   $result = pg_query($conn, $stmt) or 
//       die("Error in query: $stmt. " .  pg_last_error($conn));
//   $rows = pg_num_rows($result);
//   if ($rows <= 0) {	// not ccd_lot_id is found
//      die("No Claim ID $claim_id is found in the system!");
//   }
//   $row = pg_fetch_array($result, 0, PGSQL_ASSOC);
   ora_fetch_into($cursor_main, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   $invoice_num = $row['CUSTOMER_INVOICE_NUM'];
   $product_name = $row['PRODUCT_NAME'] . " " . $row['CCD_CUT'];

   $stmt = "select customer_name from customer_profile where customer_id = '" .  $row['CUSTOMER_ID'] . "'";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);
   ora_fetch_into($cursor, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   $customer_name = $row1['CUSTOMER_NAME'];
   list($junk, $temp_customer_name) = split("-", $customer_name); 
   if($temp_customer_name != ""){
     $customer_name = $temp_customer_name;
   }

   // initiate the pdf writer
   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');
   $pdf->ezSetMargins(20,30,30,30);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

   // Write out the intro.
   $pdf->ezText("<b>Claim Report</b>", 18, $center);
   $pdf->ezSetDy(-20);
   $pdf->ezText("<b>Port of Wilmington</b>", 12, $left);
   $pdf->ezText("1 Hausel Road                                                                                                        Phone (302) 472-7710", 12, $left);
   $pdf->ezText("Wilmington, Delaware 19801-5852                                                                             Fax (302) 472-7742", 12, $left);
   $pdf->ezSetDy(-15);
   $pdf->ezText("<b>Customer: $customer_name\nClaim Invoice #:  $invoice_num</b>", 12, $left);

   $pdf->ezSetDy(-30);
   $pdf->ezText("Claim Information:", 11, $left);

   $print_data = array();
   $comments_array = array();

   // format the dates
   if ($row['CLAIM_DATE'] != "") {
      $claim_date = date('m/d/y', strtotime($row['CLAIM_DATE']));
   } else {
      $claim_date = "";
   }

   if ($row['CHECK_DATE'] != "") {
      $check_date = date('m/d/y', strtotime($row['CHECK_DATE']));
   } else {
      $check_date = "";
   }

   if ($row['RECEIVED_DATE'] != "") {
      $received_date = date('m/d/y', strtotime($row['RECEIVED_DATE']));
   } else {
      $received_date = "";
   }

   if ($row['ENTERED_DATE'] != "") {
      $entered_date = date('m/d/y', strtotime($row['ENTERED_DATE']));
   } else {
      $entered_date = "";
   }

   if($row['COMPLETED'] == 't'){
     $completed = "Closed";
   }
   else{
     $completed = "Open";
   }

   $exporter = $row['EXPORTER'];
   $exporter = strtoupper($exporter);
   $letter_type = $row['LETTER_TYPE'];

   if($letter_type == "RShipper"){
     if(substr($row['VOYAGE'], 0, 2) == "KY") 
       $claim_letter = "Secondary Claim - KY";
     else
       $claim_letter = "Secondary Claim - Terminal";
   }
   else if($letter_type == "Shipper"){
     $claim_letter = "Shipper";
   }
   else if($letter_type == "Split"){
     if(substr($row['VOYAGE'], 0, 2) == "KY")
       ;
     else
      $claim_letter = "Secondary Claim - Terminal";
   }
   else{
     $claim_letter = "Customer";
   }

   // build a small table for details
   $claim_data = array();	// store data to be printed
   $total = 0;
   $shipper = 0;
   $denied = 0;
   $port = 0;
//   for($x = 0; $x < $rows; $x++){
   do {
//	 $row = pg_fetch_array($result, $x, PGSQL_ASSOC);
     $system = $row['SYSTEM'];
     if($system == "RF"){
       $id = "Pallet ID";
       $key = $row['RF_PALLET_ID'];
       $mark = $row['CCD_MARK'];
     }
     else if($system == "CCDS"){
       $id = "Tracking Number";
       $key = $row['CCD_LOT_ID'];
       $mark = $row['CCD_MARK'];
     }
     else if($system == "BNI"){
       $id = "WO #";
       $key = $row['BNI_BL'];
       $mark = $row['BNI_MARK'];
     }


     // Figure out these stupid numbers that make no sense
     if($letter_type == "RShipper"){
       $port_amount = $row['AMOUNT'];
       $port_qty = $row['QUANTITY_CLAIMED'];
       $sub_port = $row['AMOUNT'];
/*
       //$port_amount = 0;
       //$port_qty = 0;
       //$sub_port = 0;
       $port_amount += $row['amount'] - $row['shipper_amount'];
       $port_qty = $row['quantity_claimed'] - $row['shipper_qty'];
       $sub_port = $row['amount'] - $row['shipper_amount'];
*/
     }
     else if($letter_type == "Shipper"){
       $port_qty = 0;
       $sub_port = 0;
     }
     // fix for split
     else if($letter_type == "Split"){
       $port_amount = $row['AMOUNT'];
       $port_qty = $row['QUANTITY_CLAIMED'];
       $sub_port = $row['AMOUNT'];
     }
     else{
       $port_amount += $row['AMOUNT'] - $row['SHIPPER_AMOUNT'];
       $port_qty = $row['QUANTITY_CLAIMED'] - $row['SHIPPER_QTY'];
       $sub_port = $row['AMOUNT'] - $row['SHIPPER_AMOUNT'];
     }

     if($row['DENIED_QTY'] == 0 && $row['DENIED_QUANTITY'] > 0){
       $sub_total += $row['AMOUNT'] + $row['SHIPPER_AMOUNT'];
       $sub_port -= $row['DENIED_QUANTITY'];
       $port_amount -= $row['DENIED_QUANTITY'];
     }
     else{
       $sub_total += $row['AMOUNT'] + $row['SHIPPER_AMOUNT'] + $row['DENIED_QUANTITY'];
     }
     //$sub_total = $row['amount'] + $row['shipper_amount'] + $row['denied_quantity'];
     $total += $sub_total;

     $port_qty_total += $port_qty;

     $denied_amount += $row['DENIED_QUANTITY'];
     $total_denied_qty += $row['DENIED_QTY'];

     $shipper_qty += $row['SHIPPER_QTY'];
     $shipper_amount += $row['SHIPPER_AMOUNT'];
     
     // Figure out the notes...
     $letter_notes = $row['NOTES'];

     $internal_notes = $row['INTERNAL_NOTES'];

     if($letter_notes != ""){
       $add_ref = 1;
       if($key == "LABOR INVOICE"){
         $ref = "INV";
       }
       else{
         $ref = substr($key, -4);
       }
       array_push($comments_array, array('ref'=>"LRef: " . $ref, 'comments'=>$letter_notes));
     }
     else{
       $ref = "";
     }
     if($internal_notes != ""){
       $add_ref = 1;
       if($key == "LABOR INVOICE"){
         $ref = "INV";
       }
       else{
         $ref = substr($key, -4);
       }
       array_push($comments_array, array('ref'=>"IRef: " . $ref, 'comments'=>$internal_notes));
     }
     else{
       $ref = "";
     }

     $unit = $row['UNIT'];
     if($row['QUANTITY_CLAIMED'] > 1){
       $unit .= "s";
     }

     array_push($claim_data, array('id'=>$key, 'mark'=>$mark, 'port'=>"$" . $sub_port, 'qty'=>$row['QUANTITY_CLAIMED'] + $row['DENIED_QTY'] + $row['SHIPPER_QTY'], 'cost'=>"$" . $row['COST'], 'total_amount'=>"$" . $sub_total, 'port_qty'=>$port_qty, 'denied'=>"$" . $row['DENIED_QUANTITY'], 'ship_line_qty'=>$row['SHIPPER_QTY'], 'ship_line_amount'=>"$" . $row['SHIPPER_AMOUNT'], 'letter_notes'=>$ref, 'unit'=>$unit, 'gallons'=>$row['GALLONS'], 'denied_qty'=>$row['DENIED_QTY']));
     $sub_total = 0;
   }   while(ora_fetch_into($cursor_main, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));
   array_push($claim_data, array('id'=>"Total: " . $x, 'total_amount'=>"$" . $total, 'port'=>"$" .  $port_amount, 'port_qty'=>$port_qty_total, 'denied'=>"$" . $denied_amount, 'denied_qty'=>$total_denied_qty, 'ship_line_qty'=>$shipper_qty, 'ship_line_amount'=>"$" . $shipper_amount));

   if($completed == "Open"){
     $check = "";
   }
   else{
     if($letter_type == "RShipper"){
       $check = $total;
     }
     else{
       $check = $port_amount;
     }
   }

   $bl = $row['VESSEL_BL'];
   if($bl == ""){
     $bl = $row['BNI_BL'];
   }

   if (substr($claim_letter,0,15) =="Secondary Claim")
	$port_amount = $total;
  
   if($exporter != ""){
     array_push($print_data, 
	      array('first'=>"Commodity:\nInvoice Date:\nClaim Type:\n\nTotal Claim Amount:\nPort Payment:\n\nStatus:\n\nCheck Date:\n\n", 
		    'second'=>"$product_name\n$claim_date\n" . $row['CLAIM_TYPE'] . "\n\n$$total\n$$port_amount\n\n$completed\n\n$check_date\n",
		    'third'=>'',
		    'fourth'=>"Vessel:\nDate Received:\nExporter:\n\nShip Line Amount:\n\n\n\n\nCheck Number:\n",
		    'fifth'=>$row['VESSEL_NAME'] . "\n$received_date\n$exporter\n\n$$shipper\n\n\n\n\n" . $row['CHECK_NUM'],
		    'sixth'=>'',
		    'seventh'=>"Voyage:\nDate Entered:\nBill of Lading:\n\nDenied Amount:\n\n\n\n\nCheck Amount\n",
		    'eighth'=>$row['VOYAGE'] . "\n$entered_date\n" . $bl . "\n\n$$denied_amount\n\n\n\n\n$$check\n"));
   } else {
     array_push($print_data, 
	      array('first'=>"Commodity:\nInvoice Date:\nClaim Type:\n\nTotal Claim Amount:\nPort Payment:\n\nStatus:\n\nCheck Date:\n\n", 
		    'second'=>"$product_name\n$claim_date\n" . $row['CLAIM_TYPE'] . "\n\n$$total\n$$port_amount\n\n$completed\n\n$check_date\n",
		    'third'=>'',
		    'fourth'=>"Vessel:\nDate Received:\nLetter Type:\n\nShip Line Amount:\n\n\n\n\nCheck Number:\n",
		    'fifth'=>$row['VESSEL_NAME'] . "\n$received_date\n$claim_letter\n\n$$shipper\n\n\n\n\n" . $row['CHECK_NUM'],
		    'sixth'=>'',
		    'seventh'=>"Voyage:\nDate Entered:\nBill of Lading:\n\nDenied Amount:\n\n\n\n\nCheck Amount\n",
		    'eighth'=>$row['VOYAGE'] . "\n$entered_date\n" . $bl . "\n\n$$denied_amount\n\n\n\n\n$$check\n"));
  }

   $pdf->ezTable($print_data,  array('first'=>'', 'second'=>'', 'third'=>'', 'fourth'=>'', 
				     'fifth'=>'', 'sixth'=>'', 'seventh'=>'', 'eighth'=>''), '', 
			       array('cols'=>array('first'=>array('justification'=>'left'), 
					           'second'=>array('justification'=>'left'),
					           'fourth'=>array('justification'=>'left'),
					           'fifth'=>array('justification'=>'left'),
					           'seventh'=>array('justification'=>'left'),
					           'eighth'=>array('justification'=>'left')),
				     'shaded'=>0, 'showLines'=>0, 'width'=>545));

   $pdf->ezSetDy(-10);

   if($system == "BNI"){
     $pdf->ezTable($claim_data, array('id'=>$id, 'qty'=>'Qty', 'unit'=>'Unit', 'gallons'=>'Gallons', 'cost'=>'Price',
'total_amount'=>'Amt', 'port_qty'=>'Port Qty', 'port'=>'Port Amt',
'denied_qty'=>'Denied Qty', 'denied'=>'Denied', 'ship_line_qty'=>'Ship Line Qty', 'ship_line_amount'=>'Ship
Line Amt', 'letter_notes'=>'Ref'),
     '',  array('cols'=>array('port'=>array('justification'=>'right'),
'cost'=>array('justification'=>'right'),
'qty'=>array('justification'=>'right'),
'denied_qty'=>array('justification'=>'right'),
'port_qty'=>array('justification'=>'right'),
'denied'=>array('justification'=>'right'),
'ship_line_qty'=>array('justification'=>'right'),
'ship_line_amount'=>array('justification'=>'right'),
'total_amount'=>array('justification'=>'right')),
     'shaded'=>0, 'showLines'=>2, 'width'=>545));
   }
   else if($system == "CCDS"){
     $pdf->ezTable($claim_data, array('id'=>$id, 'mark'=>'Mark', 'qty'=>'Qty', 'cost'=>'Price',
'total_amount'=>'Amt', 'port_qty'=>'Port Qty', 'port'=>'Port Amt',
'denied_qty'=>'Denied Qty', 'denied'=>'Denied', 'ship_line_qty'=>'Ship Line Qty', 'ship_line_amount'=>'Ship
Line Amt', 'letter_notes'=>'Ref'),
     '',  array('cols'=>array('port'=>array('justification'=>'right'),
'cost'=>array('justification'=>'right'),
'id'=>array('justification'=>'left', 'width'=>'50'),
'mark'=>array('justification'=>'left'),
'qty'=>array('justification'=>'right'),
'port_qty'=>array('justification'=>'right'),
'denied_qty'=>array('justification'=>'right'),
'denied'=>array('justification'=>'right'),
'ship_line_qty'=>array('justification'=>'right'),
'ship_line_amount'=>array('justification'=>'right'),
'total_amount'=>array('justification'=>'right')),
     'shaded'=>0, 'showLines'=>2, 'width'=>545));
  }
  else{
     $pdf->ezTable($claim_data, array('id'=>$id, 'qty'=>'Qty', 'cost'=>'Price',
'total_amount'=>'Amt', 'port_qty'=>'Port Qty', 'port'=>'Port Amt',
'denied_qty'=>'Denied Qty', 'denied'=>'Denied', 'ship_line_qty'=>'Ship Line Qty', 'ship_line_amount'=>'Ship
Line Amt', 'letter_notes'=>'Ref'),
     '',  array('cols'=>array('port'=>array('justification'=>'right'),
'cost'=>array('justification'=>'right'),
'qty'=>array('justification'=>'right'),
'port_qty'=>array('justification'=>'right'),
'denied_qty'=>array('justification'=>'right'),
'denied'=>array('justification'=>'right'),
'ship_line_qty'=>array('justification'=>'right'),
'ship_line_amount'=>array('justification'=>'right'),
'total_amount'=>array('justification'=>'right')),
     'shaded'=>0, 'showLines'=>2, 'width'=>545));
  }

  if($add_ref == 1){
      $pdf->ezSetDy(-10);
      $pdf->ezTable($comments_array, array('ref'=>'Ref', 'comments'=>'Comments'), '', array('cols'=>array('ref'=>array('justification'=>'left', 'width'=>60), 'comments'=>array('justification'=>'left')), 'shaded'=>0, 'showHeadings'=>0, 'showLines'=>0, 'width'=>550));
  }


   // close database connection
//   pg_free_result($result);
//   pg_close($conn);

   $today = date('m/j/y');
   $format = "Port of Wilmington, " . $today;
   $pdf->line(20,40,578,40);
   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
   $pdf->line(20,822,578,822);
   $pdf->addText(50,34,6, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');

   include("redirect_pdf.php");
?>
