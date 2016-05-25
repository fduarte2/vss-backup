<?
   include("pow_session.php");
   // File: track_report_print.php
   //
   // This page generate a PDF file of the tracker report

   // form processor
   if(!$pallet_id){
     printf("Missing Pallet ID- please go back and try again (Should not get here).");
     exit;
   }
   if(!$schema)
     $schema = "SAG_OWNER";

   // Connect to the database
   include("connect.php");

   // To be used to eliminate trailing zeros
   $trans = array(".00"=>"");

   $ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if (!$ora_conn) {
     printf("Error logging on to the RF Oracle Server: " . ora_errorcode($ora_conn));
     printf("Please report to TS!");
     exit;
   }
   $cursor = ora_open($ora_conn);


   // Grab the cargo tracking information
   $stmt = "select ct.cargo_description, ct.warehouse_location, ct.arrival_num, ct.qty_received, ct.qty_damaged, ct.qty_in_house, ct.free_time_end, ct.billing_storage_date, to_char(date_received, 'MM/DD/YYYY HH24:MI:SS') date_received, cp.commodity_name, cust_p.customer_name from $schema.cargo_tracking ct, SAG_OWNER.commodity_profile cp, SAG_OWNER.customer_profile cust_p where pallet_id = '$pallet_id' and cp.commodity_code = ct.commodity_code and ct.receiver_id = cust_p.customer_id";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);
   ora_fetch_into($cursor, $cargo_tracking, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

   // Init some VARS
   $customer_name = $cargo_tracking['CUSTOMER_NAME'];
   $commodity = $cargo_tracking['COMMODITY_NAME'];
   $cargo_description = substr($cargo_tracking['CARGO_DESCRIPTION'], 0, 10);
   $warehouse_location = $cargo_tracking['WAREHOUSE_LOCATION'];
   $vessel_number = $cargo_tracking['ARRIVAL_NUM'];
   $qty_received = $cargo_tracking['QTY_RECEIVED'];
   $qty_damaged = $cargo_tracking['QTY_DAMAGED'];
   $qty_in_house = $cargo_tracking['QTY_IN_HOUSE'];

   $stmt = "select vessel_name from vessel_profile where arrival_num = '$vessel_number'";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);
   ora_fetch_into($cursor, $vessel_name, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   $vessel_name = $vessel_name['VESSEL_NAME'];
   if($vessel_name == ""){
     $vessel_name = "Trucked In";
   }

   // format the dates
   if ($cargo_tracking['DATE_RECEIVED'] != "") {
      $date_received = date('m/d/y h:i:s A', strtotime($cargo_tracking['DATE_RECEIVED']));
      $qty_received_text = "Qty Received:";
   } else {
      $date_received = "N/A";
      // Reset Qty if we have not received this pallet
      $qty_received_text = "Qty Expected:";
      $qty_damaged = "";
      $qty_in_house = "";
   }
   if ($cargo_tracking['FREE_TIME_END'] != "") {
      $free_time_end = date('m/d/y', strtotime($cargo_tracking['FREE_TIME_END']));
   } else {
      $free_time_end = "";
   }
   if ($cargo_tracking['BILLING_STORAGE_DATE'] != "") {
      $billing_storage_date = date('m/d/y', strtotime($cargo_tracking['BILLING_STORAGE_DATE']));
   } else {
      $billing_storage_date = "";
   }


   // Activity Log
   $stmt = "select * from activity_log where pallet_id = '$pallet_id' order by
rowid";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);
   ora_fetch_into($cursor, $activity_log, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
   $receiver = $activity_log['CHECKER_NAME'];
   if($receiver == ""){
     $receiver = "N/A";
   }

   // initiate the pdf writer
   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','portrait');
   $pdf->ezSetMargins(20,30,30,30);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica');

   // Write out the intro.
   $pdf->ezText("<b>Fruit Tag Audit</b>", 18, $center);
   $pdf->ezSetDy(-20);
   $pdf->ezText("<b>Port of Wilmington</b>", 12, $left);
   $pdf->ezText("1 Hausel Road                                                                                                        Phone (302) 472-7710", 12, $left);
   $pdf->ezText("Wilmington, Delaware 19801-5852                                                                             Fax (302) 472-7742", 12, $left);
   $pdf->ezSetDy(-15);
   $pdf->ezText("<b>Owner: $customer_name\nPallet ID:  $pallet_id</b>", 12, $left);

   $pdf->ezSetDy(-30);
   $pdf->ezText("Pallet Information:", 11, $left);


   // build a small table for activity details
   $pallet_data = array();	// store data to be printed
   $print_data = array();

   // Cargo Activity
   $stmt = "select ca.order_num, ca.qty_change, to_char(date_of_activity, 'MM/DD/YYYY HH24:MI:SS') date_of_activity, cp.customer_name, sc.service_name, per.login_id from $schema.cargo_activity ca, SAG_OWNER.customer_profile cp, SAG_OWNER.service_category sc, per_owner.personnel per where pallet_id = '$pallet_id' and ca.customer_id = cp.customer_id and ca.service_code = sc.service_code and ca.activity_id = per.employee_id order by activity_num";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);
   while(ora_fetch_into($cursor, $cargo_activity, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
     // Push each activity line
     $order_num = $cargo_activity['ORDER_NUM'];

     if ($cargo_activity['DATE_OF_ACTIVITY'] != "") {
        $date_of_activity = date('m/d/y h:i:s A', strtotime($cargo_activity['DATE_OF_ACTIVITY']));
     } else {
        $date_of_activity = "";
     }

     array_push($pallet_data, array('order_num'=>$order_num, 'date_of_activity'=>$date_of_activity, 'cases'=>$cargo_activity['QTY_CHANGE'], 'customer'=>$cargo_activity['CUSTOMER_NAME'], 'service'=>$cargo_activity['SERVICE_NAME'], 'checker'=>$cargo_activity['LOGIN_ID']));
   }
  

   if($qty_in_house <= 0){
     $billing_storage_date = "No Storage to Bill";
   }
  
   // Print the initial array that shows pallet information
   array_push($print_data, 
	      array('first'=>"Commodity:\nCargo Desc:\nReceiver:\n\n\n$qty_received_text\n", 
		    'second'=>"$commodity\n$cargo_description\n$receiver\n\n\n$qty_received\n",
		    'third'=>'',
		    'fourth'=>"Vessel:\nPort Location:\nDate Received:\n\n\nQty Damaged:\n",
		    'fifth'=>"$vessel_name\n$warehouse_location\n$date_received\n\n\n$qty_damaged\n",
		    'sixth'=>'',
		    'seventh'=>"Vessel Number:\nFree Time End:\nNext Storage Period:\n\n\nQty In House:\n",
		    'eighth'=>"$vessel_number\n$free_time_end\n$billing_storage_date\n\n\n$qty_in_house\n"));

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

   $pdf->ezTable($pallet_data, array('order_num'=>'Order #', 'customer'=>'Customer', 'date_of_activity'=>'Date', 'cases'=>'Cases Change', 'service'=>'Service Type', 'checker'=>'Employee'
),
     '',  array('cols'=>array('order_num'=>array('justification'=>'left'),
'customer'=>array('justification'=>'left'),
'date_of_activity'=>array('justification'=>'left'),
'cases'=>array('justification'=>'right'),
'service'=>array('justification'=>'center'),
'checker'=>array('justification'=>'center')),
     'shaded'=>0, 'showLines'=>2, 'width'=>545));

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
